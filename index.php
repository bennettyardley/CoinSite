<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="script.js"></script>  
</head>
<body>
<div class="login-page">
  <div class="form">
    <form method="POST" id="register" action="" class="register-form">
        <input type="hidden" name="submittedregister" value="true"/>
        <input type="text" placeholder="Username" name="username">
		<input type="password" placeholder="Password" name="password">
		<input type="password" placeholder="Confirm Password" name="passwordcon">
		<button><input placeholder="Register" type="submit" name="register" value="Register" style="background-color: transparent"></button>
      <p class="message">Already registered? <a href="#">Log In</a></p>
    </form>
    <form method="POST" id="login" action="" class="login-form">
        <input type="hidden" name="submittedlogin" value="true"/>
        <input placeholder="Username" type="text" name="username">
		<input placeholder="Password" type="password" name="password">
		<button><input placeholder="Login" type="submit" name="login" value="Login" style="background-color: transparent"></button>
      <p class="message">No Account? <a href="#">Register for an account</a></p>
    </form>
  </div>
</div>
<script src="script.js"></script>  
</body>
</html>
<?php
include("connect.php");
	session_start();

if( $_POST['login'] ) {
     if (isset($_POST['submittedlogin'])){
            $usr = mysqli_real_escape_string($dbcon, $_POST['username']);
            $pass = mysqli_real_escape_string($dbcon, $_POST['password']);
            $sql = "SELECT * FROM Users WHERE username = '$usr'";
            $result = mysqli_query($dbcon,$sql);
            $row = mysqli_fetch_array($result);
            $count = mysqli_num_rows($result);
            $password = hash('sha512',($pass.$row['Salt']));
            if ($count == 1 && $row['Password']==$password){
                $_SESSION['user'] = $row['Username'];
                echo 'Successfully Logged In';
                header( "refresh:0.5; url=play.php" );

            } else {
                echo 'Incorrect Username or Password';
            }
    }
}
else if( $_POST['register'] ) {
    if (isset($_POST['submittedregister'])){
    $uname = mysqli_real_escape_string($dbcon, $_POST['username']);
    $passw = mysqli_real_escape_string($dbcon, $_POST['password']);
    $passwCon = mysqli_real_escape_string($dbcon, $_POST['passwordcon']);
    if($passwCon != $passw){
        echo 'Passwords do not match';
        
    }
    else{
    $sql = mysqli_query($dbcon, "SELECT COUNT(*) FROM Users WHERE username='$uname'");
    $row = mysqli_fetch_array($sql);
    $amount = $row['COUNT(*)'];
    if($amount > 0){
        echo 'Username already taken';
        
    }
    else{
    $salt = bin2hex(mcrypt_create_iv(64, MCRYPT_DEV_URANDOM));
    $saltpassw =  $passw . $salt;
    $hashedpassw = hash('sha512', $saltpassw);
    $coins = 20;
    $query = "insert into Users (Username, Password, Salt, Coins) values ('$uname', '$hashedpassw', '$salt', '$coins'); ";
    $queryRun = mysqli_query($dbcon, $query);
    echo 'Account created';
    }
    }
    }
}

?>
