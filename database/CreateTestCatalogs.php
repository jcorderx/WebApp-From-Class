<?php
require_once '../html/php/Database.php';
require_once '../html/php/Catalog.php';

$db = new Database();

// delete all catalogs
echo "Deleting all catalogs...";
$db->sql->query('DELETE FROM CatalogList;');
$db->sql->query('DELETE FROM Catalogs;');
echo "Done.\n";

// give each sponsor a catalog
echo "Generating catalogs...\n";
$sponsors = $db->LoadAllSponsors();

foreach ($sponsors as $sponsor) {
    $id = $sponsor->GetID();

    $stmt = $db->sql->prepare('INSERT INTO Catalogs (sponsorId,selectionMode) VALUES (?,?)');
    $stmt->bind_param('is', $id, 'CATEGORY');
    $stmt->execute();
    $stmt->close();
    
    echo "\tMade a catalog for sponsor {$sponsor->GetID()}.\n";
}

// get a list of all catalog items
$items = $db->GetCatalogItemsFromQuery("SELECT * FROM CatalogItems");

// give each catalog a bunch of random items from CatalogItems
$qresult = $db->sql->query('SELECT * FROM Catalogs');
while ($catalogId = $qresult->fetch_assoc()["id"]) {
    echo "Adding items to catalog {$catalogId}:\n";
    $n = rand(0, count($items));

    for ($i = 0; $i < $n; ++$i) {
        $randIndex = rand(0, count($items)-1);
        $itemId = $items[$randIndex]->GetID();

        $stmt = $db->sql->prepare('INSERT INTO CatalogList (catalogId,itemId) VALUES (?,?)');
        $stmt->bind_param('ii', $catalogId, $itemId);
        $stmt->execute();
        $stmt->close();
        echo "\t({$i}) Inserted item {$itemId}.\n";
    }
}

$qresult->free();
echo "\nDone.\n";
?>