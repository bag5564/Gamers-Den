<?php

/**
 * @file
 * Show list of platforms associated to a game.
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
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // GAME data.
  $sql1 = 'SELECT game_name FROM games G WHERE gameid = ' . $_POST["gameid"];
  $q1 = $pdo->query($sql1);
  $q1->setFetchMode(PDO::FETCH_OBJ);
  $game = $q1->fetch();

  // Platforms associated to given game.
  $sql = 'SELECT GP.gameid, GP.platform_code, platform_name, publisher_name, release_year FROM game_platforms GP, platforms P, publishers PU WHERE GP.platform_code = P.platform_code AND GP.publisherid = PU.publisherid AND GP.gameid = ' . $_POST["gameid"] . ' ORDER BY release_year';
  $q = $pdo->query($sql);
  $q->setFetchMode(PDO::FETCH_ASSOC);
  $noresults = TRUE;
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
            <h2>Platforms available for "<?php echo $game->game_name ?>"</h2>
            <?php
            echo '<form action="/game_platform_form.php" method="post"><input type="submit" value="INSERT A NEW PLATFORM"><input type="hidden" name="operation" value="add"><input type="hidden" name="gameid" value="' . $_POST["gameid"] . '"></form>'; ?>
            <br>
            <table class="list">
              <thead>
                <tr>
                    <th>Platform</th>
                    <th>Publisher</th>
                    <th>Year</th>
                    <th>Edit?</th>
                    <th>Delete?</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $q->fetch()) : ?>
                <tr>
                  <?php $noresults = FALSE; ?>
                  <td><?php echo htmlspecialchars($row['platform_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['publisher_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['release_year']); ?></td>
                  <td><?php echo '<form action="/game_platform_form.php" method="post"><input type="submit" value="EDIT"><input type="hidden" name="operation" value="edit"><input type="hidden" name="gameid" value="' . htmlspecialchars($row['gameid']) . '"><input type="hidden" name="platform_code" value="' . htmlspecialchars($row['platform_code']) . '"></form>'; ?></td>
                  <td>
                    <form action="/game_platform_delete.php" method="post" onsubmit="return confirm('Do you really want to delete this platform?');"><input type="submit" value="DELETE"><input type="hidden" name="gameid" value="<?php echo htmlspecialchars($row['gameid']) ?>"><input type="hidden" name="platform_code" value="<?php echo htmlspecialchars($row['platform_code']) ?> "></form>
                  </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($noresults) : ?>
                <tr>
                  <td colspan="5">There are no platforms recorded for this game</td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
            <br><a href = "games.php" >Back to Games List </a><br>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</html>
