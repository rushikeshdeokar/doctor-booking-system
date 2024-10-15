<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- jQuery (required for DataTables) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>

       
        
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            <div class="w-1/4 bg-gray-800 text-white min-h-screen">
                <div class="p-4">
                    <h3 class="text-lg font-semibold">Modules</h3>
                    <ul class="mt-4">
                        @if(auth()->user()->role == "patient")
                        <li class="mb-2">
                            <a href="{{ route('appointments.index') }}" class="text-gray-300 hover:text-white">Appointments</a>
                        </li>
                        @endif
                        @if(auth()->user()->role == "doctor")
                        <li class="mb-2">
                            <a href="{{ route('appointments.index') }}" class="text-gray-300 hover:text-white">Doctors</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="w-3/4">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    @yield('content') <!-- This is where the page-specific content will go -->
                </main>
            </div>
        </div>
    </body>
</html>
