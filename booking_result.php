<?php
require_once 'utils/util.php';
$cars = load_storage('cars.json');
$car = null;
if(isset($_GET['car_id'])) {
  foreach($cars->findAll() as $c) {
    if($c['id'] == $_GET['car_id']) {
      $car = $c;
      break;
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Booking Result</title>
  <style>
    .centered { text-align: center; margin-top: 50px; }
    .icon { width: 100px; height: 100px; margin: 0 auto; }
    .btn-container { margin-top: 20px; }
  </style>
</head>
<body>
<header>
  <a href="index.php">Home</a>
  <nav class="right">
  <?php if(!is_logged_in()): ?>
    <a href="login.php">Login</a>
    <a href="register.php">Registration</a>
  <?php else: ?>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
    <?php if(is_admin()): ?>
      <a href="admin_cars.php">Admin</a>
    <?php endif; ?>
  <?php endif; ?>
  </nav>
</header>
<div class="container">
<?php if(isset($_GET['success'])): ?>
  <div class="centered">
    <img src="css/success_icon.png" class="icon">
    <h1>Successful booking!</h1>
    <?php if($car && isset($_GET['start']) && isset($_GET['end'])): ?>
      <p>The <?php echo $car['brand'].' '.$car['model']; ?> has been successfully booked for the interval <?php echo $_GET['start'].' - '.$_GET['end']; ?>.</p>
      <?php
        $start_time = strtotime($_GET['start']);
        $end_time = strtotime($_GET['end']);
        $days = ($end_time - $start_time)/(60*60*24) + 1;
        $price = $days * $car['daily_price_huf'];
      ?>
      <p>Total price: HUF <?php echo $price; ?></p>
    <?php endif; ?>
    <div class="btn-container">
      <a class="btn" href="profile.php">My profile</a>
    </div>
  </div>
<?php elseif(isset($_GET['fail'])): ?>
  <div class="centered">
    <img src="css/fail_icon.png" class="icon">
    <h1>Booking failed!</h1>
    <?php if($car): ?>
      <?php if(isset($_GET['reason']) && $_GET['reason']==='missing'): ?>
        <p>You must select both From and Until dates.</p>
      <?php elseif(isset($_GET['reason']) && $_GET['reason']==='date_order'): ?>
        <p>Until date must not be earlier than From date.</p>
      <?php elseif(isset($_GET['booked_start']) && isset($_GET['booked_end'])): ?>
        <p>The <?php echo $car['brand'].' '.$car['model']; ?> is not available for the specified interval. It is booked from <?php echo $_GET['booked_start']; ?> to <?php echo $_GET['booked_end']; ?>.</p>
      <?php else: ?>
        <p>The <?php echo $car['brand'].' '.$car['model']; ?> is not available in the specified interval.</p>
      <?php endif; ?>
    <?php endif; ?>
    <div class="btn-container">
      <a class="btn" href="car_details.php?id=<?php echo $_GET['car_id']; ?>">Back to the vehicle side</a>
    </div>
  </div>
<?php endif; ?>
</div>
</body>
</html>
