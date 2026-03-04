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
            'partner_name' => 'nullable|string|max:255',
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
            'partner_name' => 'nullable|string|max:255',
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
        
        $guestName = $guest->name;
        if ($guest->partner_name) {
            $guestName .= ' ' . $guest->partner_name;
        }
        
        $weddingDate = \Carbon\Carbon::parse($wedding->wedding_date)->locale('id')->isoFormat('dddd, D MMMM YYYY');
        
        $message = "Dear {$guestName}\n\n";
        $message .= "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n";
        $message .= "Bismillahirrahmanirrahim,\n";
        $message .= "Dengan memohon ridho Allah SWT dan tanpa mengurangi rasa hormat, izinkan kami menyampaikan undangan kepada Bapak/Ibu/Saudara/i sekalian.\n\n";
        $message .= "InsyaAllah akan dilaksanakan akad nikah dan resepsi dari,\n\n";
        $message .= "*{$wedding->bride_name}*\n";
        $message .= "dan\n";
        $message .= "*{$wedding->groom_name}*\n\n";
        $message .= "{$weddingDate}\n\n";
        $message .= "Untuk detail acara, silahkan klik tautan berikut:\n";
        $message .= "{$url}\n\n";
        $message .= "E-invitation ini merupakan undangan resmi dari kami karena keterbatasan jarak dan waktu.\n\n";
        $message .= "Merupakan suatu kebahagiaan dan kehormatan bagi kami jika Bapak/Ibu/Saudara/i berkenan menghadiri acara resepsi pernikahan kami. Terima kasih banyak atas perhatiannya.\n\n";
        $message .= "Kami yang berbahagia,\n";
        $message .= "{$wedding->bride_name} & {$wedding->groom_name}";

        $whatsappNumber = preg_replace('/[^0-9]/', '', $guest->whatsapp_number);

        if (substr($whatsappNumber, 0, 1) === '0') {
            $whatsappNumber = '62' . substr($whatsappNumber, 1);
        }
        $guest->update(['status_kirim' => true]);

        return response()->json([
            'whatsapp_link' => "https://wa.me/{$whatsappNumber}?text=" . urlencode($message),
        ]);
    }

    public function toggleStatusKirim(Wedding $wedding, WeddingGuest $guest)
    {
        $this->authorize('update', $wedding);
        
        $guest->update(['status_kirim' => !$guest->status_kirim]);
        
        return back()->with('success', 'Status kirim berhasil diupdate');
    }
}
