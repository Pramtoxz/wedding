<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingGallery;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $weddings = Wedding::where('user_id', auth()->id())
            ->with('galleries')
            ->get();

        return Inertia::render('Admin/Galleries/Index', [
            'weddings' => $weddings,
        ]);
    }
}
