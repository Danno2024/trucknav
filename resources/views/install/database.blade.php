@extends('install.layout', ['step' => 2])
@section('step', 'Database')
@section('content')
    <h1 class="text-xl font-bold text-gray-900 mb-1">Database Setup</h1>
    <p class="text-sm text-gray-500 mb-6">Choose where to store data. SQLite needs no setup; MySQL suits larger deployments.</p>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.database.store') }}" x-data="{ driver: '{{ old('driver', 'sqlite') }}', sqliteDb: '{{ old('driver', 'sqlite') === 'sqlite' ? old('database', 'database.sqlite') : 'database.sqlite' }}', mysqlDb: '{{ old('driver') === 'mysql' ? old('database', '') : '' }}' }">
        @csrf
        <input type="hidden" name="database" :value="driver === 'mysql' ? mysqlDb : sqliteDb">

        <div class="grid grid-cols-2 gap-3 mb-5">
            <label class="cursor-pointer">
                <input type="radio" name="driver" value="sqlite" x-model="driver" class="peer sr-only">
                <div class="rounded-lg border-2 p-4 text-center peer-checked:border-maroon-600 peer-checked:bg-maroon-50 border-gray-200 transition">
                    <div class="text-sm font-semibold text-gray-900">SQLite</div>
                    <div class="text-xs text-gray-500 mt-0.5">File-based, zero setup</div>
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="driver" value="mysql" x-model="driver" class="peer sr-only">
                <div class="rounded-lg border-2 p-4 text-center peer-checked:border-maroon-600 peer-checked:bg-maroon-50 border-gray-200 transition">
                    <div class="text-sm font-semibold text-gray-900">MySQL</div>
                    <div class="text-xs text-gray-500 mt-0.5">Separate database server</div>
                </div>
            </label>
        </div>

        <div x-show="driver === 'sqlite'" class="mb-4">
            <label for="sqlite_path" class="block text-sm font-medium text-gray-700 mb-1">SQLite File</label>
            <input id="sqlite_path" type="text" x-model="sqliteDb"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            <p class="text-xs text-gray-400 mt-1">Stored in the database folder. Created automatically if missing.</p>
        </div>

        <div x-show="driver === 'mysql'" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div class="sm:col-span-2">
                <label for="db_name" class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                <input id="db_name" type="text" x-model="mysqlDb" placeholder="truckroute"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div>
                <label for="db_host" class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                <input id="db_host" type="text" name="host" value="{{ old('host', '127.0.0.1') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div>
                <label for="db_port" class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <input id="db_port" type="number" name="port" value="{{ old('port', '3306') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div>
                <label for="db_user" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input id="db_user" type="text" name="username" value="{{ old('username', 'root') }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div>
                <label for="db_pass" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="db_pass" type="password" name="password"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
        </div>

        <p class="text-xs text-gray-400 mb-5">Tables are created automatically in the next step.</p>

        <div class="flex items-center justify-between">
            <a href="{{ route('install.welcome') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back</a>
            <button type="submit" class="px-5 py-2.5 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">
                Test &amp; Migrate &rarr;
            </button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
@endsection
