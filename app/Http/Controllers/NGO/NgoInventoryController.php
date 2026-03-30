<?php

namespace App\Http\Controllers\NGO;

use App\Http\Controllers\Controller;
use App\Models\NGO;
use App\Models\NgoInventoryItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class NgoInventoryController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo) {
            return redirect()->route('welcome')->with('error', 'NGO not found or access denied.');
        }

        $kind = $request->query('kind');
        $q = $request->query('q');

        $items = $ngo->inventoryItems()
            ->with(['custodian:id,name'])
            ->when($kind && in_array($kind, [NgoInventoryItem::KIND_FIXED_ASSET, NgoInventoryItem::KIND_CONSUMABLE], true), function ($query) use ($kind) {
                $query->where('kind', $kind);
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%'.$q.'%')
                        ->orWhere('category', 'like', '%'.$q.'%')
                        ->orWhere('asset_tag', 'like', '%'.$q.'%')
                        ->orWhere('serial_number', 'like', '%'.$q.'%')
                        ->orWhere('storage_location', 'like', '%'.$q.'%');
                });
            })
            ->latest('updated_at')
            ->paginate(30)
            ->withQueryString();

        $items->getCollection()->transform(function (NgoInventoryItem $item) {
            return [
                'id' => $item->id,
                'kind' => $item->kind,
                'name' => $item->name,
                'description' => $item->description,
                'category' => $item->category,
                'asset_tag' => $item->asset_tag,
                'serial_number' => $item->serial_number,
                'quantity' => (float) $item->quantity,
                'unit' => $item->unit,
                'reorder_level' => $item->reorder_level !== null ? (float) $item->reorder_level : null,
                'purchase_date' => $item->purchase_date?->toDateString(),
                'purchase_amount' => $item->purchase_amount !== null ? (float) $item->purchase_amount : null,
                'current_value_estimate' => $item->current_value_estimate !== null ? (float) $item->current_value_estimate : null,
                'asset_condition' => $item->asset_condition,
                'storage_location' => $item->storage_location,
                'custodian_user_id' => $item->custodian_user_id,
                'custodian_name' => $item->custodian?->name,
                'supplier_name' => $item->supplier_name,
                'invoice_reference' => $item->invoice_reference,
                'warranty_expires_at' => $item->warranty_expires_at?->toDateString(),
                'notes' => $item->notes,
                'low_stock' => $item->isLowStock(),
                'updated_at' => $item->updated_at->toISOString(),
            ];
        });

        $teamMembers = User::query()
            ->where('ngo_id', $ngo->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $summary = [
            'fixed_assets' => $ngo->inventoryItems()->where('kind', NgoInventoryItem::KIND_FIXED_ASSET)->count(),
            'consumables' => $ngo->inventoryItems()->where('kind', NgoInventoryItem::KIND_CONSUMABLE)->count(),
            'low_stock' => $ngo->inventoryItems()
                ->where('kind', NgoInventoryItem::KIND_CONSUMABLE)
                ->whereNotNull('reorder_level')
                ->whereColumn('quantity', '<=', 'reorder_level')
                ->count(),
            'book_value_total' => (float) $ngo->inventoryItems()
                ->where('kind', NgoInventoryItem::KIND_FIXED_ASSET)
                ->sum('current_value_estimate'),
        ];

        return Inertia::render('NGO/Office/Inventory', [
            'ngo' => $ngo,
            'items' => $items,
            'teamMembers' => $teamMembers,
            'summary' => $summary,
            'filters' => [
                'kind' => $kind ?: '',
                'q' => $q ?: '',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo) {
            return redirect()->route('welcome')->with('error', 'NGO not found or access denied.');
        }

        $validated = $this->validatedPayload($request, $ngo);
        $validated['ngo_id'] = $ngo->id;
        NgoInventoryItem::query()->create($validated);

        return back()->with('success', 'Item added to office inventory.');
    }

    public function update(Request $request, NgoInventoryItem $item): RedirectResponse
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo || $item->ngo_id !== $ngo->id) {
            abort(404);
        }

        $validated = $this->validatedPayload($request, $ngo);
        $item->update($validated);

        return back()->with('success', 'Inventory item updated.');
    }

    public function destroy(NgoInventoryItem $item): RedirectResponse
    {
        $ngo = Auth::user()?->ngo;
        if (! $ngo || $item->ngo_id !== $ngo->id) {
            abort(404);
        }

        $item->delete();

        return back()->with('success', 'Item removed.');
    }

    private function validatedPayload(Request $request, NGO $ngo): array
    {
        $emptyToNull = [
            'description', 'notes', 'category', 'asset_tag', 'serial_number', 'unit',
            'storage_location', 'supplier_name', 'invoice_reference', 'purchase_date', 'warranty_expires_at',
            'reorder_level', 'purchase_amount', 'current_value_estimate', 'custodian_user_id',
        ];
        $merge = [];
        foreach ($emptyToNull as $key) {
            if ($request->input($key) === '') {
                $merge[$key] = null;
            }
        }
        if ($merge !== []) {
            $request->merge($merge);
        }

        return $request->validate([
            'kind' => ['required', Rule::in([NgoInventoryItem::KIND_FIXED_ASSET, NgoInventoryItem::KIND_CONSUMABLE])],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category' => 'nullable|string|max:120',
            'asset_tag' => 'nullable|string|max:60',
            'serial_number' => 'nullable|string|max:120',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:24',
            'reorder_level' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'purchase_amount' => 'nullable|numeric|min:0',
            'current_value_estimate' => 'nullable|numeric|min:0',
            'asset_condition' => ['required', Rule::in(['good', 'fair', 'poor', 'retired', 'depleted'])],
            'storage_location' => 'nullable|string|max:255',
            'custodian_user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('ngo_id', $ngo->id)),
            ],
            'supplier_name' => 'nullable|string|max:150',
            'invoice_reference' => 'nullable|string|max:120',
            'warranty_expires_at' => 'nullable|date',
            'notes' => 'nullable|string|max:5000',
        ]);
    }
}
