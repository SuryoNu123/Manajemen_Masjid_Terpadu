<?php

use App\Models\Activity;
use App\Models\FinancialReport;
use App\Models\Mustahik;
use App\Models\Officer;
use App\Models\Role;
use App\Models\User;

function createSearchFilterUser(string $roleName): User
{
    $role = Role::firstOrCreate(['name' => $roleName], ['display_name' => ucfirst($roleName)]);

    return User::factory()->create(['role_id' => $role->id]);
}

test('ketua can search and combine activity type and status filters', function () {
    $ketua = createSearchFilterUser('ketua');

    Activity::create([
        'title' => 'Kajian Fiqih Pekanan',
        'type' => 'kajian',
        'activity_date' => '2026-11-05',
        'start_time' => '09:00',
        'speaker' => 'Ustadz Amin',
        'status' => 'scheduled',
    ]);
    Activity::create([
        'title' => 'Kajian Fiqih Bulanan',
        'type' => 'kajian',
        'activity_date' => '2026-11-02',
        'start_time' => '09:00',
        'speaker' => 'Ustadz Amin',
        'status' => 'completed',
    ]);
    Activity::create([
        'title' => 'Rapat Pengurus',
        'type' => 'rapat',
        'activity_date' => '2026-11-03',
        'start_time' => '10:00',
        'speaker' => 'Ustadz Amin',
        'status' => 'scheduled',
    ]);

    foreach (range(1, 10) as $index) {
        Activity::create([
            'title' => "Kuliah Rutin {$index}",
            'type' => 'kajian',
            'activity_date' => '2026-11-04',
            'start_time' => '09:00',
            'speaker' => 'Ustadz Amin',
            'status' => 'scheduled',
        ]);
    }

    $response = $this->actingAs($ketua)->get(route('activities.index', [
        'search' => 'Ustadz Amin',
        'type' => 'kajian',
        'status' => 'scheduled',
    ]));

    $response->assertOk()
        ->assertSee('Kajian Fiqih Pekanan')
        ->assertDontSee('Kajian Fiqih Bulanan')
        ->assertDontSee('Rapat Pengurus')
        ->assertSee('id="activity-search" name="search" type="search" value="Ustadz Amin"', false)
        ->assertSee('search=Ustadz%20Amin', false)
        ->assertSee('page=2', false);
});

test('ketua can filter officers by name position and active status', function () {
    $ketua = createSearchFilterUser('ketua');

    Officer::create(['name' => 'Imam Ahmad', 'position' => 'imam', 'is_active' => true]);
    Officer::create(['name' => 'Imam Hasan', 'position' => 'imam', 'is_active' => false]);
    Officer::create(['name' => 'Khatib Yusuf', 'position' => 'khatib', 'is_active' => true]);

    $response = $this->actingAs($ketua)->get(route('officers.index', [
        'search' => 'Imam',
        'position' => 'imam',
        'status' => 'active',
    ]));

    $response->assertOk()
        ->assertSee('Imam Ahmad')
        ->assertDontSee('Imam Hasan')
        ->assertDontSee('Khatib Yusuf')
        ->assertSee('value="active" selected', false);
});

test('bendahara can filter mustahik by name asnaf and distribution status', function () {
    $bendahara = createSearchFilterUser('bendahara');

    Mustahik::create(['name' => 'Ahmad Sehat', 'asnaf' => 'fakir', 'amount' => 100000, 'status' => 'distributed']);
    Mustahik::create(['name' => 'Ahmad Sabar', 'asnaf' => 'fakir', 'amount' => 100000, 'status' => 'pending']);
    Mustahik::create(['name' => 'Hasan Sehat', 'asnaf' => 'miskin', 'amount' => 100000, 'status' => 'distributed']);

    $response = $this->actingAs($bendahara)->get(route('mustahik.index', [
        'search' => 'Ahmad',
        'asnaf' => 'fakir',
        'status' => 'distributed',
    ]));

    $response->assertOk()
        ->assertSee('Ahmad Sehat')
        ->assertDontSee('Ahmad Sabar')
        ->assertDontSee('Hasan Sehat');
});

test('ketua can filter financial reports by year period and status', function () {
    $ketua = createSearchFilterUser('ketua');

    FinancialReport::create([
        'period_type' => 'yearly',
        'year' => 2025,
        'total_income' => 1000000,
        'total_expense' => 200000,
        'balance' => 800000,
        'status' => 'published',
    ]);
    FinancialReport::create([
        'period_type' => 'yearly',
        'year' => 2025,
        'total_income' => 1000000,
        'total_expense' => 200000,
        'balance' => 800000,
        'status' => 'draft',
    ]);
    FinancialReport::create([
        'period_type' => 'yearly',
        'year' => 2024,
        'total_income' => 900000,
        'total_expense' => 200000,
        'balance' => 700000,
        'status' => 'published',
    ]);

    $response = $this->actingAs($ketua)->get(route('laporan-keuangan.index', [
        'year' => 2025,
        'period_type' => 'yearly',
        'status' => 'published',
    ]));

    $response->assertOk()
        ->assertSee('Tahun 2025')
        ->assertDontSee('Tahun 2024')
        ->assertSee('name="year" type="number" min="1900" max="2200" value="2025"', false);
});

test('list filters reject unsupported enum values and invalid report years', function () {
    $ketua = createSearchFilterUser('ketua');
    $bendahara = createSearchFilterUser('bendahara');

    $this->actingAs($ketua)->get(route('activities.index', ['status' => 'hidden']))
        ->assertSessionHasErrors('status');
    $this->actingAs($ketua)->get(route('officers.index', ['position' => 'chair']))
        ->assertSessionHasErrors('position');
    $this->actingAs($bendahara)->get(route('mustahik.index', ['asnaf' => 'other']))
        ->assertSessionHasErrors('asnaf');
    $this->actingAs($ketua)->get(route('laporan-keuangan.index', ['year' => 1800]))
        ->assertSessionHasErrors('year');
});
