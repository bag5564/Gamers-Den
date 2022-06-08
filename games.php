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
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $sql = 'SELECT gameid, game_name, genre_name, rating_code, (SELECT count(*) FROM game_platforms GP WHERE GP.gameid = G.gameid) as tot FROM games G, genres GE WHERE G.genreid = GE.genreid ORDER BY game_name';
  if (isset($_POST["vname"])) {
    $sql = 'SELECT gameid, game_name, genre_name, rating_code, (SELECT count(*) FROM game_platforms GP WHERE GP.gameid = G.gameid) as tot  FROM games G, genres GE WHERE G.genreid = GE.genreid AND game_name LIKE "%' . $_POST["vname"] . '%" ORDER BY game_name';
  }
  $q = $pdo->query($sql);
  $q->setFetchMode(PDO::FETCH_ASSOC);
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
            <h2>List of video games</h2>
            <form action="/game_form.php" method="post" style="display: inline;"><input type="submit" value="INSERT A NEW GAME"><input type="hidden" name="operation" value="add"></form> &nbsp;&nbsp;&nbsp;
            <form action="/games.php" method="post" style="display: inline; margin-left: 320px;">
              <input type="text" id="vname" name="vname">
              <input type="submit" value="SEARCH BY NAME">
            </form>&nbsp;&nbsp;
            <form action="/games.php" method="post" style="display: inline;">
              <input type="submit" value="SEE FULL LIST">
            </form>
            <br><br>
            <table class="list" style="min-width: 60%">
              <thead>
                <tr>
                    <th style="min-width: 450px;">Name</th>
                    <th>Genre</th>
                    <th>Rating</th>
                    <th>Platforms</th>
                    <th>Manage Platforms?</th>
                    <th>Edit?</th>
                    <th>Delete?</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $q->fetch()) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['game_name']) ?></td>
                  <td><?php echo htmlspecialchars($row['genre_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['rating_code']); ?></td>
                  <td><?php echo $row['tot']; ?></td>
                  <td><?php echo '<form action="/game_platforms.php" method="post" style="display: inline;"><input type="submit" value="VIEW / MANAGE"><input type="hidden" name="gameid" value="' . htmlspecialchars($row['gameid']) . '"></form>'; ?></td>
                  <td><?php echo '<form action="/game_form.php" method="post"><input type="submit" value="EDIT"><input type="hidden" name="operation" value="edit"><input type="hidden" name="gameid" value="' . htmlspecialchars($row['gameid']) . '"></form>'; ?></td>
                  <td>
                    <form action="/game_delete.php" method="post" onsubmit="return confirm('Do you really want to delete this game?');"><input type="submit" value="DELETE"><input type="hidden" name="gameid" value="<?php echo htmlspecialchars($row['gameid']) ?>"></form>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            <br><br>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</html>
