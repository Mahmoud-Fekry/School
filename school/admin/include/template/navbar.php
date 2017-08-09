</div>
<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php">
                <i class="fa fa-university"></i> School</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right" id="app-nav">
            <ul class="nav navbar-nav">
                <li><a href="dashboard.php"> Dashboard </a></li>
                <li><a href="students.php"> Students </a></li>
                <li><a href="teachers.php"> Teachers </a></li>
                <li><a href="classes.php"> Classes </a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><?php echo $_SESSION['USERNAME'] ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="users.php?do=edit&user_id=<?php echo $_SESSION['USER_ID'] ?>"> Edit Profile</a></li>
                        <li><a href="users.php"> Edit Admins</a></li>
                        <li><a href="logout.php"> logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>