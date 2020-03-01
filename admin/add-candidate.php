<?php
require_once '../core/init.php';

logged_in_only(SITE_ROOT.'/');
fake_403();

$title = 'Add Candidate';
$parties = DB::instance()->get_table('parties');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_candidate'])) {
        Admin::instance()->add_candidate();
    }
}



include_once '../includes/header.php';
?>

<h1>Add Candidate</h1>

<?php
if (isset($errors['database'])) {
    echo '<p class="error">'.$errors['database'].'</p>';
}

if (isset($_SESSION['add_candidate_complete'])) {
    echo Session::instance()->flash('Candidate added successfully', 'success', 'add_candidate_complete');
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <label for="name">Name: <input type="text" name="name" id="name" value="<?php echo (isset($_POST['name'])) ? $_POST['name'] : ''; ?>" autocomplete="off"><?php echo (isset($errors['name'])) ? '<small class="error">'.$errors['name'].'</small>' : ''; ?></label><br/><br/>

    <label for="party">Party: 
    <select name="party">
        <option value="">Choose the Candidate's Party</option>
    <?php foreach($parties as $party): ?>
        <option value="<?php echo $party->id; ?>"<?php echo (isset($_POST['party']) && (int)$_POST['party'] === $party->id) ? ' selected' : ''; ?>><?php echo $party->name; ?></option>
    <?php endforeach; ?>
    </select></label><?php echo (isset($errors['party'])) ? '<small class="error">'.$errors['party'].'</small>' : ''; ?><br/><br/>

    <label for="party">Position: 
    <select name="position">
        <option value="">Choose the Candidate's Position</option>

        <option value="presidents"<?php echo (isset($_POST['position']) && $_POST['position'] === 'presidents') ? ' selected' : ''; ?>>Presidency</option>

        <option value="governors"<?php echo (isset($_POST['position']) && $_POST['position'] === 'governors') ? ' selected' : ''; ?>>Governorship</option>

        <option value="house_of_representatives"<?php echo (isset($_POST['position']) && $_POST['position'] === 'house_of_representatives') ? ' selected' : ''; ?>>House of Representatives</option>

        <option value="senators"<?php echo (isset($_POST['position']) && $_POST['position'] === 'senators') ? ' selected' : ''; ?>>Senate</option>

        <option value="state_assemblies"<?php echo (isset($_POST['position']) && $_POST['position'] === 'state_assemblies') ? ' selected' : ''; ?>>State House of Assembly</option>

    </select></label><?php echo (isset($errors['position'])) ? '<small class="error">'.$errors['position'].'</small>' : ''; ?><br/><br/>

    <button type="submit" name="add_candidate">Add Candidate</button>
</form>

<br/><br/>
<a href="<?php echo SITE_ROOT; ?>/admin/index.php">&larr; Go back to Admin Page</a><br/><br/>

<?php include_once '../includes/footer.php'; ?>