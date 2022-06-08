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
  // Data for REVIEW DATE select box.
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql1 = 'SELECT distinct review_date FROM game_reviews ORDER BY review_date';
  $q1 = $pdo->query($sql1);
  $q1->setFetchMode(PDO::FETCH_ASSOC);
  // Data for USER select box.
  $sql2 = 'SELECT distinct loginid FROM game_reviews ORDER BY loginid';
  $q2 = $pdo->query($sql2);
  $q2->setFetchMode(PDO::FETCH_ASSOC);
  // Main SELECT query to generate report.
  $sql = 'SELECT G.gameid, game_name, genre_name, rating_code, user_review, loginid, review_date FROM games G, genres GE, game_reviews GR WHERE G.genreid = GE.genreid and G.gameid = GR.gameid';
  // Add FILTER criteria.
  $criteria = '';
  $display_criteria = '';
  $sep = ' ';
  if (isset($_POST["vname"]) && trim($_POST["vname"]) != '') {
    $criteria = ' AND game_name LIKE "%' . trim($_POST["vname"]) . '%"';
    $display_criteria = $sep . ' NAME contains "' . $_POST["vname"] . '"';
    $sep = ' - ';
  }
  if (isset($_POST["user"]) && trim($_POST["user"]) != '') {
    $criteria = $criteria . ' AND loginid = "' . $_POST["user"] . '"';
    $display_criteria = $display_criteria . $sep . ' USER equals "' . $_POST["user"] . '"';
    $sep = ' - ';
  }
  if (isset($_POST["date"]) && trim($_POST["date"]) != '') {
    $criteria = $criteria . ' AND review_date = "' . $_POST["date"] . '"';
    $display_criteria = $display_criteria . $sep . ' DATE equals "' . $_POST["date"] . '"';
    $sep = ' - ';
  }
  if ($criteria != '') {
    $sql = $sql . $criteria;
  }
  // Add SORT criteria.
  $criteria = '';
  $order_criteria = '';
  $sep = ' ';
  $by = ' ORDER BY';
  if (isset($_POST["sname"]) && trim($_POST["sname"]) != '') {
    $criteria = $by . ' game_name ' . $_POST["oname"];
    $by = ', ';
    $order_criteria = $sep . ' NAME ' . $_POST["oname"];
    $sep = ', ';
  }
  if (isset($_POST["suser"]) && trim($_POST["suser"]) != '') {
    $criteria = $criteria . $by . ' loginid ' . $_POST["ouser"];
    $by = ', ';
    $order_criteria = $order_criteria . $sep . ' USER ' . $_POST["ouser"];
    $sep = ', ';
  }
  if (isset($_POST["sdate"]) && trim($_POST["sdate"]) != '') {
    $criteria = $criteria . $by . ' review_date ' . $_POST["odate"];
    $by = ', ';
    $order_criteria = $order_criteria . $sep . ' DATE ' . $_POST["odate"];
    $sep = ', ';
  }
  if ($criteria != '') {
    $sql = $sql . $criteria;
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
            <h2>Game Reviews</h2>
            <!-- ADD reviews -->
            <form action="/review_form.php" method="post" style="display: inline;"><input type="submit" value="ADD A REVIEW"></form> &nbsp;&nbsp;&nbsp;
            <!-- FILTER and SORT selection form  -->
            <form action="/reviews.php" method="post">
              <fieldset style="width: 80%; padding: 10px;">
              <table style="width: 100%;">
                <tr>
                  <td style="min-width: 50%;">
                    <p style="font-weight: bold;">Filter by</p>
                    <p style="font-size: 0.8em;">Allows for partial matches</p>
                    <table>
                      <tr>
                        <td>Name:</td>
                        <td><input type="text" id="vname" name="vname"></td>
                      </tr>
                      <tr>
                        <td>User:</td>
                        <td>
                          <select name="user" id="user">
                            <option value=""></option>
                            <?php while ($row2 = $q2->fetch()) :
                              echo '<option value="' . $row2['loginid'] . '">' . $row2['loginid'] . '</option>';
                            endwhile;
                            ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Date:</td>
                        <td>
                          <select name="date" id="date">
                            <option value=""></option>
                            <?php while ($row1 = $q1->fetch()) :
                              echo '<option value="' . $row1['review_date'] . '">' . $row1['review_date'] . '</option>';
                            endwhile;
                            ?>
                          </select>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    <p style="font-weight: bold;">Sort by</p>
                    <p style="font-size: 0.8em;">Check items to sort by (criteria is applied from top to bottom)</p>
                    <table>
                      <tr>
                        <td>
                          <input type="checkbox" id="sname" name="sname" value="1">
                          <span style="width: 90px; display: inline-block;">Name</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="oname" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="oname" value="DESC">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="checkbox" id="suser" name="suser" value="1">
                          <span style="width: 90px; display: inline-block;">User</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="ouser" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="ouser" value="DESC">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="checkbox" id="sdate" name="sdate" value="1">
                          <span style="width: 90px; display: inline-block;">Date</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="odate" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="odate" value="DESC">
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <div style="margin-top: 10px; margin-left: 250px;">
                <input type="submit" value="SEARCH & SORT">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="reviews.php">Reset List</a>
              </div>
              </fieldset>
            </form>
            <br>
            <!--  MAIN REPORT AREA -->
            <table class="list" style="min-width: 60%">
              <?php
              if ($display_criteria != '' || $order_criteria != '') {
                echo '<caption style="text-align: left;">';
              }
              if ($display_criteria != '') {
                echo '<span style="font-weight: bold;">Search criteria :</span> ' . $display_criteria . '<br/>';
              }
              if ($order_criteria != '') {
                echo '<span style="font-weight: bold;">Sorted by :</span> ' . $order_criteria;
              }
              if ($display_criteria != '' || $order_criteria != '') {
                echo '</caption>';
              }
              ?>
              <thead>
                <tr>
                    <th>Name</th>
                    <th>Genre</th>
                    <th>Rating</th>
                    <th style="min-width: 40%">Reviews</th>
                    <th>User</th>
                    <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $q->fetch()) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['game_name']) ?></td>
                  <td><?php echo htmlspecialchars($row['genre_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['rating_code']); ?></td>
                  <td><?php echo htmlspecialchars($row['user_review']); ?></td>
                  <td><?php echo htmlspecialchars($row['loginid']); ?></td>
                  <td><?php echo htmlspecialchars($row['review_date']); ?></td>
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
