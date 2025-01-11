<?php
require_once 'utils/util.php';

$cars = load_storage('cars.json');
$bookings = load_storage('bookings.json');

$filters = [
  'transmission' => isset($_GET['transmission']) ? trim($_GET['transmission']) : '',
  'passengers'   => isset($_GET['passengers'])   ? trim($_GET['passengers'])   : '',
  'minprice'     => isset($_GET['minprice'])     ? trim($_GET['minprice'])     : '',
  'maxprice'     => isset($_GET['maxprice'])     ? trim($_GET['maxprice'])     : '',
  'from'         => isset($_GET['from'])         ? trim($_GET['from'])         : '',
  'until'        => isset($_GET['until'])        ? trim($_GET['until'])        : ''
];

$filtered_cars = get_cars_filtered($cars, $bookings, $filters);
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Home</title>
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
  <h1>Rent cars easily!</h1>

  <form method="GET" class="filters" novalidate>
    <div>
      <label>From</label>
      <input type="date" name="from" id="fromDate" value="<?php echo htmlspecialchars($filters['from']); ?>">
    </div>
    <div>
      <label>Until</label>
      <input type="date" name="until" id="untilDate" value="<?php echo htmlspecialchars($filters['until']); ?>">
    </div>
    <div>
      <label>Transmission</label>
      <select name="transmission">
        <option value="">Any</option>
        <option value="Automatic" <?php if($filters['transmission']==='Automatic') echo 'selected'; ?>>Automatic</option>
        <option value="Manual" <?php if($filters['transmission']==='Manual') echo 'selected'; ?>>Manual</option>
      </select>
    </div>
    <div>
      <label>Passengers</label>
      <input type="number" name="passengers" value="<?php echo htmlspecialchars($filters['passengers']); ?>">
    </div>
    <div>
      <label>Price (min)</label>
      <input type="number" name="minprice" value="<?php echo htmlspecialchars($filters['minprice']); ?>">
    </div>
    <div>
      <label>Price (max)</label>
      <input type="number" name="maxprice" value="<?php echo htmlspecialchars($filters['maxprice']); ?>">
    </div>
    <div>
      <input type="submit" value="Filter">
    </div>
  </form>

  <script>
    const fromInput = document.getElementById('fromDate');
    const untilInput = document.getElementById('untilDate');
    fromInput.addEventListener('change', () => {
      untilInput.min = fromInput.value || '';
    });
  </script>

  <div style="display:flex; flex-wrap:wrap;">
    <?php foreach($filtered_cars as $car): ?>
      <div class="card" style="width: 200px;">
        <img src="<?php echo $car['image']; ?>">
        <h3><?php echo $car['brand'].' '.$car['model']; ?></h3>
        <p><?php echo $car['passengers']; ?> seats - <?php echo $car['transmission']; ?></p>
        <p><?php echo $car['daily_price_huf']; ?> Ft</p>
        <a class="btn" href="car_details.php?id=<?php echo $car['id']; ?>">Book</a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
