<?php

require_once '../website/php/CatalogAPI.php';

class Reference {
    public $ref;
}

$categoryRoot = [];
$categoryRoot["Root"] = [
    "id" => -1,
    "parentId" => 0,
    "level" => 0,
    "name" => "Root",
    "isLeaf" => false,
    "children" => []
];

$currentNode = new Reference();
$currentNode->ref =& $categoryRoot["Root"];
$nodeQueue = array($currentNode);

// The deepest category level to go to
$maxLevel = 3;

while (count($nodeQueue) > 0) {
    $currentNode = $nodeQueue[0]; // get the next category
    array_splice($nodeQueue, 0, 1); // remove the first element of the array

    // stop iterating when we reach max depth
    if ($currentNode->ref["level"] >= $maxLevel) {
        break;
    }

    // Get the child categories for the current category
    $categoryList = json_decode(eBayAPI::GetCategories($currentNode->ref["id"]), true);
    $categoryList = $categoryList["CategoryArray"]["Category"];

    // Populate this category's child categories
    $currentNode->ref =& $currentNode->ref["children"];

    for ($i = 1; $i < count($categoryList); $i++) {
        $name = $categoryList[$i]["CategoryName"];
        $isLeaf = $categoryList[$i]["LeafCategory"];

        $currentNode->ref[$name] = [
            "id" => $categoryList[$i]["CategoryID"],
            "parentId" => $categoryList[$i]["CategoryParentID"],
            "level" => $categoryList[$i]["CategoryLevel"],
            "name" => $name,
            "isLeaf" => $isLeaf,
            "children" => []
        ];

        // add the child category to the queue if it's not a leaf node
        if ($isLeaf === false) {
            $newRef = new Reference();
            $newRef->ref =& $currentNode->ref[$name];
            array_push($nodeQueue, $newRef);
        }
    }
}

// Print the entire tree of categories as a JSON structure.
printf("%s", json_encode($categoryRoot));

?>