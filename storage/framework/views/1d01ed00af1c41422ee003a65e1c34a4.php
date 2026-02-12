<!DOCTYPE html>
<html>
<head>
    <title>Vérification en attente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="max-w-2xl w-full p-8 bg-gray-800 rounded-xl text-center">
        <h1 class="text-2xl font-bold mb-4">Votre compte est en cours de vérification</h1>
        <p class="mb-4">Merci de votre inscription ! Votre profil sera examiné par notre équipe dans les 24-48 heures.</p>
        <p>Utilisateur: <?php echo e(auth()->user()->name); ?></p>
        <p>Statut: <?php echo e(auth()->user()->status); ?></p>
        <a href="/tattooer/profile" class="inline-block bg-blue-600 text-white px-6 py-2 rounded">Accéder au profil</a>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\tattooer\pending-verification-simple.blade.php ENDPATH**/ ?>