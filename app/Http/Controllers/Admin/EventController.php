<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $weddings = Wedding::where('user_id', auth()->id())
            ->with('events')
            ->get();

        return Inertia::render('Admin/Events/Index', [
            'weddings' => $weddings,
        ]);
    }
}
