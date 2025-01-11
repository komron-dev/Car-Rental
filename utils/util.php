<?php
require_once __DIR__ . '/storage.php'; 

session_start();
function is_logged_in() {
  return isset($_SESSION['user']);
}
function is_admin() {
  return is_logged_in() && $_SESSION['user']['is_admin'] === true;
}
function redirect($url) {
  header("Location: $url");
  exit();
}
function overlap($start1, $end1, $start2, $end2) {
  return !( $end1 < $start2 || $start1 > $end2 );
}
function load_storage($filename) {
  return new Storage(new JsonIO(__DIR__ . '/../data/' . $filename));
}
function validate_registration($data) {
  $errors = [];
  if (!isset($data['full_name']) || trim($data['full_name']) === '') {
    $errors[] = 'Full name is required';
  }
  if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email';
  }
  if (!isset($data['password']) || strlen(trim($data['password'])) < 6) {
    $errors[] = 'Password must be at least 6 characters';
  }
  if (!isset($data['password_again']) || $data['password'] !== $data['password_again']) {
    $errors[] = 'Passwords do not match';
  }
  return $errors;
}
function validate_login($data) {
  $errors = [];
  if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email';
  }
  if (!isset($data['password']) || trim($data['password']) === '') {
    $errors[] = 'Password required';
  }
  return $errors;
}

function get_cars_filtered($cars, $bookings, $filters) {
  return $cars->findMany(function($car) use ($filters, $bookings) {
    if ($filters['transmission'] !== '') {
      if ($car['transmission'] !== $filters['transmission']) {
        return false;
      }
    }
    if ($filters['passengers'] !== '') {
      $req_pass = (int)$filters['passengers'];
      if ($req_pass > 0 && $car['passengers'] < $req_pass) {
        return false;
      }
    }
    if ($filters['minprice'] !== '') {
      $min = (int)$filters['minprice'];
      if ($car['daily_price_huf'] < $min) {
        return false;
      }
    }
    if ($filters['maxprice'] !== '') {
      $max = (int)$filters['maxprice'];
      if ($car['daily_price_huf'] > $max) {
        return false;
      }
    }
    if ($filters['from'] !== '' && $filters['until'] !== '') {
      $reservations = $bookings->findMany(function($b) use ($car) {
        return $b['car_id'] == $car['id'];
      });
      foreach ($reservations as $r) {
        if (overlap($filters['from'], $filters['until'], $r['start_date'], $r['end_date'])) {
          return false;
        }
      }
    }
    return true;
  });
}
