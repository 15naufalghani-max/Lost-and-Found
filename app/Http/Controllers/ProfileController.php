<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile, posted items, and claim history.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $items = $user->items()
            ->withCount(['claims', 'comments'])
            ->latest()
            ->get();

        $claims = $user->claims()
            ->with('item.user')
            ->latest()
            ->get();

        $view = view()->exists('profile.index') ? 'profile.index' : 'backend.profile.index';

        return view($view, [
            'user' => $user,
            'items' => $items,
            'claims' => $claims,
        ]);
    }
}
