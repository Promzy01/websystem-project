<?php
// index.php Search Home Page
// played and made by Promise, June 2025--- enjoy

// Start session and track search count
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // to Initialize search count if not already done
    $_SESSION['search_total'] = $_SESSION['search_total'] ?? 0;
}
// Test if user is logged in 
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
// to Add in DB connection and HTML header
require_once __DIR__ . '/database/uni_db_setup.php';
require_once __DIR__ . '/includes/header.php';
// for Variables for database table and search results
$tableExists = false;
$uniList = [];
// to Test if unis table exists in database
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'unis'");
    if ($stmt->rowCount() > 0) {
        $tableExists = true;

        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($searchTerm !== '') {
            $query = $pdo->prepare("SELECT * FROM unis WHERE name LIKE ? ORDER BY name ASC");
            $query->execute(["%$searchTerm%"]);
            $uniList = $query->fetchAll();
        } else {
            $query = $pdo->query("SELECT * FROM unis ORDER BY name ASC");
            $uniList = $query->fetchAll();
        }
    }
    // to Process any database errors, if any 
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

$timestamp = date('Y-m-d H:i:s');
echo "<!-- Page rendered at $timestamp -->\n";
?>

<main class="page-content">
  <h1>Find UK Universities</h1>
      <!--Search Form to allows user to enter university name-->
  <form method="get" action="index.php" class="form-section">
    <label for="searchField">University Name:</label>
    <input
      type="text"
      id="searchField"
      name="search"
      class="input-field"
      placeholder="University of ......"
      aria-label="Search UK universities"
      value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
    >
    <button type="submit" class="action-button">Search Now</button>
  </form>

  <section id="searchResults" class="results-container">
    <?php if ($tableExists && !empty($uniList)) : ?>
      <h2 class="results-heading">
        <?php if ($searchTerm !== '') : ?>
          Results for "<?php echo htmlspecialchars($searchTerm); ?>"
        <?php else : ?>
          All Universities
        <?php endif; ?>
      </h2>
        <!--Display list of universities, save button if logged in-->
      <ul class="result-grid">
        <?php foreach ($uniList as $uni) : ?>
          <li class="result-card">
            <span class="uni-title"><?php echo htmlspecialchars($uni['name']); ?></span><br>
            <a href="<?php echo htmlspecialchars($uni['website']); ?>" target="_blank" class="link-btn">
              Visit Website
            </a>

            <?php if ($isLoggedIn) : ?>
              <form action="save_uni.php" method="post" class="delete-form" style="display:inline;">
                <input type="hidden" name="uni_id" value="<?php echo $uni['uni_id']; ?>">
                <button type="submit" class="delete-btn">Save to My List</button>
              </form>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>

    <?php elseif ($tableExists) : ?>
      <?php if ($searchTerm !== '') : ?>
        <p>No universities match your search."<?php echo htmlspecialchars($searchTerm); ?>".</p>
      <?php else : ?>
        <p>No universities available in the database.</p>
      <?php endif; ?>
    <?php else : ?>
      <p style="color: red;">The table <strong>unis</strong> does not exist in the database.</p>
    <?php endif; ?>

    <?php
           if (isset($_GET['search'])) {
          $_SESSION['search_total']++;
          echo "<p>You’ve searched {$_SESSION['search_total']} times this session.</p>";
      }
    ?>
  </section>

  <?php if (!$isLoggedIn) : ?>
    <p>
      <a href="auth/login.php" class="action-button">Login</a>
    </p>
  <?php endif; ?>
</main>

<script src="JavaScript/app.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>