<?php
session_start();
$pageTitle = "Login";
$noNavbar="";

if (isset($_SESSION['Username']))
    header('location: dashboard.php');  //redirect to dashboard page

include 'init.php';

//check if user come from post request then check if this user existed in database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $pass = sha1($_POST['password']);

    //check if user in database
    $stmt = $con->prepare("Select
                                  * 
                           FROM 
                                  users 
                           WHERE 
                                  User_Name = ? 
                           AND 
                                  Password = ?");

    $stmt->execute(array($username, $pass));

    $row = $stmt->fetch();

    //if number of recorde that found > 0 that mean database contain recorde about that user

    if ($stmt->rowCount() > 0) {

        $_SESSION['USERNAME'] = $username;
        $_SESSION['USER_ID'] = $row['User_ID'];
        header('location: dashboard.php');
        exit();
    }
}

//form to login

?>

    <form class="login text-center" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
        <h1>Admin Login</h1>
        <input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off">
        <input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password">
        <input class="btn btn-primary btn-block btn-lg" type="submit" name="submit" value="Login">
    </form>

<?php
include $temp . "footer.php";
?>