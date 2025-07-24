<?php
session_start();
require_once __DIR__ . '/../database/uni_db_setup.php';

$loginMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userInput = trim($_POST['username'] ?? '');
    $passInput = trim($_POST['password'] ?? '');

    if ($userInput === '' || $passInput === '') {
        $loginMessage = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$userInput]);
        $userRecord = $stmt->fetch();

        if ($userRecord && password_verify($passInput, $userRecord['password'])) {
            $_SESSION['user_id'] = $userRecord['id'];
            $_SESSION['username'] = $userRecord['username'];
            header("Location: ../dashboard.php");
            exit;
        } else {
            $loginMessage = "this is an invalid username and password please";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - UniSearch</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body class="login-screen">

  <div class="login-box">
    <h1>Login</h1>

    <?php if ($loginMessage !== '') : ?>
      <p class="error-message"><?php echo htmlspecialchars($loginMessage); ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
      <label for="usernameField">Your Username:</label>
      <input type="text" name="username" id="usernameField" required placeholder="Please enter your username" />

      <label for="passwordField">Password:</label>
      <input type="password" name="password" id="passwordField" required placeholder="Please enter your password" />

      <button type="submit">Login</button>
    </form>

    <p style="margin-top: 20px; text-align: center;">
      If you Don’t have any account? <a href="register.php" style="color: #fff; text-decoration: underline;">Kindly Register here</a>.
    </p>
  </div>

</body>
</html>