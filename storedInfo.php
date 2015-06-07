<!--  Danny Mejia CS290 Final project. Its a contract database able to make new users and check password and user entry -->    
<?php
    session_start();
    $myPassword = "F5zcV4Nw2CXI3YOC";
    $table = 'contractTable'; // table created assosiated with these functions
    $userTable = 'users';
 
    if (isset($_REQUEST['username'])){
        $_SESSION['name'] = $_REQUEST['username'];  
    }else{
        $_SESSION['name'] = $_POST['username'];  
    }

    $password = $_REQUEST['password'];
    $userName = $_SESSION['name'];
    
// START OF MYSQL CONNECTION AFTER USER HAS BEEN VARIFIED
        $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "mejiad-db", $myPassword, "mejiad-db");
    if ($mysqli->connect_errno){
        echo "<p class=\"error\">Failed to connect to the contract database: (" . $mysqli->connect_errno . ")" . $mysqli->connect_error. "</p>";
    }
    
    ?>