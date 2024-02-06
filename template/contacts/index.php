<?php
include_once 'src/Service/Template.php';
include_once 'src/Model/Utilisateur.php';
include_once 'src/Repository/ContactRepository.php';

$template = new Template();
$repoContact = new ContactRepository();
$contacts = $repoContact->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-200 p-8">

<?php $template->affichageMessage(); ?>

<nav class="flex" aria-label="Breadcrumb">
    <ol role="list" class="flex space-x-4 rounded-md bg-white px-6 shadow">
        <li class="flex">
            <div class="flex items-center">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="#3b82f6" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Home</span>
                </a>
            </div>
        </li>
    </ol>
</nav>

<div class="mx-auto mt-8 w-11/12 bg-white  rounded shadow-md py-2">

    <div class="flex justify-between items-center mb-4 mx-5 mt-6">
        <h1 class="text-3xl font-bold">Mes contacts</h1>
        <a href="http://applicloud/utilisateur/create"><button class="bg-blue-500 text-white px-4 py-2 rounded">+</button></a>
    </div>


    <table id="table-utilisateurs" class="table-auto w-full mb-8 mx-5">
        <thead>
        <tr>
            <th class="px-4 py-2">Contact</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Téléphone</th>
            <th class="px-4 py-2"></th>
            <th class="px-4 py-2"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        /* var  */
        /** @var Utilisateur $contact */
        foreach ($contacts as $contact){
            if (is_object($contact) && get_class($contact) === "stdClass") {
                $contact = Utilisateur::fromStdClass($contact);
            }

            $class = 'class="border px-4 py-2"';
            $colonne1 = '<td ' . $class .' >'. $contact->getNom() . ' '. $contact->getPrenom() . '</td>';
            $colonne2 = '<td ' . $class .' >'. $contact->getEmail() . '</td>';
            $colonne3 = '<td ' . $class .' >'. $contact->getTelephone() . '</td>';


            $hrefEdit = 'href="http://applicloud/utilisateur/edit/' . $contact->getId() . '"';
            $hrefSupprimer = 'href="http://applicloud/utilisateur/delete/' . $contact->getId() . '"';

            $svgEdit = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="blue"
                            class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" 
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" 
                            />
                            </svg>';
            $svgSupprimer = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" 
                                class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" 
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" 
                                />
                                </svg>';

            $colonne4 = '<td class="border px-4 py-2 flex" >
                                <a '. $hrefEdit .' class="mr-2" >' . $svgEdit . '</a>
                                <a '. $hrefSupprimer . ' >' . $svgSupprimer . '</a>
                            <td>
               ';

            echo '
                <tr>'.
                $colonne1.
                $colonne2.
                $colonne3.
                $colonne4.
                '</tr>';
        }
        ?>
        </tbody>
    </table>

</div>
</body>
</html>
