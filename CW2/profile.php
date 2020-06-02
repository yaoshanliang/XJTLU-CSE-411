<?php
include_once('header.php');
include_once('function.php');
?>

<?php
$info = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $profile = $_POST['profile'];
    $email = $_POST['email'];

    $result = $dbHelper->updateProfile($_SESSION['id'], $username, $name, $email, $gender, $birthday, $profile);
    $info = $result['message'];
    if ($result['code'] == 0) {
        $_SESSION["username"] = $username;
        $_SESSION["name"] = $name;
        $_SESSION["gender"] = $gender;
        $_SESSION["birthday"] = $birthday;
        $_SESSION["profile"] = $profile;
        $_SESSION["email"] = $email;
    }
}
?>

<div id="page-wrapper">
    <div class="center-page">
        <div class="login-page">
            <section class="login-block">
                <p class="header">Profile</p>
                <p id="loginInfo"><?php echo $info; ?> </p>
                <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="text" name="username" required="required" value="<?php echo $_SESSION['username']; ?>" placeholder="Username">
                    <input type="text" name="name" required="required" value="<?php echo $_SESSION['name']; ?>" placeholder="Name">
                    <input type="email" name="email" required="required" value="<?php echo $_SESSION['email']; ?>" placeholder="E-mail">
                    Gender: <label class="radio-inline" style="padding-left: 30px;"><input type="radio" class="radio" name="gender" value=0 <?php if ($_SESSION['gender'] == 0) echo 'checked'; ?>>Male</label>
                    <label class="radio-inline"><input type="radio" class="radio" value=1 name="gender" <?php if ($_SESSION['gender'] == 1) echo 'checked'; ?>>Female</label>
                    Birthday: <input type="date" class="date" name="birthday" required="required" value="<?php echo $_SESSION['birthday']; ?>" placeholder="Name">
                    Profile Text:<textarea style="height: 100px;" name="profile" required="required"><?php echo $_SESSION['profile']; ?></textarea>

                    <br />
                    <br />
                    <button type="submit" class="btn btn-primary login">Update Profile</button>
                    <p class="forget"><a href="./login.php" class="underline-a">Login</a></p>
                </form>
            </section>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>