<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('booking')->delete();
        DB::table('ticket')->delete();
        DB::table('jadwal_kereta')->delete();
        DB::table('destinasi')->delete();
        DB::table('kereta')->delete();

        // === KERETA (Trains) ===
        $trains = [
            ['nama_kereta' => 'Kereta Api Srilelawangsa', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kereta' => 'Kereta Api Putri Deli', 'created_at' => now(), 'updated_at' => now()],
            // ['nama_kereta' => 'Kereta Api Siantar Express', 'created_at' => now(), 'updated_at' => now()],
            // ['nama_kereta' => 'Kereta Api Sibinuang', 'created_at' => now(), 'updated_at' => now()],
            // ['nama_kereta' => 'Kereta Api Tanjung Pura', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('kereta')->insert($trains);

        // === DESTINASI (Routes) - Bidirectional ===
        $routes = [
            // Medan - Binjai (32 km)
            ['destinasi_asal' => 'Medan', 'destinasi_tujuan' => 'Binjai', 'created_at' => now(), 'updated_at' => now()],
            ['destinasi_asal' => 'Binjai', 'destinasi_tujuan' => 'Medan', 'created_at' => now(), 'updated_at' => now()],

            // Medan - Tebing Tinggi (78 km)
            ['destinasi_asal' => 'Medan', 'destinasi_tujuan' => 'Tebing Tinggi', 'created_at' => now(), 'updated_at' => now()],
            ['destinasi_asal' => 'Tebing Tinggi', 'destinasi_tujuan' => 'Medan', 'created_at' => now(), 'updated_at' => now()],

            // Medan - Pematang Siantar (128 km)
            // ['destinasi_asal' => 'Medan', 'destinasi_tujuan' => 'Pematang Siantar', 'created_at' => now(), 'updated_at' => now()],
            // ['destinasi_asal' => 'Pematang Siantar', 'destinasi_tujuan' => 'Medan', 'created_at' => now(), 'updated_at' => now()],

            // // Medan - Rantau Prapat (220 km)
            // ['destinasi_asal' => 'Medan', 'destinasi_tujuan' => 'Rantau Prapat', 'created_at' => now(), 'updated_at' => now()],
            // ['destinasi_asal' => 'Rantau Prapat', 'destinasi_tujuan' => 'Medan', 'created_at' => now(), 'updated_at' => now()],

            // // Binjai - Tebing Tinggi (46 km)
            // ['destinasi_asal' => 'Binjai', 'destinasi_tujuan' => 'Tebing Tinggi', 'created_at' => now(), 'updated_at' => now()],
            // ['destinasi_asal' => 'Tebing Tinggi', 'destinasi_tujuan' => 'Binjai', 'created_at' => now(), 'updated_at' => now()],

            // // Binjai - Pematang Siantar (96 km)
            // ['destinasi_asal' => 'Binjai', 'destinasi_tujuan' => 'Pematang Siantar', 'created_at' => now(), 'updated_at' => now()],
            // ['destinasi_asal' => 'Pematang Siantar', 'destinasi_tujuan' => 'Binjai', 'created_at' => now(), 'updated_at' => now()],

            // // Tebing Tinggi - Pematang Siantar (50 km)
            // ['destinasi_asal' => 'Tebing Tinggi', 'destinasi_tujuan' => 'Pematang Siantar', 'created_at' => now(), 'updated_at' => now()],
            // ['destinasi_asal' => 'Pematang Siantar', 'destinasi_tujuan' => 'Tebing Tinggi', 'created_at' => now(), 'updated_at' => now()],

            // // Tebing Tinggi - Rantau Prapat (142 km)
            // ['destinasi_asal' => 'Tebing Tinggi', 'destinasi_tujuan' => 'Rantau Prapat', 'created_at' => now(), 'updated_at' => now()],
            // ['destinasi_asal' => 'Rantau Prapat', 'destinasi_tujuan' => 'Tebing Tinggi', 'created_at' => now(), 'updated_at' => now()],

            // // Pematang Siantar - Rantau Prapat (92 km)
            // ['destinasi_asal' => 'Pematang Siantar', 'destinasi_tujuan' => 'Rantau Prapat', 'created_at' => now(), 'updated_at' => now()],
            // ['destinasi_asal' => 'Rantau Prapat', 'destinasi_tujuan' => 'Pematang Siantar', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('destinasi')->insert($routes);

        // === JADWAL KERETA (Schedules) ===
        // $schedules = [];
        // $baseDate = Carbon::now()->startOfDay();

        // // Morning schedules (05:00 - 11:00)
        // $morningTimes = [
        //     ['depart' => '05:00', 'duration_hours' => 1],
        //     ['depart' => '06:00', 'duration_hours' => 2],
        //     ['depart' => '07:30', 'duration_hours' => 3],
        //     ['depart' => '09:00', 'duration_hours' => 1.5],
        //     ['depart' => '10:30', 'duration_hours' => 2.5],
        // ];

        // // Afternoon schedules (12:00 - 17:00)
        // $afternoonTimes = [
        //     ['depart' => '12:00', 'duration_hours' => 2],
        //     ['depart' => '13:30', 'duration_hours' => 1.5],
        //     ['depart' => '15:00', 'duration_hours' => 3],
        //     ['depart' => '16:30', 'duration_hours' => 2],
        // ];

        // // Evening schedules (18:00 - 22:00)
        // $eveningTimes = [
        //     ['depart' => '18:00', 'duration_hours' => 2.5],
        //     ['depart' => '19:30', 'duration_hours' => 2],
        //     ['depart' => '21:00', 'duration_hours' => 1.5],
        // ];

        // // Generate schedules for 14 days (2 weeks)
        // for ($day = 0; $day < 14; $day++) {
        //     $currentDate = $baseDate->copy()->addDays($day);

        //     // Add morning schedules
        //     foreach ($morningTimes as $time) {
        //         $departure = $currentDate->copy()->setTimeFromTimeString($time['depart']);
        //         $arrival = $departure->copy()->addHours($time['duration_hours']);

        //         $schedules[] = [
        //             'jadwal_keberangkatan' => $departure->format('Y-m-d H:i:s'),
        //             'jadwal_sampai' => $arrival->format('Y-m-d H:i:s'),
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }

        //     // Add afternoon schedules
        //     foreach ($afternoonTimes as $time) {
        //         $departure = $currentDate->copy()->setTimeFromTimeString($time['depart']);
        //         $arrival = $departure->copy()->addHours($time['duration_hours']);

        //         $schedules[] = [
        //             'jadwal_keberangkatan' => $departure->format('Y-m-d H:i:s'),
        //             'jadwal_sampai' => $arrival->format('Y-m-d H:i:s'),
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }

        //     // Add evening schedules
        //     foreach ($eveningTimes as $time) {
        //         $departure = $currentDate->copy()->setTimeFromTimeString($time['depart']);
        //         $arrival = $departure->copy()->addHours($time['duration_hours']);

        //         $schedules[] = [
        //             'jadwal_keberangkatan' => $departure->format('Y-m-d H:i:s'),
        //             'jadwal_sampai' => $arrival->format('Y-m-d H:i:s'),
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }
        // }

        // DB::table('jadwal_kereta')->insert($schedules);

        // // === TICKETS ===
        // $pricePerKm = 100;

        // $routePrices = [
        //     'Medan-Binjai' => 3200,
        //     'Binjai-Medan' => 3200,
        //     'Medan-Tebing Tinggi' => 7800,
        //     'Tebing Tinggi-Medan' => 7800,
        //     'Medan-Pematang Siantar' => 12800,
        //     'Pematang Siantar-Medan' => 12800,
        //     'Medan-Rantau Prapat' => 22000,
        //     'Rantau Prapat-Medan' => 22000,
        //     'Binjai-Tebing Tinggi' => 4600,
        //     'Tebing Tinggi-Binjai' => 4600,
        //     'Binjai-Pematang Siantar' => 9600,
        //     'Pematang Siantar-Binjai' => 9600,
        //     'Tebing Tinggi-Pematang Siantar' => 5000,
        //     'Pematang Siantar-Tebing Tinggi' => 5000,
        //     'Tebing Tinggi-Rantau Prapat' => 14200,
        //     'Rantau Prapat-Tebing Tinggi' => 14200,
        //     'Pematang Siantar-Rantau Prapat' => 9200,
        //     'Rantau Prapat-Pematang Siantar' => 9200,
        // ];

        // $tickets = [];
        // $scheduleIds = DB::table('jadwal_kereta')->pluck('jadwal_id');
        // $trainIds = DB::table('kereta')->pluck('kereta_id');
        // $destinations = DB::table('destinasi')->get();

        // // Create tickets for EVERY schedule and EVERY route combination
        // foreach ($scheduleIds as $scheduleId) {
        //     // For each schedule, create tickets for ALL routes
        //     foreach ($destinations as $destination) {
        //         $routeKey = $destination->destinasi_asal . '-' . $destination->destinasi_tujuan;

        //         // Get base price
        //         $basePrice = $routePrices[$routeKey] ?? 5000;

        //         // Random train selection
        //         $trainId = $trainIds->random();

        //         // Random stock between 30-100 seats
        //         $stock = rand(30, 100);

        //         // Add some price variation (±5%)
        //         $priceVariation = rand(-5, 5) / 100;
        //         $finalPrice = (int)($basePrice * (1 + $priceVariation));

        //         $tickets[] = [
        //             'jadwal_id' => $scheduleId,
        //             'kereta_id' => $trainId,
        //             'destinasi_id' => $destination->destinasi_id,
        //             'stok_tiket' => $stock,
        //             'harga_tiket' => $finalPrice,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }
        // }

        // // Insert tickets in chunks
        // collect($tickets)->chunk(500)->each(function ($chunk) {
        //     DB::table('ticket')->insert($chunk->toArray());
        // });

        // $this->command->info('✅ Seeder completed successfully!');
        // $this->command->info('📊 Data summary:');
        // $this->command->info('   - Trains: ' . count($trains));
        // $this->command->info('   - Routes: ' . count($routes));
        // $this->command->info('   - Schedules: ' . count($schedules) . ' (14 days × 12 schedules/day)');
        // $this->command->info('   - Tickets: ' . count($tickets) . ' (every schedule × every route)');
        // $this->command->info('');
        // $this->command->info('🔍 Sample data:');
        // $this->command->info('   Date range: ' . $baseDate->format('Y-m-d') . ' to ' . $baseDate->copy()->addDays(13)->format('Y-m-d'));
    }
}
