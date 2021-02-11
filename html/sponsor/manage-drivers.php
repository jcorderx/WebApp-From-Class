<?php

require_once '../php/LoginHandler.php';
require_once '../php/Database.php';
require_once '../php/Notification.php';

LoginHandler::CheckPrivilege(UserAccount::SPONSOR_ACCOUNT);
$db = new Database();

$user = $db->LoadUserFromUsername(UserAccount::SPONSOR_ACCOUNT, LoginHandler::GetCurrentUsername());

if (!$user) {
    header("HTTP/1.1 500 Internal Server Error");
    exit;
}

$alert = false;

// handle form submission
if (isset($_POST["deleteDriverID"])) {
    $db->RemoveDriverFromSponsor($_POST["deleteDriverID"], $user->GetID());
    $alert = "Deleted driver {$_POST['deleteDriverUsername']}";

    // send an alert to the driver
    $n = new Notification(UserAccount::DRIVER_ACCOUNT, $_POST["deleteDriverID"]);
    $n->SetText("Sponsor {$user->GetUsername()} removed you. They are no longer your sponsor.");
    $n->Post();
}

// get the credits each driver has and put it into the sponsor object
// yeah this API sucks lol
$user->LoadDriverIdsAndCredits();

// load all the drivers of this sponsor
$drivers = $user->GetDrivers();

?>

<!doctype html>
<html lang="en">

<head>
    <title>Manage Drivers</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <?php require_once '../php/Navbar.php'; ?>

    <div class="jumbotron">
        <h1 class="display-3">Manage Drivers</h1>
        <p class="lead">View and remove drivers from your company here.</p>
    </div>

    <div class="container">
        <?php if ($alert): ?>
        <div class="alert alert-success" role="alert">
            <?= $alert ?>
        </div>
        <?php endif; ?>

        <!-- Display all the drivers. -->
        <?php foreach($drivers as $driver): ?>

        <div class="card" style="margin-bottom: 0.25in;">
            <div class="card-header">
                <?= "{$driver->fName} {$driver->lName} ({$driver->GetUsername()})" ?>
            </div>

            <div class="card-body">
                <p class="card-text"><strong>Credits: </strong><?= "{$user->credits[$driver->GetID()]}" ?></p>
                <form action="manage-drivers.php" method="POST">
                    <input type="hidden" name="deleteDriverID" value="<?= $driver->GetID() ?>">
                    <input type="hidden" name="deleteDriverUsername" value="<?= $driver->GetUsername() ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
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