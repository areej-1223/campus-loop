<?php
require_once 'config.php';
if (is_logged_in()) redirect('index.php');
$pageTitle = 'Create Account'; $errors = []; $name = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $name = trim((string)($_POST['name'] ?? '')); $email = strtolower(trim((string)($_POST['email'] ?? ''))); $password = (string)($_POST['password'] ?? ''); $confirm = (string)($_POST['confirm_password'] ?? '');
    if ($name === '' || mb_strlen($name) > 100) $errors[] = 'Enter your name (up to 100 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must contain at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if (!$errors) {
        $check = $pdo->prepare('SELECT user_id FROM users WHERE email=?'); $check->execute([$email]);
        if ($check->fetch()) $errors[] = 'An account with this email already exists.';
        else { $hash = password_hash($password, PASSWORD_DEFAULT); $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role,created_at) VALUES (?,?,?,'student',NOW())"); $stmt->execute([$name,$email,$hash]); session_regenerate_id(true); $_SESSION['user_id']=(int)$pdo->lastInsertId(); $_SESSION['name']=$name; $_SESSION['email']=$email; $_SESSION['role']='student'; set_flash('Account created successfully. Welcome to CampusLoop!'); redirect('index.php'); }
    }
}
require 'header.php';
?>
<div class="auth-wrap"><div class="auth-card"><span class="eyebrow">JOIN THE COMMUNITY</span><h1>Create your account</h1><p class="muted">Sign up to register for campus events.</p><?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?><form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><label for="name">Full name</label><input id="name" name="name" maxlength="100" value="<?= e($name) ?>" required autocomplete="name"><label for="email">Email address</label><input id="email" type="email" name="email" maxlength="190" value="<?= e($email) ?>" required autocomplete="email"><label for="password">Password (minimum 8 characters)</label><input id="password" type="password" name="password" minlength="8" required autocomplete="new-password"><label for="confirm_password">Confirm password</label><input id="confirm_password" type="password" name="confirm_password" minlength="8" required autocomplete="new-password"><button class="btn btn-primary btn-full" type="submit">Create account</button></form><p class="auth-switch">Already registered? <a href="login.php">Log in</a></p></div></div>
<?php require 'footer.php'; ?>
