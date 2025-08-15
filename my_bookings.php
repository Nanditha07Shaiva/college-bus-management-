<?php
// my_bookings.php
require_once __DIR__ . '/includes/auth.php';
require_role('student');
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';

$user_id = $_SESSION['user']['id'];

$sql = "SELECT bk.id, bk.seat_no, bk.created_at, b.bus_no, b.route, b.departure_time
        FROM bookings bk
        JOIN buses b ON b.id = bk.bus_id
        WHERE bk.user_id = ?
        ORDER BY bk.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>
<section class="card glass">
  <h2>My Bookings</h2>
  <?php if (!empty($_GET['msg'])): ?><div class="alert"><?= htmlspecialchars($_GET['msg']) ?></div><?php endif; ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Bus</th>
          <th>Route</th>
          <th>Departure</th>
          <th>Seat</th>
          <th>Booked At</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = $res->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($r['bus_no']) ?></td>
            <td><?= htmlspecialchars($r['route']) ?></td>
            <td><?= htmlspecialchars($r['departure_time']) ?></td>
            <td><?= (int)$r['seat_no'] ?></td>
            <td><?= htmlspecialchars($r['created_at']) ?></td>
            <td><a class="btn small danger" href="cancel_booking.php?id=<?= (int)$r['id'] ?>">Cancel</a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
