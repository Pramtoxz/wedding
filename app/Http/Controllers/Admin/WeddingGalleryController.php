<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingGallery;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class WeddingGalleryController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:2048',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('weddings/galleries', 'public');

            WeddingGallery::create([
                'wedding_id' => $wedding->id,
                'image_path' => $path,
                'caption' => $validated['captions'][$index] ?? null,
                'order' => $index,
            ]);
        }

        return back()->with('success', 'Images uploaded successfully');
    }

    public function update(Request $request, Wedding $wedding, WeddingGallery $gallery)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $gallery->update($validated);

        return back()->with('success', 'Gallery updated successfully');
    }

    public function destroy(Wedding $wedding, WeddingGallery $gallery)
    {
        $this->authorize('update', $wedding);

        \Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();

        return back()->with('success', 'Image deleted successfully');
    }
}
