<?php
include_once('header.php');
include_once('function.php');
?>

<?php
$info = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $result = $dbHelper->register($username, $password, $name);

    if ($result['code'] == 0) {
        $_SESSION["username"] = $username;
        $_SESSION["name"] = $name;
        header("Location: ./1930954.php");
    } else {
        $info = $result['message'];
    }
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="login-page">
            <section class="login-block">
                <p class="header">Registration</p>
                <p id="loginInfo"><?php echo $info; ?> </p>
                <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="text" name="username" required="required" value="" placeholder="Username">
                    <input type="password" name="password" required="required" value="" placeholder="Password">
                    <input type="text" name="name" required="required" value="" placeholder="Name">

                    <button type="submit" class="btn btn-primary login">Login</button>
                    <p class="forget"><a href="./registration.php" class="underline-a">Registration</a></p>
                </form>
            </section>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>