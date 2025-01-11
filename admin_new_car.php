<?php
require_once 'utils/util.php';
if(!is_admin()) {
  redirect('index.php');
}
$cars = load_storage('cars.json');
$errors = [];

$old_data = [
  'brand' => '',
  'model' => '',
  'year' => '',
  'fuel_type' => '',
  'transmission' => '',
  'passengers' => '',
  'daily_price_huf' => '',
  'image' => ''
];

if($_POST) {
  $old_data['brand'] = $_POST['brand'] ?? '';
  $old_data['model'] = $_POST['model'] ?? '';
  $old_data['year'] = $_POST['year'] ?? '';
  $old_data['fuel_type'] = $_POST['fuel_type'] ?? '';
  $old_data['transmission'] = $_POST['transmission'] ?? '';
  $old_data['passengers'] = $_POST['passengers'] ?? '';
  $old_data['daily_price_huf'] = $_POST['daily_price_huf'] ?? '';
  $old_data['image'] = $_POST['image'] ?? '';

  if(
    !$old_data['brand'] || !$old_data['model'] || !$old_data['year'] ||
    !$old_data['fuel_type'] || !$old_data['transmission'] ||
    !$old_data['passengers'] || !$old_data['daily_price_huf'] || !$old_data['image']
  ) {
    $errors[] = 'All fields required';
  }

  if(empty($errors)) {
    $all_cars = $cars->findAll();
    $max_id = 0;
    foreach($all_cars as $existing_car) {
      if($existing_car['id'] > $max_id) {
        $max_id = $existing_car['id'];
      }
    }
    $new_id = $max_id + 1;

    $new_car = [
      'id' => $new_id,
      'brand' => $old_data['brand'],
      'model' => $old_data['model'],
      'year' => (int)$old_data['year'],
      'fuel_type' => $old_data['fuel_type'],
      'transmission' => $old_data['transmission'],
      'passengers' => (int)$old_data['passengers'],
      'daily_price_huf' => (int)$old_data['daily_price_huf'],
      'image' => $old_data['image']
    ];
    $cars->add($new_car);
    redirect('admin_cars.php');
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Add Car</title>
</head>
<body>
<header>
  <a href="index.php">Home</a>
</header>
<div class="container">
  <h1>Add New Car</h1>
  <?php if(!empty($errors)): ?>
    <div class="error">
      <?php foreach($errors as $e) echo "<p>$e</p>"; ?>
    </div>
  <?php endif; ?>
  <form method="POST" novalidate>
    <label>Brand</label>
    <input type="text" name="brand" value="<?php echo htmlspecialchars($old_data['brand']); ?>">
    <label>Model</label>
    <input type="text" name="model" value="<?php echo htmlspecialchars($old_data['model']); ?>">
    <label>Year</label>
    <input type="number" name="year" value="<?php echo htmlspecialchars($old_data['year']); ?>">
    <label>Fuel Type</label>
    <input type="text" name="fuel_type" value="<?php echo htmlspecialchars($old_data['fuel_type']); ?>">
    <label>Transmission</label>
    <select name="transmission">
      <option value="Automatic" <?php if($old_data['transmission'] === 'Automatic') echo 'selected'; ?>>Automatic</option>
      <option value="Manual" <?php if($old_data['transmission'] === 'Manual') echo 'selected'; ?>>Manual</option>
    </select>
    <label>Passenger Capacity</label>
    <input type="number" name="passengers" value="<?php echo htmlspecialchars($old_data['passengers']); ?>">
    <label>Daily Price (HUF)</label>
    <input type="number" name="daily_price_huf" value="<?php echo htmlspecialchars($old_data['daily_price_huf']); ?>">
    <label>Image URL</label>
    <input type="text" name="image" value="<?php echo htmlspecialchars($old_data['image']); ?>">
    <input type="submit" value="Save">
  </form>
</div>
</body>
</html>
