<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;

        $bookings = Booking::where('doctor_id', $doctor->id)
                            ->whereDate('booking_date', today())
                            ->whereIn('status', ['confirmed', 'completed'])
                            ->with('patient.user')
                            ->orderBy('queue_number', 'asc')
                            ->get();

        // Hitung jumlah pasien yang menunggu
        $waitingPatients = $bookings->where('status', 'confirmed')->count();

        // Hitung jumlah pasien yang sudah selesai
        $completedPatients = $bookings->where('status', 'completed')->count();

        // Kirim semua data ke view
        return view('dokter.dashboard', compact(
            'bookings',
            'waitingPatients',
            'completedPatients'
        ));
    }
}
