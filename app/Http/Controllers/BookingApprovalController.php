<?php

namespace App\Http\Controllers;

use App\Models\BookingApproval;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingApprovalController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // Ambil daftar approval milik user yang sedang login
        // Untuk Level 2, hanya tampilkan jika Level 1 sudah disetujui
        $approvals = BookingApproval::with(['booking.requester', 'booking.vehicle', 'booking.driver', 'booking.creator', 'booking.approvals'])
            ->where('approver_id', $userId)
            ->where(function ($q) {
                // Level 1: selalu bisa dilihat oleh Approver L1
                $q->where('approval_level', 1)
                    // Level 2: hanya aktif jika Level 1 sudah disetujui
                    ->orWhere(function ($q2) {
                        $q2->where('approval_level', 2)
                            ->whereHas('booking.approvals', function ($q3) {
                                $q3->where('approval_level', 1)->where('status', 'disetujui');
                            });
                    });
            })
            ->latest()
            ->paginate(10);

        return view('approvals.index', compact('approvals'));
    }

    public function approve(Request $request, BookingApproval $approval): RedirectResponse
    {
        if ($approval->approver_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menyetujui pemesanan ini.');
        }

        if ($approval->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Persetujuan sudah pernah diproses.');
        }

        $booking = $approval->booking;

        // Validasi Level 2: Level 1 harus disetujui terlebih dahulu
        if ($approval->approval_level === 2) {
            $level1 = $booking->approvals()->where('approval_level', 1)->first();
            if (! $level1 || $level1->status !== 'disetujui') {
                return redirect()->back()->with('error', 'Persetujuan Level 1 belum disetujui.');
            }
        }

        $notes = $request->input('notes');

        DB::transaction(function () use ($approval, $booking, $notes) {
            $approval->update([
                'status' => 'disetujui',
                'notes' => $notes,
                'approved_at' => now(),
            ]);

            // Cek apakah seluruh level persetujuan (Level 1 & Level 2) telah disetujui
            $allApproved = $booking->approvals()
                ->where('status', '!=', 'disetujui')
                ->count() === 0;

            if ($allApproved) {
                $booking->update(['status' => 'disetujui']);

                // Ubah status kendaraan dan driver
                $booking->vehicle->update(['status' => 'digunakan']);
                $booking->driver->update(['status' => 'on_duty']);
            }
        });

        return redirect()->back()->with('success', "Pemesanan {$booking->booking_code} berhasil disetujui (Level {$approval->approval_level}).");
    }

    public function reject(Request $request, BookingApproval $approval): RedirectResponse
    {
        if ($approval->approver_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menolak pemesanan ini.');
        }

        if ($approval->status !== 'menunggu') {
            return redirect()->back()->with('error', 'Persetujuan sudah pernah diproses.');
        }

        $request->validate([
            'notes' => 'required|string|max:255',
        ], [
            'notes.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $booking = $approval->booking;

        DB::transaction(function () use ($approval, $booking, $request) {
            $approval->update([
                'status' => 'ditolak',
                'notes' => $request->input('notes'),
                'approved_at' => now(),
            ]);

            // Jika ditolak di level manapun, status booking langsung menjadi ditolak
            $booking->update(['status' => 'ditolak']);
        });

        return redirect()->back()->with('success', "Pemesanan {$booking->booking_code} telah ditolak.");
    }
}
