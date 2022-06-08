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
  $sql = 'SELECT lname, fname, loginid, admin FROM users ORDER BY lname';
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
            <h2>List of current users</h2>
            <?php
            echo '<form action="/user_form.php" method="post"><input type="submit" value="INSERT A NEW USER"><input type="hidden" name="operation" value="add"></form>'; ?>
            <br>
            <table class="list">
              <thead>
                <tr>
                    <th>Last name</th>
                    <th>First name</th>
                    <th>Login ID</th>
                    <th>Admin</th>
                    <th>Edit?</th>
                    <th>Delete?</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $q->fetch()) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['lname']) ?></td>
                  <td><?php echo htmlspecialchars($row['fname']); ?></td>
                  <td><?php echo htmlspecialchars($row['loginid']); ?></td>
                  <td>
                    <?php
                    if ($row['admin'] == 1) {
                      echo "Yes";
                    }
                    else {
                      echo "No";
                    } ?>
                    </td>
                  <td><?php echo '<form action="/user_form.php" method="post"><input type="submit" value="EDIT"><input type="hidden" name="operation" value="edit"><input type="hidden" name="loginid" value="' . htmlspecialchars($row['loginid']) . '"></form>'; ?></td>
                  <td>
                    <form action="/user_delete.php" method="post" onsubmit="return confirm('Do you really want to delete this user?');"><input type="submit" value="DELETE"><input type="hidden" name="loginid" value="<?php echo htmlspecialchars($row['loginid']) ?>"></form>
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
