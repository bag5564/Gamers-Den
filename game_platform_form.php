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
// Make sure a gameid is sent.
if (!isset($_POST["gameid"])) {
  header("Location: games.php");
  die();
}
// Variables to hold game-platform data.
$gameid = $_POST["gameid"];
$platform_code = '';
$publisherid = 0;
$release_year = '';
$action = "/game_platform_insert.php";
$title = "Insert a New Plaform for \"";

if ($_POST["operation"] == 'edit' && $_POST["gameid"] != "" && $_POST["platform_code"] != "") {
  try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT GP.gameid, GP.platform_code, platform_name, GP.publisherid, publisher_name, release_year FROM game_platforms GP, platforms P, publishers PU WHERE GP.platform_code = P.platform_code AND GP.publisherid = PU.publisherid AND GP.gameid = ' . $_POST["gameid"] . ' AND GP.platform_code = "' . $_POST["platform_code"] . '" ORDER BY release_year';
    $q = $pdo->query($sql);
    $q->setFetchMode(PDO::FETCH_OBJ);
    $result = $q->fetch();
    $gameid = $result->gameid;
    $platform_code = $result->platform_code;
    $publisherid = $result->publisherid;
    $release_year = $result->release_year;
    $action = "/game_platform_update.php";
    $title = "Edit Platform for \"";
  }
  catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
  }
}
try {
  // PLATFORM data for dropdown.
  $pdo1 = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql1 = 'SELECT platform_code, platform_name FROM platforms ORDER BY platform_name';
  $q1 = $pdo1->query($sql1);
  $q1->setFetchMode(PDO::FETCH_ASSOC);
  // PUBLISHER data for dropdown.
  $sql2 = 'SELECT publisherid, publisher_name FROM publishers ORDER BY publisher_name';
  $q2 = $pdo1->query($sql2);
  $q2->setFetchMode(PDO::FETCH_ASSOC);
  // GAME data.
  $sql3 = 'SELECT game_name FROM games G WHERE gameid = ' . $_POST["gameid"];
  $q3 = $pdo1->query($sql3);
  $q3->setFetchMode(PDO::FETCH_OBJ);
  $game = $q3->fetch();
  $title = $title . $game->game_name . '"';
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
                var year = document.forms["insert-form"]["release_year"].value.trim();
                if (year == "") {
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
                    <td>Platform:</td>
                    <?php
                    if ($_POST["operation"] == 'edit') {
                      echo '<td><input type="hidden" id="platform_code" name="platform_code" value="' . $platform_code . '" >' . $platform_code . '</td> ';
                    }
                    else {
                      ?>
                    <td>
                      <select name="platform_code" id="platform_code">
                        <?php while ($row1 = $q1->fetch()) : ?>
                        <option value=
                          <?php
                          echo '"' . $row1['platform_code'] . '"';
                          if ($row1['platform_code'] == $platform_code) {
                            echo ' selected ';
                          } ?>>
                          <?php echo htmlspecialchars($row1['platform_name']) ?>
                        </option>
                        <?php endwhile; ?>
                      </select>
                    </td>
                    <?php } ?>
                  </tr>
                  <tr>
                    <td>Publisher:</td>
                    <td>
                      <select name="publisherid" id="publisherid">
                      <?php while ($row2 = $q2->fetch()) : ?>
                        <option value=
                        <?php
                        echo '"' . $row2['publisherid'] . '"';
                        if ($row2['publisherid'] == $publisherid) {
                          echo ' selected ';
                        } ?>>
                          <?php echo htmlspecialchars($row2['publisher_name']) ?>
                        </option>
                      <?php endwhile; ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Year:</td>
                    <td><input type="text" id="release_year" name="release_year" value="<?php echo $release_year; ?>"></td>
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
                &nbsp;&nbsp;&nbsp;<a href="#" onClick="goBack()" >Go back to Game Platforms List</a>
              </fieldset>
            </form>
            <script>
              function goBack(){
                document.backform.submit();
              }
            </script>
            <form id="backform" name="backform" action="/game_platforms.php" method="post" style="display: inline;">
                <input class="invisible" type="submit" value="link"><input type="hidden" name="gameid" value="<?php echo $_POST["gameid"] ?>">
              </form>
            <br><br>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</html>
