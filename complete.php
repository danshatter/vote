<?php
require_once 'core/init.php';
background();

$title = 'Thank You For Voting';
logged_in_only(SITE_ROOT.'/');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        User::instance()->logout();
    }
}

complete();

include_once 'includes/header.php';
?>

<h1 class="center">Thank You For Voting</h1>
<p class="center success">You have performed your civic responsiblity</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="center">
    <button type="submit" name="logout">Click Here to Logout</button>
</form>

<?php if ($user->role === 2): ?>
    <p class="center">Hey <?php echo ready($user->first_name); ?>, Congrats on casting your vote. Now get back to work. <a href="<?php echo SITE_ROOT; ?>/admin/">Return to Admin area</a></p>
<?php endif; ?>

<?php include_once 'includes/footer.php'; ?>