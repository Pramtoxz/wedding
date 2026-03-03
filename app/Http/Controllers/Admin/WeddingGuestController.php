<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingGuest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeddingGuestController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
        ]);

        $validated['wedding_id'] = $wedding->id;
        $validated['invitation_code'] = strtoupper(Str::random(8));

        WeddingGuest::create($validated);

        return back()->with('success', 'Tamu berhasil ditambahkan');
    }

    public function update(Request $request, Wedding $wedding, WeddingGuest $guest)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
        ]);

        $guest->update($validated);

        return back()->with('success', 'Tamu berhasil diupdate');
    }

    public function destroy(Wedding $wedding, WeddingGuest $guest)
    {
        $this->authorize('update', $wedding);

        $guest->delete();

        return back()->with('success', 'Tamu berhasil dihapus');
    }

    public function sendWhatsapp(Wedding $wedding, WeddingGuest $guest)
    {
        $this->authorize('update', $wedding);

        $url = route('invitation.show', ['slug' => $wedding->slug, 'code' => $guest->invitation_code]);
        $message = "Assalamualaikum Wr. Wb.\n\nYth. {$guest->name}\n\nDengan memohon Rahmat dan Ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami:\n\n{$wedding->bride_name} & {$wedding->groom_name}\n\nUntuk melihat undangan digital, silakan klik link berikut:\n{$url}";

        $whatsappNumber = preg_replace('/[^0-9]/', '', $guest->whatsapp_number);

        if (substr($whatsappNumber, 0, 1) === '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }

        return response()->json([
            'whatsapp_link' => "https://wa.me/{$whatsappNumber}?text=" . urlencode($message),
        ]);
    }
}
