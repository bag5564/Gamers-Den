<?php

/**
 * @file
 * Show result of deleting a game.
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
            echo "Deleting game: " . $_POST["gameid"] . "...";
            $sql  = 'DELETE FROM game_platforms WHERE gameid = "' . $_POST["gameid"] . '"';
            $sql1 = 'DELETE FROM game_reviews WHERE gameid = "' . $_POST["gameid"] . '"';
            $sql2 = 'DELETE FROM games WHERE gameid = "' . $_POST["gameid"] . '"';
            try {
              $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $conn->beginTransaction();
              $conn->exec($sql);
              $conn->exec($sql1);
              $conn->exec($sql2);
              $conn->commit();
              echo '<p class="success">Record deleted successfully</p>';
              ?>
              <p>Click on this <a href="games.php">link</a> to go back to the list or wait to be redirected in 3 seconds</p>
              <script>
                  var timer = setTimeout(function() {
                      window.location='games.php'
                  }, 3000);
              </script>
              <?php
            }
            catch (PDOException $e) {
              $conn->rollback();
              echo '<p>' . $sql . '</p><br><p>' . $sql1 . '</p><br><p>' . $sql2 . '</p><br><p class="error">' . $e->getMessage() . '</p>';
              ?>
            <p>Click on this <a href="games.php">link</a> to go back to the list.</p>
              <?php
            }
            $conn = NULL;
            ?>
            </p>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</div>
</html>
