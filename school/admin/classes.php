<?php

/*
                                       ===================================================
                                       ==               Manage Classes page             ==
                                       == You can Add / Edit /Delete Classes from here  ==
                                       ====================================================
*/


session_start();
$pageTitle = "Classes";

if (isset($_SESSION['USER_ID'])) {


    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';


    /***************************************************** Start Manage Page *****************************************************/

    if ($do == 'manage') {

//check if table of classes is empty or not

        $stmt = $con->prepare("SELECT
                                     *
                               FROM
                                      classes");
        $stmt->execute();

        $classes = $stmt->fetchAll();
        ?>

        <div class="container">
            <h1 class="text-center">Manage Classes</h1>

            <a class="btn btn-primary pull-right" href="?do=add"><i class="fa fa-plus "></i> New Class</a>
            <div class="manage  text-center">
                <?php

                //if table of classes is empty display empty

                if ($stmt->rowCount() < 1)
                echo "There is no data to display!!";
                else {

                //print all classes in the table

                ?>

                <table class="table table-bordered table-responsive  table-striped table-hover">
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Number of students</td>
                        <td>Controll</td>
                    </tr>


                    <?php
                    foreach ($classes as $class) {
                        ?>
                        <tr>
                            <td><?php echo $class['C_ID']; ?></td>
                            <td><?php echo $class['C_Name']; ?></td>
                            <td>
                                <?php
                                $stmt1 = $con->prepare("SELECT * FROM students WHERE C_id=?");
                                $stmt1->execute(array($class['C_ID']));
                                echo $stmt1->rowCount();
                                ?>
                            </td>
                            <td>
                                <a href="?do=edit&class_id=<?php echo $class['C_ID']; ?>"
                                   class="btn btn-success">Edit</a>
                                <a href="?do=delete&class_id=<?php echo $class['C_ID']; ?>"
                                   class="btn btn-danger confirm">Delete</a>
                            </td>
                        </tr>

                        <?php
                    }
                    ?>
                </table>
            </div>

            <?php

            }
            ?>
        </div>
        <?php
    }

    /***************************************************** Start Add Page *****************************************************/

    // If Get Reguest  do=add go to Add Page

    else if ($do == 'add') {
        ?>
        <h1 class="text-center">Add Class</h1>

        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="post">

                <!-- Start Class Name field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Class Name</label>
                    <div class=" col-sm-10">
                        <input class="form-control" type="text" name="c_name" required="required"/>
                    </div>
                </div>

                <!-- End Student Name field -->

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

        //if user coming from request method then add the new class in database
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $class_name = $_POST['c_name'];

            // Validata the form

            $formError = array();

            if (empty($class_name))
                $formError[] = "Name can't be empty";


            //check if class exist in database

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            classes
                                   WHERE    
                                            C_Name=?
                                   LIMIT 1");
            $stmt->execute(array($class_name));

            if ($stmt->rowCount() > 0)
                $formError[] = "Class is already Exist";

            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {


                //insert data into database

                $stmt = $con->prepare("INSERT INTO
                                                    classes (C_Name)
                                       VALUES 
                                                    (:iname)");

                //execute query

                $stmt->execute(array('iname' => $class_name));

                // display success msg

                echo "<div class='alert alert-success text-center msg'>Class added successfully</div>";


            }


        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }

    }

    /***************************************************** Start Edit Page *****************************************************/

    // If Get Reguest  do=edit go to edit Page

    if ($do == 'edit') {

        //Check if Get Request class_id numerical value && get it's value

        $id = isset($_GET['class_id']) && is_numeric($_GET['class_id']) ? $_GET['class_id'] : 0;

        //select all data depend on this id

        $stmt = $con->prepare("SELECT 
                                        *
                               FROM
                                        classes
                               WHERE
                                        C_ID = ?");

        //execute query

        $stmt->execute(array($id));

        $student = $stmt->fetch();


        //if there is such id display the form

        if ($stmt->rowCount() > 0) {

            ?>

            <h1 class="text-center">Edit Class</h1>

            <div class="container">
                <form class="form-horizontal" action="?do=update" method="post">

                    <!-- Start ID Field -->

                    <input type="hidden" name="class_id" value="<?php echo $id; ?>"/>

                    <!-- Start Class Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Class Name</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="c_name"
                                   value="<?php echo $student['C_Name']; ?>"
                                   required="required"/>
                        </div>
                    </div>

                    <!-- End Class Name field -->

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

            echo "<div class='alert alert-danger text-center'>No there is such Class</div>";
        }


        ?>


        <?php
    }

    /***************************************************** Start Update Page *****************************************************/


    // If Get Reguest  do=Update go to Update Page

    if ($do == 'update') {

        //check if coming through Request Method or Directly using url

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $class_id = $_POST['class_id'];
            $class_name = $_POST['c_name'];

            // Validata the form

            $formError = array();

            if (empty($class_name))
                $formError[] = "Name can't be empty";

            //check if class exist in database

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            classes
                                   WHERE    
                                            C_Name=?
                                   AND         
                                            C_ID!=?
                                   LIMIT 1");
            $stmt->execute(array($class_name, $class_id));

            if ($stmt->rowCount() > 0)
                $formError[] = "Class is already Exist";


            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {

                //Update database with new data

                $stmt = $con->prepare("UPDATE 
                                            classes 
                                       SET 
                                            C_Name=?
                                       WHERE 
                                            C_ID=?");

                // Excute Query

                $stmt->execute(array($class_name, $class_id));

                //Echo Sucess Message

                echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Class Updated </div>';

            }
        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }
    }

    /***************************************************** Start Delete Page *****************************************************/


    // If Get Reguest  do=Delete go to Delete Page

    if ($do == 'delete') {

        //Check if Get Request Class ID numerical value && get it's value

        $class_id = isset($_GET['class_id']) && is_numeric($_GET['class_id']) ? intval($_GET['class_id']) : 0;

        //Search about class in database

        $check = checkItem('C_ID', 'classes', $class_id);

        //Check if there is class with such id

        if ($check > 0) {

            //Delte class from database

            $stmt = $con->prepare("DELETE FROM classes WHERE C_ID=?");

            // Excute Query

            $stmt->execute(array($class_id));

            //Echo Sucess Message

            echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Class Deleted </div>';
            echo '</div>';

        } else {
            echo "<div class='alert alert-danger text-center msg'>There is no Class with such id</div>";
        }
    }

    include $temp . 'footer.php';
} else {
    header('location: index.php');
    exit();
}