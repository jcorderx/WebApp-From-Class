<?php

require_once 'php/Database.php';
require_once 'php/LoginHandler.php';
require_once 'php/Navbar.php';

if (!LoginHandler::IsLoggedIn()) {
    header("Location: /index.php");
    exit;
}

$db = new Database();
$user = $db->LoadUserFromUsername(LoginHandler::GetCurrentAccountType(), LoginHandler::GetCurrentUsername());

$ns = $user->GetNotifications();

function PrintNotifications($nots) {
    if (count($nots) > 0) {
        foreach ($nots as $n) {
            $className = $n->HasBeenSeen() ? "alert alert-primary" : "alert alert-success";
            echo "<div class=\"{$className}\"><strong>{$n->GetTimestamp()}</strong>: {$n->GetText()}</div>";
            $n->SetHasBeenSeen(true);
        }
    }
    else {
        echo "<p>No new notifications</p>";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Alerts</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="jumbotron">
        <h1>View New Notifications</h1>
    </div>

    <div class="container">
        <?php PrintNotifications($ns); ?>
    </div?

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