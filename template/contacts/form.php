<?php
include_once 'src/Service/Template.php';
/** @var Utilisateur $contact */
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
            <input type="text" name="nom" id="nom" class="w-full border rounded p-2" value="<?php echo $contact->getNom();?>" required>
        </div>

        <div class="mb-4">
            <label for="prenom" class="block text-gray-700 text-sm font-bold mb-2">Prénom :</label>
            <input type="text" name="prenom" id="prenom" class="w-full border rounded p-2" value="<?php echo $contact->getPrenom();?>" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email :</label>
            <input type="email" name="email" id="email" class="w-full border rounded p-2" value="<?php echo $contact->getEmail();?>" >
        </div>

        <div class="mb-4">
            <label for="telephone" class="block text-gray-700 text-sm font-bold mb-2">Numéro de téléphone :</label>
            <input type="tel" name="telephone" id="telephone" class="w-full border rounded p-2" value="<?php echo $contact->getTelephone();?>" >
        </div>

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="mt-4">
            <input type="submit" value="Ajouter utilisateur" class="bg-blue-500 text-white px-4 py-2 rounded">
        </div>
    </form>

</div>
</body>
</html>