<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class GuestController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $search = request('search');
        
        $weddings = Wedding::where('user_id', auth()->id())
            ->with(['guests' => function ($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('whatsapp_number', 'like', "%{$search}%");
                }
            }])
            ->get();

        return Inertia::render('Admin/Guests/Index', [
            'weddings' => $weddings,
            'search' => $search,
        ]);
    }
}
