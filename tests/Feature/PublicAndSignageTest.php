<?php

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\DakwahContent;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\PrayerSchedule;
use App\Models\Role;
use App\Models\User;

test('guest can access public home page with dynamic finances and activities', function () {
    $category = FinancialCategory::create([
        'name' => 'Infaq Kotak Amal',
        'type' => 'income',
    ]);

    FinancialTransaction::create([
        'transaction_code' => 'TX-TEST-001',
        'transaction_date' => now()->toDateString(),
        'amount' => 5000000,
        'type' => 'income',
        'status' => 'approved',
        'category_id' => $category->id,
        'description' => 'Infaq Jumat',
    ]);

    Activity::create([
        'title' => 'Kajian Fiqih Bulanan',
        'type' => 'kajian',
        'activity_date' => now()->addDays(2)->toDateString(),
        'start_time' => '19:30:00',
        'speaker' => 'Ustadz Abdullah',
        'description' => 'Pembahasan fiqih thaharah.',
        'status' => 'scheduled',
        'is_public' => true,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Kajian Fiqih Bulanan');
    $response->assertSee('5.000.000');
});

test('emergency announcement displays prominently on home page', function () {
    Announcement::create([
        'title' => 'Peringatan Banjir Sekitar Masjid',
        'content' => 'Parkir kendaraan dipindahkan ke dataran tinggi.',
        'type' => 'emergency',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Peringatan Banjir Sekitar Masjid');
    $response->assertSee('DARURAT');
});

test('guest can access public mosque profile', function () {
    $response = $this->get(route('profile.public'));

    $response->assertStatus(200);
    $response->assertSee('Profil Masjid');
});

test('guest can access financial transparency page', function () {
    $response = $this->get(route('transparansi.index'));

    $response->assertStatus(200);
    $response->assertSee('Transparansi');
});

test('guest can access public dakwah articles and detail', function () {
    $role = Role::firstOrCreate(['name' => 'sekretaris'], ['display_name' => 'Sekretaris']);
    $author = User::factory()->create(['role_id' => $role->id]);

    $article = DakwahContent::create([
        'title' => 'Keutamaan Sholat Berjamaah di Masjid',
        'slug' => 'keutamaan-sholat-berjamaah-di-masjid',
        'content' => 'Sholat berjamaah melipatgandakan pahala hingga 27 derajat.',
        'category' => 'artikel',
        'status' => 'published',
        'author_id' => $author->id,
        'published_at' => now(),
    ]);

    $indexResponse = $this->get(route('dakwah.public'));
    $indexResponse->assertStatus(200);
    $indexResponse->assertSee('Keutamaan Sholat Berjamaah');

    $detailResponse = $this->get(route('dakwah.show', $article->slug));
    $detailResponse->assertStatus(200);
    $detailResponse->assertSee('27 derajat');
});

test('guest can view digital signage TV screen', function () {
    PrayerSchedule::create([
        'schedule_date' => today()->toDateString(),
        'prayer_name' => 'dzuhur',
        'prayer_time' => '12:00:00',
        'is_active' => true,
    ]);

    $response = $this->get(route('digital-signage.index'));

    $response->assertStatus(200);
    $response->assertSee('Digital Signage TV');
    $response->assertSee('Dzuhur');
});

test('guest can calculate zakat fitrah and maal via zakat calculator', function () {
    $response = $this->post(route('zakat.calculate'), [
        'zakat_type' => 'maal',
        'wealth_amount' => 150000000,
        'gold_price' => 1000000,
        'debt_amount' => 0,
    ]);

    $response->assertStatus(200);
});
