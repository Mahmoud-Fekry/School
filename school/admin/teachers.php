<?php

/*
                                       ===================================================
                                       ==               Manage Teachers page             ==
                                       == You can Add / Edit /Delete Teachers from here  ==
                                       ====================================================
*/


session_start();
$pageTitle = "Teachers";

if (isset($_SESSION['USER_ID'])) {


    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';


    /***************************************************** Start Manage Page *****************************************************/

    if ($do == 'manage') {

//check if table of student is empty or not

        $stmt = $con->prepare("SELECT
                                      *
                               FROM
                                      teachers ");
        $stmt->execute();

        $teachers = $stmt->fetchAll();
        ?>

        <div class="container">
            <h1 class="text-center">Manage Teachers</h1>

            <a class="btn btn-primary  pull-right" href="?do=add"><i class="fa fa-plus"></i> New Teacher</a>
            <div class="manage  text-center">
                <?php

                //if table of teachers is empty display empty

                if ($stmt->rowCount() < 1)
                echo "There is no data to display!!";
                else {

                //print all teachers in the table

                ?>

                <table class="table table-bordered table-responsive  table-striped table-hover">
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Salary</td>
                        <td>Classes</td>
                        <td>Controll</td>
                    </tr>


                    <?php
                    foreach ($teachers as $teacher) {
                        ?>
                        <tr>
                            <td><?php echo $teacher['T_ID']; ?></td>
                            <td><?php echo $teacher['T_Name']; ?></td>
                            <td><?php echo $teacher['T_Salary']; ?></td>
                            <td>
                                <?php
                                //select all classes related to current teacher

                                $stmt1 = $con->prepare("SELECT
                                                                  teacher_class.*,
                                                                  classes.C_Name as c_name
                                                        FROM
                                                                  teacher_class
                                                        INNER JOIN
                                                                  classes
                                                        ON
                                                                  classes.C_ID = teacher_class.C_id
                                                        WHERE
                                                                  T_id=?");

                                $stmt1->execute(array($teacher['T_ID']));

                                $teacher_classes = $stmt1->fetchAll();

                                if ($stmt1->rowCount() < 1) echo "Doesn't teach to any class !!";
                                else {

                                    foreach ($teacher_classes as $teacher_class) {
                                        ?>

                                        <div class="t_class clearfix">
                                            <span><?php echo $teacher_class['c_name']; ?></span>
                                            <a href="?do=delete_class&class_id=<?php echo $teacher_class['C_id'] ?>&teacher_id=<?php echo $teacher_class['T_id']; ?>"
                                               class="btn btn-danger pull-right">Remove</a><br>
                                        </div>
                                        <?php
                                    }
                                }

                                ?>

                            </td>
                            <td>
                                <a href="?do=edit&teacher_id=<?php echo $teacher['T_ID']; ?>"
                                   class="btn btn-success">Edit</a>
                                <a href="?do=delete&teacher_id=<?php echo $teacher['T_ID']; ?>"
                                   class="btn btn-danger confirm">Delete</a>
                                <a href="?do=add_class&teacher_id=<?php echo $teacher['T_ID']; ?>"
                                   class="btn btn-info">Add Class</a>
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
        <h1 class="text-center">Add Teacher</h1>

        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="post">

                <!-- Start Teacher Name field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Teacher Name</label>
                    <div class=" col-sm-10">
                        <input class="form-control" type="text" name="t_name" required="required"/>
                    </div>
                </div>

                <!-- End Student Name field -->

                <!-- Start Salary field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Teacher Salary</label>
                    <div class=" col-sm-10">
                        <input class="form-control" type="text" name="t_salary" required="required"/>
                    </div>
                </div>

                <!-- End Salary field -->

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

        //if user coming from request method then add the new teacher in database
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $teacher_name = $_POST['t_name'];
            $teacher_salary = $_POST['t_salary'];

            // Validata the form

            $formError = array();

            if (empty($teacher_name))
                $formError[] = "Name can't be empty";
            if (empty($teacher_salary)) {
                $formError[] = "Salary Can't Be Empty";
            } else {
                if (!is_numeric($teacher_salary)) {
                    $formError[] = 'Salary must be number';
                }
            }

            //check if teacher exist in database

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            teachers
                                   WHERE    
                                            T_Name=?
                                   LIMIT 1");
            $stmt->execute(array($teacher_name));

            //Check if there is teacher with such id

            if ($stmt->rowCount() > 0)
                $formError[] = "Teacher is already Exist";

            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {


                //insert data into database

                $stmt = $con->prepare("INSERT INTO
                                                   teachers (T_Name, T_Salary)
                                       VALUES 
                                                    (:iname, :isalary)");

                //execute query

                $stmt->execute(array('iname' => $teacher_name, 'isalary' => $teacher_salary));

                // display success msg

                echo "<div class='alert alert-success text-center msg'>Teacher added successfully</div>";


            }


        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }

    }

    /***************************************************** Start Edit Page *****************************************************/

// If Get Reguest  do=edit go to edit Page

    if ($do == 'edit') {

        //Check if Get Request teacher_id numerical value && get it's value

        $id = isset($_GET['teacher_id']) && is_numeric($_GET['teacher_id']) ? $_GET['teacher_id'] : 0;

        //select all data depend on this id

        $stmt = $con->prepare("SELECT 
                                        *
                               FROM
                                        teachers
                               WHERE
                                        T_ID = ?");

        //execute query

        $stmt->execute(array($id));

        $teacher = $stmt->fetch();


        //if there is such id display the form

        if ($stmt->rowCount() > 0) {

            ?>

            <h1 class="text-center">Edit Teacher</h1>

            <div class="container">
                <form class="form-horizontal" action="?do=update" method="post">

                    <!-- Start ID Field -->

                    <input type="hidden" name="teacher_id" value="<?php echo $id; ?>"/>

                    <!-- Start Student Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Teacher Name</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="t_name"
                                   value="<?php echo $teacher['T_Name']; ?>"
                                   required="required"/>
                        </div>
                    </div>

                    <!-- End Student Name field -->

                    <!-- Start Salary field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Teacher Salary</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="t_salary"
                                   value="<?php echo $teacher['T_Salary']; ?>"
                                   required="required"/>
                        </div>
                    </div>

                    <!-- End Salary field -->

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

            echo "<div class='alert alert-danger text-center'>No there is such Teacher</div>";
        }


        ?>


        <?php
    }

    /***************************************************** Start Update Page *****************************************************/


// If Get Reguest  do=Update go to Update Page

    if ($do == 'update') {

        //check if coming through Request Method or Directly using url

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $teacher_id = $_POST['teacher_id'];
            $teacher_name = $_POST['t_name'];
            $teacher_salary = $_POST['t_salary'];

            // Validata the form

            $formError = array();

            if (empty($teacher_name))
                $formError[] = "Name can't be empty";
            if (empty($teacher_salary)) {
                $formError[] = "Salary Can't Be Empty";
            } else {
                if (!is_numeric($teacher_salary)) {
                    $formError[] = 'Salary must be number';
                }
            }

            //check if teacher exist in database with same  new data

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            teachers
                                   WHERE    
                                            T_Name=?
                                  AND 
                                            T_ID!=?
                                   LIMIT 1");
            $stmt->execute(array($teacher_name, $teacher_id));

            //Check if there is teacher with such id

            if ($stmt->rowCount() > 0)
                $formError[] = "Teacher is already Exist";

            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {

                //Update database with new data

                $stmt = $con->prepare("UPDATE 
                                            teachers
                                       SET 
                                            T_Name=?, T_Salary=? 
                                       WHERE 
                                            T_ID=?");

                // Excute Query

                $stmt->execute(array($teacher_name, $teacher_salary, $teacher_id));

                //Echo Sucess Message

                echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Teacher Updated </div>';

            }
        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }


    }

    /***************************************************** Start Delete Page *****************************************************/


// If Get Reguest  do=Delete go to Delete Page

    if ($do == 'delete') {

        //Check if Get Request teacher ID numerical value && get it's value

        $teacher_id = isset($_GET['teacher_id']) && is_numeric($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

        //Search about teacher in database

        $check = checkItem('T_ID', 'teachers', $teacher_id);

        //Check if there is teacher with such id

        if ($check > 0) {

            //Delete teacher from database

            $stmt = $con->prepare("DELETE FROM teachers WHERE T_ID=?");

            // Excute Query

            $stmt->execute(array($teacher_id));

            //Echo Sucess Message

            echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Teacher Deleted </div>';
            echo '</div>';

        } else {
            echo "<div class='alert alert-danger text-center msg'>There is no Teacher with such id</div>";
        }
    }

    /***************************************************** Start Add Class Page *****************************************************/


    // If Get Reguest  do=add_class go to Edit_classes Page

    if ($do == 'add_class') {

        //Check if Get Request teacher_id numerical value && get it's value

        $teacher_id = isset($_GET['teacher_id']) && is_numeric($_GET['teacher_id']) ? $_GET['teacher_id'] : 0;

        echo '<div class="container">';

        echo '<h1 class="text-center t_class"></h1>';

        //Display All Classes that aren't added to that teacher


        $stmt = $con->prepare("SELECT
                                        *
                               FROM
                                        classes");
        $stmt->execute();

        $classes = $stmt->fetchAll();

        //check if there is no classes

        if ($stmt->rowCount() < 1) {
            echo 'There is no class to show';
        } else {

            ?>


            <form class="form-horizontal" action="?do=update_class&teacher_id=<?php echo $teacher_id; ?>" method="post">
                <div class="row">


                    <!-- Start id field -->

                    <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>"/>

                    <!-- End idw field -->


                    <!-- Start class field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-1 col-sm-offset-3 control-lable">Classes</label>
                        <div class="col-sm-8">
                            <select name="class" class="form-control">
                                <option value="0">....</option>
                                <?php
                                $i = 1;
                                foreach ($classes as $class) {

                                    $stmt1 = $con->prepare("SELECT * FROM teacher_class WHERE T_id=? AND C_id=?");
                                    $stmt1->execute(array($teacher_id, $class['C_ID']));
                                    if ($stmt1->rowCount() == 0)

                                        echo '<option value="' . $class['C_ID'] . '">' . $class['C_Name'] . '</option>';


                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- End Class field -->

                    <!-- Start Submit field -->

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-4 col-sm-8">
                            <input class="btn btn-primary btn-lg" type="submit" value="Add"/>
                        </div>
                    </div>

                    <!-- End Submit field -->


                </div>
            </form>
            </div>

            <?php
        }

    }

    /***************************************************** Start insert_class Page *****************************************************/


    // If Get Reguest  do=update_class go to update_Class Page

    // If Get Reguest  do=Update go to Update Page

    if ($do == 'update_class') {

        //check if coming through Request Method or Directly using url

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $teacher_id = $_POST['teacher_id'];
            $class_id = $_POST['class'];

            // Validata the form

            $formError = array();


            if ($class_id == 0)
                $formError[] = "Choose class name ";
            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {

                //insert data into database

                $stmt = $con->prepare("INSERT INTO
                                                   teacher_class (T_id, C_id)
                                       VALUES 
                                                    (:it_id, :ic_id)");

                //execute query

                $stmt->execute(array('it_id' => $teacher_id, 'ic_id' => $class_id));

                // display success msg

                echo "<div class='alert alert-success text-center msg'>Class added successfully</div>";

            }
        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }


    }


    /***************************************************** Start Delete_Class Page *****************************************************/


    // If Get Reguest  do=Delete go to Delete_Class Page

    if ($do == 'delete_class') {

        //Check if Get Request teacher ID numerical value && get it's value

        $teacher_id = isset($_GET['teacher_id']) && is_numeric($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

        //Check if Get Request class ID numerical value && get it's value

        $class_id = isset($_GET['class_id']) && is_numeric($_GET['class_id']) ? intval($_GET['class_id']) : 0;

        //Search about teacher in database

        $stmt = $con->prepare("SELECT * FROM teacher_class WHERE T_id=? AND C_id=?");

        $stmt->execute(array($teacher_id, $class_id));

        //Check if there is record with such id

        if ($stmt->rowCount() > 0) {

            //Delete teacher from database

            $stmt = $con->prepare("DELETE FROM teacher_class WHERE T_id=? AND C_id=?");

            // Excute Query

            $stmt->execute(array($teacher_id, $class_id));

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