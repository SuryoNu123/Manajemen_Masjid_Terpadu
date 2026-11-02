@csrf

<div class="space-y-4">
    <div>
        <label for="business_unit_id" class="block text-sm font-semibold text-slate-700">
            Unit Usaha <span class="text-red-500">*</span>
        </label>
        <select name="business_unit_id" id="business_unit_id" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            <option value="">-- Pilih Unit Usaha --</option>
            @foreach($businessUnits as $unit)
                <option value="{{ $unit->id }}" @selected(old('business_unit_id', $item->business_unit_id ?? ($selectedUnitId ?? '')) == $unit->id)>
                    {{ $unit->name }} ({{ ucfirst($unit->type) }})
                </option>
            @endforeach
        </select>
        @error('business_unit_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700">
                Nama Barang / Produk <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" required
                   value="{{ old('name', $item->name ?? '') }}"
                   placeholder="Contoh: Air Mineral 600ml / Mushaf Al-Quran"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sku" class="block text-sm font-semibold text-slate-700">
                SKU / Kode Barang
            </label>
            <input type="text" name="sku" id="sku"
                   value="{{ old('sku', $item->sku ?? '') }}"
                   placeholder="Contoh: BRG-001"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('sku')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="category" class="block text-sm font-semibold text-slate-700">
                Kategori
            </label>
            <input type="text" name="category" id="category"
                   value="{{ old('category', $item->category ?? '') }}"
                   placeholder="Contoh: Minuman, Makanan, Buku, Perlengkapan Ibadah"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('category')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="unit" class="block text-sm font-semibold text-slate-700">
                Satuan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="unit" id="unit" required
                   value="{{ old('unit', $item->unit ?? 'pcs') }}"
                   placeholder="pcs, botol, pack, buah"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('unit')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="cost_price" class="block text-sm font-semibold text-slate-700">
                Harga Modal (Rp) <span class="text-red-500">*</span>
            </label>
            <input type="number" step="0.01" name="cost_price" id="cost_price" required min="0"
                   value="{{ old('cost_price', $item->cost_price ?? 0) }}"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('cost_price')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="selling_price" class="block text-sm font-semibold text-slate-700">
                Harga Jual (Rp) <span class="text-red-500">*</span>
            </label>
            <input type="number" step="0.01" name="selling_price" id="selling_price" required min="0"
                   value="{{ old('selling_price', $item->selling_price ?? 0) }}"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('selling_price')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="stock" class="block text-sm font-semibold text-slate-700">
                Stok Awal / Saat Ini <span class="text-red-500">*</span>
            </label>
            <input type="number" name="stock" id="stock" required min="0"
                   value="{{ old('stock', $item->stock ?? 0) }}"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('stock')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="min_stock" class="block text-sm font-semibold text-slate-700">
                Peringatan Batas Stok Minimum <span class="text-red-500">*</span>
            </label>
            <input type="number" name="min_stock" id="min_stock" required min="0"
                   value="{{ old('min_stock', $item->min_stock ?? 5) }}"
                   class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-blue-600 focus:outline-none">
            @error('min_stock')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1"
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-600"
                   @checked(old('is_active', $item->is_active ?? true))>
            <span class="text-sm font-semibold text-slate-700">Produk Aktif / Dijual</span>
        </label>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
    <a href="{{ route('business-unit-items.index') }}"
       class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        Batal
    </a>
    <button type="submit"
            class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-700">
        Simpan Produk
    </button>
</div>

