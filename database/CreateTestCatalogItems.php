<?php
require_once '../html/php/Database.php';
require_once '../html/php/Catalog.php';
require_once '../html/php/CatalogAPI.php';

if (count($argv) !== 2) {
    echo 'Usage: '.$argv[0]." <json file>\n";
    exit;
}

$db = new Database();
$json = file_get_contents($argv[1]);
$catalogItems = eBayAPI::GetCatalogItemsFromJSON($json);

foreach ($catalogItems as $item) {
    if (!$db->AddOrUpdateCatalogItem($item)) {
        echo 'Failed to add/update item: ' . $db->GetLastError() . "\n\n";
    }
    else {
        PrintItem($item);
    }
}

function PrintItem($item) {
    echo "Title: {$item->title}\n";
    echo "\tID: {$item->GetID()}\n";
    echo "\tPrice/Ship. cost: \${$item->currentPrice}/\${$item->shippingCost}\n";
    echo "\tCategory name (ID): {$item->categoryName} ({$item->categoryId})\n";
    echo "\n\n";
}
?>