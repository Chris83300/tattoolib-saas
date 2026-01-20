<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile['name'] }} - Artiste Tatoueur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">

        {{-- Header Profil --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start gap-6">

                {{-- Avatar --}}
                <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-4xl">🎨</span>
                </div>

                {{-- Infos --}}
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">{{ $profile['name'] }}</h1>

                    @if ($profile['studio'])
                        <p class="text-gray-600 mb-2">
                            📍 {{ $profile['studio']['name'] }} - {{ $profile['studio']['city'] }}
                        </p>
                    @else
                        <p class="text-gray-600 mb-2">
                            🔹 Tatoueur Indépendant
                        </p>
                    @endif

                    @if ($profile['verified'])
                        <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                            ✓ Vérifié
                        </span>
                    @endif

                    <div class="mt-4">
                        <p class="text-gray-700">{{ $profile['bio'] }}</p>
                    </div>

                    {{-- Spécialités --}}
                    @if (!empty($profile['specialties']))
                        @php
                            $specialties = is_array($profile['specialties'])
                                ? $profile['specialties']
                                : (is_string($profile['specialties'])
                                    ? json_decode($profile['specialties'], true)
                                    : []);
                        @endphp
                        @if (!empty($specialties))
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach ($specialties as $specialty)
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                        {{ $specialty }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Actions --}}
                <div>
                    <a href="#"
                        class="block w-full bg-blue-600 text-white text-center px-6 py-3 rounded-lg hover:bg-blue-700 mb-2">
                        📅 Réserver un RDV
                    </a>

                    @if ($availability)
                        <p class="text-sm text-gray-600 text-center">
                            Prochaine dispo : {{ $availability }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-3xl font-bold text-blue-600">{{ $bookingStats['total_appointments'] }}</p>
                <p class="text-gray-600">Tatouages réalisés</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-3xl font-bold text-blue-600">{{ $bookingStats['rating'] }}</p>
                <p class="text-gray-600">Note moyenne</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-3xl font-bold text-blue-600">{{ count($portfolio) }}</p>
                <p class="text-gray-600">Réalisations</p>
            </div>
        </div>

        {{-- Portfolio --}}
        @if (!empty($portfolio))
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4">Portfolio</h2>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($portfolio as $image)
                        <img src="{{ $image }}" alt="Portfolio" class="w-full h-64 object-cover rounded-lg">
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</body>

</html>
