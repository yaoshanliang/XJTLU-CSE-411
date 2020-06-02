<?php
include_once('header.php');
include_once('function.php');
?>

<?php
$loginInfo = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = $dbHelper->login($username, $password);
    if ($result['code'] == 0) {
        $_SESSION["username"] = $username;
        $_SESSION["id"] = $result['data']['id'];
        $_SESSION["name"] = $result['data']['name'];
        $_SESSION["gender"] = $result['data']['gender'];
        $_SESSION["birthday"] = $result['data']['birthday'];
        header("Location: ./1930954.php");
    } else {
        $loginInfo = $result['message'];
    }
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="login-page">
            <section class="login-block">
                <p class="header">Login</p>
                <p id="loginInfo"><?php echo $loginInfo; ?> </p>
                <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="text" name="username" required="required" value="" placeholder="Username">
                    <input type="password" name="password" required="required" value="" placeholder="Password">
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