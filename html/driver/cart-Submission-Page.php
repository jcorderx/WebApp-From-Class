<?php

require_once '../php/LoginHandler.php';
require_once '../php/Database.php';
require_once '../php/Account.php';

// Item Id is set here and is passed here by the page url.
// We get the sponsor id from the url as well.


$itemId = $_GET["itemId"];
$sponsorId = $_GET["sponsorId"];

$db = new Database();
$user = $db->LoadUserFromUsername(LoginHandler::GetCurrentAccountType(), LoginHandler::GetCurrentUsername());

$queryStr = "INSERT INTO cart (did, sid, cid) VALUES ({$user->GetID()},{$sponsorId},{$itemId})";

$db->sql->query($queryStr);

// go back to the catalog
header("Location: view-catalog.php?sponsorId={$sponsorId}");

?>