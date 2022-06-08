<?php

/**
 * @file
 * Show result of inserting a new game.
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
            echo "Inserting new platform for game: " . $_POST["gameid"] . " " . $_POST["platform_code"] . " " . $_POST["publisherid"] . " " . $_POST["release_year"];
            ?>
            </p>
            <?php
            $sql = 'INSERT INTO game_platforms (gameid, platform_code, publisherid, release_year) ';
            $sql = $sql . 'VALUES ("' . $_POST["gameid"] . '","' . $_POST["platform_code"] . '","' . $_POST["publisherid"] . '","' . trim($_POST["release_year"]) . '")';
            try {
              $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $conn->exec($sql);
              echo '<p class="success">New record created successfully</p>';
              ?>
            <div>Click on this
              <form id="backform" name="backform" action="/game_platforms.php" method="post" style="display: inline;">
                <input class="button-link" type="submit" value="link"><input type="hidden" name="gameid" value="<?php echo $_POST["gameid"] ?>"> to go back to the list or wait to be redirected in 3 seconds
              </form>
            </div>
            <script>
                var timer = setTimeout(function() {
                    document.backform.submit();
                }, 3000);
            </script>
              <?php
            }
            catch (PDOException $e) {
              echo '<p>' . $sql . '</p><br><p class="error">' . $e->getMessage() . '</p>';
              ?>
            <div>Click on this
              <form id="backform" name="backform" action="/game_platforms.php" method="post" style="display: inline;">
                <input class="button-link" type="submit" value="link"><input type="hidden" name="gameid" value="<?php echo $_POST["gameid"] ?>"> to go back to the list
              </form>
            </div>
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
