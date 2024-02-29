<?php
include_once 'src/Service/Template.php';

global $pathForm,$path, $contact;
$template = new Template();

$option = 'ajouter';
if(strpos($path, '/utilisateur/edit/') === 0){
    $option = 'modifier';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un contact </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-200 p-8">

<?php $template->affichageMessage(); ?>

<nav class="flex" aria-label="Breadcrumb">
    <ol role="list" class="flex space-x-4 rounded-md bg-white px-6 shadow">
        <li class="flex">
            <div class="flex items-center">
                <a href="http://applicloud/" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Home</span>
                </a>
            </div>
        </li>
        <li class="flex">
            <div class="flex items-center">
                <svg class="h-full w-6 flex-shrink-0 text-gray-200" viewBox="0 0 24 44" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
                    <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
                </svg>
                <a href="#" class="ml-4 text-sm font-medium text-blue-500 hover:text-blue-700"><?php echo $option;?></a>
            </div>
        </li>
    </ol>
</nav>

<div class="max-w-md mx-auto bg-white p-8 rounded shadow-md">

    <?php $template->affichageErreurForm(); ?>

    <form action="<?php echo $pathForm; ?>" method="post">

        <div class="mb-4">
            <label for="nom" class="block text-gray-700 text-sm font-bold mb-2">Nom :</label>
            <input type="text" name="nom" id="nom" class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" role="combobox" aria-controls="options" aria-expanded="false" value="<?php echo $contact->getNom();?>" required>
        </div>

        <div class="mb-4">
            <label for="prenom" class="block text-gray-700 text-sm font-bold mb-2">Prénom :</label>
            <input type="text" name="prenom" id="prenom" class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" role="combobox" aria-controls="options" aria-expanded="false" value="<?php echo $contact->getPrenom();?>" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email :</label>
            <input type="email" name="email" id="email" class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" role="combobox" aria-controls="options" aria-expanded="false" value="<?php echo $contact->getEmail();?>" >
        </div>

        <div class="mb-4">
            <label for="telephone" class="block text-gray-700 text-sm font-bold mb-2">Numéro de téléphone :</label>
            <input type="tel" name="telephone" id="telephone" class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" role="combobox" aria-controls="options" aria-expanded="false" value="<?php echo $contact->getTelephone();?>" >
        </div>

        <div class="mb-4">
            <label for="departement" class="block text-gray-700 text-sm font-bold mb-2">Département :</label>
            <div id="departement-div" class="relative mt-2">
                <input type="text" name="departement" id="departement" class="w-full rounded-md border-0 bg-white py-1.5 pl-3 pr-12 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" role="combobox" aria-controls="options" aria-expanded="false" value="<?php echo $contact->getAdresse()->getDepartement();?>" >

                <ul id="departement-list" class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm hover:cursor-pointer hidden" role="listbox">
                    <li class="departement-li relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 hover:text-white hover:bg-indigo-500 hidden" role="option" tabindex="-1">
                        <div class="flex">
                            <span class="name truncate">Exemple</span>
                            <span class="code ml-2 truncate text-gray-500">Exemple</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="mt-4">
            <input type="submit" value="<?php echo ucfirst($option); ?> utilisateur" class="bg-blue-500 text-white px-4 py-2 rounded">
        </div>
    </form>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateDepartementOptions() {
        var departementValue = $('#departement').val();
        $.ajax({
            url: 'http://applicloud/api/departement?name='+departementValue,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                var departementSuggestions = $('#departement-list');
                departementSuggestions.children().slice(1).remove();
                var li = $('.departement-li');

                var maxValues = 5;
                var departementCount = data.length;

                if (departementCount > maxValues) {
                    data = data.slice(0, maxValues);
                }

                $.each(data, function(index, departement) {
                    var new_li = li.clone();
                    new_li.removeClass('hidden');

                    var nameElement = new_li.find('.name');
                    nameElement.text(departement.nom);

                    var codeElement = new_li.find('.code');
                    codeElement.text(departement.code);

                    departementSuggestions.append(new_li);

                    new_li.on('click', function () {
                        $('#departement').val(departement.nom);
                        $('#departement-list').addClass('hidden');
                    })
                });

            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la récupération des communes :', error);
            }
        });
    }

    $(document).ready(function() {
        var departement = $('#departement');
        var departement_list = $('#departement-list');

        departement.on('focus',function() {
            departement_list.removeClass('hidden');
        });

        $(document).on('click', function(event) {
            if (!$(event.target).closest('#departement-list').length && !$(event.target).is('#departement')) {
                departement_list.addClass('hidden');
            }
        });

        departement.on('keyup',function () {
            updateDepartementOptions();
        });

        updateDepartementOptions();
    });
</script>

</body>
</html>
