<?php

require_once 'LoginHandler.php';

if (LoginHandler::IsLoggedIn())
    $accType = LoginHandler::GetCurrentAccountType();
else
    $accType = -1;

?>

<!-- LOGGED OUT NAVBAR -->
<?php if ($accType === -1): ?>
<header>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="#">Global Industries</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/generalUser/homePage.php">Home</a>
                </li>
            </ul>
        </div>

        <form class="form-inline my-2 my-lg-0">
            <a href="/index.php" class="btn btn-info">Login</a>
        </form>

    </nav>
</header>
<?php endif; ?>

<!-- DRIVER NAVBAR -->

<?php if ($accType === UserAccount::DRIVER_ACCOUNT): ?>
<header>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="#">Global Industries</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/driver/welcome.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/driver/account.php">Account<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/driver/view-catalog.php">Catalog<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/alerts.php">View Notifications</a>
                </li>

            </ul>
            <!--                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li> 
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form> 
           
-->
        </div>
        <form class="form-inline my-2 my-lg-0">
            <a href="cartCheckoutPage.php" class="btn btn-info">Cart</a>
        </form>
        <pre> </pre>
        <form class="form-inline my-2 my-lg-0">
            <a href="/logout.php" class="btn btn-info">Logout</a>
        </form>

    </nav>
</header>
<?php endif; ?>

<!-- SPONSOR NAVBAR -->
<?php if ($accType === UserAccount::SPONSOR_ACCOUNT): ?>
<header>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="#">Global Industries</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/sponsor/welcome.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="navbar-item active">
                    <a class="nav-link" href="/sponsor/account.php">Account<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/alerts.php">View Notifications</a>
                </li>
            </ul>
        </div>

        <form class="form-inline my-2 my-lg-0">
            <a href="/logout.php" class="btn btn-info">Logout</a>
        </form>

    </nav>
</header>
<?php endif; ?>


<?php if ($accType === UserAccount::ADMIN_ACCOUNT): ?>
    <header>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="#">Global Industries</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/admin/welcome.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="navbar-item active">
                    <a class="nav-link" href="/admin/account.php">Account<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/alerts.php">View Notifications</a>
                </li>
            </ul>
        </div>

        <form class="form-inline my-2 my-lg-0">
            <a href="/logout.php" class="btn btn-info">Logout</a>
        </form>

    </nav>
</header>
<?php endif; ?>