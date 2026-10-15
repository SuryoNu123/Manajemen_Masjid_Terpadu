<?php

namespace App\Http\Controllers;

use App\Models\DakwahContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DakwahContentController extends Controller
{
    public function index(Request $request)
    {
        $contents = DakwahContent::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dakwah-contents.index', compact('contents'));
    }

    public function create()
    {
        return view('dakwah-contents.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $slug = Str::slug($data['title']);
        $baseSlug = $slug;
        $number = 1;

        while (DakwahContent::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$number++;
        }

        $data['slug'] = $slug;
        $data['author_id'] = $request->user()?->getAuthIdentifier();
        $data['published_at'] =
            $data['status'] === 'published' ? now() : null;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] =
                $request->file('cover_image')->store('dakwah', 'public');
        }

        DakwahContent::create($data);

        return redirect()
            ->route('dakwah-contents.index')
            ->with('success', 'Konten dakwah berhasil ditambahkan.');
    }

    public function edit(DakwahContent $dakwahContent)
    {
        return view('dakwah-contents.edit', [
            'content' => $dakwahContent,
        ]);
    }

    public function update(
        Request $request,
        DakwahContent $dakwahContent
    ) {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        // Pertahankan slug lama ketika judul tidak berubah.
        // Buat slug baru bila judul diubah.
        if ($data['title'] !== $dakwahContent->title) {
            $slug = Str::slug($data['title']);
            $baseSlug = $slug;
            $number = 1;

            while (
                DakwahContent::where('slug', $slug)
                    ->where('id', '!=', $dakwahContent->id)
                    ->exists()
            ) {
                $slug = $baseSlug.'-'.$number++;
            }

            $data['slug'] = $slug;
        }

        $data['published_at'] = $data['status'] === 'published'
            ? ($dakwahContent->published_at ?? now())
            : null;

        if ($request->hasFile('cover_image')) {
            if ($dakwahContent->cover_image) {
                Storage::disk('public')->delete(
                    $dakwahContent->cover_image
                );
            }

            $data['cover_image'] =
                $request->file('cover_image')->store('dakwah', 'public');
        }

        $dakwahContent->update($data);

        return redirect()
            ->route('dakwah-contents.index')
            ->with('success', 'Konten dakwah berhasil diperbarui.');
    }

    public function destroy(DakwahContent $dakwahContent)
    {
        if ($dakwahContent->cover_image) {
            Storage::disk('public')->delete($dakwahContent->cover_image);
        }

        $dakwahContent->delete();

        return redirect()
            ->route('dakwah-contents.index')
            ->with('success', 'Konten dakwah berhasil dihapus.');
    }

    /**
     * Halaman index artikel dakwah publik.
     */
    public function publicIndex(Request $request)
    {
        $articles = DakwahContent::where('status', 'published')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('content', 'like', '%'.$request->search.'%');
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.articles.index', compact('articles'));
    }

    /**
     * Halaman baca artikel dakwah publik.
     */
    public function publicShow(string $slug)
    {
        $article = DakwahContent::where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $recentArticles = DakwahContent::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('public.articles.show', compact('article', 'recentArticles'));
    }
}
