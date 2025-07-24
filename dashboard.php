<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

require_once __DIR__ . '/database/uni_db_setup.php';

// Fetch the user's saved universities
$query = $pdo->prepare("
    SELECT u.uni_id, u.name, u.website
    FROM user_unis uu
    JOIN unis u ON uu.uni_id = u.uni_id
    WHERE uu.user_id = ?
");
$query->execute([$_SESSION['user_id']]);
$savedUniversities = $query->fetchAll();

// For timestamp debug info
$timestamp = date('Y-m-d H:i:s');
echo "<!-- Dashboard loaded at $timestamp -->\n";
?>

<?php require_once __DIR__ . '/includes/header.php'; ?>

<body class="dashboard-page">
  <main class="page-content dashboard-glass">
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>

    <h2>Your Session Stats</h2>
    <p>Searches in this session: <?php echo $_SESSION['search_total'] ?? 0; ?></p>

    <h2>Your Saved Universities</h2>

    <?php if (!empty($savedUniversities)) : ?>
      <ul class="mypromisedashboard-uni-list">
        <?php foreach ($savedUniversities as $uni) : ?>
          <li>
            <span class="uni-title"><?php echo htmlspecialchars($uni['name']); ?></span>
            —
            <a href="<?php echo htmlspecialchars($uni['website']); ?>" target="_blank" class="link-btn">
              Visit Website
            </a>
            <form action="delete_uni.php" method="post" class="delete-form">
              <input type="hidden" name="uni_id" value="<?php echo $uni['uni_id']; ?>">
              <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to remove this university?');">
                Delete
              </button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <p>You don’t have any saved upuniversities yet.</p>
    <?php endif; ?>

    <p style="margin-top: 20px;">
      <a href="auth/logout.php" class="logout-btn">Logout</a>
    </p>
  </main>
</body>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
