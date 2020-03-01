<?php
require_once '../core/init.php';

logged_in_only(SITE_ROOT.'/');
fake_403();

$title = 'Remove Party';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_party'])) {
        Admin::instance()->delete_party();
    }
}

$parties = DB::instance()->get_table('parties');

include_once '../includes/header.php';
?>

<h1>Remove Party</h1>

<?php
if (isset($errors['database'])) {
    foreach ($errors['database'] as $error) {
        echo '<p class="error">'.$error.'</p><br/>';
    }
}

if (isset($errors['id'])) {
    echo '<p class="error">'.$errors['id'].'</p>';
}

if (isset($_SESSION['delete_party_complete'])) {
    echo Session::instance()->flash('Party deleted successfully', 'success', 'delete_party_complete');
}
?>

<p>This will delete a party. Deleting a party will also delete all the candidates in each election positions. Proceed with caution</p>

<?php if (count($parties) === 0): ?>
    <h3>There are currently no parties registered</h3>

<?php else: ?>
    <table>
        <tr>
            <th>Party</th>
            <th>&nbsp;</th>
        </tr>
        <?php foreach($parties as $party): ?>
        <tr>
            <td><?php echo Voting::instance()->get_party($party->id) ?></td>
            <td><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo $party->id; ?>">
                    <button type="submit" name="delete_party">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

<br/><br/>

<a href="<?php echo SITE_ROOT; ?>/admin/index.php">&larr; Go back to Admin Page</a><br/><br/>

<?php include_once '../includes/footer.php'; ?>