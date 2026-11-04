<?php
$pageTitle = $pageTitle ?? 'CampusLoop';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | CampusLoop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
    <div class="nav-wrap">
        <a class="brand" href="index.php"><span class="brand-mark">C</span> CampusLoop</a>
        <nav class="main-nav" aria-label="Main navigation">
            <a href="index.php">Home</a>
            <a href="events.php">Browse Events</a>
            <?php if (is_logged_in()): ?>
                <a href="my_events.php">My Events</a>
                <?php if (is_admin()): ?><a href="admin.php">Admin</a><?php endif; ?>
                <span class="nav-user">Hi, <?= e($_SESSION['name'] ?? 'Student') ?></span>
                <a class="nav-login" href="logout.php">Log out</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a class="nav-cta" href="register.php">Join CampusLoop</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
<?php if (!empty($_SESSION['flash'])): $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>
