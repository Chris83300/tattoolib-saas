<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pierceur - Tattoolib SaaS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'noir-profond': '#0a0a0a',
                        'ivoire-text': '#f8f8f8',
                        'beige-peau': '#f5e6d3',
                        'gris-fonde': '#1a1a1a',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-noir-profond text-ivoire-text">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-gris-fonde border-b border-ivoire-text/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('pierceur.dashboard') }}" class="text-xl font-bold text-beige-peau">
                            Tattoolib - Pierceur
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('pierceur.dashboard') }}" class="text-ivoire-text hover:text-beige-peau">Dashboard</a>
                        <a href="{{ route('pierceur.portfolio') }}" class="text-ivoire-text hover:text-beige-peau">Portfolio</a>
                        <a href="{{ route('pierceur.clients') }}" class="text-ivoire-text hover:text-beige-peau">Clients</a>
                        <a href="{{ route('pierceur.messages') }}" class="text-ivoire-text hover:text-beige-peau">Messages</a>
                        <a href="{{ route('pierceur.settings') }}" class="text-ivoire-text hover:text-beige-peau">Paramètres</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-ivoire-text hover:text-beige-peau">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gris-fonde border-t border-ivoire-text/20 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-ivoire-text/70">
                    &copy; {{ date('Y') }} Tattoolib SaaS. Tous droits réservés.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
