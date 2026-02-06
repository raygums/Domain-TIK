<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;
use App\Models\User;
use App\Models\StatusPengajuan;
use App\Models\JenisLayanan;

class DashboardController extends Controller
{
    /**
     * Dashboard utama - polymorphic view berdasarkan role user.
     * Semua role (kecuali Pimpinan) menggunakan satu view: dashboard.index
     */
    public function index()
    {
        $user = Auth::user();
        $roleName = strtolower($user->peran->nm_peran ?? 'pengguna');

        // Pimpinan tetap redirect ke controller terpisah (banyak fitur khusus)
        if (str_contains($roleName, 'pimpinan')) {
            return redirect()->route('pimpinan.dashboard');
        }

        // Tentukan data berdasarkan role menggunakan match
        $roleKey = match(true) {
            str_contains($roleName, 'admin')    => 'admin',
            $roleName === 'verifikator'         => 'verifikator',
            $roleName === 'eksekutor'           => 'eksekutor',
            default                             => 'pengguna',
        };

        $data = match($roleKey) {
            'admin'       => $this->getAdminData(),
            'verifikator' => $this->getVerifikatorData(),
            'eksekutor'   => $this->getEksekutorData(),
            default       => $this->getPenggunaData($user),
        };

        return view('dashboard.index', array_merge($data, [
            'roleKey'  => $roleKey,
            'roleName' => $user->peran->nm_peran ?? 'Pengguna',
        ]));
    }

    /**
     * Public method untuk route verifikator.dashboard
     */
    public function verifikator()
    {
        $user = Auth::user();
        $data = $this->getVerifikatorData();

        return view('dashboard.index', array_merge($data, [
            'roleKey'  => 'verifikator',
            'roleName' => $user->peran->nm_peran ?? 'Verifikator',
        ]));
    }

    // ─────────────────────────────────────────────
    // Data Builders (Private) - satu per role
    // ─────────────────────────────────────────────

    /**
     * Data dashboard Pengguna biasa
     */
    private function getPenggunaData($user): array
    {
        $submissions = Submission::where('pengguna_uuid', $user->UUID)
            ->with(['status', 'jenisLayanan', 'rincian'])
            ->latest('tgl_pengajuan')
            ->take(5)
            ->get();

        return [
            'widgets' => [
                ['judul' => 'Total Pengajuan',   'angka' => Submission::where('pengguna_uuid', $user->UUID)->count(), 'ikon' => 'document-text', 'warna' => 'myunila'],
                ['judul' => 'Dalam Proses',      'angka' => Submission::where('pengguna_uuid', $user->UUID)->whereHas('status', fn($q) => $q->whereNotIn('nm_status', ['Selesai', 'Ditolak Verifikator', 'Ditolak Eksekutor', 'Draft']))->count(), 'ikon' => 'clock', 'warna' => 'warning'],
                ['judul' => 'Selesai',           'angka' => Submission::where('pengguna_uuid', $user->UUID)->whereHas('status', fn($q) => $q->where('nm_status', 'Selesai'))->count(), 'ikon' => 'check-circle', 'warna' => 'success'],
                ['judul' => 'Ditolak',           'angka' => Submission::where('pengguna_uuid', $user->UUID)->whereHas('status', fn($q) => $q->whereIn('nm_status', ['Ditolak Verifikator', 'Ditolak Eksekutor']))->count(), 'ikon' => 'x-circle', 'warna' => 'danger'],
            ],
            'submissions'    => $submissions,
            'recentUsers'    => collect(),
            'pendingItems'   => collect(),
            'greeting'       => 'Kelola pengajuan domain dan hosting Anda dari dashboard ini.',
        ];
    }

