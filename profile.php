<?php
require_once 'utils/util.php';
if(!is_logged_in()) {
  redirect('login.php');
}
$bookings = load_storage('bookings.json');
$cars = load_storage('cars.json');
$user_bookings = $bookings->findAll(["email" => $_SESSION['user']['email']]);
$user_photo = isset($_SESSION['user']['profile_image']) && $_SESSION['user']['profile_image'] !== ''
  ? $_SESSION['user']['profile_image']
  : 'css/default_avatar.png';
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>My Profile</title>
  <style>
    .profile-container {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .profile-photo img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
    }
    .reservations {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
    }
    .reservation-card {
      background-color: #2f2f2f;
      width: 200px;
      border-radius: 8px;
      overflow: hidden;
      text-align: center;
    }
    .reservation-card img {
      width: 100%;
      height: 120px;
      object-fit: cover;
    }
    .reservation-date {
      font-size: 16px;
      margin: 8px 0;
    }
  </style>
</head>
<body>
<header>
  <a href="index.php">Home</a>
  <nav class="right">
    <a href="logout.php">Logout</a>
    <?php if(is_admin()): ?>
      <a href="admin_cars.php">Admin</a>
    <?php endif; ?>
  </nav>
</header>
<div class="container">
<div class="profile-container">
  <div class="profile-photo">
    <img src="<?php echo $user_photo; ?>">
  </div>
  <div>
    <h2><?php echo $_SESSION['user']['full_name']; ?></h2>
    <p><a class="btn" href="edit_profile.php">Edit Profile</a></p>
  </div>
</div>
  <h2>My reservations</h2>
  <?php if(!$user_bookings): ?>
    <p>No bookings yet.</p>
  <?php else: ?>
    <div class="reservations">
      <?php foreach($user_bookings as $b): ?>
        <?php
          $car = null;
          foreach($cars->findAll() as $c) {
            if($c['id'] == $b['car_id']) {
              $car = $c;
              break;
            }
          }
        ?>
        <?php if($car): ?>
          <?php
            $start_ts = strtotime($b['start_date']);
            $end_ts   = strtotime($b['end_date']);
            $days     = ($end_ts - $start_ts) / (60*60*24) + 1;
            $fee      = $days * $car['daily_price_huf'];
          ?>
          <div class="reservation-card">
            <img src="<?php echo $car['image']; ?>">
            <p><?php echo $car['brand'].' '.$car['model']; ?></p>
            <p><?php echo $car['passengers']; ?> seats - <?php echo $car['transmission']; ?></p>
            <div class="reservation-date">
              <?php echo $b['start_date']; ?> – <?php echo $b['end_date']; ?>
            </div>
            <p><?php echo "{$fee} HUF";?></p>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
