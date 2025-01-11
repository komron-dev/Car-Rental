<?php
require_once 'utils/util.php';
if(!is_admin()) {
  redirect('index.php');
}
$cars = load_storage('cars.json');
$all_cars = $cars->findAll();
$bookings = load_storage('bookings.json')->findAll();
$bookedCars = [];
foreach ($bookings as $b) {
  $bookedCars[$b['car_id']] = true;
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Admin - Cars</title>
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
  <h1>Car Management</h1>
  <p><a class="btn" href="admin_new_car.php">Add New Car</a></p>
  <div style="display:flex; flex-wrap:wrap;">
  <?php foreach($all_cars as $car): ?>
    <div class="card" style="width:200px;">
      <img src="<?php echo $car['image']; ?>">
      <p><?php echo $car['brand'].' '.$car['model']; ?></p>
      <p><?php echo $car['daily_price_huf']; ?> Ft/day</p>
      <a class="btn" href="admin_edit_car.php?id=<?php echo $car['id']; ?>">Edit</a>
      <?php
        $warning = isset($bookedCars[$car['id']])
          ? "This car is booked for certain days! Are you sure you want to delete it?"
          : "Are you sure you want to delete this car?";
      ?>
      <a class="btn"
         href="admin_delete_car.php?id=<?php echo $car['id']; ?>"
         onclick="return confirm('<?php echo $warning; ?>');"
      >
        Delete
      </a>
    </div>
  <?php endforeach; ?>
  </div>

  <h2>All Bookings</h2>
  <?php
  $users = load_storage('users.json');
  if (!$bookings):
  ?>
    <p>No bookings</p>
  <?php else: ?>
    <?php foreach($bookings as $b): ?>
      <?php
        $c = $cars->findOne(['id' => $b['car_id']]);
        $u = $users->findOne(['email' => $b['email']]);
      ?>
      <?php if($c && $u): ?>
        <p><?php echo $c['brand'].' '.$c['model'].' => '.$u['email'].' ('.$b['start_date'].' - '.$b['end_date'].')'; ?></p>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>
