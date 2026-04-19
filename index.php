<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>autoSOS - Connexion & Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-blue-600 italic">auto<span class="text-red-500">SOS</span></h1>
            <p class="text-gray-500 text-sm">Votre assistance mécanique en un clic</p>
        </div>

        <div class="flex border-b mb-6">
            <button id="tabLogin" class="w-1/2 py-2 text-center font-semibold border-b-2 border-blue-500 text-blue-600">Connexion</button>
            <button id="tabRegister" class="w-1/2 py-2 text-center font-semibold border-b-2 border-transparent text-gray-400 hover:text-gray-600">Inscription</button>
        </div>

        <div id="loginSection">
            <form id="loginForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="exemple@mail.com" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition-colors shadow-md">
                    Se connecter
                </button>
            </form>
            <div id="message" class="mt-4 text-center text-sm"></div>
        </div>

        <div id="registerSection" class="hidden">
            <form id="registerForm" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="nom" placeholder="Nom" class="px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
                    <input type="text" name="prenom" placeholder="Prénom" class="px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
                <input type="password" name="password" placeholder="Mot de passe" class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
                <input type="text" name="telephone" placeholder="Téléphone" class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">

                <div class="bg-gray-50 p-3 rounded-lg border">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Je suis un :</label>
                    <select name="role" id="roleSelect" class="w-full bg-transparent outline-none">
                        <option value="client">Client (Besoin d'aide)</option>
                        <option value="mecanicien">Mécanicien (Je répare)</option>
                    </select>
                </div>

                <div id="mecaFields" class="hidden space-y-4 animate-fade-in">
                    <input type="text" name="specialite" placeholder="Spécialité (ex: Diagnostic, Freins)" class="w-full px-4 py-2 border border-blue-200 rounded-lg outline-none bg-blue-50">
                    <input type="text" name="localisation" placeholder="Votre Ville" class="w-full px-4 py-2 border border-blue-200 rounded-lg outline-none bg-blue-50">
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg transition-colors shadow-md">
                    Créer mon compte
                </button>
            </form>
        </div>
    </div>

    <script>
        // Logique de basculement entre Login et Register
        $('#tabLogin').on('click', function() {
            $(this).addClass('border-blue-500 text-blue-600').removeClass('border-transparent text-gray-400');
            $('#tabRegister').addClass('border-transparent text-gray-400').removeClass('border-blue-500 text-blue-600');
            $('#loginSection').removeClass('hidden');
            $('#registerSection').addClass('hidden');
        });

        $('#tabRegister').on('click', function() {
            $(this).addClass('border-blue-500 text-blue-600').removeClass('border-transparent text-gray-400');
            $('#tabLogin').addClass('border-transparent text-gray-400').removeClass('border-blue-500 text-blue-600');
            $('#registerSection').removeClass('hidden');
            $('#loginSection').addClass('hidden');
        });

        // Affichage dynamique des champs mécanicien
        $('#roleSelect').on('change', function() {
            if ($(this).val() === 'mecanicien') {
                $('#mecaFields').slideDown().removeClass('hidden');
            } else {
                $('#mecaFields').slideUp();
            }
        });
    </script>

    <script src="assets/js/login.js"></script>
    <script src="assets/js/register.js"></script>
</body>

</html>