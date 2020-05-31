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
                <!-- @if(auth('web')->user()) -->
                    <a href="javascript:void(0)" class="user-name">
                        <?php
                            // $name = auth('web')->user()->name;
                            // if(mb_strlen($name) > 4) {
                            //     $name = mb_substr($name, 0, 4).'...';
                            // }
                            // echo $name;
                        ?>
                        </a>
                <!-- @else -->
                    <a href="{{ url('web/auth/login') }}" class="user-name">Login</a>
                <!-- @endif -->
                <i class="down-up"></i>
            </div>
            <!-- @if(auth('web')->user()) -->
            <ol class="down-menus-li">
                <li><a href="{{ url('web/auth/password') }}">修改密码</a></li>
                <li><a href="{{ url('web/auth/logout') }}">退出系统</a></li>
            </ol>
            <!-- @endif -->
        </div>
        <div class="clearfix"></div>
    </nav>
</header>