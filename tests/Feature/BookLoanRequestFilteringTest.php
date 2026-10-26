<?php

use App\Models\BookLoanRequest;
use App\Models\Jamaah;
use App\Models\LibraryBook;
use App\Models\Role;
use App\Models\User;

test('secretary can search requests by book or jamaah and retain combined filters in pagination', function () {
    $role = Role::firstOrCreate(['name' => 'sekretaris'], ['display_name' => 'Sekretaris']);
    $secretary = User::factory()->create(['role_id' => $role->id]);
    $regularJamaah = Jamaah::create(['name' => 'Jamaah Reguler', 'domicile' => 'Bandung', 'status' => 'aktif']);
    $namedJamaah = Jamaah::create(['name' => 'Tafsir Reader', 'domicile' => 'Bandung', 'status' => 'aktif']);
    $matchingBook = LibraryBook::create([
        'book_code' => 'BK-FILTER-001',
        'title' => 'Tafsir Koleksi',
        'category' => 'Islam',
        'total_copies' => 1,
        'is_active' => true,
    ]);
    $otherBook = LibraryBook::create([
        'book_code' => 'BK-FILTER-002',
        'title' => 'Buku Umum',
        'category' => 'Umum',
        'total_copies' => 1,
        'is_active' => true,
    ]);

    foreach (range(1, 15) as $index) {
        BookLoanRequest::create([
            'library_book_id' => $matchingBook->id,
            'jamaah_id' => $regularJamaah->id,
            'status' => 'pending',
        ]);
    }

    BookLoanRequest::create([
        'library_book_id' => $otherBook->id,
        'jamaah_id' => $namedJamaah->id,
        'status' => 'pending',
    ]);

    $wrongStatusBook = LibraryBook::create([
        'book_code' => 'BK-FILTER-003',
        'title' => 'Tafsir Dibatalkan',
        'category' => 'Islam',
        'total_copies' => 1,
        'is_active' => true,
    ]);
    BookLoanRequest::create([
        'library_book_id' => $wrongStatusBook->id,
        'jamaah_id' => $regularJamaah->id,
        'status' => 'approved',
    ]);
    $oldDateBook = LibraryBook::create([
        'book_code' => 'BK-FILTER-004',
        'title' => 'Tafsir Lama',
        'category' => 'Islam',
        'total_copies' => 1,
        'is_active' => true,
    ]);
    $oldDateRequest = BookLoanRequest::create([
        'library_book_id' => $oldDateBook->id,
        'jamaah_id' => $regularJamaah->id,
        'status' => 'pending',
    ]);
    $oldDateRequest->forceFill(['created_at' => today()->subDay()])->save();

    $response = $this->actingAs($secretary)->get(route('book-loan-requests.index', [
        'search' => 'Tafsir',
        'status' => 'pending',
        'start_date' => today()->toDateString(),
        'end_date' => today()->toDateString(),
    ]));

    $response->assertOk()
        ->assertSee('Tafsir Koleksi')
        ->assertDontSee('Tafsir Dibatalkan')
        ->assertDontSee('Tafsir Lama')
        ->assertSee('value="Tafsir"', false)
        ->assertSee('value="pending" selected', false)
        ->assertSee('search=Tafsir', false)
        ->assertSee('status=pending', false)
        ->assertSee('start_date='.today()->toDateString(), false)
        ->assertSee('end_date='.today()->toDateString(), false)
        ->assertSee('page=2', false);

    $this->actingAs($secretary)->get(route('book-loan-requests.index', [
        'search' => 'Tafsir',
        'status' => 'pending',
        'start_date' => today()->toDateString(),
        'end_date' => today()->toDateString(),
        'page' => 2,
    ]))->assertOk()->assertSee('Tafsir Reader');
});

test('request filters reject unsupported status and reversed date ranges', function () {
    $role = Role::firstOrCreate(['name' => 'ketua'], ['display_name' => 'Ketua']);
    $ketua = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($ketua)->get(route('book-loan-requests.index', ['status' => 'lost']))
        ->assertSessionHasErrors('status');

    $this->actingAs($ketua)->get(route('book-loan-requests.index', [
        'start_date' => '2026-10-20',
        'end_date' => '2026-10-19',
    ]))->assertSessionHasErrors('end_date');
});
