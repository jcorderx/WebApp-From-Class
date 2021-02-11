<?php

require_once '../html/php/Database.php';

$db = new Database();
$categoryTable = "Category";

// get the category tree from the file. Set to "children" so we can ignore the
// root category, which is meaningless
$categoryTree = json_decode(file_get_contents("category-tree.json"), true)["Root"]["children"];

// remove all old entries from the category table
$db->sql->query("DELETE FROM {$categoryTable};");

// Recursively add all categories to the category table.
AddCategory($categoryTree);

function AddCategory($root) {
    global $db, $categoryTable;

    // Insert all of the categories listed in $root.
    foreach ($root as $key => $value) {
        $id = $value["id"];
        $parentId = $value["parentId"];
        $name = $db->sql->escape_string($value["name"]);
        $isLeaf = ($value["isLeaf"] ? 1 : 0);

        echo "Adding category '{$name}'...\n";

        $queryStr = "INSERT INTO {$categoryTable} (id,categoryParentId,categoryName,leafCategory) VALUES ({$id},{$parentId},'{$name}',{$isLeaf});";

        if (!$db->sql->query($queryStr)) {
            throw new Exception("Failed to insert category '{$name}'. Error: '{$db->GetLastError()}'.\nQuery: '{$queryStr}'.");
        }
        
        // recursively add this category's child categories
        AddCategory($value["children"]);
    }
}

?>