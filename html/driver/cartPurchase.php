<?php

require_once '../php/LoginHandler.php';
require_once '../php/Account.php';
require_once '../php/Catalog.php';
require_once '../php/Notification.php';

LoginHandler::CheckPrivilege(UserAccount::DRIVER_ACCOUNT);

if (!isset($_GET["sponsorId"])) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

// load the driver
$db = new Database();
$user = $db->LoadUserFromUsername(UserAccount::DRIVER_ACCOUNT, LoginHandler::GetCurrentUsername());

// Get their sponsor
$sponsor = $db->LoadUserFromId(UserAccount::SPONSOR_ACCOUNT, $_GET["sponsorId"]);

// Get the items in their cart
$items = $db->GetCatalogItemsFromQuery("SELECT * FROM CatalogItems WHERE id IN (SELECT cid FROM cart WHERE did={$user->GetID()} AND sid={$sponsor->GetID()});");

// Remove all items from their cart.
$db->sql->query("DELETE FROM cart WHERE did={$user->GetID()} AND sid={$sponsor->GetID()};");

$purchased = [];
$failed = [];

// Attempt to purchase all items.
foreach ($items as $item) {
    if (!$user->PurchaseItem($item, $sponsor)) {    
        $n = new Notification(UserAccount::DRIVER_ACCOUNT, $user->GetID());
        $n->SetText("Failed to purchase item {$item->title}. Check that you have enough credits.");
        $n->Post();
        array_push($failed, $item);
    }
    else {
        array_push($purchased, $item);
    }
}

$user->LoadSponsorIdsAndCredits();

?>

<!doctype html>
<html lang="en">

<head>
    <title>Purchased Items</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <?php require_once '../php/Navbar.php'; ?>

    <div class="container text-center">
        <h3>You now have <?= $user->credits[$sponsor->GetID()] ?> credits.</h3>

        <?php foreach($failed as $item): ?>
        <div class="alert alert-danger">
            Failed to purchase item <?= $item->title ?>.
        </div>
        <?php endforeach; ?>

        <?php foreach($purchased as $item): ?>
        <div class="alert alert-success">
            Purchased item <?= $item->title ?>.
        </div>
        <?php endforeach; ?>
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