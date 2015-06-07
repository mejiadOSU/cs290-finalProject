<?php
session_start(); //Danny Mejia CS290 Final project. Its a contract database able to make new users and check password and user entry
?>

<!DOCTYPE html>

<html>
    <head>
        <link href="stylez.css" rel="stylesheet" type="text/css">
        <title>Logout Page</title>
    </head>
    <body>
        
    <table bgcolor="#999900" width="100%" class="pageHeader" >
    <tr>
        <td><header>Logout Page</header></td>
        <br>
        <br>
        <br>
    </tr>
    </table>

    <?php
    if($_SESSION['loggedin'] != TRUE) { // CHECKS IF USER IS EVEN LOGGED IN
        echo "You must be logged in to see this page." . "<br>";
        echo "<a href=\"login.html\">Click here to return to login page.</a>";
    }else { // ELSE CHECK IT THEY WANT TO LOG OUT
    
        echo "<table bgcolor=\"#FFFFFF\">";
        if(isset($_GET['logout'])) {
        echo "<tr>";
        $_SESSION['loggedin'] = false; 
        $_SESSION = array();
        session_destroy();
        echo "<td>";
        echo "<p>Your successfully logged out.<p><br>";
        echo "<a href=\"login.html\">Click here to return to login page.</a>";
        echo "<td>";
        echo "<tr> </table>";
        }
    }
    ?>

    </body>
    
    <br/>
    <br/>
    <br/>
    
    <hr>

    <footer>Contracts Database by Danny Mejia</footer>
    
</html>