<!--  Danny Mejia CS290 Final project. Its a contract database able to make new users and check password and user entry -->
<?php
session_start();
include 'storedInfo.php'; // stores secret code and opens mysqli table
// THIS FILE DOES ALL THE NEW USER CREATOR AND USER/PASSSWORD CHECKER

// IF ITS A NEW CONTRACT THEN ATTEMPT TO MAKE A NEW USER CODE
if ($_GET['new'] === 'yes'){
    $sql = "INSERT INTO $userTable (userName, password) VALUES ('$userName', '$password')";
    
    if ($mysqli->query($sql) === TRUE){
        echo "User successfully created!<br>";
        echo "<a href=\"login.html\">Click here to log in.</a>";
    }else{
        echo "<p class=\"error\">User creation failed: Username already in use.</p>";
        echo "If you forgot your password, contact admin to reset.<br>";
        echo "<a href=\"login.html\">Click here to try again.</a>";
    }
}else{ // ELSE CHECK IF USER HAS AN ACCOUNT
    
$userQuery = mysqli_query($mysqli,"SELECT * FROM $userTable WHERE userName='$userName' AND password='$password' ");
$user_check = mysqli_num_rows($userQuery);
        
    if($user_check === 0){
        echo "<p class=\"error\">Incorrect user name or password.</p>";
        echo "If you forgot your password, contact admin to reset.<br>";
        echo "<a href=\"login.html\">Click here to try again.</a>";
    }else {
        $_SESSION['loggedin'] = true; 
        echo "Hello " . $_SESSION['name'] . " you are connected to the contract database!<br>";
        echo "Here are your contracts:";
        echo '<br><br><a href="tableChanges.php?username=' . $_SESSION['name'] . '">Click here to make changes.</a>';
        include 'tableChanges.php'; // page to make changes to table
    }
    }
?>