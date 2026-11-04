<?php
require_once 'config.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(404); exit('Event not found.'); }
$stmt = $pdo->prepare("SELECT e.*, u.name AS creator_name, (SELECT COUNT(*) FROM registrations r WHERE r.event_id=e.event_id) AS registered_count FROM events e LEFT JOIN users u ON e.created_by=u.user_id WHERE e.event_id=?");
$stmt->execute([$id]); $event = $stmt->fetch();
if (!$event) { http_response_code(404); exit('Event not found.'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login(); verify_csrf();
    if (strtotime($event['event_date']) < time()) $errors[] = 'Registration is closed because this event has already started.';
    if ((int)$event['registered_count'] >= (int)$event['capacity']) $errors[] = 'Sorry, this event is full.';
    $dup = $pdo->prepare('SELECT registration_id FROM registrations WHERE user_id=? AND event_id=?'); $dup->execute([$_SESSION['user_id'], $id]);
    if ($dup->fetch()) $errors[] = 'You have already registered for this event.';
    if (!$errors) {
        try {
            $pdo->beginTransaction();
            $lock = $pdo->prepare('SELECT capacity, event_date FROM events WHERE event_id=? FOR UPDATE'); $lock->execute([$id]); $current = $lock->fetch();
            $count = $pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id=?'); $count->execute([$id]); $registered = (int)$count->fetchColumn();
            if (!$current || strtotime($current['event_date']) < time() || $registered >= (int)$current['capacity']) {
                $pdo->rollBack(); $errors[] = 'Registration is no longer available for this event.';
            } else {
                $insert = $pdo->prepare('INSERT INTO registrations (user_id,event_id,registered_at) VALUES (?,?,NOW())');
                $insert->execute([$_SESSION['user_id'], $id]); $pdo->commit(); set_flash('You are registered for this event!'); redirect('my_events.php');
            }
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            if ($e->getCode() === '23000') $errors[] = 'You have already registered for this event.'; else $errors[] = 'Could not complete registration. Please try again.';
        }
    }
}
$pageTitle = $event['title']; require 'header.php';
?>
<div class="detail-layout"><article class="detail-main"><a class="back-link" href="events.php">← Back to events</a><div class="detail-date"><span><?= e(date('l, F j, Y', strtotime($event['event_date']))) ?></span><strong><?= e(date('g:i A', strtotime($event['event_date']))) ?></strong></div><span class="event-tag">Campus event</span><h1><?= e($event['title']) ?></h1><p class="detail-description"><?= nl2br(e($event['description'])) ?></p><div class="detail-info"><div><span class="info-icon">⌖</span><div><small>Location</small><strong><?= e($event['venue']) ?></strong></div></div><div><span class="info-icon">♧</span><div><small>Available places</small><strong><?= max(0, (int)$event['capacity'] - (int)$event['registered_count']) ?> of <?= (int)$event['capacity'] ?></strong></div></div></div></article><aside class="register-panel"><h2>Join this event</h2><p>Save your place and keep this event in your personal list.</p><?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?><?php if (strtotime($event['event_date']) < time()): ?><p class="status-message">This event has already started.</p><?php elseif ((int)$event['registered_count'] >= (int)$event['capacity']): ?><p class="status-message">This event is full.</p><?php elseif (is_logged_in()): ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><button class="btn btn-primary btn-full" type="submit">Register for event</button></form><?php else: ?><a class="btn btn-primary btn-full" href="login.php">Log in to register</a><p class="small-note">New to CampusLoop? <a href="register.php">Create an account</a>.</p><?php endif; ?></aside></div>
<?php require 'footer.php'; ?>
