<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-blue-900 via-indigo-800 to-blue-900 overflow-hidden relative px-4 sm:px-0">
        
        {{-- <div class="absolute inset-0 bg-cover bg-center opacity-10 mix-blend-overlay" style="background-image: url('{{ asset('images/pattern.png') }}');"></div> --}}

        <div class="relative w-full sm:max-w-md z-10">
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border-b-4 border-indigo-600">
                
                <div class="pt-10 pb-6 px-8 text-center">
                    <x-application-logo class="w-24 h-24 mx-auto drop-shadow-md" />
                    
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Sistem Domain TIK
                    </h2>
                    <p class="text-indigo-600 font-semibold text-sm uppercase tracking-wider mt-1">
                        Universitas Lampung
                    </p>
                    <p class="text-gray-500 mt-3 text-sm leading-relaxed">
                        Gerbang akses layanan digital terpadu untuk civitas akademika.
                    </p>
                </div>

                <div class="px-8 py-8 bg-gray-50 border-t border-gray-100">
                    
                    @if(session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-lg text-center">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-3 rounded-lg text-center border border-red-200">
                             Login Gagal. Silakan coba lagi atau hubungi admin.
                        </div>
                    @endif

                    <div class="space-y-6">
                        <div class="relative group">
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-blue-500 rounded-lg blur opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
                            <a href="{{ route('auth.sso.redirect') }}" 
                               class="relative w-full flex items-center justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md transition-all transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Masuk dengan SSO UNILA
                            </a>
                        </div>

                        <div class="relative flex py-2 items-center">
                            <div class="flex-grow border-t border-gray-300"></div>
                            <span class="flex-shrink-0 mx-4 text-gray-400 text-xs font-semibold uppercase tracking-wider">Belum memiliki akses?</span>
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>

                        <a href="{{ route('auth.sso.redirect') }}" 
                           class="w-full flex items-center justify-center py-3 px-4 border-2 border-indigo-600 text-sm font-bold rounded-lg text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none transition-all">
                            Daftarkan Akun ke Sistem
                        </a>
                    </div>
                </div>

                <div class="px-8 py-4 bg-gray-100 text-center border-t border-gray-200">
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Gunakan <strong>NIP/NPM</strong> dan <strong>Password SSO</strong> (Siakadu) Anda.<br>
                        Mengalami kendala? <a href="#" class="text-indigo-600 hover:text-indigo-800 font-semibold underline">Hubungi Helpdesk TIK</a>.
                    </p>
                </div>
            </div>
            
            <div class="text-center mt-8 text-blue-200 text-sm opacity-80">
                &copy; {{ date('Y') }} UPT TIK Universitas Lampung. Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</x-guest-layout>