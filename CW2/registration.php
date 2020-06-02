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
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $profile = $_POST['profile'];
    $email = $_POST['email'];

    $result = $dbHelper->register($username, $password, $name, $email, $gender, $birthday, $profile);

    if ($result['code'] == 0) {
        $_SESSION["username"] = $username;
        $_SESSION["name"] = $name;
        $_SESSION["gender"] = $gender;
        $_SESSION["birthday"] = $birthday;
        $_SESSION["profile"] = $profile;
        $_SESSION["email"] = $email;
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
                    <input type="email" name="email" required="required" value="" placeholder="E-mail">
                    Gender: <label class="radio-inline" style="padding-left: 30px;"><input type="radio" class="radio" name="gender" value=0 checked>Male</label>
                    <label class="radio-inline"><input type="radio" class="radio" value=1 name="gender">Female</label>
                    Birthday: <input type="date" class="date" name="birthday" required="required" value="" placeholder="Name">
                    Profile Text:<textarea style="height: 100px;" name="profile"></textarea>
                    <br />
                    <br />
                    <button type="submit" class="btn btn-primary login">Register</button>
                    <p class="forget"><a href="./login.php" class="underline-a">Login</a></p>
                </form>
            </section>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>