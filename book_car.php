<?php
require_once 'utils/util.php';
if(!is_logged_in()) {
  redirect('login.php');
}
if(!isset($_POST['car_id']) || !isset($_POST['start_date']) || !isset($_POST['end_date'])) {
  redirect('index.php');
}
$car_id = $_POST['car_id'];
$start = $_POST['start_date'];
$end = $_POST['end_date'];
if(empty($start) || empty($end)) {
  redirect('booking_result.php?fail=1&car_id='.$car_id.'&reason=missing');
}
if(strtotime($end) < strtotime($start)) {
  redirect('booking_result.php?fail=1&car_id='.$car_id.'&reason=date_order');
}
$bookings = load_storage('bookings.json');
foreach($bookings->findAll() as $b) {
  if($b['car_id'] == $car_id) {
    $existing_start = strtotime($b['start_date']);
    $existing_end   = strtotime($b['end_date']);
    $start_time     = strtotime($start);
    $end_time       = strtotime($end);
    if(!($end_time < $existing_start || $start_time > $existing_end)) {
      redirect('booking_result.php?fail=1&car_id='.$car_id.'&booked_start='.$b['start_date'].'&booked_end='.$b['end_date']);
    }
  }
}
$new_booking = [
  "car_id" => $car_id,
  "email" => $_SESSION['user']['email'],
  "start_date" => $start,
  "end_date" => $end
];
$bookings->add($new_booking);
redirect('booking_result.php?success=1&car_id='.$car_id.'&start='.$start.'&end='.$end);
