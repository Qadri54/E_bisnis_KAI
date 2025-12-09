<!DOCTYPE html>
<html>

    <head>
        <title>E-Ticket KAI - {{ $booking->kode_booking }}</title>
        <style>
            body {
                font-family: sans-serif;
                color: #333;
            }

            .header {
                border-bottom: 2px solid #F26522;
                padding-bottom: 20px;
                margin-bottom: 20px;
            }

            .logo {
                font-size: 24px;
                font-weight: bold;
                color: #2D3E98;
            }

            .title {
                float: right;
                font-size: 18px;
                color: #F26522;
                font-weight: bold;
            }

            .info-grid {
                width: 100%;
                margin-bottom: 20px;
            }

            .info-grid td {
                padding: 5px;
                vertical-align: top;
            }

            .label {
                font-size: 12px;
                color: #666;
                text-transform: uppercase;
            }

            .value {
                font-size: 14px;
                font-weight: bold;
            }

            .qr-area {
                text-align: center;
                border: 1px dashed #ccc;
                padding: 20px;
                margin-top: 30px;
            }

            .footer {
                margin-top: 50px;
                font-size: 10px;
                color: #999;
                text-align: center;
                border-top: 1px solid #eee;
                padding-top: 10px;
            }

            .badge {
                background: #dff0d8;
                color: #3c763d;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
            }
        </style>
    </head>

    <body>

        <div class="header">
            <span class="logo">KAI Access</span>
            <span class="title">E-TICKET / RESI PEMBAYARAN</span>
            <div style="clear: both;"></div>
        </div>

        <table class="info-grid">
            <tr>
                <td width="33%">
                    <div class="label">Kode Booking</div>
                    <div class="value" style="font-size: 20px; color: #F26522;">{{ $booking->kode_booking }}</div>
                </td>
                <td width="33%">
                    <div class="label">Status</div>
                    <div class="value"><span class="badge">LUNAS / PAID</span></div>
                </td>
                <td width="33%">
                    <div class="label">Tanggal Pesan</div>
                    <div class="value">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y H:i') }}</div>
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <table class="info-grid">
            <tr>
                <td colspan="2">
                    <div class="label">Kereta</div>
                    <div class="value">{{ $booking->ticket->kereta->nama_kereta }} (Ekonomi)</div>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <div class="label">Berangkat</div>
                    <div class="value">{{ $booking->ticket->destinasi->destinasi_asal }}</div>
                    <div>
                        {{ \Carbon\Carbon::parse($booking->ticket->jadwalKereta->jadwal_keberangkatan)->format('d M Y, H:i') }}
                        WIB
                    </div>
                </td>
                <td width="50%">
                    <div class="label">Tiba</div>
                    <div class="value">{{ $booking->ticket->destinasi->destinasi_tujuan }}</div>
                    <div>
                        {{ \Carbon\Carbon::parse($booking->ticket->jadwalKereta->jadwal_sampai)->format('d M Y, H:i') }}
                        WIB
                    </div>
                </td>
            </tr>
        </table>

        <table class="info-grid" style="background: #f9f9f9; padding: 15px;">
            <tr>
                <td width="50%">
                    <div class="label">Nama Pemesan</div>
                    <div class="value">{{ $booking->user->name ?? 'Guest' }}</div>
                </td>
                <td width="25%">
                    <div class="label">Jumlah Penumpang</div>
                    <div class="value">{{ $booking->banyak_tiket }} Orang</div>
                </td>
                <td width="25%">
                    <div class="label">Total Harga</div>
                    <div class="value">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>

        <div class="qr-area">
            <!-- Placeholder untuk QR Code (Bisa diganti dengan real QR code library jika ada) -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->kode_booking }}"
                width="120">
            <br>
            <span class="label">Scan QR Code ini di stasiun untuk mencetak Boarding Pass</span>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} PT Kereta Api Indonesia (Persero).<br>
            Ini adalah bukti pembayaran yang sah. Harap simpan dokumen ini.
        </div>

    </body>

</html>