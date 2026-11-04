<?php
require_once 'config.php';
if (is_logged_in()) redirect('index.php');
$pageTitle='Login'; $errors=[]; $email='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf(); $email=strtolower(trim((string)($_POST['email']??''))); $password=(string)($_POST['password']??'');
    if (!filter_var($email,FILTER_VALIDATE_EMAIL) || $password==='') $errors[]='Enter your email address and password.';
    else { $stmt=$pdo->prepare('SELECT user_id,name,email,password,role FROM users WHERE email=?'); $stmt->execute([$email]); $user=$stmt->fetch(); if (!$user || !password_verify($password,$user['password'])) $errors[]='Email or password is incorrect.'; else { session_regenerate_id(true); $_SESSION['user_id']=(int)$user['user_id']; $_SESSION['name']=$user['name']; $_SESSION['email']=$user['email']; $_SESSION['role']=$user['role']; $_SESSION['csrf_token']=bin2hex(random_bytes(32)); set_flash('Welcome back, '.$user['name'].'!'); redirect('index.php'); } }
}
require 'header.php';
?>
<div class="auth-wrap"><div class="auth-card"><span class="eyebrow">WELCOME BACK</span><h1>Log in to CampusLoop</h1><p class="muted">Access your registrations and discover more events.</p><?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?><form method="post" class="form-stack"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><label for="email">Email address</label><input id="email" type="email" name="email" value="<?= e($email) ?>" required autocomplete="email"><label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password"><button class="btn btn-primary btn-full" type="submit">Log in</button></form><p class="auth-switch">Don't have an account? <a href="register.php">Create one</a></p></div></div>
<?php require 'footer.php'; ?>
