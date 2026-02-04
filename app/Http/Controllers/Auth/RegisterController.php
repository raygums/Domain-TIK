<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * RegisterController - Handle Registrasi User Mandiri
 * 
 * Tanggung Jawab (Single Responsibility):
 * - Menampilkan form registrasi
 * - Menerima input registrasi dan delegate ke UserService
 * - Handle response (success/error) dan redirect
 * 
 * Design Pattern:
 * - Dependency Injection untuk UserService
 * - Thin Controller: Logic ada di Service Layer
 * - Form Request untuk validation
 */
class RegisterController extends Controller
{
    /**
     * UserService instance untuk business logic.
     */
    protected UserService $userService;

    /**
     * Constructor - Inject dependencies.
     * 
     * @param  UserService  $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the registration form.
     * 
     * GET /register
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     * 
     * POST /register
     * 
     * Flow:
     * 1. Data sudah tervalidasi oleh RegisterRequest
     * 2. Delegate ke UserService untuk proses registrasi
     * 3. Redirect ke login dengan flash message
     * 
     * @param  RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // Data sudah tervalidasi di RegisterRequest
        $validatedData = $request->validated();

        // Tambahkan file upload ke data jika ada
        if ($request->hasFile('file_ktp_ktm')) {
            $validatedData['file_ktp_ktm'] = $request->file('file_ktp_ktm');
        }

        // Delegate ke service layer
        $result = $this->userService->register($validatedData);

        // Handle response
        if ($result['success']) {
            return redirect()
                ->route('login')
                ->with('success', $result['message'])
                ->with('registered', true); // Flag untuk show special message
        }

        // Jika gagal, kembali ke form dengan error
        return back()
            ->withInput($request->except('kata_sandi', 'kata_sandi_confirmation'))
            ->with('error', $result['message']);
    }
}
