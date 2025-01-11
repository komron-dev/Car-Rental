<?php
require_once 'utils/util.php';

$errors = [];
$old_data = [
  'email' => '',
  'password' => ''
];

if ($_POST) {
  $old_data['email'] = $_POST['email'] ?? '';
  $old_data['password'] = $_POST['password'] ?? '';

  $errors = validate_login($_POST);
  if (empty($errors)) {
    $users = load_storage('users.json');
    $user = $users->findOne(["email" => $old_data['email']]);
    $entered_password = str_replace(' ', '', $old_data['password']);
    if (!$user) {
      $errors[] = 'Incorrect credentials';
    } else {
      $actual_password = str_replace(' ', '', $user['password']);
      if ($entered_password === $actual_password) {
        $_SESSION['user'] = $user;
        redirect('index.php');
      } else {
        $errors[] = 'Password is incorrect';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Login</title>
</head>
<body>
<header>
  <a href="index.php">Home</a>
</header>
<div class="container">
  <h1>Login</h1>
  <?php if(!empty($errors)): ?>
    <div class="error">
      <?php foreach($errors as $e) echo "<p>$e</p>"; ?>
    </div>
  <?php endif; ?>
  <form method="POST" novalidate>
    <label>Email address</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($old_data['email']); ?>">
    <label>Password</label>
    <input type="password" name="password" value="<?php echo htmlspecialchars($old_data['password']); ?>">
    <input type="submit" value="Login">
  </form>
</div>
</body>
</html>
