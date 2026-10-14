<?php

namespace App\Http\Controllers;

use App\Models\LibraryBook;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LibraryBookController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
        ]);

        $search = $filters['search'] ?? '';
        $category = $filters['category'] ?? '';
        $status = $filters['status'] ?? '';

        $books = LibraryBook::query()
            ->withCount([
                'loans as borrowed_copies' => function ($query) {
                    $query->where('status', 'borrowed');
                },
            ])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('book_code', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('publisher', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where('is_active', $status === 'aktif');
            })
            ->orderBy('title')
            ->paginate(10)
            ->withQueryString();

        $categories = LibraryBook::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('library-books.index', compact(
            'books',
            'categories',
            'search',
            'category',
            'status'
        ));
    }

    public function create()
    {
        return view('library-books.create', [
            'book' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_code' => [
                'required',
                'string',
                'max:255',
                'unique:library_books,book_code',
            ],
            'isbn' => [
                'nullable',
                'string',
                'max:255',
                'unique:library_books,isbn',
            ],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publication_year' => [
                'nullable',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],
            'category' => ['required', 'string', 'max:255'],
            'total_copies' => ['required', 'integer', 'min:1'],
            'shelf_location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        LibraryBook::create($validated);

        return redirect()
            ->route('library-books.index')
            ->with('success', 'Buku berhasil ditambahkan ke katalog.');
    }

    public function edit(LibraryBook $libraryBook)
    {
        $libraryBook->loadCount([
            'loans as borrowed_copies' => function ($query) {
                $query->where('status', 'borrowed');
            },
        ]);

        return view('library-books.edit', [
            'book' => $libraryBook,
        ]);
    }

    public function update(Request $request, LibraryBook $libraryBook)
    {
        $validated = $request->validate([
            'book_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('library_books', 'book_code')
                    ->ignore($libraryBook->id),
            ],
            'isbn' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('library_books', 'isbn')
                    ->ignore($libraryBook->id),
            ],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publication_year' => [
                'nullable',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],
            'category' => ['required', 'string', 'max:255'],
            'total_copies' => ['required', 'integer', 'min:1'],
            'shelf_location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $borrowedCopies = $libraryBook->loans()
            ->where('status', 'borrowed')
            ->count();

        if ($validated['total_copies'] < $borrowedCopies) {
            throw ValidationException::withMessages([
                'total_copies' => "Jumlah eksemplar tidak boleh kurang dari jumlah buku yang sedang dipinjam ({$borrowedCopies}).",
            ]);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $libraryBook->update($validated);

        return redirect()
            ->route('library-books.index')
            ->with('success', 'Data katalog buku berhasil diperbarui.');
    }
}
