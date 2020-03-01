<?php
require_once '../core/init.php';

logged_in_only(SITE_ROOT.'/');
fake_403();

$title = 'Remove Candidate';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['show'])) {
        $candidates = Admin::instance()->show($_POST['position']);
    }

    if (isset($_POST['delete_candidate'])) {
        Admin::instance()->delete_candidate();
    }
}

include_once '../includes/header.php';
?>

<h1>Remove Candidate</h1>

<?php
if (isset($errors['database'])) {
    echo '<p class="error">'.$errors['database'].'</p>';
}

if (isset($_SESSION['delete_candidate_complete'])) {
    if (isset($_SESSION['admin_position'])) {
        $candidates = Admin::instance()->show($_SESSION['admin_position']);
    }

    echo Session::instance()->flash('Candidate deleted successfully', 'success', 'delete_candidate_complete');
}
?>

<p>This will delete a candidate from the running for a particular position</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <label for="position">Position: 
    <select name="position" id="position">
        <option value="">Choose the Candidate Position</option>

        <option value="presidents"<?php echo (isset($_POST['position']) && $_POST['position'] === 'presidents') ? ' selected' : ''; ?>>Presidency</option>

        <option value="governors"<?php echo (isset($_POST['position']) && $_POST['position'] === 'governors') ? ' selected' : ''; ?>>Governorship</option>

        <option value="house_of_representatives"<?php echo (isset($_POST['position']) && $_POST['position'] === 'house_of_representatives') ? ' selected' : ''; ?>>House of Representatives</option>

        <option value="senators"<?php echo (isset($_POST['position']) && $_POST['position'] === 'senators') ? ' selected' : ''; ?>>Senate</option>

        <option value="state_assemblies"<?php echo (isset($_POST['position']) && $_POST['position'] === 'state_assemblies') ? ' selected' : ''; ?>>State House of Assembly</option>

    </select></label><?php echo (isset($errors['position'])) ? '<small class="error">'.$errors['position'].'</small>' : ''; ?>
    <br/><br/>

    <button type="submit" name="show">Show all candidates</button>
</form>
<br/><br/>

<?php if (isset($candidates)): ?>

    <?php if (count($candidates) === 0): ?>
        <h3>No registered candidates for this position</h3>

    <?php else: ?>
    <table>
        <tr>
            <th>Party</th>
            <th>Name of Candidate</th>
            <th>&nbsp;</th>
        </tr>
        <?php foreach($candidates as $candidate): ?>
        <tr>
            <td><?php echo Voting::instance()->get_party($candidate->party_id) ?></td>
            <td><?php echo ready($candidate->name); ?></td>
            <td><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo $candidate->id; ?>">
                    <button type="submit" name="delete_candidate">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>

    <?php endif; ?>
    </table>
<br/><br/>

<?php endif; ?>

<a href="<?php echo SITE_ROOT; ?>/admin/index.php">&larr; Go back to Admin Page</a><br/><br/>

<?php include_once '../includes/footer.php'; ?>