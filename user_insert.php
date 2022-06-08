<?php

/**
 * @file
 * Show result of inserting a new user.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'bag5564';
$password = 'xxx';
$host = 'localhost';
$dbname = 'bag5564_431W';

?>
<!DOCTYPE html>
<html>
    <head>
      <title>Gamers Den</title>
      <link rel="stylesheet" href="styles.css">
    </head>
    <body>
      <div id="wrapper">
        <header><h1>Gamers Den</h1></header>
        <main>
          <div id="main-content">
            <p>
            <?php
            echo "Inserting new user: " . $_POST["fname"] . " " . $_POST["lname"] . " " . $_POST["loginid"] . "...";
            ?>
            </p>
            <?php
            $sql = 'INSERT INTO users (lname, fname, loginid, admin) ';
            $sql = $sql . 'VALUES ("' . trim($_POST["lname"]) . '","' . trim($_POST["fname"]) . '","' . trim($_POST["loginid"]) . '","' . trim($_POST["admin"]) . '")';
            try {
              $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $conn->exec($sql);
              echo '<p class="success">New record created successfully</p>';
              ?>
            <p>Click on this <a href="users.php">link</a> to go back to the list or wait to be redirected in 3 seconds</p>
            <script>
                var timer = setTimeout(function() {
                    window.location='users.php'
                }, 3000);
            </script>
              <?php
            }
            catch (PDOException $e) {
              echo '<p>' . $sql . '</p><br><p class="error">' . $e->getMessage() . '</p>';
              ?>
            <p>Click on this <a href="users.php">link</a> to go back to the list.</p>
              <?php
            }
            $conn = NULL;
            ?>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</div>
</html>
