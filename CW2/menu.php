<header class="nav-header">
    <nav>
        <div class="site-title">
            <a href="./1930954.php">
                <span class="site-title-main">Sports Tracker</span>
                <sup class="site-title-up">PRO</sup>
            </a>
        </div>

        <ul class="nav-menus">
            <li><a href="./1930954.php">Home</a></li>
            <li><a href="">Course</a></li>
            <li><a href="">Exam</a></li>
            <li><a href="">Personal</a></li>
        </ul>


        <div class="down-menus">
            <div class="down-menus-main">
                <?php if (isset($_SESSION['name'])) { ?>
                    <a href="javascript:void(0)" class="user-name">
                        <?php
                        echo $_SESSION['name'];
                        ?>
                    </a>
                <?php } else { ?>
                    <a href="./login.php" class="user-name">Login</a>
                <?php } ?>

                <i class="down-up"></i>
            </div>
            <?php if ($_SESSION['name']) { ?>
            <ol class="down-menus-li">
                <li><a href="./profile.php">Profile</a></li>
                <li><a href="./logout.php">Logout</a></li>
            </ol>
            <?php } ?>
        </div>
        <div class="clearfix"></div>
    </nav>
</header>