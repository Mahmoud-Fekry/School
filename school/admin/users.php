<?php

/*
                                       ===================================================
                                       ==               Manage Amin page             ==
                                       == You can Add / Edit /Delete Admin from here  ==
                                       ====================================================
*/


session_start();
$pageTitle = "Admin";

if (isset($_SESSION['USER_ID'])) {


    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';


    /***************************************************** Start Manage Page *****************************************************/

    if ($do == 'manage') {

        //Dislay all admin in table

        $stmt = $con->prepare("SELECT
                                     *
                               FROM
                                      users
                               WHERE 
                                      User_ID!=?");
        $stmt->execute(array($_SESSION['USER_ID']));

        $admins = $stmt->fetchAll();
        ?>

        <div class="container">
            <h1 class="text-center">Manage Admins</h1>

            <a class="btn btn-primary pull-right" href="?do=add"><i class="fa fa-plus "></i> New Admin</a>
            <div class="manage  text-center">


                <table class="table table-bordered table-responsive  table-striped table-hover">
                    <tr>
                        <td>ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Controll</td>
                    </tr>


                    <?php
                    foreach ($admins as $admin) {
                        ?>
                        <tr>
                            <td><?php echo $admin['User_ID']; ?></td>
                            <td><?php echo $admin['User_Name']; ?></td>
                            <td><?php echo $admin['Email']; ?></td>
                            <td><?php echo $admin['Full_Name']; ?></td>
                            <td>
                                <a href="?do=edit&user_id=<?php echo $admin['User_ID']; ?>"
                                   class="btn btn-success">Edit</a>
                                <a href="?do=delete&user_id=<?php echo $admin['User_ID']; ?>"
                                   class="btn btn-danger confirm">Delete</a>
                            </td>
                        </tr>

                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php
    }

    /***************************************************** Start Add Page *****************************************************/

    // If Get Reguest  do=add go to Add Page

    else if ($do == 'add') {
        ?>
        <h1 class="text-center">Add Admin</h1>

        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="post">

                <!-- Start username field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Username</label>
                    <div class=" col-sm-10">
                        <input class="form-control" type="text" name="username" required="required"/>
                    </div>
                </div>

                <!-- End username field -->

                <!-- Start Password field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2  control-lable">Password</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="password" name="password" autocomplete="new-password"
                               required="required"/>
                    </div>
                </div>

                <!-- End Password field -->

                <!-- Start Email field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Email</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="email" name="email" required="required"/>
                    </div>
                </div>

                <!-- End Email field -->

                <!-- Start Full Name field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Full Name</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="fullname" required="required"/>
                    </div>
                </div>

                <!-- End Full Name field -->

                <!-- Start Submit field -->

                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input class="btn btn-primary btn-lg" type="submit" value="Add"/>
                    </div>
                </div>

                <!-- End Submit field -->

            </form>
        </div>

        <?php
    }

    /***************************************************** Start insert Page *****************************************************/

    // If Get Reguest  do=insert go to insert Page

    else if ($do == 'insert') {

        //check if user coming from request method or through direct url

        //if user coming from request method then add the new student in database
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];

            //Password Trick

            $hashPass = sha1($pass);

            // Validate the form

            $formError = array();

            if (empty($username)) {
                $formError[] = 'Username Can\'t Be Empty';
            } else {
                if (strlen($username) > 20) {
                    $formError[] = 'Username Must Be Less Than 20 Charactar';
                }
            }

            if (empty($pass)) {
                $formError[] = 'Password Can\'t Be Empty';
            }

            if (empty($email)) {
                $formError[] = 'Email Can\'t Be Empty';
            }
            if (empty($fullname)) {
                $formError[] = 'Full Name Can\'t Be Empty';
            }

            //check if user exist in database

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            users
                                   WHERE    
                                            User_Name=?
                                   LIMIT 1");
            $stmt->execute(array($username));

            if ($stmt->rowCount() > 0)
                $formError[] = "Admin is already Exist";

            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {


                //Insert admin info in database

                $stmt = $con->prepare("INSERT INTO users (User_Name, Password, Email, Full_Name)
                                                      VALUES (:iuser, :ipass, :iemail, :iname)");

                // Excute Query

                $stmt->execute(array('iuser' => $username, 'ipass' => $hashPass, 'iemail' => $email, 'iname' => $fullname));

                // display success msg

                echo "<div class='alert alert-success text-center msg'>Admin added successfully</div>";


            }


        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }

    }

    /***************************************************** Start Edit Page *****************************************************/

    // If Get Reguest  do=edit go to edit Page

    if ($do == 'edit') {

        //Check if Get Request admin_id numerical value && get it's value

        $user_id = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? intval($_GET['user_id']) : 0;

        //select all data depend on this id

        $stmt = $con->prepare("SELECT 
                                        *
                               FROM
                                        users
                               WHERE
                                        USER_ID = ?");

        //execute query

        $stmt->execute(array($user_id));

        $admins = $stmt->fetch();


        //if there is such id display the form

        if ($stmt->rowCount() > 0) {

            ?>

            <h1 class="text-center">Edit Admin</h1>

            <div class="container">
                <form class="form-horizontal" action="?do=update" method="post">

                    <!-- Start ID Field -->

                    <input type="hidden" name="userid" value="<?php echo $user_id; ?>"/>

                    <!-- Start Username field -->


                    <!-- Start  Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Username</label>
                        <div class=" col-sm-10">
                            <input class="form-control u" type="text" name="username"
                                   value="<?php echo $admins['User_Name']; ?>" autocomplete="off" required="required"/>
                        </div>
                    </div>

                    <!-- End Username field -->

                    <!-- Start Password field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-lable">Password</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="old-password" value="<?php echo $admins['Password'] ?>"/>
                            <input class="form-control" type="password" name="new-password"
                                   autocomplete="new-password"/>
                        </div>
                    </div>

                    <!-- End Password field -->

                    <!-- Start Email field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Email</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email"
                                   value="<?php echo $admins['Email']; ?>" required="required"/>
                        </div>
                    </div>

                    <!-- End Email field -->

                    <!-- Start Full Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Full Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="fullname"
                                   value="<?php echo $admins['Full_Name']; ?>" required="required"/>
                        </div>
                    </div>

                    <!-- End Full Name field -->

                    <!-- Start Submit field -->

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary btn-lg" type="submit" value="Save"/>
                        </div>
                    </div>

                    <!-- End Submit field -->

                </form>
            </div>

            <?php

        } // If Ther is No  Such ID Display Error Message

        else {

            echo "<div class='alert alert-danger text-center'>No there is such Admin</div>";
        }

        ?>


        <?php
    }

    /***************************************************** Start Update Page *****************************************************/


    // If Get Reguest  do=Update go to Update Page

    if ($do == 'update') {

        //check if coming through Request Method or Directly using url

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get variable from form

            $userid = $_POST['userid'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];

            //Password Trick

            $pass = empty($_POST['new-password']) ? $_POST['old-password'] : sha1($_POST['new-password']);


            // Validate the form

            $formError = array();

            if (empty($username)) {
                $formError[] = 'Username Can\'t Be Empty';
            } else {
                if (strlen($username) > 20) {
                    $formError[] = 'Username Must Be Less Than 20 Charactar';
                }
            }

            if (empty($pass)) {
                $formError[] = 'Password Can\'t Be Empty';
            }

            if (empty($email)) {
                $formError[] = 'Email Can\'t Be Empty';
            }
            if (empty($fullname)) {
                $formError[] = 'Full Name Can\'t Be Empty';
            }

            //check if student exist in database

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            users
                                   WHERE    
                                            User_Name=?
                                   AND
                                            User_ID!=?
                                   LIMIT 1");
            $stmt->execute(array($username, $userid));

            if ($stmt->rowCount() > 0)
                $formError[] = "Admin is already Exist";

            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {

                //Update database with this data

                $stmt = $con->prepare("UPDATE users SET User_Name=? , Email=? , Full_Name=? , Password=? WHERE User_ID=?");

                // Excute Query

                $stmt->execute(array($username, $email, $fullname, $pass, $userid));
                //Echo Sucess Message

                echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Admin Updated </div>';

            }
        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }


    }

    /***************************************************** Start Delete Page *****************************************************/


    // If Get Reguest  do=Delete go to Delete Page

    if ($do == 'delete') {

        //Check if Get Request admin ID numerical value && get it's value

        $user_id = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ?intval($_GET['user_id']) : 0;

        //Search about admin in database

        $check = checkItem('User_ID', 'users', $user_id);

        //Check if there is admin with such id

        if ($check > 0) {

            //Delte admin from database

            $stmt = $con->prepare("DELETE FROM users WHERE User_ID=?");

            // Excute Query

            $stmt->execute(array($user_id));

            //Echo Sucess Message

            echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Admin Deleted </div>';
            echo '</div>';

        } else {
            echo "<div class='alert alert-danger text-center msg'>There is no admin with such id</div>";
        }
    }

    include $temp . 'footer.php';
} else {
    header('location: index.php');
    exit();
}