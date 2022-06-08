<?php

/**
 * @file
 * Show list of users.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'bag5564';
$password = 'xxx';
$host = 'localhost';
$dbname = 'bag5564_431W';

$gameid = 0;
$game_name = '';
$genreid = 0;
$rating_code = '';
$action = "/game_insert.php";
$title = "Insert a New Game";

if ($_POST["operation"] == 'edit' && $_POST["gameid"] != "") {
  try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT gameid, game_name, genreid, rating_code FROM games WHERE gameid = "' . $_POST["gameid"] . '"';
    $q = $pdo->query($sql);
    $q->setFetchMode(PDO::FETCH_OBJ);
    $result = $q->fetch();
    $gameid = $result->gameid;
    $game_name = $result->game_name;
    $rating_code = $result->rating_code;
    $genreid = $result->genreid;
    $action = "/game_update.php";
    $title = "Edit Game ";
  }
  catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
  }
}
try {
  // Data for genre select.
  $pdo1 = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql1 = 'SELECT genreid, genre_name FROM genres ORDER BY genre_name';
  $q1 = $pdo1->query($sql1);
  $q1->setFetchMode(PDO::FETCH_ASSOC);
  // Data for rating select.
  $sql2 = 'SELECT rating_code, rating_description FROM ratings ORDER BY rating_description';
  $q2 = $pdo1->query($sql2);
  $q2->setFetchMode(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
  die("Could not connect to the database $dbname :" . $e->getMessage());
}
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
        <?php include 'navigation.php'; ?>
        <main>
          <div id="main-content">
            <script type="text/javascript">
              function required() {
                var name = document.forms["insert-form"]["game_name"].value.trim();
                if (name == "") {
                  alert("All fields are required");
                  return false;
                }
                else {
                  return true;
                }
              }
            </script>
            <form name="insert-form" action="<?php echo $action; ?>" method="post" onsubmit="return required()">
              <fieldset>
                <h2><?php echo $title; ?></h2>
                <p class="instructions">All fields are required</p>
                <input type="hidden" id="gameid" name="gameid" value="<?php echo $gameid; ?>">
                <table>
                  <tr>
                    <td>Name:</td>
                    <td><input type="text" id="game_name" name="game_name" value="<?php echo $game_name; ?>"></td>
                  </tr>
                  <tr>
                    <td>Genre:</td>
                    <td>
                      <select name="genreid" id="genreid">
                      <?php while ($row1 = $q1->fetch()) : ?>
                        <option value=
                        <?php
                        echo '"' . $row1['genreid'] . '"';
                        if ($row1['genreid'] == $genreid) {
                          echo ' selected ';
                        } ?>>
                          <?php echo htmlspecialchars($row1['genre_name']) ?>
                        </option>
                      <?php endwhile; ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Rating:</td>
                    <td>
                      <select name="rating_code" id="rating_code">
                      <?php while ($row2 = $q2->fetch()) : ?>
                        <option value=
                        <?php
                        echo '"' . $row2['rating_code'] . '"';
                        if ($row2['rating_code'] == $rating_code) {
                          echo ' selected ';
                        } ?>>
                          <?php echo $row2['rating_code'] . ' - ' . htmlspecialchars($row2['rating_description']) ?>
                        </option>
                      <?php endwhile; ?>
                      </select>
                    </td>
                  </tr>
                </table>
                <br/>
                <?php
                if ($_POST["operation"] == 'edit') {
                  echo '<input type="submit" value="UPDATE">';
                }
                else {
                  echo '<input type="submit" value="INSERT">';
                }
                ?>
                &nbsp;&nbsp;&nbsp;<a href = "games.php" >Go back without saving </a>
              </fieldset>
            </form>
            <br><br>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</html>
