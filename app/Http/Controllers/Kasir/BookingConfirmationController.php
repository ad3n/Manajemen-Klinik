<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingConfirmationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Booking $booking)
    {
        // Validasi dasar, pastikan booking yang akan dikonfirmasi memang untuk hari ini
        if ($booking->booking_date != today()->format('Y-m-d')) {
            return back()->with('error', 'Booking ini tidak dapat dikonfirmasi untuk hari ini.');
        }

        // Ubah status menjadi 'confirmed'
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('kasir.dashboard')
                         ->with('success', 'Kedatangan pasien berhasil dikonfirmasi.');
    }
}
