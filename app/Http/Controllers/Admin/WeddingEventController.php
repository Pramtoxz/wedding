<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class WeddingEventController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'location_name' => 'required|string|max:255',
            'address' => 'required|string',
            'map_url' => 'nullable|url',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'order' => 'nullable|integer',
        ]);

        $validated['wedding_id'] = $wedding->id;

        $event = WeddingEvent::create($validated);

        return back()->with('success', 'Event added successfully');
    }

    public function update(Request $request, Wedding $wedding, WeddingEvent $event)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'location_name' => 'required|string|max:255',
            'address' => 'required|string',
            'map_url' => 'nullable|url',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'order' => 'nullable|integer',
        ]);

        $event->update($validated);

        return back()->with('success', 'Event updated successfully');
    }

    public function destroy(Wedding $wedding, WeddingEvent $event)
    {
        $this->authorize('update', $wedding);

        $event->delete();

        return back()->with('success', 'Event deleted successfully');
    }
}
