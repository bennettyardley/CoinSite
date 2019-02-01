<?php
    include("connect.php");
	session_start();

    if (!isset($_SESSION['user'])){
        header("Location: index.php");
        exit;
    }
    $username = $_SESSION['user'];
    $query = "select Coins from Users where Username = '$username';";
    $result = mysqli_query($dbcon, $query);
    $row = mysqli_fetch_array($result);
    $coins = $row['Coins'];
    echo ("You have: $coins coins");
?>
<html>
<head>
<title>Play</title>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="form">
<form method="POST" action="">
        <input type="hidden" name="submitted" value="true"/>
        <input type="text" placeholder="How many coins?" name="coins"><br><br>
        <div class="radiolabel"><input type="radio" id="heads" name="50" value="heads"><label>Heads</label>
        <input type="radio" id="tails" name="50" value="tails"><label>Tails</label></div>
		<button><input type="submit" value="Flip" style="background-color: transparent"></button>
</form>
</div>
</body>
</html>
<?php
    if (isset($_POST['submitted'])){
    $amount = mysqli_real_escape_string($dbcon, $_POST['coins']);
    if($amount == "123456789"){
        $sql = "UPDATE Users SET Coins = 20 WHERE Username = '$username';";
    }
    if ($amount < 0 || $amount > $coins){
        echo 'Invalid Amount of Coins';
        header("Refresh:0.5");  
    }
    else{    
    $fifty = $_POST['50'];
    if ($_POST['50'] == "heads"){
        $choice = 1;
    }
    else {
        $choice = 2;
    }
    $roll = mt_rand(1,2);
    if($roll==$choice){
        $between = ($coins + $amount);
        $sql = "UPDATE Users SET Coins = '$between' WHERE Username = '$username';";
        $go = mysqli_query($dbcon, $sql);
        echo '<body style="background-color:green">';
        header("Refresh:0");
    }
    else{
        $between = ($coins - $amount);
        if ($between > 0){
        $sql = "UPDATE Users SET Coins = '$between' WHERE Username = '$username';";
        $go = mysqli_query($dbcon, $sql);
        }
        else{
            $sql = "UPDATE Users SET Coins = '0' WHERE Username = '$username';";
            $go = mysqli_query($dbcon, $sql);
            
        }
        echo '<body style="background-color:red">';
        header("Refresh:0");
    }
    }
    }
?>
