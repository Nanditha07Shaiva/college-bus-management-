<?php
// cancel_booking.php
require_once __DIR__ . '/includes/auth.php';
require_role('student');
require_once __DIR__ . '/includes/config.php';

$id = (int)($_GET['id'] ?? 0);
$user_id = $_SESSION['user']['id'];

if ($id <= 0) {
    header("Location: my_bookings.php?msg=Invalid+booking");
    exit;
}

$stmt = $conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii",$id,$user_id);
$stmt->execute();

header("Location: my_bookings.php?msg=Booking+cancelled");
exit;
