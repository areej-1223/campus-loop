<?php
require_once 'config.php';
$pageTitle = 'Home';
$stmt = $pdo->query("SELECT e.*, u.name AS creator_name FROM events e LEFT JOIN users u ON e.created_by = u.user_id WHERE e.event_date >= NOW() ORDER BY e.event_date ASC LIMIT 3");
$upcoming = $stmt->fetchAll();
require 'header.php';
?>
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">STUDENT EVENT HUB</span>
        <h1>Make campus life <span>count.</span></h1>
        <p>Discover workshops, competitions, seminars, and student activities happening around your campus—all in one place.</p>
        <div class="hero-actions"><a class="btn btn-primary" href="events.php">Explore events</a><a class="btn btn-outline" href="register.php">Create an account</a></div>
        <div class="hero-note"><span class="dot"></span> Find your next campus experience</div>
    </div>
    <div class="hero-art" aria-hidden="true"><div class="art-circle circle-one"></div><div class="art-circle circle-two"></div><div class="art-card card-top"><span>✦</span><div><b>Discover</b><small>New experiences</small></div></div><div class="art-card card-bottom"><span>✓</span><div><b>Get involved</b><small>Meet your community</small></div></div><div class="art-center">CL</div></div>
</section>
<section class="section-block">
    <div class="section-heading"><div><span class="eyebrow">WHAT'S HAPPENING</span><h2>Upcoming events</h2><p>Explore what's coming up at your campus.</p></div><a class="text-link" href="events.php">View all events →</a></div>
    <?php if (!$upcoming): ?>
        <div class="empty-state"><h3>No upcoming events yet</h3><p>Check back soon, or sign in as an administrator to add the first event.</p></div>
    <?php else: ?>
        <div class="event-grid">
        <?php foreach ($upcoming as $event): ?>
            <article class="event-card"><div class="event-date"><span><?= e(date('M', strtotime($event['event_date']))) ?></span><strong><?= e(date('d', strtotime($event['event_date']))) ?></strong></div><div class="event-card-body"><span class="event-tag">Campus event</span><h3><?= e($event['title']) ?></h3><p><?= e(mb_strimwidth($event['description'], 0, 115, '…')) ?></p><div class="event-meta"><span>⌖ <?= e($event['venue']) ?></span><span>◷ <?= e(date('g:i A', strtotime($event['event_date']))) ?></span></div><a class="card-link" href="event.php?id=<?= (int)$event['event_id'] ?>">View details →</a></div></article>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<section class="how-section"><div class="section-heading centered"><div><span class="eyebrow">SIMPLE BY DESIGN</span><h2>Your next event is three steps away</h2></div></div><div class="steps-grid"><div class="step"><span>01</span><h3>Explore</h3><p>Browse upcoming campus events and find something you love.</p></div><div class="step"><span>02</span><h3>Register</h3><p>Sign in and reserve your place in just a few clicks.</p></div><div class="step"><span>03</span><h3>Show up</h3><p>Keep track of your registrations from your personal page.</p></div></div></section>
<?php require 'footer.php'; ?>
