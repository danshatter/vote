<?php
require_once 'core/init.php';
background();

logged_in_only(SITE_ROOT.'/');
has_voted();

$voting = Voting::instance()->initialize();
$title = 'Voting Page';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        User::instance()->logout();
    }
}

include_once 'includes/header.php';
?>

<h1 class="center">Voting Page</h1>

<?php
if (isset($_SESSION['success'])) {
    echo Session::instance()->flash('You are logged in', 'success', 'success');
}
?>

<h1>User Details</h1>

<img src="<?php echo Image::instance()->display_profile(); ?>" class="profile_image" alt="None" /><br/><br/>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <button type="submit" name="logout">Logout</button>
</form>

<?php
if (isset($_SESSION['not_complete'])) {
    echo Session::instance()->flash('You need to vote to view this page', 'error', 'not_complete');
}

if (isset($_SESSION['confirm_fail'])) {
    echo Session::instance()->flash('You must choose your favourite candidate to view that page', 'error', 'confirm_fail');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['vote'])) {
        if (Voting::instance()->user_vote()) {
            Redirect::to(SITE_ROOT.'/confirm.php');
        }
        
    }
}
?>

<p class="bold">NAME: <?php echo strtoupper($user->first_name.' '.$user->last_name.' '.$user->middle_name); ?></p>
<p class="bold">DATE OF BIRTH: <?php echo $user->date_of_birth; ?></p>
<p class="bold">E-MAIL: <?php echo strtolower($user->email); ?></p>
<p class="bold">SEX: <?php echo strtoupper($user->sex); ?></p>

<?php $_SESSION['captcha'] = captcha(); ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <label for="presidents" class="bold">President<br>
    <select name="presidents" id="presidents">
        <option value="">Please choose your President</option>

    <?php foreach ($voting['presidents'] as $president): ?>
        <option value="<?php echo $president->id; ?>"<?php echo (isset($_POST['presidents']) && (int)$_POST['presidents'] === $president->id) ? ' selected' : ''; ?>>(<?php echo Voting::instance()->get_party($president->party_id); ?>) <?php echo ready($president->name); ?></option>
    <?php endforeach; ?>

    </select></label><br><br/>

    <label for="governors" class="bold">Governor<br>
    <select name="governors" id="governors">
        <option value="">Please choose your Governor</option>
        
    <?php foreach ($voting['governors'] as $governor): ?>
        <option value="<?php echo $governor->id; ?>"<?php echo (isset($_POST['governors']) && (int)$_POST['governors'] === $governor->id) ? ' selected' : ''; ?>>(<?php echo Voting::instance()->get_party($governor->party_id); ?>) <?php echo ready($governor->name); ?></option>
    <?php endforeach; ?>

    </select></label><br><br/>

    <label for="house_of_representatives" class="bold">House of Representatives Leader<br>
    <select name="house_of_representatives" id="house_of_representatives">
        <option value="">Please choose your House of Representatives Leader</option>

    <?php foreach ($voting['house_of_representatives'] as $house_of_representative): ?>
        <option value="<?php echo $house_of_representative->id; ?>"<?php echo (isset($_POST['house_of_representatives']) && (int)$_POST['house_of_representatives'] === $house_of_representative->id) ? ' selected' : ''; ?>>(<?php echo Voting::instance()->get_party($house_of_representative->party_id); ?>) <?php echo ready($house_of_representative->name); ?></option>
    <?php endforeach; ?>

    </select></label><br><br/>
    

    <label for="senators" class="bold">Senate President<br>
    <select name="senators" id="senators">
        <option value="">Please choose your Senate President</option>

    <?php foreach ($voting['senators'] as $senator): ?>
        <option value="<?php echo $senator->id; ?>"<?php echo (isset($_POST['senators']) && (int)$_POST['senators'] === $senator->id) ? ' selected' : ''; ?>>(<?php echo Voting::instance()->get_party($senator->party_id); ?>) <?php echo ready($senator->name); ?></option>
    <?php endforeach; ?>

    </select></label><br><br/>

    <label for="state_assemblies" class="bold">State House of Assembly Leader<br>
    <select name="state_assemblies" id="state_assemblies">
        <option value="">Please choose your State House of Assembly Leader</option>

    <?php foreach ($voting['state_assemblies'] as $state_assembly): ?>
        <option value="<?php echo $state_assembly->id; ?>"<?php echo (isset($_POST['state_assemblies']) && (int)$_POST['state_assemblies'] === $state_assembly->id) ? ' selected' : ''; ?>>(<?php echo Voting::instance()->get_party($state_assembly->party_id); ?>) <?php echo ready($state_assembly->name); ?></option>
    <?php endforeach; ?>

    </select></label><br><br/>

    <p>Are You Human? </p>
    <img src="<?php echo SITE_ROOT; ?>/captcha.php" alt="None" />
    <br/><br/>
    <label for="captcha">Enter Image Text<br/><input type="text" name="captcha" id="captcha" autocomplete="off"></label>
    <?php echo (isset($errors['captcha'])) ? '<i class="error">'.$errors['captcha'].'</i>' : ''; ?><br><br/>

    <button type="submit" name="vote">Cast Vote</button>

</form>

<?php include_once 'includes/footer.php'; ?>