<?php

use App\Models\BookLoan;
use App\Models\BookLoanRequest;
use App\Models\Jamaah;
use App\Models\LibraryBook;
use App\Models\Role;
use App\Models\User;

function createTestLibraryBook(array $attributes = []): LibraryBook
{
    return LibraryBook::create(array_merge([
        'book_code' => 'BK-'.fake()->unique()->numerify('######'),
        'title' => 'Fiqih Ibadah',
        'author' => 'Ustadz Ahmad',
        'category' => 'Fiqih',
        'total_copies' => 1,
        'is_active' => true,
    ], $attributes));
}

function createLinkedJamaahUser(): array
{
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $jamaah = Jamaah::create([
        'name' => $user->name,
        'domicile' => 'Kota Bandung',
        'status' => 'aktif',
        'user_id' => $user->id,
    ]);

    return [$user, $jamaah];
}

test('public catalog shows only active books and supports search', function () {
    createTestLibraryBook(['title' => 'Tafsir Ringkas']);
    createTestLibraryBook(['title' => 'Buku Internal', 'is_active' => false]);

    $this->get(route('library.public', ['search' => 'Tafsir']))
        ->assertOk()
        ->assertSee('Tafsir Ringkas')
        ->assertDontSee('Buku Internal');
});

test('linked jamaah can request an available book and sees only their own history', function () {
    [$user] = createLinkedJamaahUser();
    $book = createTestLibraryBook();

    $response = $this->actingAs($user)->post(route('book-loan-requests.store', $book));

    $response->assertRedirect(route('book-loan-requests.mine'));
    $this->assertDatabaseHas('book_loan_requests', [
        'library_book_id' => $book->id,
        'jamaah_id' => $user->jamaah->id,
        'status' => 'pending',
    ]);
    $this->assertDatabaseCount('book_loans', 0);
    $this->actingAs($user)->get(route('book-loan-requests.mine'))->assertOk();
});

test('jamaah without a linked profile cannot request a book', function () {
    $role = Role::firstOrCreate(['name' => 'jamaah'], ['display_name' => 'Jamaah']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $book = createTestLibraryBook();

    $this->actingAs($user)->post(route('book-loan-requests.store', $book))
        ->assertRedirect()
        ->assertSessionHas('error');
    $this->actingAs($user)->get(route('book-loan-requests.mine'))->assertOk()->assertSee('belum ditautkan');
    $this->assertDatabaseCount('book_loan_requests', 0);
});

test('jamaah cannot request a book with no available copies', function () {
    [$user, $jamaah] = createLinkedJamaahUser();
    $book = createTestLibraryBook();
    BookLoan::create([
        'loan_code' => 'PJM-EXISTING',
        'library_book_id' => $book->id,
        'jamaah_id' => $jamaah->id,
        'borrowed_at' => today(),
        'due_date' => today()->addDays(7),
        'status' => 'borrowed',
    ]);

    $this->actingAs($user)->post(route('book-loan-requests.store', $book))->assertSessionHasErrors('book');
    $this->assertDatabaseCount('book_loan_requests', 0);
});

test('secretary approval creates a loan and due date, while jamaah cannot process another request', function () {
    [$user, $jamaah] = createLinkedJamaahUser();
    $book = createTestLibraryBook();
    $loanRequest = BookLoanRequest::create([
        'library_book_id' => $book->id,
        'jamaah_id' => $jamaah->id,
        'status' => 'pending',
    ]);
    $this->actingAs($user)->patch(route('book-loan-requests.approve', $loanRequest), [
        'due_date' => today()->addDays(10)->toDateString(),
    ])->assertForbidden();

    $secretaryRole = Role::firstOrCreate(['name' => 'sekretaris'], ['display_name' => 'Sekretaris']);
    $secretary = User::factory()->create(['role_id' => $secretaryRole->id]);
    $this->actingAs($secretary)->patch(route('book-loan-requests.approve', $loanRequest), [
        'due_date' => today()->addDays(10)->toDateString(),
    ])->assertRedirect();

    expect($loanRequest->fresh()->status)->toBe('approved');
    expect($loanRequest->fresh()->loan)->toBeInstanceOf(BookLoan::class);
    expect($loanRequest->fresh()->loan->due_date->toDateString())->toBe(today()->addDays(10)->toDateString());
    $this->assertDatabaseCount('book_loans', 1);
});
