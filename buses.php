<?php
// buses.php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';

$sql = "
SELECT b.*, (b.total_seats - IFNULL(x.booked,0)) AS available_seats, u.name AS driver_name
FROM buses b
LEFT JOIN (
  SELECT bus_id, COUNT(*) AS booked FROM bookings GROUP BY bus_id
) x ON x.bus_id = b.id
LEFT JOIN users u ON u.id = b.driver_id
ORDER BY b.id
";
$res = $conn->query($sql);
?>
<section class="card glass">
  <h2>Available Buses</h2>
  <?php if (!empty($_GET['msg'])): ?><div class="alert"><?= htmlspecialchars($_GET['msg']) ?></div><?php endif; ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Bus No</th>
          <th>Route</th>
          <th>Departure</th>
          <th>Total</th>
          <th>Available</th>
          <th>Driver</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $res->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['bus_no']) ?></td>
            <td><?= htmlspecialchars($row['route']) ?></td>
            <td><?= htmlspecialchars($row['departure_time']) ?></td>
            <td><?= (int)$row['total_seats'] ?></td>
            <td><?= (int)$row['available_seats'] ?></td>
            <td><?= htmlspecialchars($row['driver_name'] ?? '-') ?></td>
            <td><a class="btn small" href="bus_details.php?id=<?= (int)$row['id'] ?>">View</a></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
