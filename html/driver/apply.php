<?php
require_once '../php/Database.php';
require_once '../php/Account.php';
require_once '../php/LoginHandler.php';

LoginHandler::CheckPrivilege(UserAccount::DRIVER_ACCOUNT);

$db = new Database();
$user = $db->LoadUserFromUsername(LoginHandler::GetCurrentAccountType(), LoginHandler::GetCurrentUsername());

$allSponsors = $db->LoadAllSponsors();

$alertDisplay = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get account type, username, and password from the for
    $password = $_POST["psw"];
    $sponsorId = $_POST["sponsor"];

    if(!(password_verify($password, $user->GetPasswordHash()))) {
        $alertDisplay = true;
    }
    else {
      $user->ApplyToSponsor($sponsorId);
      // now that they've signed up, re-direct to account page
      header("Location: ../driver/account.php");
      exit;
    }
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

  <style>
  .jumbotron {
    background-color: #1E8449;
    color: #fffff;
  }
  </style>

<body>

  <?php require_once '../php/Navbar.php'; ?>

  <div class="jumbotron text-center">
    <h1>Apply to a Sponsor</h1>
  </div>

  <div class="container">
    <div class="alert alert-danger" role="alert" style="display:<?php echo ($alertDisplay?"block":"none"); ?>;">
        Sorry, but your username or password is incorrect.
    </div>
    <form action="apply.php" method="post" style="border:0px solid #ccc">
      <label for="psw"><b>Password</b></label>
      <input type="password" class="form-control" placeholder="Enter Password" name="psw" required>
      <br>
      <div class="form-group">
        <label for="exampleFormControlSelect1"><b>Select Sponsor to Apply with</b></label>
        <select class="form-control" id="exampleFormControlSelect1" name="sponsor">
          <?php

            $i = 0;

            foreach($allSponsors as $sponsor) {
              echo "
              <option value=\"{$sponsor->GetID()}\">
                        $sponsor->companyName
              </option>
              ";
              $i++;
            }

          ?>
        </select>
      </div>

      <div class="clearfix">
        <button type="submit" class="btn btn-primary">Apply</button>
      </div>
    </div>
  </form>
</body>
</html>
