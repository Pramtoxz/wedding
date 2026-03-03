<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingGallery;
use App\Models\WeddingGuest;
use App\Models\WeddingWish;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_weddings' => Wedding::count(),
            'total_events' => WeddingEvent::count(),
            'total_galleries' => WeddingGallery::count(),
            'total_guests' => WeddingGuest::count(),
            'total_wishes' => WeddingWish::count(),
        ];

        $recentWeddings = Wedding::latest()
            ->take(5)
            ->get()
            ->map(function ($wedding) {
                return [
                    'id' => $wedding->id,
                    'bride_name' => $wedding->bride_name,
                    'groom_name' => $wedding->groom_name,
                    'wedding_date' => $wedding->wedding_date,
                    'slug' => $wedding->slug,
                    'guests_count' => $wedding->guests()->count(),
                    'wishes_count' => $wedding->wishes()->count(),
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentWeddings' => $recentWeddings,
        ]);
    }
}
