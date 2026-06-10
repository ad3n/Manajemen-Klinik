<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    // Menampilkan form untuk membuat rekam medis
    public function create(Booking $booking)
    {
        // Pastikan dokter hanya bisa mengisi rekam medis untuk pasiennya sendiri
        if ($booking->doctor_id !== auth()->user()->doctor->id) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('dokter.medical_records.create', compact('booking'));
    }

    // Menyimpan rekam medis baru
    public function store(Request $request, Booking $booking)
    {
        // Pastikan dokter hanya bisa mengisi rekam medis untuk pasiennya sendiri
        if ($booking->doctor_id !== auth()->user()->doctor->id) {
            abort(403, 'AKSES DITOLAK');
        }

        $request->validate([
            'complaint' => 'required|string',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
        ]);

        // Buat rekam medis yang terhubung dengan booking ini
        $booking->medicalRecord()->create($request->all());

        // Update status booking menjadi 'completed' (selesai)
        $booking->update(['status' => 'completed']);

        return redirect()->route('dokter.dashboard')
                         ->with('success', 'Rekam medis berhasil disimpan.');
    }
}