    /**
     * Data dashboard Admin
     */
    private function getAdminData(): array
    {
        $penggunaQuery = User::whereHas('peran', fn($q) => $q->where('nm_peran', 'Pengguna'));

        $totalPengguna = (clone $penggunaQuery)->count();
        $nonaktif      = (clone $penggunaQuery)->where('a_aktif', false)->count();
        $aktif         = (clone $penggunaQuery)->where('a_aktif', true)->count();

        $recentUsers = User::where('a_aktif', false)
            ->whereHas('peran', fn($q) => $q->where('nm_peran', 'Pengguna'))
            ->with('peran')
            ->latest('create_at')
            ->take(10)
            ->get();

        return [
            'widgets' => [
                ['judul' => 'Total Pengguna',       'angka' => $totalPengguna, 'ikon' => 'users', 'warna' => 'myunila', 'keterangan' => "$aktif aktif, $nonaktif non-aktif"],
                ['judul' => 'Menunggu Verifikasi',  'angka' => $nonaktif, 'ikon' => 'clock', 'warna' => 'warning', 'keterangan' => 'Akun belum diaktifkan'],
                ['judul' => 'Total Pengajuan',      'angka' => Submission::count(), 'ikon' => 'document-text', 'warna' => 'info', 'keterangan' => Submission::whereMonth('tgl_pengajuan', now()->month)->whereYear('tgl_pengajuan', now()->year)->count() . ' bulan ini'],
                ['judul' => 'Pengajuan Selesai',    'angka' => Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Selesai'))->count(), 'ikon' => 'check-circle', 'warna' => 'success', 'keterangan' => 'Total disetujui'],
            ],
            'submissions'    => collect(),
            'recentUsers'    => $recentUsers,
            'pendingItems'   => collect(),
            'greeting'       => 'Kelola dan verifikasi akun pengguna dari sini.',
            'adminNote'      => 'Anda memiliki akses untuk mengelola akun dengan role Pengguna. Role lain dikelola oleh Pimpinan.',
        ];
    }

    /**
     * Data dashboard Verifikator
     */
    private function getVerifikatorData(): array
    {
        $pendingSubmissions = Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Diajukan'))
            ->with(['pengguna', 'unitKerja', 'jenisLayanan', 'status', 'rincian'])
            ->latest('tgl_pengajuan')
            ->take(5)
            ->get();

        return [
            'widgets' => [
                ['judul' => 'Menunggu Verifikasi', 'angka' => Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Diajukan'))->count(), 'ikon' => 'clock', 'warna' => 'warning'],
                ['judul' => 'Disetujui Hari Ini',  'angka' => Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Disetujui Verifikator'))->whereDate('last_update', today())->count(), 'ikon' => 'check-circle', 'warna' => 'success'],
                ['judul' => 'Ditolak Hari Ini',    'angka' => Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Ditolak Verifikator'))->whereDate('last_update', today())->count(), 'ikon' => 'x-circle', 'warna' => 'danger'],
            ],
            'submissions'    => collect(),
            'recentUsers'    => collect(),
            'pendingItems'   => $pendingSubmissions,
            'greeting'       => 'Verifikasi dan kelola pengajuan layanan domain & hosting.',
        ];
    }

    /**
     * Data dashboard Eksekutor
     */
    private function getEksekutorData(): array
    {
        $tasks = Submission::whereHas('status', fn($q) => $q->whereIn('nm_status', ['Disetujui Verifikator', 'Sedang Dikerjakan']))
            ->with(['pengguna', 'unitKerja', 'jenisLayanan', 'status', 'rincian'])
            ->latest('tgl_pengajuan')
            ->take(5)
            ->get();

        return [
            'widgets' => [
                ['judul' => 'Tugas Baru',           'angka' => Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Disetujui Verifikator'))->count(), 'ikon' => 'clipboard-list', 'warna' => 'info'],
                ['judul' => 'Sedang Dikerjakan',    'angka' => Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Sedang Dikerjakan'))->count(), 'ikon' => 'clock', 'warna' => 'warning'],
                ['judul' => 'Selesai Hari Ini',     'angka' => Submission::whereHas('status', fn($q) => $q->where('nm_status', 'Selesai'))->whereDate('last_update', today())->count(), 'ikon' => 'check-circle', 'warna' => 'success'],
            ],
            'submissions'    => collect(),
            'recentUsers'    => collect(),
            'pendingItems'   => $tasks,
            'greeting'       => 'Kelola dan eksekusi tugas pengajuan layanan.',
        ];
    }
}
