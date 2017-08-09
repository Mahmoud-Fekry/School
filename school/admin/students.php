<?php

/*
                                       ===================================================
                                       ==               Manage students page             ==
                                       == You can Add / Edit /Delete students from here  ==
                                       ====================================================
*/


session_start();
$pageTitle = "Students";

if (isset($_SESSION['USER_ID'])) {


    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';


    /***************************************************** Start Manage Page *****************************************************/

    if ($do == 'manage') {

//check if table of student is empty or not

        $stmt = $con->prepare("SELECT
                                      students.*,
                                      classes.C_Name as c_name
                               FROM
                                      students
                               INNER JOIN 
                                      classes
                               ON 
                                       classes.C_ID = students.C_id");
        $stmt->execute();

        $students = $stmt->fetchAll();
        ?>

        <div class="container">
            <h1 class="text-center">Manage Students</h1>

            <a class="btn btn-primary pull-right" href="?do=add"><i class="fa fa-plus "></i> New Student</a>
            <div class="manage  text-center">
                <?php

                //if table of students is empty display empty

                if ($stmt->rowCount() < 1)
                echo "There is no data to display!!";
                else {

                //print all student in the table

                ?>

                <table class="table table-bordered table-responsive  table-striped table-hover">
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Level</td>
                        <td>Class</td>
                        <td>Controll</td>
                    </tr>


                    <?php
                    foreach ($students as $student) {
                        ?>
                        <tr>
                            <td><?php echo $student['S_ID']; ?></td>
                            <td><?php echo $student['S_Name']; ?></td>
                            <td><?php echo $student['S_Level']; ?></td>
                            <td><?php echo $student['c_name']; ?></td>
                            <td>
                                <a href="?do=edit&student_id=<?php echo $student['S_ID']; ?>"
                                   class="btn btn-success">Edit</a>
                                <a href="?do=delete&student_id=<?php echo $student['S_ID']; ?>"
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
        <h1 class="text-center">Add Student</h1>

        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="post">

                <!-- Start Student Name field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Student Name</label>
                    <div class=" col-sm-10">
                        <input class="form-control" type="text" name="s_name" required="required"/>
                    </div>
                </div>

                <!-- End Student Name field -->

                <!-- Start level field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Level</label>
                    <div class="col-sm-10">
                        <select name="level">
                            <option value="0">....</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                </div>

                <!-- End level field -->

                <!-- Start Class field -->

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-lable">Class</label>
                    <div class="col-sm-10">
                        <select name="class">
                            <option value="0">....</option>
                            <?php

                            $stmt = $con->prepare("SELECT * FROM classes");
                            $stmt->execute();
                            $classes = $stmt->fetchAll();
                            foreach ($classes as $class) {
                                echo '<option value="' . $class['C_ID'] . '"> ' . $class['C_Name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- End Class field -->

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

            $student_name = $_POST['s_name'];
            $student_level = $_POST['level'];
            $student_class = $_POST['class'];

            // Validata the form

            $formError = array();

            if (empty($student_name))
                $formError[] = "Name can't be empty";
            if ($student_level == 0)
                $formError[] = "Choose student level";
            if ($student_class == 0)
                $formError[] = "Choose student Class";

            //check if student exist in database

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            students
                                   WHERE    
                                            S_Name=?
                                   LIMIT 1");
            $stmt->execute(array($student_name));

            if ($stmt->rowCount() > 0)
                $formError[] = "Student is already Exist";

            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {


                //insert data into database

                $stmt = $con->prepare("INSERT INTO
                                                    students (S_Name, S_Level, C_id)
                                       VALUES 
                                                    (:iname, :ilevel, :iclass)");

                //execute query

                $stmt->execute(array('iname' => $student_name, 'ilevel' => $student_level, 'iclass' => $student_class));

                // display success msg

                echo "<div class='alert alert-success text-center msg'>Student added successfully</div>";


            }


        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }

    }

    /***************************************************** Start Edit Page *****************************************************/

    // If Get Reguest  do=edit go to edit Page

    if ($do == 'edit') {

        //Check if Get Request student_id numerical value && get it's value

        $id = isset($_GET['student_id']) && is_numeric($_GET['student_id']) ? $_GET['student_id'] : 0;

        //select all data depend on this id

        $stmt = $con->prepare("SELECT 
                                        *
                               FROM
                                        students
                               WHERE
                                        S_ID = ?");

        //execute query

        $stmt->execute(array($id));

        $student = $stmt->fetch();


        //if there is such id display the form

        if ($stmt->rowCount() > 0) {

            ?>

            <h1 class="text-center">Edit Student</h1>

            <div class="container">
                <form class="form-horizontal" action="?do=update" method="post">

                    <!-- Start ID Field -->

                    <input type="hidden" name="student_id" value="<?php echo $id; ?>"/>

                    <!-- Start Student Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Student Name</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="s_name"
                                   value="<?php echo $student['S_Name']; ?>"
                                   required="required"/>
                        </div>
                    </div>

                    <!-- End Student Name field -->

                    <!-- Start level field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Level</label>
                        <div class="col-sm-10">
                            <select name="level">
                                <option value="0">....</option>

                                <option value="1" <?php if ($student['S_Level'] == 1) echo 'selected' ?>>1</option>
                                <option value="2" <?php if ($student['S_Level'] == 2) echo 'selected' ?>>2</option>
                                <option value="3" <?php if ($student['S_Level'] == 3) echo 'selected' ?>>3</option>
                                <option value="4" <?php if ($student['S_Level'] == 4) echo 'selected' ?>>4</option>


                            </select>
                        </div>
                    </div>

                    <!-- End level field -->

                    <!-- Start Class field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Class</label>
                        <div class="col-sm-10">
                            <select name="class">
                                <option value="0">....</option>
                                <?php

                                $stmt1 = $con->prepare("SELECT * FROM classes");
                                $stmt1->execute();
                                $classes = $stmt1->fetchAll();
                                foreach ($classes as $class) {
                                    if ($student['C_id'] == $class['C_ID']) {
                                        $select = 'selected';
                                    } else
                                        $select = " ";
                                    echo '<option value="' . $class['C_ID'] . '" ' . $select . '> ' . $class['C_Name'] . '</option>';

                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- End Class field -->

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

            echo "<div class='alert alert-danger text-center'>No there is such Student</div>";
        }


        ?>


        <?php
    }

    /***************************************************** Start Update Page *****************************************************/


    // If Get Reguest  do=Update go to Update Page

    if ($do == 'update') {

        //check if coming through Request Method or Directly using url

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $student_id = $_POST['student_id'];
            $student_name = $_POST['s_name'];
            $student_level = $_POST['level'];
            $student_class = $_POST['class'];

            // Validata the form

            $formError = array();

            if (empty($student_name))
                $formError[] = "Name can't be empty";
            if ($student_level == 0)
                $formError[] = "Choose student level";
            if ($student_class == 0)
                $formError[] = "Choose student Class";


            //check if student exist in database

            $stmt = $con->prepare("SELECT
                                            *
                                   FROM
                                            students
                                   WHERE    
                                            S_Name=?
                                   AND
                                            S_ID!=?
                                   LIMIT 1");
            $stmt->execute(array($student_name, $student_id));

            if ($stmt->rowCount() > 0)
                $formError[] = "Student is already Exist";

            //display errors if exist

            foreach ($formError as $error) {
                echo '<div class="alert alert-danger text-center msg">' . $error . '</div>';
            }

            //Check if there is no error if true execute query

            if (empty($formError)) {

                //Update database with new data

                $stmt = $con->prepare("UPDATE 
                                            students 
                                       SET 
                                            S_Name=?, S_Level=?, C_id=? 
                                       WHERE 
                                            S_ID=?");

                // Excute Query

                $stmt->execute(array($student_name, $student_level, $student_class, $student_id));

                //Echo Sucess Message

                echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Item Updated </div>';

            }
        } //if user coming from direct url displar erorr msg
        else {
            echo "<div class='alert alert-danger text-center msg'>You can't browse this page directly</div>";
        }


    }

    /***************************************************** Start Delete Page *****************************************************/


    // If Get Reguest  do=Delete go to Delete Page

    if ($do == 'delete') {

        //Check if Get Request student ID numerical value && get it's value

        $student_id = isset($_GET['student_id']) && is_numeric($_GET['student_id']) ? intval($_GET['student_id']) : 0;

        //Search about student in database

        $check = checkItem('S_ID', 'students', $student_id);

        //Check if there is student with such id

        if ($check > 0) {

            //Delte student from database

            $stmt = $con->prepare("DELETE FROM students WHERE S_ID=?");

            // Excute Query

            $stmt->execute(array($student_id));

            //Echo Sucess Message

            echo '<div class="alert alert-success text-center msg">' . $stmt->rowCount() . ' Student Deleted </div>';
            echo '</div>';

        } else {
            echo "<div class='alert alert-danger text-center msg'>There is no student with such id</div>";
        }
    }

    include $temp . 'footer.php';
} else {
    header('location: index.php');
    exit();
}