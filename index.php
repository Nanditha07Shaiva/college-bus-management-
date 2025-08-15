<?php
// index.php
session_start();
if (!empty($_SESSION['user'])) { header('Location: home.php'); exit; }

require_once __DIR__ . '/includes/config.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $msg = "Enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, name, role, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows === 1) {
            $u = $res->fetch_assoc();
            // Demo: plain text password. For production use password_hash & password_verify.
            if ($password === $u['password']) {
                $_SESSION['user'] = [
                    'id' => (int)$u['id'],
                    'username' => $u['username'],
                    'name' => $u['name'],
                    'role' => $u['role']
                ];
                header('Location: home.php');
                exit;
            } else {
                $msg = "Invalid password.";
            }
        } else {
            $msg = "User not found.";
        }
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<section class="auth-wrap">
  <div class="auth-card glass">
    <h1>Sign in</h1>

    <?php if (!empty($_GET['msg'])): ?>
      <div class="alert"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if ($msg): ?>
      <div class="alert"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post" class="form">
      <label>Username
        <input type="text" name="username" required>
      </label>
      <label>Password
        <input type="password" name="password" required>
      </label>
      <button type="submit" class="btn primary">Login</button>
    </form>

    <div class="hint">
      <p><strong>Demo users:</strong></p>
      <p>Student → <code>student1</code> / <code>student123</code></p>
      <p>Driver → <code>driver1</code> / <code>driver123</code></p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
