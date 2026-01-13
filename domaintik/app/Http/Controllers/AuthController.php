<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Menampilkan halaman login (GANTI KE DUMMY SSO).
     */
    public function index(): View
    {
        // Jangan tampilkan form login biasa (email/pass)
        // Tampilkan form simulasi SSO
        return view('auth.dummy_login'); 
    }

    /**
     * Memproses login (LOGIKA SIMULASI SSO).
     * Perhatikan: Kita ganti LoginRequest jadi Request biasa 
     * karena kita tidak butuh validasi password di sini.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input Dummy
        $request->validate([
            'sso_group' => 'required', // mahasiswa, dosen, tendik
            'nip'       => 'required',
            'nama'      => 'required',
        ]);

        $ssoGroup = $request->input('sso_group');
        $nip      = $request->input('nip');
        $nama     = $request->input('nama');

        // 2. LOGIKA MAPPING ROLE (SSO Group -> Database Role ID)
        $mapping = DB::table('referensi.sso_role_mappings')
                     ->where('sso_group_name', $ssoGroup)
                     ->first();

        // Default ke Role 1 (Pemohon) jika mapping tidak ketemu
        $targetRoleId = $mapping ? $mapping->target_role_id : 1;

        // 3. CEK WHITELIST (Override untuk Admin/Verifikator)
        // NIP "Dewa" yang kita seed tadi
        $adminNips = [
            '198702152011012002' => 2, // Siti -> Verifikator
            '199003202015011003' => 3, // Andi -> Eksekutor
            '198501012010011001' => 4, // Admin TIK -> Super Admin
        ];

        if (array_key_exists($nip, $adminNips)) {
            $targetRoleId = $adminNips[$nip];
        }

        // 4. FIND OR CREATE USER (Auto-Provisioning)
        $user = User::where('nomor_identitas', $nip)->first();

        if (!$user) {
            // Jika user belum ada, buat baru
            $user = User::create([
                'name' => $nama,
                'email' => $nip . '@simulation.unila.ac.id', // Email dummy
                'nomor_identitas' => $nip,
                'role_id' => $targetRoleId, // Simpan Role ID hasil mapping
                'password' => bcrypt('password'), // Password default (tidak dipakai login)
            ]);
        } else {
            // Optional: Update nama jika berubah dari SSO
             $user->update(['name' => $nama]);
        }

        // 5. LOGIN PAKSA (Tanpa Cek Password)
        Auth::login($user);
        $request->session()->regenerate();

        // 6. Redirect ke Dashboard
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout (TETAP PAKAI YANG LAMA).
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Gunakan service yang sudah ada atau logout manual
        if (method_exists($this->authService, 'logout')) {
            $this->authService->logout();
        } else {
            // Fallback jika AuthService belum siap
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }
}