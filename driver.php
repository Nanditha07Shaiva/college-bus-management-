<?php
// driver.php
require_once __DIR__ . '/includes/auth.php';
require_role('driver');
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';

$driver_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT * FROM buses WHERE driver_id = ?");
$stmt->bind_param("i",$driver_id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();
?>
<section class="card glass">
  <h2>Driver Dashboard</h2>
  <?php if (!$bus): ?>
    <p>No bus assigned to your account yet.</p>
  <?php else: 
    $bus_id = (int)$bus['id'];
    $stmt2 = $conn->prepare("SELECT COUNT(*) AS c FROM bookings WHERE bus_id = ?");
    $stmt2->bind_param("i",$bus_id);
    $stmt2->execute();
    $cnt = $stmt2->get_result()->fetch_assoc()['c'] ?? 0;

    $stmt3 = $conn->prepare("SELECT u.name, u.username, bk.seat_no FROM bookings bk JOIN users u ON u.id = bk.user_id WHERE bk.bus_id = ? ORDER BY bk.seat_no ASC");
    $stmt3->bind_param("i",$bus_id);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
  ?>
    <div class="bus-summary">
      <p><strong>Bus:</strong> <?= htmlspecialchars($bus['bus_no']) ?> — <?= htmlspecialchars($bus['route']) ?></p>
      <p><strong>Departure:</strong> <?= htmlspecialchars($bus['departure_time']) ?></p>
      <p><strong>Total seats:</strong> <?= (int)$bus['total_seats'] ?> &nbsp; | &nbsp; <strong>Booked:</strong> <?= (int)$cnt ?></p>
    </div>

    <table class="table">
      <thead>
        <tr><th>Seat</th><th>Student Name</th><th>Username</th></tr>
      </thead>
      <tbody>
        <?php while ($r = $res3->fetch_assoc()): ?>
          <tr>
            <td><?= (int)$r['seat_no'] ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['username']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
