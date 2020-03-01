<?php
require_once '../core/init.php';

logged_in_only(SITE_ROOT.'/');
fake_403();

$title = 'Register Admin';

include_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register_admin'])) {
        if (Admin::instance()->create_user()) {
            Redirect::to($_SERVER['PHP_SELF']);
        }
    }
}
?>

<div>
    <h1>Register Admin</h1>

<?php
    if (isset($_SESSION['register_admin_success'])) {
        echo Session::instance()->flash('Registration successful.', 'success', 'register_admin_success');
    }
?>

    <p>We strive to make the world a better place and we would love to get people on it as well. Register with us and become a part of our team</p>
    
    <?php if (isset($errors['database'])): ?>
        <h4 class="error"><?php echo $errors['database']; ?></h4>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <label for="first_name">First Name<span class="error">*</span><br><input type="text" name="first_name" id="first_name" value="<?php echo (isset($_POST['first_name'])) ? $_POST['first_name'] : ''; ?>"></label><br>
        <?php echo (isset($errors['first_name'])) ? '<small class="error">'.$errors['first_name'].'</small>' : ''; ?><br>

        <label for="middle_name">Middle Name<br><input type="text" name="middle_name" id="middle_name" value="<?php echo (isset($_POST['middle_name'])) ? $_POST['middle_name'] : ''; ?>"></label><br>
        <?php echo (isset($errors['middle_name'])) ? '<small class="error">'.$errors['middle_name'].'</small>' : ''; ?><br>

        <label for="last_name">Last Name<span class="error">*</span><br><input type="text" name="last_name" id="last_name" value="<?php echo (isset($_POST['last_name'])) ? $_POST['last_name'] : ''; ?>"></label><br>
        <?php echo (isset($errors['last_name'])) ? '<small class="error">'.$errors['last_name'].'</small>' : ''; ?><br>

        <label for="email">Email<span class="error">*</span><br><input type="email" name="email" id="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ''; ?>"></label><br>
        <?php echo (isset($errors['email'])) ? '<small class="error">'.$errors['email'].'</small>' : ''; ?><br>

        <label for="date_of_birth">Date of Birth<span class="error">*</span><br><input type="date" name="date_of_birth" id="date_of_birth"  value="<?php echo (isset($_POST['date_of_birth'])) ? $_POST['date_of_birth'] : ''; ?>"></label><br>
        <?php echo (isset($errors['date_of_birth'])) ? '<small class="error">'.$errors['date_of_birth'].'</small>' : ''; ?><br>

        <label for="sex">Sex<span class="error">*</span><br><select name="sex" id="sex">
            <option value="">Please select your gender</option>
            <option value="male"<?php echo (isset($_POST['sex']) && $_POST['sex'] === 'male') ? ' selected' : ''; ?>>Male</option>
            <option value="female"<?php echo (isset($_POST['sex']) && $_POST['sex'] === 'female') ? ' selected' : ''; ?>>Female</option>
        </select></label><br>
        <?php echo (isset($errors['sex'])) ? '<small class="error">'.$errors['sex'].'</small>' : ''; ?><br>

        <label for="profile">Profile Image<br><input type="file" name="profile" id="profile"></label><br>
        <?php echo (isset($errors['profile'])) ? '<small class="error">'.$errors['profile'].'</small>' : ''; ?><br>

        <label for="password">Password<span class="error">*</span><br><input type="password" name="password" id="password"></label><br>

        <?php if (isset($errors['password'])): ?>
            <?php if (is_array($errors['password'])): ?>
                <?php foreach ($errors['password'] as $error): ?>
                    <small class="error"><?php echo $error; ?></small><br/>
                <?php endforeach; ?>
            <?php else: ?>
                <small class="error"><?php echo $errors['password']; ?></small>
            <?php endif; ?>
        <?php endif; ?>
        <br/>

        <label for="password_again">Password Again<span class="error">*</span><br><input type="password" name="password_again" id="password_again"></label><br>
        <?php echo (isset($errors['password_again'])) ? '<small class="error">'.$errors['password_again'].'</small>' : ''; ?><br>
        <br/>
        
        <button type="submit" name="register_admin">Register</button>
    </form>
</div>
<br/><br/>
<a href="<?php echo SITE_ROOT; ?>/admin/index.php">&larr; Go back to Admin Page</a><br/><br/>

<?php include_once '../includes/footer.php'; ?>