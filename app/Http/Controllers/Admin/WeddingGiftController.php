<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingGift;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class WeddingGiftController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Wedding $wedding)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'qr_code' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
        ]);

        $validated['wedding_id'] = $wedding->id;

        if ($request->hasFile('qr_code')) {
            $validated['qr_code_path'] = $request->file('qr_code')->store('weddings/qrcodes', 'public');
        }

        $gift = WeddingGift::create($validated);

        return back()->with('success', 'Gift account added successfully');
    }

    public function update(Request $request, Wedding $wedding, WeddingGift $gift)
    {
        $this->authorize('update', $wedding);

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'qr_code' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('qr_code')) {
            if ($gift->qr_code_path) {
                \Storage::disk('public')->delete($gift->qr_code_path);
            }
            $validated['qr_code_path'] = $request->file('qr_code')->store('weddings/qrcodes', 'public');
        }

        $gift->update($validated);

        return back()->with('success', 'Gift account updated successfully');
    }

    public function destroy(Wedding $wedding, WeddingGift $gift)
    {
        $this->authorize('update', $wedding);

        if ($gift->qr_code_path) {
            \Storage::disk('public')->delete($gift->qr_code_path);
        }

        $gift->delete();

        return back()->with('success', 'Gift account deleted successfully');
    }
}
