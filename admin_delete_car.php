<?php
require_once 'utils/util.php';
if(!is_admin()) {
  redirect('index.php');
}
if(!isset($_GET['id'])) {
  redirect('admin_cars.php');
}
$cars = load_storage('cars.json');
$bookings = load_storage('bookings.json');
$id = $_GET['id'];
$car = $cars->findOne(['id'=>$id]);
if($car) {
  $cars->deleteMany(function($c) use ($id) {
    return $c['id'] == $id;
  });
  $bookings->deleteMany(function($b) use ($id) {
    return $b['car_id'] == $id;
  });
}
redirect('admin_cars.php');
