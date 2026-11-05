<?php

use App\Models\BusinessUnit;
use App\Models\BusinessUnitSale;
use App\Models\Role;
use App\Models\User;

function createBusinessSaleSearchUser(): User
{
    $role = Role::firstOrCreate(['name' => 'bendahara'], ['display_name' => 'Bendahara']);

    return User::factory()->create(['role_id' => $role->id]);
}

function createBusinessSaleSearchUnit(): BusinessUnit
{
    return BusinessUnit::create([
        'code' => 'SEARCH-UNIT',
        'name' => 'Koperasi Jamaah',
        'type' => 'koperasi',
        'is_active' => true,
    ]);
}

it('searches sales by buyer name and preserves the keyword in pagination links', function () {
    $bendahara = createBusinessSaleSearchUser();
    $unit = createBusinessSaleSearchUnit();

    foreach (range(1, 16) as $index) {
        BusinessUnitSale::create([
            'business_unit_id' => $unit->id,
            'sale_date' => today()->toDateString(),
            'buyer_name' => "Nadia Pembeli {$index}",
            'reference_number' => "NOTA-NADIA-{$index}",
            'payment_method' => 'tunai',
            'quantity' => 1,
            'amount' => 15000,
        ]);
    }

    $response = $this->actingAs($bendahara)->get(route('business-unit-sales.index', [
        'reference_date' => today()->toDateString(),
        'business_unit_id' => $unit->id,
        'search' => 'Nadia',
    ]));

    $response->assertOk()
        ->assertSee('Nadia Pembeli 1')
        ->assertSee('search=Nadia', false)
        ->assertSee('business_unit_id='.$unit->id, false)
        ->assertSee('page=2', false);
});

it('searches sales by receipt number', function () {
    $bendahara = createBusinessSaleSearchUser();
    $unit = createBusinessSaleSearchUnit();

    BusinessUnitSale::create([
        'business_unit_id' => $unit->id,
        'sale_date' => today()->toDateString(),
        'buyer_name' => 'Siti Aminah',
        'reference_number' => 'NOTA-KHUSUS-9081',
        'payment_method' => 'tunai',
        'quantity' => 2,
        'amount' => 30000,
    ]);

    $this->actingAs($bendahara)
        ->get(route('business-unit-sales.index', ['search' => 'KHUSUS-9081']))
        ->assertOk()
        ->assertSee('Siti Aminah')
        ->assertSee('NOTA-KHUSUS-9081');
});
