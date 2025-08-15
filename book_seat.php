<?php
// book_seat.php
require_once __DIR__ . '/includes/auth.php';
require_role('student');
require_once __DIR__ . '/includes/config.php';

$user_id = $_SESSION['user']['id'];
$bus_id  = (int)($_POST['bus_id'] ?? 0);
$seat_no = (int)($_POST['seat_no'] ?? 0);

if ($bus_id <= 0 || $seat_no <= 0) {
    header("Location: buses.php?msg=Invalid+booking+data");
    exit;
}

// validate bus & seat range
$stmt = $conn->prepare("SELECT total_seats FROM buses WHERE id = ?");
$stmt->bind_param("i",$bus_id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();
if (!$bus || $seat_no > (int)$bus['total_seats']) {
    header("Location: buses.php?msg=Invalid+bus+or+seat");
    exit;
}

// check seat already booked
$stmt2 = $conn->prepare("SELECT id FROM bookings WHERE bus_id = ? AND seat_no = ?");
$stmt2->bind_param("ii",$bus_id,$seat_no);
$stmt2->execute();
if ($stmt2->get_result()->num_rows > 0) {
    header("Location: bus_details.php?id=$bus_id&msg=Seat+already+booked");
    exit;
}

// optional: prevent multiple seats per student on same bus
$stmt3 = $conn->prepare("SELECT id FROM bookings WHERE bus_id = ? AND user_id = ?");
$stmt3->bind_param("ii",$bus_id,$user_id);
$stmt3->execute();
if ($stmt3->get_result()->num_rows > 0) {
    header("Location: bus_details.php?id=$bus_id&msg=You+already+booked+this+bus");
    exit;
}

// insert booking
$stmt4 = $conn->prepare("INSERT INTO bookings (user_id, bus_id, seat_no) VALUES (?,?,?)");
$stmt4->bind_param("iii",$user_id,$bus_id,$seat_no);
$stmt4->execute();

header("Location: my_bookings.php?msg=Seat+booked+successfully");
exit;
