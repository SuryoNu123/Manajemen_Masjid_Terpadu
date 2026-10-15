<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function index(): View
    {
        $officers = Officer::where('is_active', true)->orderBy('name')->get();
        $boardMembers = User::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['ketua', 'bendahara', 'sekretaris', 'marbot']);
            })
            ->get();

        return view('public.profile', compact('officers', 'boardMembers'));
    }
}

