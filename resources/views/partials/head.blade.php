<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="theme-color" content="#000000">

<title>{{ $title ?? config('app.name') }}</title>

<!-- PWA -->
<link rel="manifest" href="/manifest.json">

<!-- iOS Meta Tags -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="TattooLib">

<link rel="icon" href="{{ asset('logo.ico') }}" sizes="any">
<link rel="icon" href="{{ asset('logo.ico') }}" type="image/x-icon">
<link rel="apple-touch-icon" href="{{ asset('logo.ico') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
