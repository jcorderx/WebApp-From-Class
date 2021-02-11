<?php
require_once '../html/php/Database.php';
require_once '../html/php/Catalog.php';

$db = new Database();

$sponsors = $db->LoadAllSponsors();
$categories = $db->GetItemCategoriesFromQuery("SELECT * FROM Category");

foreach ($sponsors as $sponsor) {
    echo "Creating catalog for sponsor {$sponsor->GetID()}...\n";
    $catalog = $db->LoadCatalog($sponsor->GetID());
    $catalog->Reset();
    $catalog->SetSelectionMode(Catalog::SELECT_BY_CATEGORY);
    
    // add some random categories
    for ($i = 0; $i < rand(1, 4); ++$i) {
        $category = $categories[array_rand($categories)];
        echo "\tAdding category '{$category->categoryName}'.\n";
        $catalog->AddCategory($category);
    }

    echo "\tRegenerating catalog...";
    $catalog->RegenerateCatalog();
    echo "Done.\n";
}

echo "\nDone.\n";

?>