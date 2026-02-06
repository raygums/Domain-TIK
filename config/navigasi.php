<?php

/**
 * Konfigurasi Navigasi Sidebar - Config-Driven
 * 
 * Setiap role memiliki daftar menu masing-masing.
 * Struktur: [
 *     'route'       => 'nama.route',
 *     'judul'       => 'Label Menu',
 *     'ikon'        => 'nama-ikon',         // Ikon dari komponen x-icon
 *     'badge_key'   => 'key_dari_stats',    // Opsional: key untuk menampilkan badge jumlah
 *     'badge_color' => 'warning',           // Opsional: warna badge
 * ]
 */

return [

    // ─── Pimpinan (Super Admin) ───
    'pimpinan' => [
        [
            'route' => 'pimpinan.dashboard',
            'judul' => 'Dashboard',
            'ikon'  => 'home',
        ],
        [
            'route' => 'pimpinan.users',
            'judul' => 'Manajemen Pengguna',
            'ikon'  => 'users',
        ],
        [
            'route' => 'pimpinan.activity-logs',
            'judul' => 'Log Aktivitas Sistem',
            'ikon'  => 'clock',
        ],
    ],

    // ─── Admin ───
    'admin' => [
        [
            'route' => 'dashboard',
            'judul' => 'Dashboard',
            'ikon'  => 'home',
        ],
        [
            'route' => 'admin.users.verification',
            'judul' => 'Manajemen Pengguna',
            'ikon'  => 'user-check',
        ],
        [
            'route' => 'admin.audit.aktivitas',
            'judul' => 'Log Aktivitas',
            'ikon'  => 'clock',
        ],
        [
            'route' => 'admin.audit.submissions',
            'judul' => 'Log Status Pengajuan',
            'ikon'  => 'document-text',
        ],
    ],

    // ─── Verifikator ───
    'verifikator' => [
        [
            'route' => 'verifikator.dashboard',
            'judul' => 'Dashboard',
            'ikon'  => 'home',
        ],
        [
            'route' => 'verifikator.index',
            'judul' => 'Daftar Pengajuan',
            'ikon'  => 'clipboard-list',
        ],
        [
            'route' => 'verifikator.history',
            'judul' => 'Riwayat Verifikasi',
            'ikon'  => 'clock',
        ],
        [
            'route' => 'verifikator.my-history',
            'judul' => 'Log Aktivitas',
            'ikon'  => 'document-text',
        ],
    ],

    // ─── Eksekutor ───
    'eksekutor' => [
        [
            'route' => 'dashboard',
            'judul' => 'Dashboard',
            'ikon'  => 'home',
        ],
        [
            'route' => 'eksekutor.index',
            'judul' => 'Daftar Tugas',
            'ikon'  => 'clipboard-list',
        ],
        [
            'route' => 'eksekutor.history',
            'judul' => 'Log Perubahan Status',
            'ikon'  => 'clock',
        ],
        [
            'route' => 'eksekutor.my-history',
            'judul' => 'Log Pekerjaan',
            'ikon'  => 'document-text',
        ],
    ],

    // ─── Pengguna (Default) ───
    'pengguna' => [
        [
            'route' => 'dashboard',
            'judul' => 'Dashboard',
            'ikon'  => 'home',
        ],
        [
            'route' => 'submissions.create',
            'judul' => 'Buat Pengajuan',
            'ikon'  => 'plus-circle',
        ],
        [
            'route' => 'submissions.index',
            'judul' => 'Daftar Pengajuan',
            'ikon'  => 'clipboard-list',
        ],
    ],

];
