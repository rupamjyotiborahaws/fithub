<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Security Meta Tags --}}
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
    <meta http-equiv="Permissions-Policy" content="camera=(), microphone=(), geolocation=(), payment=()">
    <meta name="robots" content="noindex, nofollow" />
    
    {{-- Cache Control Meta Tags --}}
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <title>@yield('title', config('app.name', 'FitHub'))</title>
    {{-- Styles --}}
    @vite(['resources/css/app.css',
            'resources/css/admin.css',
            'resources/css/toastr.css'
    ])
    {{-- Scripts --}}
    @vite(['resources/js/app.js',
           'resources/js/jquery-3.7.1.min.js',
           'resources/js/toastr.min.js',
           'resources/js/admin_dashboard.js',
           'resources/js/layout.js'
    ])
    @stack('head')
</head>
<body>
    <div class="container-fluid">
        <div class="row bg-white border-b shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="font-semibold text-lg" style="text-decoration: none;">{{ $client_settings['name'] }}</a>
    
                <nav class="flex items-center gap-6">
                    @auth
                        {{-- Common links --}}
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600" style="text-decoration: none; font-size:17px; color: #3490dc;">Dashboard</a>
                        @if(auth()->user()->isAdmin)
                            <!-- <a href="{{ route('member.registration') }}" class="text-sm hover:text-indigo-600" style="text-decoration: none;">Member Management</a> -->
                            <div class="relative" id="member-dropdown">
                                <button id="member-button" class="flex items-center gap-1 focus:outline-none" style="text-decoration: none; color: #3490dc;" type="button">
                                    Member
                                    <svg id="member-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                {{-- Dropdown Menu --}}
                                <div id="member-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 sub-menu hidden z-50">
                                    <div class="py-1">
                                        <a href="{{ route('member.registration') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">New Registration</a>
                                        <a href="{{ route('member.progress_tracker') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Progress Tracker</a>
                                        <a href="{{ route('member.allot_trainer') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Allot Trainer</a>
                                        <a href="{{ route('member.transfer_membership') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Transfer Membership</a>
                                    </div>
                                </div>
                            </div>

                            <div class="relative" id="member-dropdown">
                                <button id="trainer-button" class="flex items-center gap-1 focus:outline-none" style="text-decoration: none; color: #3490dc;" type="button">
                                    Trainer
                                    <svg id="trainer-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                {{-- Dropdown Menu --}}
                                <div id="trainer-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 sub-menu hidden z-50">
                                    <div class="py-1">
                                        <a href="{{ route('trainer.registration') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Add Trainer</a>
                                    </div>
                                </div>
                            </div>

                            {{-- Fee Management Dropdown --}}
                            <div class="relative" id="fee-dropdown">
                                <button id="fee-button" class="text-sm hover:text-indigo-600 flex items-center gap-1 focus:outline-none" style="text-decoration: none; color: #3490dc;" type="button">
                                    Fee
                                    <svg id="fee-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                {{-- Dropdown Menu --}}
                                <div id="fee-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 sub-menu hidden z-50">
                                    <div class="py-1">
                                        <a href="{{ route('get_fee_collection') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Fee Collect</a>
                                    </div>
                                </div>
                            </div>

                            {{-- Attendance Management Dropdown --}}
                            <div class="relative" id="attendance-dropdown">
                                <button id="attendance-button" class="text-sm hover:text-indigo-600 flex items-center gap-1 focus:outline-none" style="text-decoration: none; color: #3490dc;" type="button">
                                    Attendance
                                    <svg id="attendance-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                {{-- Dropdown Menu --}}
                                <div id="attendance-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 sub-menu hidden z-50">
                                    <div class="py-1">
                                        <a href="{{ route('get_attendance') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Check In</a>
                                        <a href="{{ route('attendance_report') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Report</a>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Settings Dropdown --}}
                            <div class="relative" id="settings-dropdown">
                                <button id="settings-button" class="text-sm hover:text-indigo-600 flex items-center gap-1 focus:outline-none" style="text-decoration: none; color: #3490dc;" type="button">
                                    Settings
                                    <svg id="settings-arrow" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                {{-- Dropdown Menu --}}
                                <div id="settings-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 sub-menu hidden z-50">
                                    <div class="py-1">
                                        <a href="{{ route('get_client') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Client</a>
                                        <a href="{{ route('get_memberships') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Membership</a>
                                        <a href="{{ route('get_config') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-indigo-600" style="text-decoration: none;">Configuration</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="" class="text-sm hover:text-indigo-600" style="text-decoration: none;">Classes</a>
                            <a href="" class="text-sm hover:text-indigo-600" style="text-decoration: none;">Progress</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:underline" style="text-decoration: none; font-size:17px;">Logout</button>
                        </form>
                    @else
                        <a href="" class="text-sm hover:text-indigo-600" style="text-decoration: none;">Login</a>
                        <a href="" class="text-sm hover:text-indigo-600" style="text-decoration: none;">Register</a>
                    @endauth
                </nav>
            </div>
        </div>
        <!-- Loader -->
        <div id="loader" class="loader-overlay">
            <div class="loader"></div>
            <div class="loadercontent"></div>
        </div>
        <main>
            @yield('content')
        </main>

        <footer class="mt-5 border-t bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-xs text-gray-500 flex justify-between">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'FitHub') }}. All rights reserved.</span>
                <span>v{{ config('app.version', '1.0.0') }}</span>
            </div>
        </footer>
    </div>
</body>
</html>