<?php

session_start();
$pageTitle = "Dashboard";
include 'init.php';

if (isset($_SESSION['USERNAME'])) {

    ?>

    <div class="dashboard text-center">
        <div class="container">
            <h1 class="modal-header page-header">Welcome</h1>

            <!--------------------------------------  Display all data about classes ---------------------------------->

            <div class="row row1">
                <?php

                //bring all data from  classes table to set title

                $class_stmt = $con->prepare("SELECT
                                                      * 
                                             FROM 
                                                      classes");

                $class_stmt->execute();

                $classes = $class_stmt->fetchAll();

                //check if there is no data then display that

                if ($class_stmt->rowCount() < 1) {

                    ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            No data to display !!
                        </div>
                    </div>

                    <?php
                } // Display All data

                else {

                    foreach ($classes as $class) {
                        ?>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php echo 'Class ' . $class['C_Name']; ?>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6 s">
                                            <div class="student">
                                                <p class="s-head"> Students</p>

                                                <?php
                                                //Display All Students in this class

                                                $student_stmt = $con->prepare("SELECT
                                                                                       *
                                                                               FROM
                                                                                        students
                                                                               WHERE 
                                                                                        C_id=?");

                                                $student_stmt->execute(array($class['C_ID']));

                                                $students = $student_stmt->fetchAll();

                                                //ifthere is no student display that

                                                if ($student_stmt->rowCount() < 1) {
                                                    echo "No students in this class !!";
                                                } else {
                                                    foreach ($students as $student) {
                                                        echo '<p class="s-body">' . $student['S_Name'] . '</p>';
                                                    }
                                                }

                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 t">
                                            <div class="teacher">
                                                <p class="t-head"> Teachers</p>

                                                <?php
                                                //Display All Teachers in this class


                                                $teacher_stmt = $con->prepare("SELECT
                                                                                          teacher_class.*,
                                                                                          teachers.T_Name as t_name
                                                                                FROM
                                                                                          teacher_class
                                                                                INNER JOIN
                                                                                          teachers
                                                                                ON
                                                                                          teachers.T_ID = teacher_class.T_id
                                                                                WHERE
                                                                                          C_id=?");

                                                $teacher_stmt->execute(array($class['C_ID']));

                                                $teachers = $teacher_stmt->fetchAll();

                                                //ifthere is no teacher display that

                                                if ($teacher_stmt->rowCount() < 1) {
                                                    echo "No teachers for this class !!";
                                                } else {
                                                    foreach ($teachers as $teacher) {
                                                        echo '<p class="t-body">' . $teacher['t_name'] . '</p>';
                                                    }
                                                }

                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }

                }

                ?>

            </div>
        </div>

    </div>


    <?php

    include $temp . 'footer.php';

} else {
    header('location: index.php');
    exit();
}