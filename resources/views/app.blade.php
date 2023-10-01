<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', '') }}</title>
    <!-- Scripts -->
    @routes

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
    <link rel="icon"
        href="{{ asset('assets/img/267597133-dd2b54bd-7929-4d4f-94f6-e00db9b38272.png?h=25229d02805597a50cb766a729b2a35b') }}"
        type="image/x-icon" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @inertiaHead

</head>

<body class="bg-gray-100 font-sans antialiased">
    @inertia
</body>

</html>
