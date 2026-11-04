<?php
require_once 'config.php';
$pageTitle = 'Browse Events';
$search = trim((string)($_GET['q'] ?? ''));
$sql = "SELECT e.*, u.name AS creator_name FROM events e LEFT JOIN users u ON e.created_by = u.user_id WHERE 1=1";
$params = [];
if ($search !== '') { $sql .= " AND (e.title LIKE ? OR e.description LIKE ? OR e.venue LIKE ?)"; $term = '%' . $search . '%'; $params = [$term, $term, $term]; }
$sql .= " ORDER BY e.event_date ASC";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $events = $stmt->fetchAll();
require 'header.php';
?>
<section class="page-intro"><span class="eyebrow">FIND YOUR NEXT EXPERIENCE</span><h1>Browse campus events</h1><p>Discover activities, learn something new, and connect with your campus community.</p></section>
<form class="search-bar" method="get" action="events.php"><label class="sr-only" for="q">Search events</label><input id="q" type="search" name="q" value="<?= e($search) ?>" placeholder="Search by event, description, or venue..."><button class="btn btn-primary" type="submit">Search</button><?php if ($search !== ''): ?><a class="btn btn-outline" href="events.php">Clear</a><?php endif; ?></form>
<?php if (!$events): ?><div class="empty-state"><h3>No events found</h3><p>Try another search term or check back later.</p></div><?php else: ?><div class="event-grid events-page-grid">
<?php foreach ($events as $event): ?><article class="event-card"><div class="event-date"><span><?= e(date('M', strtotime($event['event_date']))) ?></span><strong><?= e(date('d', strtotime($event['event_date']))) ?></strong></div><div class="event-card-body"><span class="event-tag"><?= strtotime($event['event_date']) < time() ? 'Past event' : 'Campus event' ?></span><h3><?= e($event['title']) ?></h3><p><?= e(mb_strimwidth($event['description'], 0, 145, '…')) ?></p><div class="event-meta"><span>⌖ <?= e($event['venue']) ?></span><span>◷ <?= e(date('M d, Y · g:i A', strtotime($event['event_date']))) ?></span></div><a class="card-link" href="event.php?id=<?= (int)$event['event_id'] ?>">View details →</a></div></article><?php endforeach; ?>
</div><?php endif; ?>
<?php require 'footer.php'; ?>
