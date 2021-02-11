<?php

require_once '../php/LoginHandler.php';
require_once '../php/Account.php';

LoginHandler::CheckPrivilege(UserAccount::ADMIN_ACCOUNT);

?>

<!doctype html>
<html lang="en">

<head>
    <title>Account</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .button {
            background-color: #000001;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 8px 8px;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <?php require_once '../php/Navbar.php'; ?>

    <div class="container" style="text-align:center;">
        <a href="/admin/changeinfo.php" class="button">Change Account Info</a>
        <a href="/admin/list-sponsors.php" class="button">List of Sponsors</a>
        <a href="/admin/transaction.php" class="button">Point Transaction History</a>
        <a href="/admin/mandriver.php" class="button">Change User Info</a>
        <a href="/admin/adduser.php" class="button">Add New User</a>
        <a href="/admin/login-as-other.php" class="button">Login As User</a>

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