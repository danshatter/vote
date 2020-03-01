<?php
require_once 'core/init.php';
background();
display_result();

login_redirect(SITE_ROOT.'/vote.php');
$title = 'Voting System Federal Republic of Nigeria';

include_once 'includes/header.php';
?>

<h1 class="center">Welcome to the Voting Portal of the Federal Republic of Nigeria</h1>

<?php
if (isset($_SESSION['not_logged_in'])) {
    echo Session::instance()->flash('You must be logged in to view that page', 'error center', 'not_logged_in');
}

if (isset($_SESSION['register_success'])) {
    echo Session::instance()->flash('Registration successful. Now you can log in', 'success center', 'register_success');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        User::instance()->login_validate();
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="center">
    <label for="email">E-mail Address<br><input type="text" name="email" id="email" autocomplete="off" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : '' ?>"></label><br><br>

    <label for="password">Password<br><input type="password" name="password" id="password"></label><br><br>

    <button type="submit" name="login">Login</button>
</form>

<p class="center">Not registered yet? <a href="<?php echo SITE_ROOT; ?>/register.php">Register</a></p><br/>

<p class="center">Hurry up and vote for your favourite candidate. Election ends <?php echo '<span class="bold">'.display_rem_time().'</span>'; ?>. Remember, your vote counts.</p>

<?php include_once 'includes/footer.php'; ?>