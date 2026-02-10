@extends('layouts.app')

@section('title', 'Pilih Jenis Layanan')

@section('content')
<div class="py-8 lg:py-12">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Pilih Jenis Layanan</h1>
            <p class="mt-3 text-lg text-gray-600">Silakan pilih layanan yang ingin Anda ajukan</p>
        </div>

        {{-- Service Cards --}}
        <div class="grid gap-6 sm:grid-cols-3">
            
            {{-- Domain Service --}}
            <a href="{{ route('submissions.create', ['type' => 'domain']) }}" 
               class="group relative overflow-hidden rounded-2xl border-2 border-myunila-200 bg-white p-8 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:border-myunila-400 hover:shadow-2xl hover:shadow-myunila/30">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-myunila-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-myunila-100"></div>
                <div class="relative text-center">
                    {{-- Icon --}}
                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-unila text-white shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl">
                        <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </div>
                    
                    {{-- Title --}}
                    <h3 class="mb-3 text-2xl font-bold text-gray-900">Domain</h3>
                    
                    {{-- Description --}}
                    <p class="mb-6 text-gray-600">Sub Domain *.unila.ac.id untuk website unit kerja Anda</p>
                    
                    {{-- Features List --}}
                    <ul class="mb-6 space-y-2 text-left text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Format: namadomain.unila.ac.id
                        </li>
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            SSL Certificate Gratis
                        </li>
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            DNS Management
                        </li>
                    </ul>
                    
                    {{-- Button --}}
                    <div class="inline-flex items-center text-myunila font-semibold transition-all duration-300 group-hover:gap-2">
                        Pilih Layanan
                        <svg class="ml-1 h-5 w-5 transition-all duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Hosting Service --}}
            <a href="{{ route('submissions.create', ['type' => 'hosting']) }}" 
               class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-8 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:border-green-400 hover:shadow-2xl hover:shadow-green-500/30">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-green-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-green-100"></div>
                <div class="relative text-center">
                    {{-- Icon --}}
                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl">
                        <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z" opacity=".3"/>
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/>
                            <circle cx="6" cy="7" r=".75" fill="white"/>
                            <circle cx="6" cy="12" r=".75" fill="white"/>
                            <circle cx="6" cy="17" r=".75" fill="white"/>
                        </svg>
                    </div>
                    
                    {{-- Title --}}
                    <h3 class="mb-3 text-2xl font-bold text-gray-900">Hosting</h3>
                    
                    {{-- Description --}}
                    <p class="mb-6 text-gray-600">Web Hosting untuk meng-host website unit kerja</p>
                    
                    {{-- Features List --}}
                    <ul class="mb-6 space-y-2 text-left text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Storage sesuai kebutuhan
                        </li>
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Database MySQL/PostgreSQL
                        </li>
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Control Panel (cPanel)
                        </li>
                    </ul>
                    
                    {{-- Button --}}
                    <div class="inline-flex items-center text-green-600 font-semibold transition-all duration-300 group-hover:gap-2">
                        Pilih Layanan
                        <svg class="ml-1 h-5 w-5 transition-all duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- VPS Service --}}
            <a href="{{ route('submissions.create', ['type' => 'vps']) }}" 
               class="group relative overflow-hidden rounded-2xl border-2 border-gray-200 bg-white p-8 shadow-lg transition-all duration-300 hover:-translate-y-2 hover:border-purple-400 hover:shadow-2xl hover:shadow-purple-500/30">
                <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-purple-50 transition-all duration-300 group-hover:scale-150 group-hover:bg-purple-100"></div>
                <div class="relative text-center">
                    {{-- Icon --}}
                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl">
                        <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zM7 19c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zM7 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                    </div>
                    
                    {{-- Title --}}
                    <h3 class="mb-3 text-2xl font-bold text-gray-900">VPS</h3>
                    
                    {{-- Description --}}
                    <p class="mb-6 text-gray-600">Virtual Private Server dengan akses root penuh</p>
                    
                    {{-- Features List --}}
                    <ul class="mb-6 space-y-2 text-left text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Full Root Access
                        </li>
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Custom OS & Software
                        </li>
                        <li class="flex items-center">
                            <svg class="mr-2 h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Dedicated Resources
                        </li>
                    </ul>
                    
                    {{-- Button --}}
                    <div class="inline-flex items-center text-purple-600 font-semibold transition-all duration-300 group-hover:gap-2">
                        Pilih Layanan
                        <svg class="ml-1 h-5 w-5 transition-all duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

        </div>

        {{-- Back Button --}}
        <div class="mt-12 text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-gray-600 transition hover:text-gray-900">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

    </div>
</div>
@endsection
