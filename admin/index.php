<?php
require_once '../core/init.php';

logged_in_only(SITE_ROOT.'/');
fake_403();

$title = 'Admin Page';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        User::instance()->logout();
    }
}

include_once '../includes/header.php';
?>

<h1>Welcome to the Admin Page, <?php echo ready($user->first_name); ?></h1>

<?php
if (isset($_SESSION['admin_success'])) {
    echo Session::instance()->flash('You have successfully logged in', 'success', 'admin_success');
}
?>

<p>What would you like to do today?</p>

<a href="<?php echo SITE_ROOT; ?>/admin/add-party.php">Add a Party?</a><br/><br/>
<a href="<?php echo SITE_ROOT; ?>/admin/remove-party.php">Remove a Party?</a><br/><br/>
<a href="<?php echo SITE_ROOT; ?>/admin/add-candidate.php">Add a Candidate?</a><br/><br/>
<a href="<?php echo SITE_ROOT; ?>/admin/remove-candidate.php">Remove a Candidate?</a><br/><br/>
<a href="<?php echo SITE_ROOT; ?>/admin/add-admin-user.php">Add a new Admin User</a><br/><br/>

<?php if ($user->voted === 0): ?>
    <p>Hey <?php echo ready($user->first_name); ?>, Would you like to vote as well? <a href="<?php echo SITE_ROOT; ?>/vote.php">Click Here</a></p>
<?php endif; ?>
<br/>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <button type="submit" name="logout">Logout</button>
</form>

<?php include_once '../includes/footer.php'; ?>