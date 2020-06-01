<?php
include_once('header.php');
include_once('function.php');
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $account = $_POST['account'];
    $password = $_POST['password'];
    if (empty($name)) {
        echo "Name is empty";
    } else {
        echo $name;
    }
    return;
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="login-page">
            <section class="login-block">
                <p class="header">Login</p>
                <p id="loginInfo"> </p>
                <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="text" id="account" required="required" value="" placeholder="Account">
                    <input type="password" id="password" required="required" value="" placeholder="Password">
                    <button type="submit" class="btn btn-primary login">Login</button>
                    <p class="forget"><a href="./forget.php" class="underline-a">Forget Password？</a></p>
                </form>
            </section>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>