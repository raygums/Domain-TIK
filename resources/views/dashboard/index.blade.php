{{--
    Dashboard Terpusat (Polymorphic View)
    
    View ini digunakan oleh SEMUA role (kecuali Pimpinan).
    Data yang masuk:
    - $roleKey     : 'pengguna' | 'admin' | 'verifikator' | 'eksekutor'
    - $roleName    : Nama role dari database
    - $widgets     : Array kartu statistik [{judul, angka, ikon, warna, keterangan?}]
    - $submissions : Collection pengajuan terbaru (role pengguna)
    - $recentUsers : Collection user terbaru (role admin)
    - $pendingItems: Collection item pending (role verifikator/eksekutor)
    - $greeting    : Teks sambutan
    - $adminNote   : Catatan khusus admin (opsional)
--}}
@extends($roleKey === 'pengguna' ? 'layouts.app' : 'layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="{{ $roleKey === 'pengguna' ? 'py-8 lg:py-12' : '' }}">
    <div class="mx-auto max-w-7xl px-4 {{ $roleKey === 'pengguna' ? '' : 'py-8' }} sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                @if($roleKey === 'pengguna')
                    Selamat Datang, {{ Auth::user()->nm ?? 'Pengguna' }}
                @else
                    Dashboard {{ $roleName }}
                @endif
            </h1>
            <p class="mt-2 text-gray-600">{{ $greeting }}</p>
            @if(!empty($adminNote))
                <p class="mt-1 text-sm text-gray-500">
                    <x-icon name="information-circle" class="inline h-4 w-4 text-info" />
                    {{ $adminNote }}
                </p>
            @endif
        </div>

        {{-- SSO-Gate Alert (Pengguna belum aktif) --}}
        @if($roleKey === 'pengguna' && !Auth::user()->a_aktif)
        <div class="mb-8 overflow-hidden rounded-xl border border-warning bg-warning-light shadow-md">
            <div class="flex items-start gap-4 p-5">
                <div class="flex-shrink-0">
                    <x-icon name="exclamation-circle" class="h-7 w-7 text-warning" />
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-warning-dark">Akun Belum Aktif</h3>
                    <p class="mt-1 text-sm text-gray-700">
                        Akun Anda sedang dalam proses verifikasi oleh Tim Verifikator.
                        Seluruh fitur pengajuan akan tersedia setelah akun diaktifkan.
                    </p>
                    <div class="mt-3 flex items-center gap-2 text-sm text-gray-600">
                        <x-icon name="clock" class="h-4 w-4" />
                        <span>Proses verifikasi biasanya memakan waktu 1-2 hari kerja.</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Widget Statistik - Loop dari array $widgets --}}
        <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-{{ count($widgets) <= 3 ? '3' : '4' }}">
            @foreach($widgets as $widget)
                <x-komponen.ui.kartu-statistik
                    :judul="$widget['judul']"
                    :angka="$widget['angka']"
                    :ikon="$widget['ikon'] ?? 'document-text'"
                    :warna="$widget['warna'] ?? 'myunila'"
                    :keterangan="$widget['keterangan'] ?? null"
                />
            @endforeach
        </div>

        {{-- Quick Actions (Pengguna) --}}
        @if($roleKey === 'pengguna')
        <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @if(Auth::user()->a_aktif)
                <a href="{{ route('submissions.create') }}" class="group relative overflow-hidden rounded-2xl border border-myunila-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-myunila/20">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-myunila-50 transition group-hover:bg-myunila-100"></div>
                    <div class="relative">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-unila text-white">
                            <x-icon name="plus" class="h-6 w-6" />
                        </div>
                        <h3 class="font-semibold text-gray-900">Buat Pengajuan</h3>
                        <p class="mt-1 text-sm text-gray-500">Ajukan domain atau hosting baru</p>
                    </div>
                </a>
                <a href="{{ route('submissions.index') }}" class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gray-50 transition group-hover:bg-gray-100"></div>
                    <div class="relative">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-600">
                            <x-icon name="clipboard-list" class="h-6 w-6" />
                        </div>
                        <h3 class="font-semibold text-gray-900">Daftar Pengajuan</h3>
                        <p class="mt-1 text-sm text-gray-500">Lihat semua pengajuan Anda</p>
                    </div>
                </a>
            @else
                <div class="relative overflow-hidden rounded-2xl border border-gray-300 bg-gray-50 p-6 shadow-sm opacity-60 cursor-not-allowed">
                    <div class="relative">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gray-200 text-gray-400">
                            <x-icon name="shield-check" class="h-6 w-6" />
                        </div>
                        <h3 class="font-semibold text-gray-500">Buat Pengajuan</h3>
                        <p class="mt-1 text-sm text-gray-400">Memerlukan aktivasi akun</p>
                    </div>
                </div>
            @endif
        </div>
        @endif

        {{-- Quick Actions (Admin) --}}
        @if($roleKey === 'admin')
        <div class="mb-8 grid gap-6 md:grid-cols-2">
            <a href="{{ route('admin.users.verification') }}" class="group overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="rounded-xl bg-myunila-50 p-3 transition group-hover:bg-myunila-100">
                        <x-icon name="user-check" class="h-8 w-8 text-myunila" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Akun Pengguna</h3>
                        <p class="mt-1 text-sm text-gray-600">Aktivasi dan kelola status akun pengguna yang baru mendaftar.</p>
                    </div>
                    <x-icon name="chevron-right" class="h-5 w-5 flex-shrink-0 text-gray-400 transition group-hover:translate-x-1 group-hover:text-myunila" />
                </div>
            </a>
            <a href="{{ route('admin.audit.aktivitas') }}" class="group overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="rounded-xl bg-gray-100 p-3 transition group-hover:bg-myunila-50">
                        <x-icon name="document-text" class="h-8 w-8 text-gray-400 transition group-hover:text-myunila" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Log Audit Pengguna</h3>
                        <p class="mt-1 text-sm text-gray-600">Lihat riwayat aktivitas dan audit trail pengguna sistem.</p>
                    </div>
                    <x-icon name="chevron-right" class="h-5 w-5 flex-shrink-0 text-gray-400 transition group-hover:translate-x-1 group-hover:text-myunila" />
                </div>
            </a>
        </div>
        @endif

        {{-- Recent Users (Admin) --}}
        @if($roleKey === 'admin' && $recentUsers->isNotEmpty())
        <x-komponen.ui.tabel judul="Pendaftaran Terbaru (Menunggu Verifikasi)">
            <x-slot:aksi>
                <a href="{{ route('admin.users.verification', ['status' => 'tidak_aktif']) }}" class="text-sm font-medium text-myunila hover:underline">Lihat Semua</a>
            </x-slot:aksi>
            <div class="divide-y divide-gray-200">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between px-6 py-4 transition hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-myunila-100 text-sm font-bold text-myunila">
                            {{ strtoupper(substr($user->nm, 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $user->nm }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @if($user->sso_id)
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                <x-icon name="key" class="h-3 w-3" /> SSO
                            </span>
                        @endif
                        <span class="text-xs text-gray-500">{{ $user->create_at?->diffForHumans() ?? '-' }}</span>
                        <a href="{{ route('admin.users.verification', ['search' => $user->email]) }}" class="inline-flex items-center gap-1 rounded-lg bg-myunila-50 px-3 py-1.5 text-xs font-medium text-myunila transition hover:bg-myunila-100">
                            <x-icon name="arrow-right" class="h-3 w-3" /> Verifikasi
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </x-komponen.ui.tabel>
        @endif

        {{-- Pending Items (Verifikator: pengajuan menunggu verifikasi) --}}
        @if($roleKey === 'verifikator')
        <x-komponen.ui.tabel 
            judul="Pengajuan Menunggu Verifikasi" 
            :kosong="$pendingItems->isEmpty()"
            pesanKosong="Tidak ada pengajuan menunggu"
            ikonKosong="check-circle"
        >
            <div class="divide-y divide-gray-200">
                @foreach($pendingItems as $submission)
                <div class="flex items-center justify-between px-6 py-4 transition hover:bg-gray-50">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $submission->rincian?->nm_subdomain ?? ($submission->rincian?->nm_domain ?? 'N/A') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $submission->pengguna?->nm ?? '-' }} - {{ $submission->unitKerja?->nm_unit ?? ($submission->unitKerja?->nm_lmbg ?? 'N/A') }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <x-komponen.ui.badge-status :status="$submission->jenisLayanan?->nm_layanan ?? 'domain'" />
                            <span class="text-xs text-gray-400">{{ $submission->tgl_pengajuan?->diffForHumans() ?? '-' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('verifikator.show', $submission->UUID) }}" class="ml-4 inline-flex items-center gap-1 rounded-lg bg-myunila px-4 py-2 text-sm font-semibold text-white transition hover:bg-myunila-dark">
                        Verifikasi <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                </div>
                @endforeach
            </div>
        </x-komponen.ui.tabel>
        @endif

        {{-- Pending Items (Eksekutor: tugas terbaru) --}}
        @if($roleKey === 'eksekutor')
        <x-komponen.ui.tabel 
            judul="Tugas Terbaru"
            :kosong="$pendingItems->isEmpty()"
            pesanKosong="Tidak ada tugas saat ini"
            ikonKosong="clipboard-list"
        >
            <x-slot:aksi>
                <a href="{{ route('eksekutor.index') }}" class="text-sm font-medium text-myunila hover:underline">Lihat semua</a>
            </x-slot:aksi>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No. Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Pemohon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($pendingItems as $task)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $task->no_tiket }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $task->pengguna?->nm ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $task->unitKerja?->nm_unit ?? '-' }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <x-komponen.ui.badge-status :status="ucfirst($task->jenisLayanan?->nm_layanan ?? 'domain')" />
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <x-komponen.ui.badge-status :status="$task->status?->nm_status ?? '-'" />
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                            {{ $task->tgl_pengajuan ? \Carbon\Carbon::parse($task->tgl_pengajuan)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('eksekutor.show', $task->UUID) }}" class="text-myunila hover:underline">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </x-komponen.ui.tabel>
        @endif

        {{-- Recent Submissions (Pengguna) --}}
        @if($roleKey === 'pengguna' && Auth::user()->a_aktif && $submissions->isNotEmpty())
        <div class="mb-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="font-semibold text-gray-900">Pengajuan Terbaru Anda</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($submissions as $submission)
                <a href="{{ route('submissions.show', $submission) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                    <div class="flex items-center gap-4">
                        @php $serviceType = $submission->jenisLayanan?->nm_layanan ?? 'domain'; @endphp
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $serviceType === 'vps' ? 'bg-purple-100 text-purple-800' : ($serviceType === 'hosting' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            <x-icon :name="$serviceType === 'vps' ? 'server' : ($serviceType === 'hosting' ? 'server-stack' : 'globe-alt')" class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="font-mono text-sm font-semibold text-myunila">{{ $submission->no_tiket }}</p>
                            <p class="text-sm text-gray-600">{{ $submission->rincian?->nm_domain ?? ucfirst($serviceType) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-komponen.ui.badge-status :status="$submission->status?->nm_status ?? 'Draft'" />
                        <x-icon name="chevron-right" class="h-5 w-5 text-gray-400" />
                    </div>
                </a>
                @endforeach
            </div>
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-3">
                <a href="{{ route('submissions.index') }}" class="text-sm font-medium text-myunila hover:underline">Lihat semua pengajuan &rarr;</a>
            </div>
        </div>
        @endif

        {{-- Info Akun (Pengguna) --}}
        @if($roleKey === 'pengguna')
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-myunila-50 px-6 py-4">
                <h2 class="font-semibold text-gray-900">Informasi Akun</h2>
            </div>
            <div class="p-6">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-gray-900">{{ Auth::user()->nm ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Username / NIP</dt>
                        <dd class="mt-1 text-gray-900">{{ Auth::user()->usn ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-gray-900">{{ Auth::user()->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Role</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full bg-myunila-100 px-3 py-1 text-sm font-medium text-myunila">
                                {{ Auth::user()->peran->nm_peran ?? 'Pengguna' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status Akun</dt>
                        <dd class="mt-1">
                            @if(Auth::user()->a_aktif)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-success-light px-3 py-1 text-sm font-medium text-success">
                                    <x-icon name="check-circle" class="h-4 w-4" /> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-warning-light px-3 py-1 text-sm font-medium text-warning">
                                    <x-icon name="clock" class="h-4 w-4" /> Menunggu Verifikasi
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Login Terakhir</dt>
                        <dd class="mt-1 text-gray-900">{{ Auth::user()->last_login_at?->diffForHumans() ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
