<?php
// home.php
require_once __DIR__ . '/includes/auth.php';
require_login();
include __DIR__ . '/includes/header.php';
$u = current_user();
?>
<section class="card glass">
  <h2>Welcome, <?= htmlspecialchars($u['name']) ?></h2>
  <p class="muted">Role: <strong><?= htmlspecialchars(ucfirst($u['role'])) ?></strong></p>

  <div class="grid">
    <a class="tile glass" href="buses.php">
      <h3>Available Buses</h3>
      <p>View routes, timings and seat availability</p>
    </a>

    <?php if ($u['role'] === 'student'): ?>
    <a class="tile glass" href="my_bookings.php">
      <h3>My Bookings</h3>
      <p>View or cancel your seats</p>
    </a>
    <?php endif; ?>

    <?php if ($u['role'] === 'driver'): ?>
    <a class="tile glass" href="driver.php">
      <h3>Driver Dashboard</h3>
      <p>Passenger list & count</p>
    </a>
    <?php endif; ?>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
