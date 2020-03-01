<?php
require_once '../core/init.php';

logged_in_only(SITE_ROOT.'/');
fake_403();

$title = 'Add Party';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_party'])) {
        Admin::instance()->add_party();
    }
}

include_once '../includes/header.php';
?>

<h1>Add Party</h1>

<?php
if (isset($_SESSION['add_party_success'])) {
    echo Session::instance()->flash('Party added successfully', 'success', 'add_party_success');
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <label for="party">Add Party: <input type="text" name="party" id="party" autocomplete="off"></label><?php echo (isset($errors['add_party'])) ? '<small class="error">'.$errors['add_party'].'</small>' : ''; ?><br/><br/>
    
    <button type="submit" name="add_party">Add Party</button>
</form>
<br/><br/>

<a href="<?php echo SITE_ROOT; ?>/admin/index.php">&larr; Go back to Admin Page</a><br/><br/>

<?php include_once '../includes/footer.php'; ?>