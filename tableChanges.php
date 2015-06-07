<!--  Danny Mejia CS290 Final project. Its a contract database able to make new users and check password and user entry -->
<?php
/*session code ides from class tutorials found here 
 *http://stackoverflow.com/questions/1545357/how-to-check-if-a-user-is-logged-in-in-php*/
session_start();
include 'storedInfo.php'; // stores secret code and opens mysqli table
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="stylez.css" rel="stylesheet" type="text/css">
        <title>Transmit Changes Page</title>
    </head>
    <body>
        
<?php
// LOGGIN CHECKER. THIS PAGE WILL NOT SHOW WITHOUT A PROPER LOGGIN
    if($_SESSION['loggedin'] != TRUE) {
        echo "You must be logged in to see this page." . "<br>";
        echo "<a href=\"login.html\">Click here to return to login page.</a>";
    }else {

// IF ITS NOT A NEW SESSION THEN ITS NOT THE LOGIN PAGE AND SHOW THE HEADER FOR THE CHANGES PAGE
if (!isset($_GET['password'])){
    echo "<table bgcolor=\"#999900\" width=\"100%\" class=\"pageHeader\" >
    <tr>
        <td><header>Transmit Changes Page</header></td>
        <br>
        <br>
        <br>
    </tr>";
}

// CODE TO ADD A NEW CONTRACT
if (isset($_POST['newCompany'])){  
    if (!empty($_POST['number']) && !empty($_POST['company']) && 
            !empty($_POST['funds']) && !empty($_POST['dateStart']) && !empty($_POST['dateEnd'])) {
        
        echo "Attempting to add contract...";
        
// DATE VALIDATOR CODE IDEA FROM LLIA  ROSTOVTSEV, 4 Nov 2013: http://stackoverflow.com/questions/19773418/regex-to-validate-date-in-php-using-format-as-yyyy-mm-dd
        $date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
        $startDate = $_POST['dateStart'];
        $endDate = $_POST['dateEnd'];

        if (!preg_match($date_regex, $startDate)) {
            echo "<p class=\"error\">Your start date does not match the YYYY-MM-DD format.</p>";
        } elseif(!preg_match($date_regex, $endDate)){
            echo "<p class=\"error\">Your end date does not match the YYYY-MM-DD format.</p>";
        }else{
// WHEN PASSED THE DATE VALIDATOR THIS WILL ATTEMPT TO ADD IT TO THE TABLE
        $number = $_POST['number'];
        $company = $_POST['company'];
        $funds = $_POST['funds'];
        
        $sql = "INSERT INTO $table".
                "(number, userName, company, funds, dateStart, dateEnd) ".
                "VALUES('$number', '$userName' , '$company', '$funds', '$startDate', '$endDate')";
        
        if (!mysqli_query($mysqli,$sql)) {
            echo "<p class=\"error\">Error: duplicate entry of contract number " . "\"" . $number . "\"</P>";
            echo "<p class=\"error\">Contract numbers must be unique. Try again</p>";
        } else {
            echo "New record created successfully";
          }
        }
    }else{ // ERRORS FRO THE BLANK BOXS
        if (empty($_POST['number'])){
            echo "<p class=\"error\">You must enter a contract number.</P>";
        }
        if (empty($_POST['company'])){
            echo "<p class=\"error\">You must enter the company name.</p>";
        }
        if (empty($_POST['funds'])){
            echo "<p class=\"error\">You must enter funds available.</p>";
        }
        if (empty($_POST['dateStart'])){
            echo "<p class=\"error\">You must enter a start date.</p>";
        }
        if (empty($_POST['dateEnd'])){
            echo "<p class=\"error\">You must enter an end date.</p>";
        }
        echo "<p class=\"error\">Please try again.</p>";
    }
}

//POST CODE TO DELETE CONTRACT SELECTED
// SOME OF THIS CODE CAME FROM http://www.w3schools.com/php/php_mysql_delete.asp. I COULDNT THINK OR GET ANY OTHER WAY TO DELETE A RECORD
if(isset($_POST['deleteContract'])) {
    $contractNumber = $_POST['deleteContract'];
    $sql = "DELETE FROM $table WHERE number = '$contractNumber'";

    if ($mysqli->query($sql) === TRUE) {
        echo "Contract deleted successfully";
    } else {
        echo "<p class=\"error\">Error deleting contract: " . $mysqli->error . "</p>";
    }
}

// MAKE PUBLIC CODE
if(isset($_POST['makePublic'])){
    $contractNumber = $_POST['makePublic'];
    $sql = "UPDATE $table SET userName = \"public\" WHERE number = '$contractNumber'";
    if ($mysqli->query($sql) === TRUE) {
        echo "Successfully made public";
    } else {
        echo "Error: " . $mysqli->error;
    }
}

// MAKE PRIVATE CODE
if(isset($_POST['makePrivate'])){
    $contractNumber = $_POST['makePrivate'];
    $sql = "UPDATE $table SET userName = '$userName' WHERE number = '$contractNumber'";
    if ($mysqli->query($sql) === TRUE) {
        echo "Successfully made public";
    } else {
        echo "Error: " . $mysqli->error;
    }
}
    /* USED TO INITIALY CREATE DATA BASE
    if (!$mysqli->query("DROP TABLE IF EXISTS contractTable") ||
        !$mysqli->query("CREATE TABLE contractTable(number INT PRIMARY KEY UNIQUE, userName VARCHAR(30) NOT NULL UNIQUE, company VARCHAR(30) NOT NULL, funds INT NOT NULL, dateStart DATE NOT NULL, dateEnd DATE NOT NULL, password VARCHAR(10) NOT NULL)") ||
        !$mysqli->query("INSERT INTO contractTable(number, userName, company, funds, dateStart, dateEnd, password) VALUES ('290','Danny', 'OSU', 20000, '2013-01-11', '2015-05-24', '1234')")) {
        echo "Table creation failed: (" . $mysqli->errno . ")" . $mysqli->error;
    }*/

// THIS IS THE TABLE PRINT CODE. IT ALWAYS PRINTS BUT THERE IS AN IF TO ADD THE OPTIONS OF DELETE AND MAKE PUBLIC
// THE FOLLOWING PRINT TABLE CODE IDEA CAME FROM http://www.anyexample.com/programming/php/php_mysql_example__display_table_as_html.xml
$result = mysqli_query($mysqli, "SELECT * FROM $table WHERE userName='$userName' OR userName='public' ");
    if (!$result){
        echo "Could not query table <br>";
    }else{
    
    echo '<table border = "1" class=\"contractTable\" width=\"100%\">';
    $count = 0;
    $categoryArray = array (0=>"");
    while ($row = mysqli_fetch_object($result)){
        
    if ($count == 0) {echo '<tr>' . '<td width=\"13%\">' . "Contract #" . '<td width=\"13%\">' . "User name" 
            .'<td width=\"13%\">' . "Company" . '<td width=\"13%\">' . "Funds" . '<td width=\"20%\">' 
            . "Start date" . '<td width=\"20%\">' . "End date";}
        
    if (isset($_GET['password'])){
        $movieId = $row->number;
                echo '<tr>' . '<td width=\"20%\">' . $row->number . '<td width=\"20%\">' . $row->userName . '<td width=\"20%\">' . $row->company . '<td width=\"20%\">'
                . "$" . $row->funds . '<td width=\"20%\">' . $row->dateStart . '<td width=\"20%\">' . $row->dateEnd;
    }else{
        $movieId = $row->number;
                echo '<tr>' . '<td>' . $row->number . '<td>' . $row->userName . '<td>' . $row->company . '<td>'
                . "$" . $row->funds . '<td>' . $row->dateStart . '<td>' . $row->dateEnd
                . '<td width=\"13%\">' . "<form id=\"deleteContract\" action=\"tableChanges.php\" method=\"post\">"
                . "<input type=\"hidden\" name=\"username\" value=\"$userName\">"
                . "<button type=\"submit\" name=\"deleteContract\" value=$row->number >Delete me</button>"
                . "</form>"
                . '<td width=\"13%\">' . "<form id=\"makePublic\" action=\"tableChanges.php\" method=\"post\">"
                . "<input type=\"hidden\" name=\"username\" value=\"$userName\">"
                . "<button type=\"submit\" name=\"makePublic\" value=$row->number >Make Public</button>"
                . "</form>"
                . '<td width=\"13%\">' . "<form id=\"makePrivate\" action=\"tableChanges.php\" method=\"post\">"
                . "<input type=\"hidden\" name=\"username\" value=\"$userName\">"
                . "<button type=\"submit\" name=\"makePrivate\" value=$row->number >Make Private</button>"
                . "</form>";
    }
        $count++;
    }
    echo '</table>';
    
    // IF ITS NOT A NEW SESSION THEN PRINT THE CONTRACT ADD FORM 
    if (!isset($_GET['password'])){
    echo "<p>To add a contract enter all the information below and press the submit button.</P>";
    echo "<br><form id=\"newCompany\" action=\"tableChanges.php\" method=\"post\">";
    echo "Company name:    <input type=\"text\" name=\"company\"><br>";
    echo "Contract number: <input type=\"number\" name=\"number\"><br>";
    echo "Funding:         <input type=\"number\" name=\"funds\"><br>";
    echo "Date Started:    <input type=\"text\" name=\"dateStart\"><br>";
    echo "Ending date:     <input type=\"text\" name=\"dateEnd\"><br>";
    echo "<input type=\"hidden\" name=\"username\" value=\"$userName\">";
    echo "<input type=\"submit\" name=\"newCompany\" value=\"submit\">";
    echo "</form><br><p>Enter the date in this format: YYYY-MM-DD.";
    echo "<br>When month and day in single digits enter the zero \"0\" before the number.</P>";
    }
    }

   }
    ?>

    </body>
    
    <br/>
    <br/>
    <br/>
    
    <hr>
<?php // LOGOUT BUTTON SENDS USER NAME TO REMEMBER SESSION
        echo "<form id=\"logout\" action=\"logOutPage.php\" method=\"get\">"
        . "<input type=\"hidden\" name=\"username\" value=\"$userName\">"
        . "<button type=\"submit\" name=\"logout\" value=\"yes\" >Logout</button>"
        . "</form>";
?>
    <footer>Contracts Database by Danny Mejia</footer>
    
</html>