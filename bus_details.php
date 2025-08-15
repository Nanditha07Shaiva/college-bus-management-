<?php
// bus_details.php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';

$bus_id = (int)($_GET['id'] ?? 0);
if ($bus_id <= 0) { header('Location: buses.php?msg=Invalid+bus'); exit; }

$stmt = $conn->prepare("SELECT b.*, u.name AS driver_name FROM buses b LEFT JOIN users u ON u.id=b.driver_id WHERE b.id=?");
$stmt->bind_param("i",$bus_id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();
if (!$bus) { header('Location: buses.php?msg=Bus+not+found'); exit; }

$stmt2 = $conn->prepare("SELECT seat_no FROM bookings WHERE bus_id=?");
$stmt2->bind_param("i",$bus_id);
$stmt2->execute();
$r2 = $stmt2->get_result();
$booked = [];
while ($row = $r2->fetch_assoc()) { $booked[(int)$row['seat_no']] = true; }

$total = (int)$bus['total_seats'];
?>
<section class="card glass">
  <h2>Bus <?= htmlspecialchars($bus['bus_no']) ?> — <?= htmlspecialchars($bus['route']) ?></h2>
  <p class="muted">
    <strong>Departure:</strong> <?= htmlspecialchars($bus['departure_time']) ?> &nbsp;|&nbsp;
    <strong>Driver:</strong> <?= htmlspecialchars($bus['driver_name'] ?? '-') ?> &nbsp;|&nbsp;
    <strong>Total seats:</strong> <?= $total ?>
  </p>

  <?php if (!empty($_GET['msg'])): ?><div class="alert"><?= htmlspecialchars($_GET['msg']) ?></div><?php endif; ?>

  <?php if ($_SESSION['user']['role'] === 'student'): ?>
    <form method="post" action="book_seat.php" class="seat-form">
      <input type="hidden" name="bus_id" value="<?= $bus_id ?>">
      <div class="seat-grid">
        <?php for ($i=1; $i<= $total; $i++): $isBooked = isset($booked[$i]); ?>
          <label class="seat <?= $isBooked ? 'booked' : '' ?>">
            <input type="radio" name="seat_no" value="<?= $i ?>" <?= $isBooked ? 'disabled' : '' ?>>
            <span class="seat-num"><?= $i ?></span>
            <?php if ($isBooked): ?><span class="seat-tag">Booked</span><?php endif; ?>
          </label>
        <?php endfor; ?>
      </div>
      <button type="submit" class="btn primary">Book Selected Seat</button>
    </form>
  <?php else: ?>
    <p class="muted">Only students can book seats.</p>
    <div class="seat-grid-preview">
      <?php for ($i=1; $i<= $total; $i++): $isBooked = isset($booked[$i]); ?>
        <div class="seat small <?= $isBooked ? 'booked' : '' ?>">
          <span class="seat-num"><?= $i ?></span>
        </div>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
