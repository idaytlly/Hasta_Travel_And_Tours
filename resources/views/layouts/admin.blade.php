<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASTA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">

    <aside class="w-20 bg-gradient-to-b from-red-500 to-red-600 flex flex-col items-center py-6 space-y-8 shadow-xl">
        <div class="bg-white px-3 py-1 rounded-md font-bold text-red-500 text-xs">HASTA</div>
        
        <nav class="flex flex-col space-y-6 flex-1">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-700' : '' }} text-white p-3 rounded-lg"><i class="fas fa-th text-xl"></i></a>
            <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings*') ? 'bg-red-700' : '' }} text-white p-3 rounded-lg"><i class="fas fa-edit text-xl"></i></a>
            <a href="{{ route('admin.cars.index') }}" class="{{ request()->routeIs('admin.cars*') ? 'bg-red-700' : '' }} text-white p-3 rounded-lg"><i class="fas fa-car text-xl"></i></a>
        </nav>

        <form action="{{ route('logout') }}" method="POST">@csrf
            <button class="text-white hover:bg-red-700 p-3 rounded-lg"><i class="fas fa-sign-out-alt text-xl"></i></button>
        </form>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
            <h2 class="font-bold text-gray-700">@yield('header_title', 'Admin Dashboard')</h2>
            <div class="flex items-center space-x-3">
                <span class="text-sm font-bold">{{ Auth::user()->name }}</span>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=ef4444&color=fff" class="w-8 h-8 rounded-full">
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>