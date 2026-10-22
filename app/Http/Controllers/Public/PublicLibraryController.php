<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LibraryBook;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicLibraryController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $books = LibraryBook::query()
            ->where('is_active', true)
            ->withCount(['loans as borrowed_copies' => fn ($query) => $query->where('status', 'borrowed')])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, fn ($query, string $category) => $query->where('category', $category))
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        return view('public.library.index', [
            'books' => $books,
            'categories' => LibraryBook::where('is_active', true)->distinct()->orderBy('category')->pluck('category'),
            'filters' => $filters,
        ]);
    }

    public function show(LibraryBook $book): View
    {
        abort_unless($book->is_active, 404);
        $book->loadCount(['loans as borrowed_copies' => fn ($query) => $query->where('status', 'borrowed')]);

        return view('public.library.show', compact('book'));
    }
}
