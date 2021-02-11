<?php
require_once '../php/LoginHandler.php';
require_once '../php/Database.php';
require_once '../php/Account.php';
require_once '../php/CatalogAPI.php';

LoginHandler::CheckPrivilege(UserAccount::SPONSOR_ACCOUNT);

$db = new Database();
$user = $db->LoadUserFromUsername(UserAccount::SPONSOR_ACCOUNT, LoginHandler::GetCurrentUsername());

if (!$user) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}

// get the sponsor's catalog
$catalog = $db->LoadCatalog($user->GetID());

$checked1 = $catalog->GetSelectionMode() == Catalog::SELECT_BY_RULES;
$checked2 = $catalog->GetSelectionMode() == Catalog::SELECT_BY_LIST;
$checked3 = $catalog->GetSelectionMode() == Catalog::SELECT_BY_CATEGORY;

// load categories
$categories = $db->GetItemCategoriesFromQuery("SELECT * FROM Category;");

// Handle POST form submissions.
//
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["selectionMethod"])) {
    // first form
    $method = $_POST["selectionMethod"];
    $keywords = $_POST["keywords"];

    $catalog->SetKeywords($keywords);
    $catalog->SetSelectionMode($method);
}
else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["categorySelect"])) {
    // second form: adding a category
    $cid = $_POST["categorySelect"];
    $category = $db->GetItemCategoriesFromQuery("SELECT * FROM Category WHERE id={$cid};");
    $catalog->AddCategory($category[0]);
}
else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ebayLink"])) {
    // third form: adding ebay items to the list
    $itemID = eBayAPI::GetItemIDFromURL($_POST["ebayLink"]);
    if ($itemID !== -1) {
        $item = eBayAPI::GetSingleItem($itemID);
        $item = eBayAPI::GetCatalogItemFromShoppingJSON($item);
        $catalog->AddItem($item);
    }
}

// Handle GET form submissions.
//

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["removeCategory"])) {
    // Remove a category from the catalog.
    $cid = $_GET["removeCategory"];
    $category = $db->GetItemCategoriesFromQuery("SELECT * FROM Category WHERE id={$cid};");
    $catalog->RemoveCategory($category[0]);
}
else if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["removeItem"])) {
    $iid = $_GET["removeItem"];
    $item = $db->GetCatalogItemsFromQuery("SELECT * FROM CatalogItems WHERE id={$iid};");
    $catalog->RemoveItem($item[0]);
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Configure Your Catalog</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        .card {
            margin-bottom: 0.25in;
        }
    </style>
</head>

<body>
    <?php require_once '../php/Navbar.php'; ?>
    <div class="jumbotron">
        <h1 class="display-3">Configure Catalog</h1>
        <p class="lead">Use this page to configure your catalog, including items, rules for selecting items, update
            frequency, and more.</p>
    </div>

    <div class="container">
        <form method="POST" action="config-catalog.php">

            <!-- Method section -->
            <h3>Catalog Selection Method</h3>
            <hr>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">How do you want your catalog items selected?</h5>

                    <div>
                        <input type="radio" name="selectionMethod" value="RULES" id="selectionMethod1"
                            <?php echo ($checked1 ? "checked" : ""); ?>>
                        <label for="selectionMethod1">By Rule</label>
                    </div>

                    <div>
                        <input type="radio" name="selectionMethod" value="LIST" id="selectionMethod2"
                            <?php echo ($checked2 ? "checked" : ""); ?>>
                        <label for="selectionMethod2">By Item</label>
                    </div>

                    <div>
                        <input type="radio" name="selectionMethod" value="CATEGORY" id="selectionMethod3"
                            <?php echo ($checked3 ? "checked" : ""); ?>>
                        <label for="selectionMethod3">By Category</label>
                    </div>
                </div>
            </div>

            <!-- Rules section -->
            <h3>Rules</h3>
            <hr>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add and remove catalog item selection rules.</h5>
                    <label for="keywords">Keywords (separate with spaces): </label>
                    <input type="text" class="form-control" name="keywords" id="keywords"
                        value="<?= $catalog->GetKeywords() ?>">
                </div>
            </div>
            <!-- 
            
            <h3>Update Interval</h3>
            <hr>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Select how often you want your catalog to refresh with new items.</h5>
                    <select name="update-frequency" class="form-control">
                        <option value="hourly">Hourly</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>
            </div>
            -->
            <button class="btn btn-primary" type="submit" style="margin-bottom:0.25in;">Save Changes</button>
        </form>

        <h3>Categories</h3>
        <hr>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add and remove categories for your catalog here.</h5>
                <hr>
                <h5 class="card-title">Categories</h5>

                <form class="form-inline" method="POST" action="config-catalog.php">
                    <select class="form-control" name="categorySelect" id="categorySelect">
                        <?php foreach($categories as $category): ?>

                        <option value="<?= $category->GetID() ?>">
                            <?= $category->categoryName ?>
                        </option>

                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>

                <div class="card" style="margin:0.25in 0.25in;">
                    <div class="card-body" style="overflow-y: auto; height: 300px;">

                        <ul>
                            <?php foreach($catalog->GetCategories() as $category): ?>

                            <li><?= $category->categoryName ?> (<a href="<?= "/sponsor/config-catalog.php?removeCategory={$category->GetID()}" ?>">Remove</a>)</li>

                            <?php endforeach; ?>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <h3>Items</h3>
        <hr>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add and remove specific items to your catalog here.</h5>
                <hr>
                <h5 class="card-title">Items</h5>

                <!-- Form for adding items -->
                <form class="form-inline" action="config-catalog.php" method="POST">
                    <div class="form-group">
                        <label for="ebayLinkInput">eBay Item URL:</label>
                        <input type="text" id="ebayLinkInput" name="ebayLink" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>

            <div class="card" style="margin:0 0.25in;">
                <div class="card-body" style="overflow-y: auto; height: 300px;">
                    <ul>
                        <?php foreach($catalog->GetItems() as $item): ?>

                        <li><?= $item->title ?> (<a href="<?="/sponsor/config-catalog.php?removeItem={$item->GetID()}"?>">Remove</a>)</li>

                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>