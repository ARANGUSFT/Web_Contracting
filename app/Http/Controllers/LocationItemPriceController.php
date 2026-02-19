<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use App\Models\Item;
use App\Models\ItemPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationItemPriceController extends Controller
{
    /**
     * Mostrar precios de items para una ubicación
     * Prioridad:
     * 1. Ciudad
     * 2. Estado
     * 3. Global
     */
    public function index(CompanyLocation $location)
    {
        // 🔑 Location base por ESTADO (sin ciudad)
        $stateLocationId = CompanyLocation::where('user_id', $location->user_id)
            ->where('state', $location->state)
            ->whereNull('city')
            ->value('id');

        $items = Item::query()
            // ✅ SOLO ITEMS ACTIVOS
            ->where('items.is_active', true)

            // ---- CATEGORÍA ----
            ->leftJoin('item_categories', 'item_categories.id', '=', 'items.category_id')

            // ---- CIUDAD ----
            ->leftJoin('item_prices as ip_city', function ($join) use ($location) {
                $join->on('items.id', '=', 'ip_city.item_id')
                    ->where('ip_city.company_location_id', $location->id)
                    ->where('ip_city.is_active', true);
            })

            // ---- ESTADO ----
            ->leftJoin('item_prices as ip_state', function ($join) use ($stateLocationId) {
                $join->on('items.id', '=', 'ip_state.item_id')
                    ->where('ip_state.company_location_id', $stateLocationId)
                    ->where('ip_state.is_active', true);
            })

            ->select([
                'items.id',
                'items.name',
                'items.global_price',
                'items.category_id',

                // categoría
                'item_categories.name as category_name',

                // precios
                'ip_city.price as city_price',
                'ip_state.price as state_price',

                // precio efectivo
                DB::raw('
                    COALESCE(
                        ip_city.price,
                        ip_state.price,
                        items.global_price
                    ) as effective_price
                '),

                // fuente del precio
                DB::raw("
                    CASE
                        WHEN ip_city.price IS NOT NULL THEN 'city'
                        WHEN ip_state.price IS NOT NULL THEN 'state'
                        WHEN items.global_price IS NOT NULL THEN 'global'
                        ELSE 'missing'
                    END as price_source
                "),
            ])
            ->orderBy('item_categories.sort_order')
            ->orderBy('items.sort_order')
            ->orderBy('items.name')
            ->get();

        return view(
            'admin.locations.prices.index',
            compact('location', 'items')
        );
    }

    /**
     * Guardar precios (bulk) SOLO para esta ubicación
     */
    public function store(Request $request, CompanyLocation $location)
    {
        $request->validate([
            'prices'   => 'required|array',
            'prices.*' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $location) {

            foreach ($request->prices as $itemId => $price) {

                // 🧹 Si viene vacío → eliminar override
                if ($price === null || $price === '') {
                    ItemPrice::where('company_location_id', $location->id)
                        ->where('item_id', $itemId)
                        ->delete();

                    continue;
                }

                // 💾 Guardar / actualizar override
                ItemPrice::updateOrCreate(
                    [
                        'company_location_id' => $location->id,
                        'item_id'             => $itemId,
                    ],
                    [
                        'price'     => $price,
                        'is_active' => true,
                    ]
                );
            }
        });

        return back()->with('success', 'Prices updated successfully');
    }
}
