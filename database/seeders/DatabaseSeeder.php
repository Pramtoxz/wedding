<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wedding;
use App\Models\WeddingEvent;
use App\Models\WeddingGallery;
use App\Models\WeddingGift;
use App\Models\WeddingGuest;
use App\Models\WeddingRsvp;
use App\Models\WeddingWish;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $wedding = Wedding::create([
            'user_id' => $user->id,
            'slug' => 'siti-ahmad-' . Str::random(6),
            'bride_name' => 'Siti Nurhaliza',
            'bride_father_name' => 'Bapak Siti',
            'bride_mother_name' => 'Ibu Siti',
            'groom_name' => 'Ahmad Dhani',
            'groom_father_name' => 'Bapak Ahmad',
            'groom_mother_name' => 'Ibu Ahmad',
            'wedding_date' => now()->addDays(30),
            'opening_text' => 'Bismillahirrahmanirrahim. Dengan memohon Rahmat dan Ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami.',
            'closing_text' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu. Wassalamualaikum Wr. Wb.',
            'music_url' => '/music/wedding-1.mp3',
            'theme_primary_color' => '#8B4513',
            'theme_secondary_color' => '#D2691E',
            'theme_accent_color' => '#F4A460',
            'theme_font_family' => 'Playfair Display',
            'is_active' => true,
        ]);

        WeddingEvent::create([
            'wedding_id' => $wedding->id,
            'event_name' => 'Akad Nikah',
            'event_date' => now()->addDays(30),
            'event_time' => '08:00',
            'location_name' => 'Masjid Al-Ikhlas',
            'address' => 'Jl. Raya Bogor No. 123, Jakarta Timur',
            'map_url' => 'https://maps.google.com',
            'order' => 1,
        ]);

        WeddingEvent::create([
            'wedding_id' => $wedding->id,
            'event_name' => 'Resepsi Pernikahan',
            'event_date' => now()->addDays(30),
            'event_time' => '11:00',
            'location_name' => 'Gedung Serbaguna',
            'address' => 'Jl. Raya Bogor No. 125, Jakarta Timur',
            'map_url' => 'https://maps.google.com',
            'order' => 2,
        ]);

        WeddingGift::create([
            'wedding_id' => $wedding->id,
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'Siti Nurhaliza',
            'order' => 1,
        ]);

        WeddingGift::create([
            'wedding_id' => $wedding->id,
            'bank_name' => 'Mandiri',
            'account_number' => '0987654321',
            'account_name' => 'Ahmad Dhani',
            'order' => 2,
        ]);

        WeddingGift::create([
            'wedding_id' => $wedding->id,
            'bank_name' => 'GoPay',
            'account_number' => '081234567890',
            'account_name' => 'Siti Nurhaliza',
            'order' => 3,
        ]);

        $firstNames = ['Budi', 'Ani', 'Siti', 'Ahmad', 'Rina', 'Dedi', 'Sari', 'Eko', 'Dewi', 'Agus', 'Lina', 'Hadi', 'Maya', 'Rudi', 'Fitri', 'Joko', 'Wati', 'Bambang', 'Sri', 'Yanto'];
        $lastNames = ['Santoso', 'Wijaya', 'Susanti', 'Hartono', 'Kurniawan', 'Indah', 'Pratama', 'Lestari', 'Setiawan', 'Rahayu', 'Nugroho', 'Putri', 'Saputra', 'Handayani', 'Wibowo'];
        $statuses = ['attending', 'not_attending', 'maybe'];
        $messages = [
            'Selamat menempuh hidup baru! Semoga menjadi keluarga yang sakinah, mawaddah, warahmah.',
            'Barakallahu lakuma wa baraka alaikuma wa jamaa bainakuma fi khair.',
            'Happy wedding! Semoga langgeng sampai kakek nenek.',
            'Selamat ya! Semoga menjadi pasangan yang harmonis.',
            'Semoga diberkahi keturunan yang sholeh dan sholehah.',
            'Congratulations! Wishing you a lifetime of love and happiness.',
            'Selamat menikah! Semoga selalu bahagia.',
            'Semoga Allah memberkahi pernikahan kalian.',
            'Selamat menempuh hidup baru, semoga langgeng.',
            'Bahagia selalu untuk kalian berdua!',
        ];

        for ($i = 1; $i <= 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            $whatsapp = '08' . rand(1000000000, 9999999999);
            
            $guest = WeddingGuest::create([
                'wedding_id' => $wedding->id,
                'name' => $name,
                'whatsapp_number' => $whatsapp,
                'invitation_code' => strtoupper(Str::random(8)),
            ]);

            if (rand(1, 100) <= 70) {
                $status = $statuses[array_rand($statuses)];
                WeddingRsvp::create([
                    'wedding_id' => $wedding->id,
                    'wedding_guest_id' => $guest->id,
                    'guest_name' => $name,
                    'attendance_status' => $status,
                    'guest_count' => $status === 'attending' ? rand(1, $guest->guest_quota) : 0,
                    'notes' => $status === 'attending' ? 'Akan hadir' : null,
                ]);
            }

            if (rand(1, 100) <= 50) {
                WeddingWish::create([
                    'wedding_id' => $wedding->id,
                    'guest_name' => $name,
                    'message' => $messages[array_rand($messages)],
                    'is_approved' => true,
                ]);
            }
        }
    }
}
