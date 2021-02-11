<?php

require_once '../php/LoginHandler.php';
require_once '../php/Account.php';
require_once '../php/Database.php';

LoginHandler::CheckPrivilege(UserAccount::ADMIN_ACCOUNT);

$alert = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $user = $db->LoadUserFromUsername(UserAccount::ADMIN_ACCOUNT, LoginHandler::GetCurrentUsername());

    $accType = UserAccount::DRIVER_ACCOUNT;
    if ($_POST["accountType"] === "Driver") {
        $accType = UserAccount::DRIVER_ACCOUNT;
    }
    else if ($_POST["accountType"] === "Sponsor") {
        $accType = UserAccount::SPONSOR_ACCOUNT;
    }

    $lh = new LoginHandler($accType, $_POST["username"], "", "");
    LoginHandler::Logout();
    if ($lh->Login(true) !== false) {
        switch ($accType) {
            case UserAccount::DRIVER_ACCOUNT:
                header("Location: /driver/welcome.php");
                exit;

            case UserAccount::SPONSOR_ACCOUNT:
                header("Location: /sponsor/welcome.php");
                exit;
        }
    }
    else {
        $alert = "Failed to log in. Make sure you entered the username correctly.";

        // attempt to log back in as admin
        $lh = new LoginHandler(UserAccount::ADMIN_ACCOUNT, $user->GetUsername(), "", "");
        $lh->Login(true);
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <?php require_once '../php/Navbar.php'; ?>

    <div class="container">

        <?php if ($alert): ?>
        <div class="alert alert-danger"><?= $alert ?></div>
        <?php endif; ?>

        <form action="login-as-other.php" method="POST">
            <!-- Account type selection -->

            <!-- NOTE: I had to move this into the form, or else it wouldn't send
            the account type to the server. -->
            <div class="form-group">
                <label for="exampleFormControlSelect1">Select Account Type</label>
                <select class="form-control" id="exampleFormControlSelect1" name="accountType">
                    <option value="Driver">Driver</option>
                    <option value="Sponsor">Sponsor</option>
                </select>
            </div>
            <!-- End Account type selection -->


            <div class="form-group">
                <label for="exampleInputUsername1">Username</label>
                <input type="username" class="form-control" id="exampleInputUsername1" aria-describedby="emailHelp"
                    placeholder="Username" name="username">
            </div>


            <!-- End Login Boxes -->

            <!-- SignUp box -->
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
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