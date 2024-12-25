<?php
require_once __DIR__ . "/database/koneksi.php";
require_once __DIR__ . "/resources/functions/functions.php";

$request_site = isset($_GET['request_site']) ? $_GET['request_site'] : 'home';

session_start();


if ($request_site === "logout") {
  session_destroy();
  header("Location: login");
  exit();
}


$logged_in = user();
if (!$logged_in) {
  $request_site = "login";
}

$path = __DIR__ . "/resources/pages/";
if ($logged_in) {
  $page_path = $path . "$logged_in->role/$request_site.php";
} else {
  $page_path = $path . "$request_site.php";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Presenza</title>
  <link rel="stylesheet" href="resources/assets/css/output.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  <script src="resources/assets/javascript/admin.js"></script>
</head>

<body>
  <?php
  if (file_exists($page_path)) {
    require $page_path;
  } else {
    require "{$path}404.php";
  }

  if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
  }
  ?>
</body>

</html>