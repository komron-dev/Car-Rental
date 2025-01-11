<?php
require_once 'utils/util.php';

$errors = [];
$old_data = [
  'full_name' => '',
  'email' => '',
  'password' => '',
  'password_again' => ''
];
$profile_image_path = '';

if($_POST) {
  $old_data['full_name'] = $_POST['full_name'] ?? '';
  $old_data['email'] = $_POST['email'] ?? '';
  $old_data['password'] = $_POST['password'] ?? '';
  $old_data['password_again'] = $_POST['password_again'] ?? '';

  $errors = validate_registration($_POST);

  if(!empty($_FILES['profile_image']['name'])) {
    $upload_dir = __DIR__ . '/uploads/';
    if(!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }
    $filename = time().'_'.basename($_FILES['profile_image']['name']);
    $target_file = $upload_dir.$filename;
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if(!in_array($ext, ['jpg','jpeg','png','gif'])) {
      $errors[] = 'Profile photo must be an image (jpg, jpeg, png, gif)';
    } else {
      if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
        $profile_image_path = 'uploads/'.$filename;
      } else {
        $errors[] = 'Failed to upload the profile photo';
      }
    }
  }

  if(empty($errors)) {
    $users = load_storage('users.json');
    $existing = $users->findOne(['email' => $old_data['email']]);
    if($existing) {
      $errors[] = 'Email already in use';
    } else {
      if($profile_image_path === '') {
        $profile_image_path = 'uploads/default.png';
      }
      $new_user = [
        "full_name" => $old_data['full_name'],
        "email" => $old_data['email'],
        "password" => $old_data['password'],
        "is_admin" => false,
        "profile_image" => $profile_image_path
      ];
      $users->add($new_user);
      redirect('login.php');
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Registration</title>
</head>
<body>
<header>
  <a href="index.php">Home</a>
</header>
<div class="container">
  <h1>Registration</h1>
  <?php if(!empty($errors)): ?>
    <div class="error">
      <?php foreach($errors as $e) echo "<p>$e</p>"; ?>
    </div>
  <?php endif; ?>
  <form method="POST" enctype="multipart/form-data" novalidate>
    <label>Full name</label>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars($old_data['full_name']); ?>">
    <label>Email address</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($old_data['email']); ?>">
    <label>Password</label>
    <input type="password" name="password" value="<?php echo htmlspecialchars($old_data['password']); ?>">
    <label>Confirm password</label>
    <input type="password" name="password_again" value="<?php echo htmlspecialchars($old_data['password_again']); ?>">
    <label>Profile photo</label>
    <input type="file" name="profile_image">
    <input type="submit" value="Register">
  </form>
</div>
</body>
</html>
