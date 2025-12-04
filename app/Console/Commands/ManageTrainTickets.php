<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Kereta;
use App\Models\Destinasi;
use App\Models\JadwalKereta;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class ManageTrainTickets extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:manage {--type= : generate atau daily}';
    protected $description = 'Generate tiket 8 hari kedepan atau update stok harian';

    /**
     * Execute the console command.
     */
    public function handle() {
        $type = $this->option('type');

        if ($type == 'generate') {
            $this->generateTickets();
        } elseif ($type == 'daily') {
            $this->dailyUpdate();
        } else {
            $this->error('Harap masukkan tipe: --type=generate atau --type=daily');
        }
    }

    private function generateTickets() {
        $this->info('Memulai generate tiket untuk 8 hari kedepan...');

        $keretas = Kereta::all();
        $destinasis = Destinasi::all();

        if ($keretas->isEmpty() || $destinasis->isEmpty()) {
            $this->error("Data kereta atau destinasi kosong. proses dibatalkan");
            return;
        }

        // MASTER RUTE ADALAH ARRAY KONFIGURASI RUTE YANG BERFUNGSI AGAR KERETA TIDAK BERTUKAR JADWAL ATAU DESTINASI
        // MASTER RUTE INI BISA DIAMBIL DARI DATABASE JUGA, TAPI UNTUK SIMPLIFIKASI KITA DEFINISIKAN DI SINI
        $masterRute = [
            // [RUTE 1] Srilelawangsa: Medan -> Binjai (Pagi)
            [
                'kereta_id' => 1,    // Pastikan ID Kereta ini ada di DB
                'destinasi_id' => 1,    // Pastikan ID Destinasi ini ada di DB
                'jam_berangkat' => '08:00',
                'durasi_menit' => 30,
                'harga' => 5000,
                'stok' => 100
            ],
            // [RUTE 2] Srilelawangsa: Binjai -> Medan (Siang)
            [
                'kereta_id' => 1,
                'destinasi_id' => 2,
                'jam_berangkat' => '12:00',
                'durasi_menit' => 30,
                'harga' => 5000,
                'stok' => 100
            ],
            // [RUTE 3] Putri Deli: Medan ->  (Satu kali sehari)
            [
                'kereta_id' => 2,
                'destinasi_id' => 3,
                'jam_berangkat' => '07:30',
                'durasi_menit' => 240, // 4 Jam
                'harga' => 27000,
                'stok' => 200
            ],
            // [RUTE 4] putri deli: Tanjung Balai -> Medan (Malam - Eksekutif)
            [
                'kereta_id' => 2,
                'destinasi_id' => 4,
                'jam_berangkat' => '22:00',
                'durasi_menit' => 300, // 5 Jam
                'harga' => 150000,
                'stok' => 50
            ],
        ];


        // Loop untuk 8 hari ke depan (Mulai besok s/d 8 hari, atau hari ini s/d 7 hari kemudian)
        // Asumsi: Kita generate dari hari ini sampai 8 hari ke depan
        for ($i = 1; $i < 8; $i++) {
            Carbon::setLocale('id');
            $tanggal = Carbon::now()->addDays($i)->startOfDay();


            DB::transaction(function () use ($masterRute, $tanggal) {

                // 3. LOGIC: LOOP ARRAY MASTER RUTE
                // Kita meloop konfigurasi di atas, BUKAN meloop tabel Kereta secara buta.
                foreach ($masterRute as $rute) {

                    // A. Hitung Waktu
                    // Gabungkan Tanggal Loop dengan Jam dari Config
                    $waktuBerangkat = $tanggal->copy()->setTimeFromTimeString($rute['jam_berangkat']);
                    $waktuSampai = $waktuBerangkat->copy()->addMinutes($rute['durasi_menit']);

                    // B. Create Jadwal
                    $jadwal = JadwalKereta::create([
                        'jadwal_keberangkatan' => $waktuBerangkat,
                        'jadwal_sampai' => $waktuSampai,
                    ]);

                    // C. Create Tiket
                    // ID Jadwal diambil dari variabel $jadwal di atas (relasi terbentuk otomatis)
                    Ticket::create([
                        'jadwal_id' => $jadwal->jadwal_id,
                        'kereta_id' => $rute['kereta_id'],
                        'destinasi_id' => $rute['destinasi_id'],
                        'stok_tiket' => $rute['stok'],
                        'harga_tiket' => $rute['harga'],
                    ]);
                }
            });

            $this->info("Sukses: Tiket tanggal " . $tanggal->format('Y-m-d') . " (" . count($masterRute) . " rute) berhasil dibuat.");
        }
    }
    private function dailyUpdate() {
        $this->info('Menjalankan daily update...');

        // [PERBAIKAN 1] Gunakan get(), JANGAN pluck()
        // Agar variabel $jadwal nanti berisi Object Model lengkap, bukan cuma angka ID.
        $expiredSchedules = JadwalKereta::where('jadwal_keberangkatan', '<', Carbon::now()->startOfDay())
            ->get();

        if ($expiredSchedules->isEmpty()) {
            $this->info('Tidak ada jadwal expired hari ini.');
            return;
        }

        foreach ($expiredSchedules as $jadwal) {
            // 1. Parse Waktu Berangkat Lama
            $waktuBerangkatLama = Carbon::parse($jadwal->jadwal_keberangkatan);

            // 2. CEK INI KERETA APA? (Ambil dari tabel Ticket)
            // Kita butuh tahu kereta_id untuk menentukan durasi yang benar
            $tiketTerkait = Ticket::where('jadwal_id', $jadwal->jadwal_id)->first();

            // Default durasi jika data tidak ditemukan (misal 1 jam)
            $durasiPasti = 60;

            if ($tiketTerkait) {
                // --- LOGIKA HARDCODE DURASI (SESUAIKAN DENGAN CONFIG ANDA) ---
                // ID 1 (Srilelawangsa) -> 30 Menit
                if ($tiketTerkait->kereta_id == 1) {
                    $durasiPasti = 30;
                }
                // ID 2 (Putri Deli) -> 4 Jam (240 Menit)
                elseif ($tiketTerkait->kereta_id == 2) {
                    $durasiPasti = 240;
                }
                // ID 3 (Sribilah) -> 5 Jam (300 Menit)
                elseif ($tiketTerkait->kereta_id == 3) {
                    $durasiPasti = 300;
                }
            }

            // 3. TENTUKAN TANGGAL BARU
            // Geser tanggal berangkat 8 hari ke depan
            $waktuBerangkatBaru = $waktuBerangkatLama->copy()->addDays(8);

            // Tentukan waktu sampai BARU berdasarkan DURASI PASTI (Bukan data lama)
            $waktuSampaiBaru = $waktuBerangkatBaru->copy()->addMinutes($durasiPasti);

            // 4. UPDATE DATABASE
            $jadwal->update([
                'jadwal_keberangkatan' => $waktuBerangkatBaru->format('Y-m-d H:i:s'),
                'jadwal_sampai' => $waktuSampaiBaru->format('Y-m-d H:i:s'),
            ]);

            // Reset stok tiket
            if ($tiketTerkait) {
                $tiketTerkait->update(['stok_tiket' => 100]);
            }

            $this->info("Jadwal ID {$jadwal->jadwal_id} diperbaiki. Durasi di-reset jadi {$durasiPasti} menit.");
        }

        $this->info('Daily update selesai.');
    }
}
