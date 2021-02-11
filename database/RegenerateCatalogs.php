<?php

require_once '../html/php/Database.php';
require_once '../html/php/Catalog.php';
require_once '../html/php/Account.php';

$db = new Database();
$sponsors = $db->LoadAllSponsors();

foreach ($sponsors as $sponsor) {
    echo "Regenerating {$sponsor->GetUsername()}'s catalog...";
    $catalog = $db->LoadCatalog($sponsor->GetID());
    $catalog->RegenerateCatalog();
    echo "Done.\n";
}

?>