@extends('admin.layouts.superadmin')

@section('title', 'Project Photos - ' . ($tipo === 'job_request' ? 'Job Request' : 'Emergency') . ' #' . $id)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  {{-- Encabezado --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
    <div>
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
        <span class="capitalize">{{ $tipo === 'job_request' ? 'Job Request' : 'Emergency' }}</span> #{{ $id }} Photos
      </h1>
      <p class="text-sm text-gray-500 mt-1">
        {{ $fotos->count() }} {{ Str::plural('photo', $fotos->count()) }} available
      </p>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('superadmin.photos.projects') }}"
         class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Projects
      </a>

      {{-- Botón: generar/reusar link público --}}
      <form method="POST" action="{{ route('superadmin.photos.share') }}" class="inline-flex">
        @csrf
        <input type="hidden" name="tipo" value="{{ $tipo }}">
        <input type="hidden" name="id" value="{{ $id }}">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700">
          <svg class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 10l4.553-4.553a1.5 1.5 0 10-2.121-2.121L12.88 6.877M9 14l-4.553 4.553a1.5 1.5 0 102.121 2.121L11.12 17.123M7 12h10"/>
          </svg>
          Share public link
        </button>
      </form>
    </div>
  </div>

  {{-- Mensaje flash y/o URL pública para copiar --}}
  @if(session('status'))
    <div class="mb-3 px-4 py-3 rounded bg-green-50 text-green-800 text-sm">
      {{ session('status') }}
    </div>
  @endif

  @php $publicUrl = session('share_url') ?? ($shareUrl ?? null); @endphp
  @if($publicUrl)
    <div class="mb-6 p-3 rounded border bg-blue-50 text-blue-800 text-sm flex items-center gap-2">
      <span class="font-medium">Public URL:</span>
      <input id="shareUrl"
             class="flex-1 bg-white rounded px-2 py-1 border border-blue-200"
             readonly
             value="{{ $publicUrl }}">
      <button type="button"
              onclick="navigator.clipboard.writeText(document.getElementById('shareUrl').value)"
              class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">
        Copy
      </button>
      <a href="{{ $publicUrl }}" target="_blank"
         class="px-3 py-1 rounded border border-blue-300 text-blue-700 hover:bg-blue-100">
        Open
      </a>
    </div>
  @endif

  {{-- Galería --}}
  @if($fotos->isEmpty())
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="px-6 py-16 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No photos available</h3>
        <p class="mt-1 text-sm text-gray-500">This project doesn't have any uploaded photos yet.</p>
      </div>
    </div>
  @else
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700">Photo Gallery</span>
          </div>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            {{ $fotos->count() }} {{ Str::plural('item', $fotos->count()) }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
        @foreach($fotos as $foto)
          <div class="group relative rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-colors duration-200">
            <div class="aspect-w-4 aspect-h-3 bg-gray-100">
              <img src="{{ asset('storage/' . $foto->url) }}"
                   alt="Project photo {{ $loop->iteration }}"
                   class="w-full h-48 object-cover transition-opacity duration-200 group-hover:opacity-90 cursor-pointer"
                   onclick="openLightbox('{{ asset('storage/' . $foto->url) }}')">
            </div>
            <div class="p-3 bg-white">
              <div class="p-3 flex justify-between items-center">
                <div class="text-xs text-gray-500">
                  <span>Photo #{{ $loop->iteration }}</span><br>
                  <span>{{ $foto->created_at->format('M d, Y - H:i') }}</span>
                </div>
                <a href="{{ asset('storage/' . $foto->url) }}"
                   download="{{ $tipo }}-{{ $id }}-photo-{{ $loop->iteration }}.jpg"
                   class="inline-flex items-center justify-center p-1 rounded-full text-gray-400 hover:text-green-600 hover:bg-green-100"
                   title="Download photo">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>

{{-- Lightbox mínimo (opcional) --}}
<script>
  function openLightbox(src) {
    const wrap = document.createElement('div');
    wrap.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.8);display:flex;align-items:center;justify-content:center;z-index:9999';
    wrap.onclick = () => document.body.removeChild(wrap);
    const img = document.createElement('img');
    img.src = src;
    img.style.cssText = 'max-width:90vw;max-height:90vh;box-shadow:0 10px 40px rgba(0,0,0,.6);border-radius:8px';
    wrap.appendChild(img);
    document.body.appendChild(wrap);
  }
</script>

<!-- Lightbox Modal (hidden by default) -->
<div id="lightboxModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeLightbox()">
            <div class="absolute inset-0 bg-gray-900 opacity-90"></div>
        </div>
        
        <div class="inline-block align-middle max-w-4xl w-full p-4">
            <div class="relative">
                <button onclick="closeLightbox()" class="absolute -top-10 right-0 text-white hover:text-gray-300 focus:outline-none">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="lightboxImage" class="w-full max-h-screen object-contain" src="" alt="Enlarged view">
            </div>
        </div>
    </div>
</div>

<script>
    function openLightbox(imageUrl) {
        document.getElementById('lightboxImage').src = imageUrl;
        document.getElementById('lightboxModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        document.getElementById('lightboxModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLightbox();
        }
    });
</script>

<style>
    .aspect-w-4 {
        position: relative;
        width: 100%;
        padding-bottom: 75%; /* 4:3 Aspect Ratio */
    }
    .aspect-w-4 > * {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endsection