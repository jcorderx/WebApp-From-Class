<?php
require_once 'php/Database.php';
require_once 'php/Account.php';
require_once 'php/LoginHandler.php';
require_once 'php/Navbar.php';

$db = new Database();
$admin = $db->LoadUserFromUsername(UserAccount::ADMIN_ACCOUNT, "test_admin0");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];

  $user = $db->LoadUserFromUsername(UserAccount::DRIVER_ACCOUNT, $username);

  $n = new Notification(UserAccount::ADMIN_ACCOUNT, $admin->GetId());
  $n->SetText("{$user->GetUsername()} has requested a password reset.");
  $n->Post();

  exit;
}

?>

<!DOCTYPE html>
<html>
  <head>

    <meta charset="UTF-8">
    <title>Welcome</title>
    <meta name="viewport" cotent="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin=
        "anonymous">
    <link rel="stylesheet" href="background.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



  </head>

  <body>
    <div class="container">
        <form action="forgot.php" method="POST">
          <div class="form-group">
            <label for="exampleInputUsername1">Enter Username to reset password</label>
            <input type="username" class="form-control" id="exampleInputUsername1" aria-describedby="emailHelp"
            placeholder="Username" name="username">
          </div>
          <div class="clearfix">
            <button type="submit" class="btn btn-primary">Send Request</button>
          </div>
      </form>
    </div>
