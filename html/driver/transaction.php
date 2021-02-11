<?php

require_once '../php/LoginHandler.php';
require_once '../php/Account.php';
require_once '../php/Database.php';
require_once '../php/Navbar.php';

LoginHandler::CheckPrivilege(UserAccount::DRIVER_ACCOUNT);

$db = new Database();
$user = $db->LoadUserFromUsername(LoginHandler::GetCurrentAccountType(), LoginHandler::GetCurrentUsername());

// get purchase history
$items = $user->GetPurchaseHistory();

// get point transaction history
$pointHistory = [];
$qresult = $db->sql->query("SELECT * FROM PointHistory WHERE driverId={$user->GetID()};");

if ($qresult) {
        while ($row = $qresult->fetch_assoc()) {
                $row["sponsor"] = $db->LoadUserFromId(UserAccount::SPONSOR_ACCOUNT, $row["sponsorId"]);
                array_push($pointHistory, $row);
        }
        $qresult->free();
}

?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


<link rel="stylesheet" href="background.css">

<body>
        <div class="container">
                <div class="row">
                        <div class="col-md-6">
                                <h1>Purchase History</h1>
                                <ul>
                                        <?php foreach($items as $item): ?>
                                        <li>
                                                <strong><a
                                                                href="<?=$item->item->viewItemURL?>"><?= $item->item->title ?></a></strong>
                                                <ul>
                                                        <li>Price: <?= $item->price ?> Credits</li>
                                                        <li>Date: <?= $item->date ?></li>
                                                        <li>Sponsor: <?= $item->sponsor->companyName ?></li>
                                                </ul>
                                        </li>
                                        <?php endforeach; ?>
                                </ul>
                        </div>

                        <div class="col-md-6">
                                <h1>Point History</h1>
                                <ul>
                                        <?php foreach($pointHistory as $h): ?>
                                        <li><strong><?= $h["dateC"] ?></strong>: <?= $h["sponsor"]->companyName ?> gave/took <?= $h["changeAm"] ?> points.</li>
                                        <?php endforeach; ?>
                                </ul>
                        </div>
                </div>
        </div>
</body>