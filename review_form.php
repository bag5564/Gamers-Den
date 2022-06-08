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

try {
  // Data for GAME select.
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql1 = 'SELECT gameid, game_name FROM games ORDER BY game_name';
  $q1 = $pdo->query($sql1);
  $q1->setFetchMode(PDO::FETCH_ASSOC);
  // Data for USER select.
  $sql2 = 'SELECT loginid FROM users ORDER BY loginid';
  $q2 = $pdo->query($sql2);
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
                var review = document.forms["insert-form"]["user_review"].value.trim();
                if (review == "") {
                  alert("All fields are required");
                  return false;
                }
                else {
                  return true;
                }
              }
            </script>
            <form name="insert-form" action="/review_insert.php" method="post" onsubmit="return required()">
              <fieldset>
                <h2>Insert a Review</h2>
                <p class="instructions">All fields are required</p>
                <table>
                  <tr>
                    <td>Name:</td>
                    <td>
                      <select name="gameid" id="gameid">
                      <?php while ($row1 = $q1->fetch()) : ?>
                        <option value="<?php echo $row1['gameid'];?>">
                          <?php echo htmlspecialchars($row1['game_name']) ?>
                        </option>
                      <?php endwhile; ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>User:</td>
                    <td>
                      <select name="loginid" id="loginid">
                      <?php while ($row2 = $q2->fetch()) : ?>
                        <option value="<?php echo $row2['loginid'];?>">
                          <?php echo htmlspecialchars($row2['loginid']) ?>
                        </option>
                      <?php endwhile; ?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td>Review:</td>
                    <td>
                      <textarea id="user_review" name="user_review" rows="5" cols="100"></textarea>
                    </td>
                  </tr>
                </table>
                <br/>
                <input type="submit" value="INSERT">
                &nbsp;&nbsp;&nbsp;<a href = "reviews.php" >Go back without saving </a>
              </fieldset>
            </form>
            <br><br>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</html>
