<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran - #{{ $payment->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* PENTING: CSS ini akan menyembunyikan tombol saat halaman di-print */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto max-w-2xl my-8">
        <div class="text-right mb-4 no-print">
            <a href="{{ route('kasir.dashboard') }}" class="px-4 py-2 text-sm bg-gray-500 text-white rounded hover:bg-gray-600">Kembali</a>
            <button onclick="window.print()" class="px-4 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Cetak</button>
        </div>

        <div class="bg-white p-8 border rounded-lg shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">KLINIK DEANKA</h1>
                    <p class="text-gray-500">Jl. Wonosari RT. 03 RW. 004 Desa Sambeng</p>
                    <p class="text-gray-500">Kecamatan Kasiman Kabupaten Bojonegoro</p>
                    <p class="text-gray-500">Telp: 0812-2779-2020</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-semibold text-gray-700">KWITANSI</h2>
                    <p class="text-gray-500">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-8">
                <div>
                    <strong class="text-gray-600">Tanggal:</strong>
                    <p>{{ \Carbon\Carbon::parse($payment->paid_at)->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                </div>
                <div class="text-right">
                    <strong class="text-gray-600">Kasir:</strong>
                    <p>{{ $payment->cashier->name }}</p>
                </div>
                <div>
                    <strong class="text-gray-600">Pasien:</strong>
                    <p>{{ $payment->booking->patient->user->name }}</p>
                </div>
            </div>

            <table class="w-full text-left mb-8">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 font-semibold text-gray-600">Deskripsi</th>
                        <th class="p-3 font-semibold text-gray-600 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-3">Biaya Konsultasi - {{ $payment->booking->doctor->user->name }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($payment->consultation_fee, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3">Biaya Obat & Tindakan</td>
                        <td class="p-3 text-right">Rp {{ number_format($payment->medicine_fee, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="p-3 font-bold text-lg text-right">Total Pembayaran</td>
                        <td class="p-3 font-bold text-lg text-right">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center text-gray-500 text-sm">
                <p>Terima kasih atas kunjungan Anda.</p>
                <p>Semoga lekas sembuh!</p>
            </div>
        </div>
    </div>
</body>
</html>
