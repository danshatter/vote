<?php
require_once 'core/init.php';
background();

logged_in_only(SITE_ROOT.'/');
confirm();

$title = 'Confirm Vote';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['back'])) {
        Voting::instance()->unsure();
    }

    if (isset($_POST['logout'])) {
        User::instance()->logout();
    }
}

include_once 'includes/header.php';
?>

<h1 class="center">Confirm Vote</h1>

<h1>User Details</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <button type="submit" name="logout">Logout</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_vote'])) {
        Voting::instance()->cast_vote();
    }
}
?>

<p class="bold">NAME: <?php echo strtoupper($user->first_name.' '.$user->last_name.' '.$user->middle_name); ?></p>
<p class="bold">DATE OF BIRTH: <?php echo $user->date_of_birth; ?></p>
<p class="bold">E-MAIL: <?php echo strtolower($user->email); ?></p>
<p class="bold">SEX: <?php echo strtoupper($user->sex); ?></p>
<br/><br/><br/>

<p>PRESIDENT</p><h2 class="up"><?php echo Voting::instance()->get_candidate('presidents', (int)$_SESSION['choice']['presidents'])->name ?? 'DID NOT VOTE'; ?></h2><br/><br/>

<p>GOVERNOR</p><h2 class="up"><?php echo Voting::instance()->get_candidate('governors', (int)$_SESSION['choice']['governors'])->name ?? 'DID NOT VOTE'; ?></h2><br/><br/>

<p>HOUSE OF REPRESENTATIVES LEADER</p><h2 class="up"><?php echo Voting::instance()->get_candidate('house_of_representatives', (int)$_SESSION['choice']['house_of_representatives'])->name ?? 'DID NOT VOTE'; ?></h2><br/><br/>

<p>SENATE PRESIDENT</p><h2 class="up"><?php echo Voting::instance()->get_candidate('senators', (int)$_SESSION['choice']['senators'])->name ?? 'DID NOT VOTE'; ?></h2><br/><br/>

<p>STATE HOUSE OF ASSEMBLY LEADER</p><h2 class="up"><?php echo Voting::instance()->get_candidate('state_assemblies', (int)$_SESSION['choice']['state_assemblies'])->name ?? 'DID NOT VOTE'; ?></h2><br/><br/>
<br/><br/><br/>

<p>Are you sure you want to cast this vote?</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <button type="submit" name="back">No. Go Back to Voting Page</button>
    <button type="submit" name="confirm_vote" class="left-margin">Yes. Cast My Vote</button>
</form>


<?php include_once 'includes/footer.php'; ?>