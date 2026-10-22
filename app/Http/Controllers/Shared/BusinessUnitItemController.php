<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\BusinessUnitItem;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessUnitItemController extends Controller
{
    public function index(Request $request): View
    {
        $businessUnits = BusinessUnit::where('is_active', true)->orderBy('name')->get();

        $query = BusinessUnitItem::with('businessUnit')
            ->when($request->filled('business_unit_id'), function ($q) use ($request) {
                $q->where('business_unit_id', $request->business_unit_id);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('low_stock') && $request->low_stock == '1', function ($q) {
                $q->whereColumn('stock', '<=', 'min_stock');
            })
            ->latest('id');

        $items = $query->paginate(15)->withQueryString();

        return view('business-unit-items.index', compact('items', 'businessUnits'));
    }

    public function create(Request $request): View
    {
        $businessUnits = BusinessUnit::where('is_active', true)->orderBy('name')->get();
        $selectedUnitId = $request->query('business_unit_id');

        return view('business-unit-items.create', compact('businessUnits', 'selectedUnitId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'business_unit_id' => ['required', 'exists:business_units,id'],
            'sku' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $item = BusinessUnitItem::create($data);

        ActivityLogger::log(
            'create_business_item',
            "Menambahkan produk '{$item->name}' pada unit usaha ID {$item->business_unit_id} (Stok: {$item->stock}).",
            $item
        );

        return redirect()
            ->route('business-unit-items.index', ['business_unit_id' => $item->business_unit_id])
            ->with('success', 'Barang / produk berhasil ditambahkan.');
    }

    public function edit(BusinessUnitItem $businessUnitItem): View
    {
        $businessUnits = BusinessUnit::where('is_active', true)->orderBy('name')->get();

        return view('business-unit-items.edit', [
            'item' => $businessUnitItem,
            'businessUnits' => $businessUnits,
        ]);
    }

    public function update(Request $request, BusinessUnitItem $businessUnitItem): RedirectResponse
    {
        $data = $request->validate([
            'business_unit_id' => ['required', 'exists:business_units,id'],
            'sku' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:30'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $businessUnitItem->update($data);

        ActivityLogger::log(
            'update_business_item',
            "Memperbarui produk '{$businessUnitItem->name}' (Stok: {$businessUnitItem->stock}).",
            $businessUnitItem
        );

        return redirect()
            ->route('business-unit-items.index', ['business_unit_id' => $businessUnitItem->business_unit_id])
            ->with('success', 'Barang / produk berhasil diperbarui.');
    }

    public function destroy(BusinessUnitItem $businessUnitItem): RedirectResponse
    {
        ActivityLogger::log(
            'delete_business_item',
            "Menghapus produk '{$businessUnitItem->name}'.",
            $businessUnitItem
        );

        $businessUnitItem->delete();

        return redirect()
            ->route('business-unit-items.index')
            ->with('success', 'Barang / produk berhasil dihapus.');
    }

    public function adjustStock(Request $request, BusinessUnitItem $businessUnitItem): RedirectResponse
    {
        $data = $request->validate([
            'adjustment_type' => ['required', 'in:add,subtract,set'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $oldStock = $businessUnitItem->stock;

        if ($data['adjustment_type'] === 'add') {
            $businessUnitItem->stock += $data['quantity'];
        } elseif ($data['adjustment_type'] === 'subtract') {
            $businessUnitItem->stock = max(0, $businessUnitItem->stock - $data['quantity']);
        } else {
            $businessUnitItem->stock = $data['quantity'];
        }

        $businessUnitItem->save();

        ActivityLogger::log(
            'adjust_business_stock',
            "Penyesuaian stok produk '{$businessUnitItem->name}' dari {$oldStock} menjadi {$businessUnitItem->stock} ({$data['notes']}).",
            $businessUnitItem
        );

        return redirect()
            ->back()
            ->with('success', "Stok {$businessUnitItem->name} berhasil diperbarui (Stok saat ini: {$businessUnitItem->stock}).");
    }
}
