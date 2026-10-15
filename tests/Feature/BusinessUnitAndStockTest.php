<?php

use App\Models\BusinessUnit;
use App\Models\BusinessUnitItem;
use App\Models\FinancialCategory;
use App\Models\Role;
use App\Models\User;

function createBendaharaUser(): User
{
    $role = Role::firstOrCreate(['name' => 'bendahara'], ['display_name' => 'Bendahara']);

    return User::factory()->create(['role_id' => $role->id]);
}

test('bendahara can create and manage business unit items', function () {
    $bendahara = createBendaharaUser();

    $unit = BusinessUnit::create([
        'code' => 'KOP-01',
        'name' => 'Koperasi Syariah Masjid',
        'type' => 'koperasi',
        'location' => 'Gedung Barat Lantai 1',
        'manager_name' => 'Pak Haji Syarif',
        'is_active' => true,
    ]);

    $response = $this->actingAs($bendahara)->post(route('business-unit-items.store'), [
        'business_unit_id' => $unit->id,
        'sku' => 'AIR-600',
        'name' => 'Air Mineral Botol 600ml',
        'category' => 'Minuman',
        'cost_price' => 2500,
        'selling_price' => 4000,
        'stock' => 50,
        'min_stock' => 10,
        'unit' => 'botol',
        'is_active' => true,
    ]);

    $response->assertRedirect(route('business-unit-items.index', ['business_unit_id' => $unit->id]));
    $this->assertDatabaseHas('business_unit_items', [
        'name' => 'Air Mineral Botol 600ml',
        'stock' => 50,
        'min_stock' => 10,
    ]);
});

test('bendahara can adjust stock quantity', function () {
    $bendahara = createBendaharaUser();

    $unit = BusinessUnit::create([
        'code' => 'KNT-01',
        'name' => 'Kantin Berkah',
        'type' => 'kantin',
        'is_active' => true,
    ]);

    $item = BusinessUnitItem::create([
        'business_unit_id' => $unit->id,
        'name' => 'Roti Manis Cokelat',
        'cost_price' => 3000,
        'selling_price' => 5000,
        'stock' => 20,
        'min_stock' => 5,
        'unit' => 'pcs',
        'is_active' => true,
    ]);

    // Tambah stok
    $this->actingAs($bendahara)->post(route('business-unit-items.adjust-stock', $item), [
        'adjustment_type' => 'add',
        'quantity' => 15,
        'notes' => 'Kiriman baru supplier',
    ]);

    expect($item->fresh()->stock)->toBe(35);

    // Kurangi stok
    $this->actingAs($bendahara)->post(route('business-unit-items.adjust-stock', $item), [
        'adjustment_type' => 'subtract',
        'quantity' => 5,
        'notes' => 'Rusak kemasan',
    ]);

    expect($item->fresh()->stock)->toBe(30);
});

test('recording business unit sale automatically creates financial transaction', function () {
    $bendahara = createBendaharaUser();

    $category = FinancialCategory::create([
        'name' => 'Unit Usaha & Koperasi',
        'type' => 'income',
    ]);

    $unit = BusinessUnit::create([
        'code' => 'KOP-02',
        'name' => 'Koperasi Serba Usaha',
        'type' => 'koperasi',
        'is_active' => true,
    ]);

    $response = $this->actingAs($bendahara)->post(route('business-unit-sales.store'), [
        'business_unit_id' => $unit->id,
        'sale_date' => today()->toDateString(),
        'amount' => 350000,
        'quantity' => 25,
        'payment_method' => 'tunai',
        'description' => 'Penjualan harian koperasi',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('business_unit_sales', [
        'business_unit_id' => $unit->id,
        'amount' => 350000,
    ]);

    $this->assertDatabaseHas('financial_transactions', [
        'amount' => 350000,
        'type' => 'income',
    ]);
});
