<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$u = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>College Bus Management</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <!-- background: if assets/bus-bg.jpg exists it will be used, else CSS SVG used -->
  <div class="page-bg"></div>
  <div class="overlay"></div>

  <header class="topbar glass">
    <div class="brand">College Bus Management</div>
    <nav class="nav">
      <?php if ($u): ?>
        <a href="home.php">Home</a>
        <a href="buses.php">Buses</a>
        <?php if ($u['role']==='student'): ?><a href="my_bookings.php">My Bookings</a><?php endif; ?>
        <?php if ($u['role']==='driver'): ?><a href="driver.php">Driver</a><?php endif; ?>
        <span class="user">Hello, <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['role']) ?>)</span>
        <a class="btn" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn" href="index.php">Login</a>
      <?php endif; ?>
    </nav>
  </header>

  <main class="container">
