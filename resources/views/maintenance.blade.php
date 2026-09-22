<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance Mode - TruckRoute</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">
    <div class="w-full max-w-lg px-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Scheduled Maintenance</h1>
            <p class="text-gray-600 mb-6">{{ $message }}</p>
            <div class="border-t border-gray-100 pt-4">
                <p class="text-sm text-gray-400">We'll be back online shortly. Thank you for your patience.</p>
            </div>
        </div>
        <p class="text-center text-xs text-gray-400 mt-6">Powered by TruckRoute</p>
    </div>
</body>
</html>
