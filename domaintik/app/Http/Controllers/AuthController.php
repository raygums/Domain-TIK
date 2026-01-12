<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function index(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $this->authService->login(
            $request->email,
            $request->password,
            $request->boolean('remember')
        );

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(): RedirectResponse
    {
        $this->authService->logout();
        return redirect('/');
    }
}