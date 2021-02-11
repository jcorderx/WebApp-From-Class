<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="background.css">

<body>
<?php

require_once '../php/Database.php';
require_once '../php/Navbar.php';
require_once '../php/LoginHandler.php';
require_once '../php/Account.php';



$db = new Database();
$user = $db->LoadUserFromUsername(LoginHandler::GetCurrentAccountType(), LoginHandler::GetCurrentUsername());
?>
  <div class="container">

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Email <input type="text" class="form-control"  name="email" value = <?php echo $user->email; ?>><br />   
      Phone Number: <input type="text" class="form-control"  name="phone" value = <?php echo $user->phoneNumber; ?>><br />
      Please Type Password To Confirm: <input type="text" class="form-control"  name="pass"><br />
      <input type="submit" class="btn btn-primary" value="Submit">
  </form>
</div>
<?php
$phone = $pass = $email = "";
$email = $_POST["email"];
$pass = $_POST["pass"];
$phone = $_POST["phone"];
if(!password_verify($pass, $user->GetPasswordHash())){
if($pass == ""){}
else
echo "The password is incorrect";

}
else{
echo "hello";
$queryStr = "UPDATE Sponsors SET phoneNumber='{$phone}', email='{$email}' WHERE id={$user->GetID()};";
$db->sql->query($queryStr);
}
?>
</html>
</body>
