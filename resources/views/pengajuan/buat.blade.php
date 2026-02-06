{{--
    Form Pengajuan Refactored → resources/views/pengajuan/buat.blade.php
    
    Menggunakan komponen baru:
    - x-komponen.formulir.input  → Handle label, error, old(), type, required
    - x-komponen.formulir.select → Handle opsi array/collection
    - x-komponen.formulir.textarea → Handle textarea
    - x-komponen.formulir.bagian → Section wrapper
    - x-komponen.formulir.radio-group → Radio buttons
    - x-komponen.ui.badge-status → Badge dinamis
--}}
@extends('layouts.app')

@section('title', 'Formulir Pengajuan ' . ucfirst($type))

@section('content')
<div class="py-8 lg:py-12">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ url('/') }}" class="mb-4 inline-flex items-center gap-2 text-sm text-gray-600 transition hover:text-myunila">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Kembali ke Beranda
            </a>
            
            <div class="flex items-center gap-4">
                @php
                    $iconBg = match($type) {
                        'hosting' => 'bg-gradient-ocean',
                        'vps'     => 'bg-info',
                        default   => 'bg-gradient-unila',
                    };
                    $iconName = match($type) {
                        'hosting' => 'server-stack',
                        'vps'     => 'server',
                        default   => 'globe-alt',
                    };
                    $formTitle = match($type) {
                        'hosting' => 'Formulir Permohonan Hosting',
                        'vps'     => 'Formulir Permohonan VPS',
                        default   => 'Formulir Permohonan Sub Domain',
                    };
                    $formSubtitle = match($type) {
                        'hosting' => 'Layanan Hosting Universitas Lampung',
                        'vps'     => 'Layanan Virtual Private Server Universitas Lampung',
                        default   => 'Layanan Domain Universitas Lampung',
                    };
                @endphp
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $iconBg }} text-white shadow-lg shadow-myunila/30">
                    <x-icon :name="$iconName" class="h-7 w-7" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">{{ $formTitle }}</h1>
                    <p class="text-gray-600">{{ $formSubtitle }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('submissions.store') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="request_type" value="{{ $type }}">
            
            {{-- ═══ Section 1: Tipe Pengajuan ═══ --}}
            <x-komponen.formulir.bagian number="1" title="Tipe Pengajuan" subtitle="Pilih jenis permohonan yang ingin Anda ajukan">
                <div class="space-y-4">
                    <label class="mb-3 block text-sm font-medium text-gray-700">
                        Tipe Pengajuan <span class="text-error">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                        @php
                            $tipePengajuanOptions = match($type) {
                                'hosting', 'vps' => [
                                    'pengajuan_baru'    => ['label' => 'Pengajuan Baru',       'icon' => 'plus'],
                                    'perpanjangan'      => ['label' => 'Perpanjangan',          'icon' => 'arrow-right'],
                                    'upgrade_downgrade' => ['label' => 'Upgrade / Downgrade',   'icon' => 'chevron-up'],
                                    'penonaktifan'      => ['label' => 'Penonaktifan',          'icon' => 'x-circle'],
                                    'laporan_masalah'   => ['label' => 'Laporan Masalah',       'icon' => 'exclamation-circle'],
                                ],
                                default => [
                                    'pengajuan_baru'    => ['label' => 'Pengajuan Baru',           'icon' => 'plus'],
                                    'perpanjangan'      => ['label' => 'Perpanjangan',              'icon' => 'arrow-right'],
                                    'perubahan_data'    => ['label' => 'Perubahan Data / Pointing', 'icon' => 'document-text'],
                                    'penonaktifan'      => ['label' => 'Penonaktifan',              'icon' => 'x-circle'],
                                    'laporan_masalah'   => ['label' => 'Laporan Masalah',           'icon' => 'exclamation-circle'],
                                ],
                            };
                        @endphp
                        @foreach($tipePengajuanOptions as $value => $option)
                            <label class="relative flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-gray-200 bg-white p-4 text-center transition hover:border-myunila-300 hover:bg-myunila-50 has-[:checked]:border-myunila has-[:checked]:bg-myunila-50">
                                <input type="radio" name="tipe_pengajuan" value="{{ $value }}" class="peer sr-only" {{ old('tipe_pengajuan', 'pengajuan_baru') == $value ? 'checked' : '' }} required>
                                <x-icon :name="$option['icon']" class="mb-2 h-6 w-6 text-gray-400 peer-checked:text-myunila" />
                                <span class="text-xs font-medium text-gray-700 peer-checked:text-myunila">{{ $option['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('tipe_pengajuan')
                        <p class="mt-2 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Conditional: Existing Service Info --}}
                <div id="existing_service_section" class="mt-6 hidden rounded-lg border border-warning/30 bg-warning-light p-4">
                    <h4 class="mb-3 font-medium text-gray-900">Informasi Layanan Existing</h4>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="existing_domain" class="mb-1 block text-sm font-medium text-gray-700">
                                @if($type === 'vps') Hostname VPS Existing
                                @elseif($type === 'hosting') Akun Hosting Existing
                                @else Domain Existing
                                @endif
                                <span class="text-error">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="existing_domain" id="existing_domain"
                                    value="{{ old('existing_domain') }}"
                                    placeholder="{{ $type === 'vps' ? 'vps-example' : ($type === 'hosting' ? 'akun-hosting' : 'contoh') }}"
                                    class="form-input {{ $type !== 'vps' ? 'max-w-xs' : '' }}">
                                @if($type !== 'vps')
                                    <span class="whitespace-nowrap text-lg font-semibold text-myunila">.unila.ac.id</span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Masukkan domain/hosting/VPS yang sudah Anda miliki</p>
                        </div>

                        <x-komponen.formulir.input name="existing_ticket" label="No. Tiket Sebelumnya" placeholder="Contoh: TIK-20260101-XXXX" hint="Kosongkan jika layanan dibuat sebelum sistem ini ada" />

                        {{-- Ticket feedback indicators --}}
                        <div class="md:col-span-2 -mt-2">
                            <div id="ticket_loading" class="hidden">
                                <div class="flex items-center gap-2 text-sm text-blue-600">
                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Memuat data dari tiket...</span>
                                </div>
                            </div>
                            <div id="ticket_success" class="hidden">
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <x-icon name="check-circle" class="h-4 w-4" />
                                    <span>Data berhasil dimuat. Silakan periksa dan sesuaikan jika ada perubahan.</span>
                                </div>
                            </div>
                            <div id="ticket_error" class="hidden">
                                <div class="flex items-center gap-2 text-sm text-red-600">
                                    <x-icon name="x-circle" class="h-4 w-4" />
                                    <span id="ticket_error_message">Tiket tidak ditemukan atau tidak valid.</span>
                                </div>
                            </div>
                        </div>

                        <x-komponen.formulir.input name="existing_expired" label="Tanggal Expired (Opsional)" type="date" />
                    </div>

                    <div id="detail_keterangan_section" class="mt-4">
                        <label for="existing_notes" class="mb-1 block text-sm font-medium text-gray-700">
                            <span id="keterangan_label">Keterangan Permohonan</span> <span class="text-error">*</span>
                        </label>
                        <textarea name="existing_notes" id="existing_notes" rows="3"
                            placeholder="Jelaskan detail permohonan Anda..."
                            class="form-input">{{ old('existing_notes') }}</textarea>
                        <p id="keterangan_hint" class="mt-1 text-sm text-gray-500">Jelaskan perubahan yang diminta atau alasan permohonan</p>
                    </div>
                </div>
            </x-komponen.formulir.bagian>

            {{-- ═══ Section 2: Data Sub Domain / Kategori Pemohon ═══ --}}
            @php
                $section2Title = match($type) {
                    'vps'     => 'Data Pemohon VPS',
                    'hosting' => 'Data Pemohon Hosting',
                    default   => 'Data Sub Domain',
                };
            @endphp
            <x-komponen.formulir.bagian number="2" :title="$section2Title">
                <div class="space-y-6">
                    <div>
                        <label class="mb-3 block text-sm font-medium text-gray-700">Kategori Pemohon <span class="text-error">*</span></label>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                            @php
                                $kategoriOptions = [
                                    'lembaga_fakultas'     => 'Lembaga / Fakultas / Jurusan',
                                    'kegiatan_lembaga'     => 'Kegiatan Lembaga / Fakultas / Jurusan',
                                    'organisasi_mahasiswa' => 'Organisasi Mahasiswa',
                                    'kegiatan_mahasiswa'   => 'Kegiatan Mahasiswa',
                                    'lainnya'              => 'Lain-lain',
                                ];
                            @endphp
                            @foreach($kategoriOptions as $value => $label)
                                <label class="relative flex cursor-pointer items-center justify-center rounded-xl border-2 border-gray-200 bg-white p-4 text-center transition hover:border-myunila-300 hover:bg-myunila-50 has-[:checked]:border-myunila has-[:checked]:bg-myunila-50">
                                    <input type="radio" name="kategori_pemohon" value="{{ $value }}" class="peer sr-only" {{ old('kategori_pemohon') == $value ? 'checked' : '' }} required>
                                    <span class="text-xs font-medium text-gray-700 peer-checked:text-myunila">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('kategori_pemohon')
                            <p class="mt-2 text-sm text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <x-komponen.formulir.input name="nama_organisasi" label="Nama Lembaga / Organisasi / Kegiatan" placeholder="Contoh: Fakultas Teknik / BEM Universitas / Seminar Nasional IT" :required="true" />
                </div>
            </x-komponen.formulir.bagian>

            {{-- ═══ Section 3: Penanggung Jawab Administratif ═══ --}}
            <x-komponen.formulir.bagian number="3" title="Penanggung Jawab Administratif" subtitle="Pejabat yang akan menandatangani formulir permohonan">
                <div class="grid gap-6 md:grid-cols-2">
                    <x-komponen.formulir.radio-group name="kategori_admin" label="Kategori Penanggung Jawab" :options="['dosen' => 'Dosen', 'tendik' => 'Tendik']" :required="true" class="md:col-span-2" />
                    <x-komponen.formulir.input name="admin_responsible_name" label="Nama" placeholder="Contoh: Dr. Ir. Ahmad Sudrajat, M.T." :required="true" class="md:col-span-2" />
                    <x-komponen.formulir.input name="admin_responsible_position" label="Jabatan" placeholder="Contoh: Dekan / Ketua Jurusan / Kepala UPT" :required="true" />
                    <x-komponen.formulir.input name="admin_responsible_nip" label="No. Identitas (NIP/NIDN)" placeholder="Contoh: 198501012010011001" />
                    <x-komponen.formulir.input name="admin_alamat_kantor" label="Alamat Kantor" placeholder="Gedung A Lt. 2, Fakultas Teknik Unila" />
                    <x-komponen.formulir.input name="admin_alamat_rumah" label="Alamat Rumah" placeholder="Jl. Contoh No. 123, Bandar Lampung" />
                    <x-komponen.formulir.input name="admin_telepon_kantor" label="No. Telepon Kantor" type="tel" placeholder="(0721) 123456" />
                    <x-komponen.formulir.input name="admin_responsible_phone" label="No. Telepon Rumah / HP" type="tel" placeholder="081234567890" :required="true" />
                    <x-komponen.formulir.input name="admin_email" label="Email" type="email" placeholder="email@unila.ac.id" :required="true" class="md:col-span-2" />
                </div>
            </x-komponen.formulir.bagian>

            {{-- ═══ Section 4: Penanggung Jawab Teknis ═══ --}}
            <x-komponen.formulir.bagian number="4" title="Penanggung Jawab Teknis" subtitle="Orang yang akan mengelola akun hosting (bisa sama dengan pemohon)">
                <div class="mb-6 rounded-lg border border-info/30 bg-info-light p-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="fill_from_user" class="h-4 w-4 rounded border-gray-300 text-myunila focus:ring-myunila">
                        <span class="text-sm text-gray-700">Gunakan data saya sebagai Penanggung Jawab Teknis</span>
                    </label>
                </div>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Kategori Penanggung Jawab Teknis <span class="text-error">*</span></label>
                        <div class="flex gap-4">
                            @foreach(['mahasiswa' => 'Mahasiswa', 'dosen' => 'Dosen', 'tendik' => 'Tendik'] as $val => $lbl)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="kategori_teknis" value="{{ $val }}" required
                                    class="h-4 w-4 border-gray-300 text-myunila focus:ring-myunila"
                                    onchange="updateTechIdentityLabel()"
                                    {{ old('kategori_teknis', 'mahasiswa') == $val ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">{{ $lbl }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <x-komponen.formulir.input name="tech_name" label="Nama" placeholder="Nama lengkap pengelola teknis" :required="true" class="md:col-span-2"
                        data-user-name="{{ $user->name ?? '' }}" />
                    
                    <div>
                        <label for="tech_nip" id="tech_nip_label" class="mb-1 block text-sm font-medium text-gray-700">
                            NPM <span class="text-error">*</span>
                        </label>
                        <input type="text" name="tech_nip" id="tech_nip"
                            value="{{ old('tech_nip') }}" placeholder="NPM" required
                            data-user-nip="{{ $user->nomor_identitas ?? '' }}"
                            class="form-input @error('tech_nip') form-input-error @enderror">
                        @error('tech_nip') <p class="mt-1 text-sm text-error">{{ $message }}</p> @enderror
                    </div>

                    <x-komponen.formulir.input name="tech_nik" label="NIK / Passport" placeholder="Nomor NIK atau Passport" :required="true" />
                    <x-komponen.formulir.input name="tech_phone" label="No. Telepon" type="tel" placeholder="081234567890" :required="true" />
                    <x-komponen.formulir.input name="tech_alamat_kantor" label="Alamat Kantor" placeholder="Alamat kantor/kampus" />
                    <x-komponen.formulir.input name="tech_alamat_rumah" label="Alamat Rumah" placeholder="Alamat rumah" />
                    <x-komponen.formulir.input name="tech_email" label="Email" type="email" placeholder="email@students.unila.ac.id" :required="true" class="md:col-span-2"
                        data-user-email="{{ $user->email ?? '' }}" />
                </div>
            </x-komponen.formulir.bagian>

            {{-- ═══ Section 5: Data Layanan yang Diminta ═══ --}}
            @php
                $section5Title = match($type) {
                    'vps'     => 'Spesifikasi VPS yang Diminta',
                    'hosting' => 'Data Hosting yang Diminta',
                    default   => 'Nama Sub Domain yang Diminta',
                };
            @endphp
            <div id="section_layanan_baru" class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 bg-myunila-50 px-6 py-4">
                    <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-900">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-myunila text-xs font-bold text-white">5</span>
                        <span id="section5_title">{{ $section5Title }}</span>
                    </h2>
                    <p id="section5_subtitle" class="mt-1 text-sm text-gray-500">Isi data layanan baru yang ingin Anda ajukan</p>
                </div>
                <div class="p-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Domain/Hostname Field (tetap manual karena ada suffix & availability check) --}}
                        <div id="requested_domain_wrapper" class="md:col-span-2">
                            <label for="requested_domain" class="mb-1 block text-sm font-medium text-gray-700">
                                @if($type === 'vps') Hostname VPS
                                @elseif($type === 'hosting') Nama Akun Hosting
                                @else Sub Domain
                                @endif
                                <span class="text-error">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1 max-w-xs">
                                    <input type="text" name="requested_domain" id="requested_domain"
                                        value="{{ old('requested_domain') }}"
                                        placeholder="{{ $type === 'vps' ? 'vps-namamu' : ($type === 'hosting' ? 'hosting-namamu' : 'namadomain') }}"
                                        minlength="2" maxlength="12" pattern="[a-z0-9\-]+"
                                        class="form-input w-full pr-10 @error('requested_domain') form-input-error @enderror">
                                    <div id="domain_check_icon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                        <svg class="checking h-5 w-5 animate-spin text-gray-400 hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <svg class="available h-5 w-5 text-success hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <svg class="taken h-5 w-5 text-error hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                </div>
                                @if($type !== 'vps')
                                    <span class="whitespace-nowrap text-lg font-semibold text-myunila">.unila.ac.id</span>
                                @endif
                            </div>
                            <div id="domain_availability_message" class="mt-2 text-sm hidden"></div>
                            <p class="mt-2 text-sm text-gray-500">
                                <span class="font-medium">Ketentuan:</span> Minimal 2 karakter, maksimal 12 karakter. Hanya huruf kecil, angka, dan tanda hubung (-).
                            </p>
                            @error('requested_domain')
                                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- VPS-specific fields --}}
                        @if($type === 'vps')
                            <x-komponen.formulir.select name="vps_cpu" label="Jumlah CPU Core" :options="['1' => '1 Core', '2' => '2 Core', '4' => '4 Core']" placeholder="Pilih jumlah CPU" :required="true" />
                            <x-komponen.formulir.select name="vps_ram" label="RAM" :options="['1' => '1 GB', '2' => '2 GB', '4' => '4 GB', '8' => '8 GB']" placeholder="Pilih kapasitas RAM" :required="true" />
                            <x-komponen.formulir.select name="vps_storage" label="Storage" :options="['20' => '20 GB', '40' => '40 GB', '80' => '80 GB', '100' => '100 GB']" placeholder="Pilih kapasitas storage" :required="true" />
                            <x-komponen.formulir.select name="vps_os" label="Sistem Operasi" :options="['ubuntu-22.04' => 'Ubuntu 22.04 LTS', 'ubuntu-20.04' => 'Ubuntu 20.04 LTS', 'centos-8' => 'CentOS 8', 'debian-11' => 'Debian 11']" placeholder="Pilih OS" :required="true" />
                            <x-komponen.formulir.textarea name="vps_purpose" label="Tujuan Penggunaan VPS" rows="3" placeholder="Jelaskan tujuan penggunaan VPS, misalnya: untuk hosting aplikasi SIAKAD, web service API, dll." :required="true" class="md:col-span-2" />
                        @endif

                        {{-- Hosting-specific fields --}}
                        @if($type === 'hosting')
                            <x-komponen.formulir.select name="hosting_quota" label="Kuota Storage" :options="['500' => '500 MB', '1000' => '1 GB', '2000' => '2 GB', '5000' => '5 GB']" placeholder="Pilih kuota" :required="true" />
                        @endif

                        {{-- Password field --}}
                        <x-komponen.formulir.input name="admin_password" label="Admin Password (Hint)" placeholder="Kata kunci password (6-8 karakter)"
                            hint="<span class='font-medium'>Ketentuan:</span> Minimal 6 karakter, maksimal 8 karakter. Password final akan digenerate oleh tim TIK."
                            class="{{ $type === 'hosting' ? '' : 'md:col-span-2' }}" />
                    </div>
                </div>
            </div>

            {{-- Hidden fields for DB compatibility --}}
            <input type="hidden" name="unit_id" value="{{ $categories->first()?->units->first()?->id ?? '' }}">
            <input type="hidden" name="application_name" id="hidden_application_name" value="">
            <input type="hidden" name="description" id="hidden_description" value="">

            {{-- Persetujuan --}}
            <x-komponen.formulir.bagian title="Persetujuan" headerBg="bg-warning-light">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="agreement" required class="mt-1 h-4 w-4 rounded border-gray-300 text-myunila focus:ring-myunila">
                    <span class="text-sm text-gray-700">
                        Dengan ini saya menyatakan bahwa data di atas adalah benar. Saya bertindak atas nama institusi yang saya wakili dan saya mematuhi semua aturan yang ditentukan dan berlaku bagi seluruh pengguna fasilitas layanan Hosting Universitas Lampung.
                    </span>
                </label>
            </x-komponen.formulir.bagian>

            {{-- Info Box --}}
            <div class="rounded-2xl border border-info/30 bg-info-light p-6">
                <div class="flex gap-4">
                    <div class="shrink-0">
                        <x-icon name="information-circle" class="h-6 w-6 text-info" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Langkah Selanjutnya</h3>
                        <p class="mt-1 text-sm text-gray-700">
                            Setelah submit, sistem akan otomatis membuat <strong>2 formulir</strong>:
                        </p>
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-lg border border-myunila/20 bg-white p-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-icon name="computer-desktop" class="h-5 w-5 text-myunila" />
                                    <span class="font-medium text-gray-900">Paperless (Digital)</span>
                                </div>
                                <p class="text-xs text-gray-600">Untuk administrasi internal TIK. Tersimpan otomatis di sistem.</p>
                            </div>
                            <div class="rounded-lg border border-myunila/20 bg-white p-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-icon name="document-text" class="h-5 w-5 text-myunila" />
                                    <span class="font-medium text-gray-900">Hardcopy (PDF)</span>
                                </div>
                                <p class="text-xs text-gray-600">Untuk dicetak & ditandatangani atasan (Kajur/Dekan/Wakil Rektor).</p>
                            </div>
                        </div>
                        <ol class="mt-4 list-inside list-decimal space-y-1 text-sm text-gray-700">
                            <li>Download formulir PDF yang sudah terisi otomatis</li>
                            <li>Cetak formulir dan minta <strong>tanda tangan basah</strong> dari atasan</li>
                            <li>Scan formulir yang sudah ditandatangani</li>
                            <li>Upload scan formulir beserta foto/scan identitas (KTM/Karpeg)</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ url('/') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <x-icon name="check-circle" class="h-5 w-5" />
                    Buat Formulir Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// ═══════════════════════════════════════════════════
// Dynamic label for tech identity (NPM vs NIP/NIDN)
// ═══════════════════════════════════════════════════
function updateTechIdentityLabel() {
    const kategoriTech = document.querySelector('input[name="kategori_teknis"]:checked')?.value;
    const techNipLabel = document.getElementById('tech_nip_label');
    const techNipInput = document.getElementById('tech_nip');
    if (!techNipLabel || !techNipInput) return;
    
    if (kategoriTech === 'mahasiswa') {
        techNipLabel.innerHTML = 'NPM <span class="text-error">*</span>';
        techNipInput.placeholder = 'NPM';
    } else {
        techNipLabel.innerHTML = 'NIP/NIDN <span class="text-error">*</span>';
        techNipInput.placeholder = 'NIP/NIDN';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateTechIdentityLabel();
    
    // ── Auto-fill from logged-in user ──
    const fillCheckbox = document.getElementById('fill_from_user');
    const techName  = document.getElementById('tech_name');
    const techNip   = document.getElementById('tech_nip');
    const techEmail = document.getElementById('tech_email');
    
    fillCheckbox?.addEventListener('change', function() {
        if (this.checked) {
            if (techName)  techName.value  = techName.dataset.userName  || '';
            if (techNip)   techNip.value   = techNip.dataset.userNip    || '';
            if (techEmail) techEmail.value = techEmail.dataset.userEmail || '';
        } else {
            if (techName)  techName.value  = '';
            if (techNip)   techNip.value   = '';
            if (techEmail) techEmail.value = '';
        }
    });

    // ── DOM References ──
    const tipePengajuanRadios     = document.querySelectorAll('input[name="tipe_pengajuan"]');
    const existingServiceSection  = document.getElementById('existing_service_section');
    const existingTicket          = document.getElementById('existing_ticket');
    const existingDomain          = document.getElementById('existing_domain');
    const existingNotes           = document.getElementById('existing_notes');
    const existingExpired         = document.getElementById('existing_expired');
    const sectionLayananBaru      = document.getElementById('section_layanan_baru');
    const requestedDomainWrapper  = document.getElementById('requested_domain_wrapper');
    const requestedDomain         = document.getElementById('requested_domain');
    const adminPassword           = document.getElementById('admin_password');
    const keteranganLabel         = document.getElementById('keterangan_label');
    const keteranganHint          = document.getElementById('keterangan_hint');
    const vpsCpu       = document.getElementById('vps_cpu');
    const vpsRam       = document.getElementById('vps_ram');
    const vpsStorage   = document.getElementById('vps_storage');
    const vpsOs        = document.getElementById('vps_os');
    const vpsPurpose   = document.getElementById('vps_purpose');
    const hostingQuota = document.getElementById('hosting_quota');

    // ── Section Toggle Logic ──
    function toggleFormSections() {
        const selectedTipe      = document.querySelector('input[name="tipe_pengajuan"]:checked')?.value;
        const isPengajuanBaru   = selectedTipe === 'pengajuan_baru';
        const isLaporanMasalah  = selectedTipe === 'laporan_masalah';
        const needsExistingInfo = ['perpanjangan', 'perubahan_data', 'upgrade_downgrade', 'penonaktifan', 'laporan_masalah'].includes(selectedTipe);
        
        if (needsExistingInfo) {
            existingServiceSection.classList.remove('hidden');
            existingDomain.setAttribute('required', 'required');
            existingNotes.setAttribute('required', 'required');
            
            const labels = {
                laporan_masalah:   { label: 'Detail Masalah',           hint: 'Jelaskan masalah yang Anda alami secara detail', placeholder: 'Jelaskan masalah yang Anda alami secara detail...' },
                perpanjangan:      { label: 'Keterangan Perpanjangan',  hint: 'Jelaskan alasan perpanjangan layanan',          placeholder: 'Contoh: Layanan masih aktif digunakan...' },
                perubahan_data:    { label: 'Detail Perubahan',         hint: 'Jelaskan perubahan data yang diminta',           placeholder: 'Contoh: Ubah pointing domain ke IP xxx...' },
                upgrade_downgrade: { label: 'Detail Upgrade/Downgrade', hint: 'Jelaskan perubahan spesifikasi yang diminta',    placeholder: 'Contoh: Upgrade dari 500MB ke 2GB...' },
                penonaktifan:      { label: 'Alasan Penonaktifan',      hint: 'Jelaskan alasan penonaktifan layanan',           placeholder: 'Contoh: Kegiatan sudah selesai...' },
            };
            const cfg = labels[selectedTipe] || labels['perpanjangan'];
            if (keteranganLabel) keteranganLabel.textContent = cfg.label;
            if (keteranganHint)  keteranganHint.textContent  = cfg.hint;
            if (existingNotes)   existingNotes.placeholder   = cfg.placeholder;
        } else {
            existingServiceSection.classList.add('hidden');
            existingDomain.removeAttribute('required');
            existingNotes.removeAttribute('required');
            if (existingTicket) existingTicket.value = '';
            if (existingDomain) existingDomain.value = '';
            if (existingNotes)  existingNotes.value  = '';
            if (existingExpired) existingExpired.value = '';
        }
        
        const vpsFields     = [vpsCpu, vpsRam, vpsStorage, vpsOs, vpsPurpose];
        const hostingFields = [hostingQuota];
        const setRequired   = (els, req) => els.forEach(el => el && (req ? el.setAttribute('required','required') : el.removeAttribute('required')));

        if (isPengajuanBaru) {
            sectionLayananBaru.classList.remove('hidden');
            requestedDomainWrapper.classList.remove('hidden');
            requestedDomain.setAttribute('required', 'required');
            if (adminPassword) adminPassword.setAttribute('required', 'required');
            setRequired(vpsFields, true);
            setRequired(hostingFields, true);
        } else if (selectedTipe === 'upgrade_downgrade') {
            sectionLayananBaru.classList.remove('hidden');
            requestedDomainWrapper.classList.add('hidden');
            requestedDomain.removeAttribute('required');
            if (adminPassword) adminPassword.removeAttribute('required');
            setRequired(vpsFields, true);
            if (vpsOs) vpsOs.removeAttribute('required');
            if (vpsPurpose) vpsPurpose.removeAttribute('required');
            setRequired(hostingFields, true);
        } else {
            sectionLayananBaru.classList.add('hidden');
            requestedDomain.removeAttribute('required');
            if (adminPassword) adminPassword.removeAttribute('required');
            setRequired(vpsFields, false);
            setRequired(hostingFields, false);
        }
    }

    tipePengajuanRadios.forEach(radio => radio.addEventListener('change', toggleFormSections));
    toggleFormSections();

    // ── Auto-fill hidden fields on submit ──
    document.querySelector('form').addEventListener('submit', function() {
        const namaOrg = document.getElementById('nama_organisasi')?.value || '';
        const domain  = requestedDomain?.value || existingDomain?.value || '';
        document.getElementById('hidden_application_name').value = namaOrg || domain;
        
        const selectedTipe = document.querySelector('input[name="tipe_pengajuan"]:checked')?.value;
        const tipeLabel = {
            'pengajuan_baru': 'Pengajuan Baru', 'perpanjangan': 'Perpanjangan',
            'perubahan_data': 'Perubahan Data', 'upgrade_downgrade': 'Upgrade/Downgrade',
            'penonaktifan': 'Penonaktifan', 'laporan_masalah': 'Laporan Masalah'
        }[selectedTipe] || 'Permohonan';
        
        document.getElementById('hidden_description').value = tipeLabel + ' layanan untuk ' + namaOrg;
    });

    // ── Domain formatting & availability ──
    requestedDomain?.addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9\-]/g, '');
        checkDomainAvailability();
    });
    existingDomain?.addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9\-\.]/g, '');
    });

    // ── Ticket Auto-fill ──
    let ticketFetchTimeout;
    const ticketLoadingEl    = document.getElementById('ticket_loading');
    const ticketSuccessEl    = document.getElementById('ticket_success');
    const ticketErrorEl      = document.getElementById('ticket_error');
    const ticketErrorMessage = document.getElementById('ticket_error_message');
    
    existingTicket?.addEventListener('input', function() {
        clearTimeout(ticketFetchTimeout);
        ticketLoadingEl?.classList.add('hidden');
        ticketSuccessEl?.classList.add('hidden');
        ticketErrorEl?.classList.add('hidden');
        
        const ticketNumber = this.value.trim().toUpperCase();
        this.value = ticketNumber;
        if (ticketNumber.length < 10) return;
        
        ticketLoadingEl?.classList.remove('hidden');
        ticketFetchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/api/submission-by-ticket/${encodeURIComponent(ticketNumber)}`);
                const result = await response.json();
                ticketLoadingEl?.classList.add('hidden');
                
                if (result.success && result.data) {
                    const data = result.data;
                    if (existingDomain)  existingDomain.value  = data.domain || '';
                    if (existingExpired) existingExpired.value = data.expired_date || '';
                    
                    const fields = {
                        'nama_organisasi': data.nama_organisasi,
                        'admin_responsible_name': data.admin_name,
                        'admin_responsible_position': data.admin_position,
                        'admin_responsible_nip': data.admin_nip,
                        'admin_email': data.admin_email,
                        'admin_responsible_phone': data.admin_phone,
                        'admin_telepon_kantor': data.admin_telepon_kantor,
                        'admin_alamat_kantor': data.admin_alamat_kantor,
                        'admin_alamat_rumah': data.admin_alamat_rumah,
                        'tech_name': data.tech_name,
                        'tech_nip': data.tech_nip,
                        'tech_nik': data.tech_nik,
                        'tech_email': data.tech_email,
                        'tech_phone': data.tech_phone,
                        'tech_alamat_kantor': data.tech_alamat_kantor,
                        'tech_alamat_rumah': data.tech_alamat_rumah,
                    };
                    Object.entries(fields).forEach(([id, val]) => {
                        const el = document.getElementById(id);
                        if (el && val) el.value = val;
                    });
                    
                    ['kategori_pemohon', 'kategori_admin', 'kategori_teknis'].forEach(name => {
                        const key = name === 'kategori_teknis' ? 'kategori_tech' : name;
                        if (data[key]) {
                            const radio = document.querySelector(`input[name="${name}"][value="${data[key]}"]`);
                            if (radio) { radio.checked = true; if (name === 'kategori_teknis') updateTechIdentityLabel(); }
                        }
                    });
                    
                    ticketSuccessEl?.classList.remove('hidden');
                    setTimeout(() => ticketSuccessEl?.classList.add('hidden'), 5000);
                } else {
                    ticketErrorMessage.textContent = result.message || 'Tiket tidak ditemukan.';
                    ticketErrorEl?.classList.remove('hidden');
                }
            } catch (error) {
                ticketLoadingEl?.classList.add('hidden');
                ticketErrorMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                ticketErrorEl?.classList.remove('hidden');
            }
        }, 800);
    });

    // ── Domain Availability Checker ──
    let domainCheckTimeout;
    function checkDomainAvailability() {
        clearTimeout(domainCheckTimeout);
        const domainInput       = requestedDomain.value.trim();
        const iconContainer     = document.getElementById('domain_check_icon');
        const messageContainer  = document.getElementById('domain_availability_message');
        const checkingIcon      = iconContainer.querySelector('.checking');
        const availableIcon     = iconContainer.querySelector('.available');
        const takenIcon         = iconContainer.querySelector('.taken');
        
        [iconContainer, checkingIcon, availableIcon, takenIcon, messageContainer].forEach(el => el.classList.add('hidden'));
        if (domainInput.length < 2) return;
        
        iconContainer.classList.remove('hidden');
        checkingIcon.classList.remove('hidden');
        
        domainCheckTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/api/check-domain?domain=${encodeURIComponent(domainInput)}`);
                const data = await response.json();
                checkingIcon.classList.add('hidden');
                
                if (data.available) {
                    availableIcon.classList.remove('hidden');
                    messageContainer.innerHTML = '<span class="text-success font-medium">✓ Domain tersedia</span>';
                    requestedDomain.classList.remove('border-error'); requestedDomain.classList.add('border-success');
                } else {
                    takenIcon.classList.remove('hidden');
                    messageContainer.innerHTML = '<span class="text-error font-medium">✗ Domain sudah digunakan</span>';
                    requestedDomain.classList.remove('border-success'); requestedDomain.classList.add('border-error');
                }
                messageContainer.classList.remove('hidden');
            } catch (e) {
                checkingIcon.classList.add('hidden');
                iconContainer.classList.add('hidden');
            }
        }, 500);
    }
});
</script>
@endpush
@endsection
