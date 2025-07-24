<?php
session_start();
require_once __DIR__ . '/../database/uni_db_setup.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = "All fields are required.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

            $message = "Registration successful! You can now <a href='login.php'>login</a>.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Username already exists.";
            } else {
                $message = "Error: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - UniSearch</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body class="register-screen">
  <div class="register-box">
    <form class="register-form" method="post" action="register.php">
      <h1>Kindly Register</h1>

      <?php if ($message !== '') : ?>
        <p class="error-message"><?php echo $message; ?></p>
      <?php endif; ?>

      <label for="username">Username</label>
      <input type="text" name="username" id="username" placeholder="Please enter username" required />

      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Please enter password" required />

      <button type="submit">Sign Up</button>

      <div class="link-area">
        Do you have an account with us? <a href="login.php">Please Login Here</a>
      </div>
    </form>
  </div>
</body>
</html>
