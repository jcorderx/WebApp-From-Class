<!DOCTYPE html>
<html>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="background.css">
<?php

require_once '../php/Database.php';
require_once '../php/Navbar.php';
?>
<head>
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
<center>
<h2>Account</h2>

</br>
<a href="changeinfo.php" class="button">Change Info</a>
<a href="givepoints.php" class="button">Give Points</a>
<a href="view-applicants.php" class="button">Driver Applications</a>
<a href="transaction.php" class="button">Points History</a>
<a href="select-driver.php" class="button">Change Point Ratio</a>
<a href="config-catalog.php" class="button">Configure Catalog</a>
<a href="manage-drivers.php" class="button">Manage Drivers</a>

</center>


</body>
</html>

