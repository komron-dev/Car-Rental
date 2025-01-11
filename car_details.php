<?php
require_once 'utils/util.php';
if(!isset($_GET['id'])) {
  redirect('index.php');
}
$cars = load_storage('cars.json');
$car = null;
foreach($cars->findAll() as $c) {
  if($c['id'] == $_GET['id']) {
    $car = $c;
    break;
  }
}
if(!$car) {
  redirect('index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Car Details</title>
  <style>
    .booking-box { display: flex; gap: 10px; margin-top: 15px; }
    .booking-box div { display: flex; flex-direction: column; }
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
  <h1><?php echo $car['brand'].' '.$car['model']; ?></h1>
  <div style="display:flex; gap:30px;">
    <div style="width:400px;">
      <img src="<?php echo $car['image']; ?>" style="max-width:100%;">
    </div>
    <div>
      <p>Fuel: <?php echo $car['fuel_type']; ?></p>
      <p>Shifter: <?php echo $car['transmission']; ?></p>
      <p>Year of manufacture: <?php echo $car['year']; ?></p>
      <p>Number of seats: <?php echo $car['passengers']; ?></p>
      <h2>HUF <?php echo $car['daily_price_huf']; ?>/day</h2>
      <?php if(is_logged_in()): ?>
        <form method="POST" action="book_car.php" class="booking-box" novalidate id="bookingForm">
          <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
          <div>
            <label>From</label>
            <input type="date" name="start_date" id="start_date">
          </div>
          <div>
            <label>Until</label>
            <input type="date" name="end_date" id="end_date">
          </div>
          <input type="submit" value="Book it">
        </form>
        <script>
          const startInput = document.getElementById('start_date');
          const endInput = document.getElementById('end_date');
          startInput.addEventListener('change', () => {
            endInput.min = startInput.value || '';
          });
        </script>
      <?php else: ?>
        <p>You must <a href="login.php">login</a> to book.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
