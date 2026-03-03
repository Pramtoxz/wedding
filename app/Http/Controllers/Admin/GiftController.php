<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingGift;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GiftController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $weddings = Wedding::where('user_id', auth()->id())
            ->with('gifts')
            ->get();

        return Inertia::render('Admin/Gifts/Index', [
            'weddings' => $weddings,
        ]);
    }
}
