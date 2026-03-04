<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingRsvp;
use App\Models\WeddingWish;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvitationController extends Controller
{
    public function show(Request $request, $slug)
    {
        $wedding = Wedding::where('slug', $slug)
            ->where('is_active', true)
            ->with(['events', 'galleries', 'gifts', 'wishes'])
            ->firstOrFail();

        $guestCode = $request->query('code');
        $guest = null;

        if ($guestCode) {
            $guestData = $wedding->guests()->where('invitation_code', $guestCode)->first();
            if ($guestData) {
                $guest = $guestData;
                // Combine name with partner name for display
                if ($guestData->partner_name) {
                    $guest->display_name = $guestData->name . ' ' . $guestData->partner_name;
                } else {
                    $guest->display_name = $guestData->name;
                }
            }
        }

        return Inertia::render('Invitation/Show', [
            'wedding' => $wedding,
            'guest' => $guest,
        ]);
    }

    public function storeRsvp(Request $request, $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'attendance_status' => 'required|in:attending,not_attending,maybe',
            'guest_count' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'wedding_guest_id' => 'nullable|exists:wedding_guests,id',
        ]);

        $validated['wedding_id'] = $wedding->id;

        WeddingRsvp::create($validated);

        return back()->with('success', 'RSVP submitted successfully');
    }

    public function storeWish(Request $request, $slug)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $validated['wedding_id'] = $wedding->id;

        WeddingWish::create($validated);

        return back()->with('success', 'Wish submitted successfully');
    }
}
