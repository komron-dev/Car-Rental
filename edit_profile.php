<?php
require_once 'utils/util.php';
if(!is_logged_in()) {
  redirect('login.php');
}
$users = load_storage('users.json');
$current_user = $_SESSION['user'];
$errors = [];

$old_data = [
  'full_name' => $current_user['full_name'],
];
$profile_image_path = $current_user['profile_image'];

if($_POST) {
  $old_data['full_name'] = $_POST['full_name'] ?? '';

  if(trim($old_data['full_name']) === '') {
    $errors[] = 'Full name cannot be empty';
  }

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
    $updated_user = $users->findOne(['email' => $current_user['email']]);
    if(!$updated_user) {
      $errors[] = 'User not found';
    } else {
      $updated_user['full_name'] = $old_data['full_name'];
      $updated_user['profile_image'] = $profile_image_path;

      // We must re-save the user
      // In array-based "Storage", we must update by index
      $all_users = $users->findAll();
      $theIndex = null;
      foreach($all_users as $i => $u) {
        if($u['email'] === $current_user['email']) {
          $theIndex = $i;
          break;
        }
      }
      if($theIndex !== null) {
        $users->update((string)$theIndex, $updated_user);
      }

      $_SESSION['user'] = $updated_user;
      redirect('profile.php');
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Edit Profile</title>
</head>
<body>
<header>
  <a href="index.php">Home</a>
  <nav class="right">
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<div class="container">
  <h1>Edit Profile</h1>
  <?php if(!empty($errors)): ?>
    <div class="error">
      <?php foreach($errors as $e) echo "<p>$e</p>"; ?>
    </div>
  <?php endif; ?>
  <form method="POST" enctype="multipart/form-data" novalidate>
    <label>Full Name</label>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars($old_data['full_name']); ?>">

    <label>Change Profile Photo</label>
    <input type="file" name="profile_image">

    <p>(Current Photo)</p>
    <img src="<?php echo htmlspecialchars($profile_image_path); ?>" style="width:100px; height:100px; object-fit:cover; border-radius:50%; border:2px solid #ffd100;">

    <br><br>
    <input type="submit" value="Save Changes">
  </form>
</div>
</body>
</html>
