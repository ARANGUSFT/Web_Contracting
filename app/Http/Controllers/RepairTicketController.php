<?php

namespace App\Http\Controllers;

use App\Models\RepairTicket;
use App\Models\JobRequest;
use App\Models\Emergencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepairTicketController extends Controller
{
    private function authorizeTicket(RepairTicket $repairTicket): void
    {
        if (request()->is('superadmin/*')) return;
        if (auth('web')->id() !== $repairTicket->user_id) {
            abort(403);
        }
    }

    private function pathFromUrl(string $url): string
    {
        if (!str_starts_with($url, 'http')) {
            return ltrim($url, '/');
        }
        $base = Storage::disk('public')->url('');
        return ltrim(str_replace($base, '', $url), '/');
    }

    // ── INDEX ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = RepairTicket::with(['jobRequest', 'emergency', 'fotosAdmin', 'fotosCrew'])
            ->where('user_id', auth()->id())
            ->latest('repair_date');

        if ($request->filled('ref_type') && $request->filled('ref_id')) {
            $query->where('reference_type', $request->ref_type)
                  ->where('reference_id',   $request->ref_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $tickets = $query->paginate(12);

        $statsQuery = RepairTicket::where('user_id', auth()->id());
        if ($request->filled('ref_type') && $request->filled('ref_id')) {
            $statsQuery->where('reference_type', $request->ref_type)
                       ->where('reference_id',   $request->ref_id);
        }
        $stats = [
            'total'      => $statsQuery->count(),
            'pending'    => (clone $statsQuery)->where('status', 'pending')->count(),
            'en_process' => (clone $statsQuery)->where('status', 'en_process')->count(),
            'completed'  => (clone $statsQuery)->where('status', 'completed')->count(),
        ];

        $refLabel = null;
        if ($request->filled('ref_type') && $request->filled('ref_id')) {
            $refLabel = $request->ref_type === 'job'
                ? optional(JobRequest::find($request->ref_id))->job_number_name
                : optional(Emergencies::find($request->ref_id))->job_number_name;
        }

        return view('leads.pg.repair-tickets.index', compact('tickets', 'stats', 'refLabel'));
    }



    

    // ── STORE ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_type' => 'required|in:job,emergency',
            'reference_id'   => 'required|integer',
            'repair_date'    => 'required|date',
            'description'    => 'required|string',
            'status'         => 'required|in:pending,en_process,completed',
            'photos.*'       => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ]);


        $validated['user_id'] = auth()->id() ?? auth('web')->id();

        
        // ── Sequence number por trabajo padre ─────────────────────
        // Cada job/emergency tiene su propia secuencia: RT-0001, RT-0002…
        $validated['sequence_number'] = RepairTicket::where('reference_type', $validated['reference_type'])
            ->where('reference_id', $validated['reference_id'])
            ->count() + 1;

 
        $ticket = RepairTicket::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if (!$file) continue;
                $path = $file->store('repair-tickets/admin', 'public');
                $ticket->fotos()->create(['url' => $path, 'source' => 'admin']);
            }
        }

        return redirect()->back()->with('repair_success', 'Repair ticket submitted successfully.');
    }

    // ── REFERENCES ────────────────────────────────────────────────
    public function references(Request $request)
    {
        $type   = $request->query('type');
        $userId = auth('web')->id() ?? auth()->id();

        if ($type === 'job') {
            $items = JobRequest::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->get(['id', 'job_number_name', 'company_name',
                       'job_address_street_address', 'job_address_city', 'job_address_state'])
                ->map(fn($j) => [
                    'id'      => $j->id,
                    'label'   => $j->job_number_name,
                    'company' => $j->company_name ?? '',
                    'address' => collect([
                        $j->job_address_street_address,
                        $j->job_address_city,
                        $j->job_address_state,
                    ])->filter()->implode(', '),
                ]);
        } else {
            $items = Emergencies::where('user_id', $userId)
                ->orderByDesc('created_at')
                ->get(['id', 'job_number_name', 'company_name',
                       'job_address', 'job_city', 'job_state'])
                ->map(fn($e) => [
                    'id'      => $e->id,
                    'label'   => $e->job_number_name,
                    'company' => $e->company_name ?? '',
                    'address' => collect([
                        $e->job_address,
                        $e->job_city,
                        $e->job_state,
                    ])->filter()->implode(', '),
                ]);
        }

        return response()->json($items);
    }

    // ── EDIT ──────────────────────────────────────────────────────
    public function edit(RepairTicket $repairTicket)
    {
        $this->authorizeTicket($repairTicket);

        if (request()->is('superadmin/*')) {
            return view('admin.calendar.repair-edit', compact('repairTicket'));
        }

        return view('leads.pg.repair-tickets.edit', compact('repairTicket'));
    }

    // ── UPDATE ────────────────────────────────────────────────────
    public function update(Request $request, RepairTicket $repairTicket)
    {
        $this->authorizeTicket($repairTicket);

        $validated = $request->validate([
            'repair_date' => 'required|date',
            'description' => 'required|string',
            'status'      => 'required|in:pending,en_process,completed',
            'crew_id'     => 'nullable|integer',
            'photos.*'    => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ]);

        $repairTicket->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if (!$file) continue;
                $path = $file->store('repair-tickets/admin', 'public');
                $repairTicket->fotos()->create(['url' => $path, 'source' => 'admin']);
            }
        }

        return redirect()->back()->with('repair_success', 'Ticket updated successfully.');
    }

    // ── DELETE PHOTO ──────────────────────────────────────────────
    public function deletePhoto(Request $request, RepairTicket $repairTicket, int $index)
    {
        $this->authorizeTicket($repairTicket);

        $foto = $repairTicket->fotosAdmin()->skip($index)->first();

        if (!$foto) {
            return response()->json(['success' => false, 'message' => 'Photo not found.'], 404);
        }

        Storage::disk('public')->delete($this->pathFromUrl($foto->url));
        $foto->delete();

        return response()->json(['success' => true, 'message' => 'Photo deleted.']);
    }

    // ── DESTROY ───────────────────────────────────────────────────
    public function destroy(RepairTicket $repairTicket)
    {
        $this->authorizeTicket($repairTicket);

        foreach ($repairTicket->fotos as $foto) {
            Storage::disk('public')->delete($this->pathFromUrl($foto->url));
        }

        $repairTicket->delete();

        if (request()->is('superadmin/*')) {
            return redirect()->route('superadmin.calendar.index')
                             ->with('repair_success', 'Ticket deleted.');
        }

        return redirect()->route('calendar.view')
                         ->with('repair_success', 'Ticket deleted.');
    }

    // ── UPDATE STATUS API ─────────────────────────────────────────
    // PATCH /api/repair-tickets/{id}/status
    public function updateStatusApi(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,en_process,completed',
        ]);

        $rt = RepairTicket::findOrFail($id);
        $rt->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $rt->status]);
    }

    // ── UPLOAD ADMIN PHOTOS (API) ─────────────────────────────────
    // POST /superadmin/repair-tickets/{id}/upload-photos
    public function storeAdminPhotos(Request $request, RepairTicket $repairTicket)
    {
        $this->authorizeTicket($repairTicket);

        $request->validate([
            'photos.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx|max:20480',
        ]);

        $uploaded = [];
        foreach ($request->file('photos', []) as $file) {
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_',
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = $safeName . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('repair-tickets/admin', $filename, 'public');
            $repairTicket->fotos()->create(['url' => $path, 'source' => 'admin']);
            $uploaded[] = $path;
        }

        return response()->json(['success' => true, 'photos' => $uploaded]);
    }

    // ── UPLOAD CREW PHOTOS (API) ──────────────────────────────────
    // POST /api/repair-tickets/{id}/crew-photos
    public function storeCrewPhotos(Request $request, $id)
    {
        $repairTicket = RepairTicket::findOrFail($id);

        $request->validate([
            'photos.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $paths = [];
        foreach ($request->file('photos', []) as $file) {
            $path = $file->store('repair-tickets/crew', 'public');
            $repairTicket->fotos()->create(['url' => $path, 'source' => 'crew']);
            $paths[] = Storage::disk('public')->url($path);
        }

        return response()->json(['success' => true, 'crew_photos' => $paths]);
    }


    public function savePayment(Request $request, RepairTicket $repairTicket)
    {
        $request->validate([
            'amount'          => 'nullable|numeric|min:0',
            'payment_date'    => 'nullable|date',
            'payment_receipt' => 'nullable|file|mimes:pdf|max:10240',
            'payment_status'  => 'required|in:unpaid,paid',
        ]);

        $data = $request->only(['amount', 'payment_date', 'payment_status']);

        if ($request->hasFile('payment_receipt')) {
            if ($repairTicket->payment_receipt_path) {
                Storage::disk('public')->delete($repairTicket->payment_receipt_path);
            }
            $data['payment_receipt_path'] = $request->file('payment_receipt')
                ->store('receipts/repairs', 'public');
        }

        $repairTicket->update($data);
        return response()->json(['success' => true]);
    }
}