<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$fullname = $_POST['fullname'];
$birthyear = $_POST['birthyear'];
$sleephours = $_POST['sleephours'];
$city = $_POST['city'];

if (!is_numeric($birthyear) || empty($fullname) || empty($sleephours)) {
    echo "Please fill all fields correctly.";
    exit;
}

$currentYear = date("Y");
$age = $currentYear - $birthyear;
$totalSleepYears = ($sleephours * 365 * $age) / 24;
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Info</title>
</head>
<body>
<div>
  <h2>User Information</h2>
  <table border="1">
    <tr><td>Full Name</td><td><?= htmlspecialchars($fullname) ?></td></tr>
    <tr><td>Age</td><td><?= $age ?></td></tr>
    <tr><td>Total Years Sleeping</td><td><?= number_format($totalSleepYears, 2) ?></td></tr>
    <tr><td>City</td><td><?= htmlspecialchars($city) ?></td></tr>
  </table>

  <?php
  if ($age > 50) {
      echo "<p>You might want to start planning for retirement.</p>";
  }
  if ($totalSleepYears > 15) {
      echo "<p>You've spent a huge part of your life sleeping!</p>";
  }
  if ($city !== "Quezon City") {
      echo "<p>You don’t live in the best city.</p>";
  } else {
      echo "<p>Quezon City rocks!</p>";
  }
  if ($age <= 25) {
      echo "<p>You're still young, enjoy learning!</p>";
  }
  ?>
</div>
</body>
</html>
