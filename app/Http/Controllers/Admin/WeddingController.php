<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class WeddingController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $weddings = Wedding::where('user_id', auth()->id())
            ->with(['events', 'galleries', 'guests', 'rsvps', 'wishes'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Weddings/Index', [
            'weddings' => $weddings,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Weddings/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bride_name' => 'required|string|max:255',
            'bride_father_name' => 'nullable|string|max:255',
            'bride_mother_name' => 'nullable|string|max:255',
            'bride_photo' => 'nullable|image|max:2048',
            'groom_name' => 'required|string|max:255',
            'groom_father_name' => 'nullable|string|max:255',
            'groom_mother_name' => 'nullable|string|max:255',
            'groom_photo' => 'nullable|image|max:2048',
            'wedding_date' => 'required|date',
            'cover_image' => 'nullable|image|max:2048',
            'opening_text' => 'nullable|string',
            'closing_text' => 'nullable|string',
            'music_url' => 'nullable|string',
            'theme_primary_color' => 'nullable|string|max:7',
            'theme_secondary_color' => 'nullable|string|max:7',
            'theme_accent_color' => 'nullable|string|max:7',
            'theme_font_family' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['bride_name'] . '-' . $validated['groom_name']) . '-' . Str::random(6);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('bride_photo')) {
            $validated['bride_photo'] = $request->file('bride_photo')->store('couples/bride', 'public');
        }

        if ($request->hasFile('groom_photo')) {
            $validated['groom_photo'] = $request->file('groom_photo')->store('couples/groom', 'public');
        }

        $wedding = Wedding::create($validated);

        return redirect()->route('admin.weddings.show', $wedding->id)
            ->with('success', 'Undangan berhasil dibuat');
    }

    public function show(Wedding $wedding)
    {
        $this->authorize('view', $wedding);

        $wedding->load(['events', 'galleries', 'guests', 'rsvps', 'wishes', 'gifts']);

        return Inertia::render('Admin/Weddings/Show', [
            'wedding' => $wedding,
        ]);
    }

    public function edit(Wedding $wedding)
    {
        $this->authorize('update', $wedding);

        return Inertia::render('Admin/Weddings/Edit', [
            'wedding' => $wedding,
        ]);
    }

    public function update(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'bride_name' => 'required|string|max:255',
            'bride_father_name' => 'nullable|string|max:255',
            'bride_mother_name' => 'nullable|string|max:255',
            'bride_photo' => 'nullable|image|max:2048',
            'groom_name' => 'required|string|max:255',
            'groom_father_name' => 'nullable|string|max:255',
            'groom_mother_name' => 'nullable|string|max:255',
            'groom_photo' => 'nullable|image|max:2048',
            'wedding_date' => 'required|date',
            'cover_image' => 'nullable|image|max:2048',
            'opening_text' => 'nullable|string',
            'closing_text' => 'nullable|string',
            'music_url' => 'nullable|string',
            'theme_primary_color' => 'nullable|string|max:7',
            'theme_secondary_color' => 'nullable|string|max:7',
            'theme_accent_color' => 'nullable|string|max:7',
            'theme_font_family' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Remove file fields from validated data - handle separately
        unset($validated['cover_image'], $validated['bride_photo'], $validated['groom_photo']);

        if ($request->hasFile('cover_image')) {
            if ($wedding->cover_image) {
                Storage::disk('public')->delete($wedding->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($request->hasFile('bride_photo')) {
            if ($wedding->bride_photo) {
                Storage::disk('public')->delete($wedding->bride_photo);
            }
            $validated['bride_photo'] = $request->file('bride_photo')->store('couples/bride', 'public');
        }

        if ($request->hasFile('groom_photo')) {
            if ($wedding->groom_photo) {
                Storage::disk('public')->delete($wedding->groom_photo);
            }
            $validated['groom_photo'] = $request->file('groom_photo')->store('couples/groom', 'public');
        }

        $wedding->update($validated);

        return redirect()->route('admin.weddings.show', $wedding->id)
            ->with('success', 'Undangan berhasil diupdate');
    }

    public function destroy(Wedding $wedding)
    {
        $this->authorize('delete', $wedding);

        if ($wedding->cover_image) {
            Storage::disk('public')->delete($wedding->cover_image);
        }

        $wedding->delete();

        return redirect()->route('admin.weddings.index')
            ->with('success', 'Wedding deleted successfully');
    }

    public function getWhatsappLink(Wedding $wedding)
    {
        $this->authorize('view', $wedding);

        $url = route('invitation.show', $wedding->slug);
        $message = "Assalamualaikum Wr. Wb.\n\nDengan memohon Rahmat dan Ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami:\n\n{$wedding->bride_name} & {$wedding->groom_name}\n\nUntuk melihat undangan digital, silakan klik link berikut:\n{$url}";

        return response()->json([
            'whatsapp_link' => 'https://wa.me/?text=' . urlencode($message),
        ]);
    }
}
