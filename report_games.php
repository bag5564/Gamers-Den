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
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // GENRE data for dropdown.
  $sql1 = 'SELECT genreid, genre_name FROM genres ORDER BY genre_name';
  $q1 = $pdo->query($sql1);
  $q1->setFetchMode(PDO::FETCH_ASSOC);
  // RATING data for dropdown.
  $sql2 = 'SELECT rating_code, rating_description FROM ratings ORDER BY rating_code';
  $q2 = $pdo->query($sql2);
  $q2->setFetchMode(PDO::FETCH_ASSOC);
  // PLATFORM data for dropdown.
  $sql3 = 'SELECT platform_code, platform_name FROM platforms ORDER BY platform_name';
  $q3 = $pdo->query($sql3);
  $q3->setFetchMode(PDO::FETCH_ASSOC);
  // PUBLISHER data for dropdown.
  $sql4 = 'SELECT publisherid, publisher_name FROM publishers ORDER BY publisher_name';
  $q4 = $pdo->query($sql4);
  $q4->setFetchMode(PDO::FETCH_ASSOC);
  // Data for YEAR select box.
  $sql5 = 'SELECT distinct release_year FROM game_platforms ORDER BY release_year';
  $q5 = $pdo->query($sql5);
  $q5->setFetchMode(PDO::FETCH_ASSOC);

  // Main SELECT query to generate report.
  $sql = 'SELECT G.gameid, G.game_name, GE.genre_name, G.rating_code, P.platform_name, PU.publisher_name, GP.release_year FROM games G INNER JOIN genres GE ON G.genreid = GE.genreid LEFT JOIN game_platforms GP ON G.gameid = GP.gameid LEFT JOIN platforms P ON GP.platform_code = P.platform_code LEFT JOIN publishers PU ON GP.publisherid = PU.publisherid';
  // Add FILTER criteria.
  $criteria = '';
  $display_criteria = '';
  $where = ' WHERE';
  $sep = ' ';
  if (isset($_POST["vname"]) && trim($_POST["vname"]) != '') {
    $criteria = $where . ' game_name LIKE "%' . trim($_POST["vname"]) . '%"';
    $where = ' AND';
    $display_criteria = $sep . ' NAME contains "' . $_POST["vname"] . '"';
    $sep = ' - ';
  }
  if (isset($_POST["genre"]) && trim($_POST["genre"]) != '') {
    $criteria = $criteria . $where . ' GE.genre_name = "' . $_POST["genre"] . '"';
    $where = ' AND';
    $display_criteria = $display_criteria . $sep . ' GENRE equals "' . $_POST["genre"] . '"';
    $sep = ' - ';
  }
  if (isset($_POST["rating"]) && trim($_POST["rating"]) != '') {
    $criteria = $criteria . $where . ' G.rating_code = "' . $_POST["rating"] . '"';
    $where = ' AND';
    $display_criteria = $display_criteria . $sep . ' RATING equals "' . $_POST["rating"] . '"';
    $sep = ' - ';
  }
  if (isset($_POST["platform"]) && trim($_POST["platform"]) != '') {
    $criteria = $criteria . $where . ' GP.platform_code = "' . $_POST["platform"] . '"';
    $where = ' AND';
    $display_criteria = $display_criteria . $sep . ' PLATFORM equals "' . $_POST["platform"] . '"';
    $sep = ' - ';
  }
  if (isset($_POST["publisher"]) && trim($_POST["publisher"]) != '') {
    $criteria = $criteria . $where . ' PU.publisher_name = "' . $_POST["publisher"] . '"';
    $where = ' AND';
    $display_criteria = $display_criteria . $sep . ' PUBLISHER equals "' . $_POST["publisher"] . '"';
    $sep = ' - ';
  }
  if (isset($_POST["year"]) && trim($_POST["year"]) != '') {
    $criteria = $criteria . $where . ' GP.release_year = ' . $_POST["year"];
    $where = ' AND';
    $display_criteria = $display_criteria . $sep . ' YEAR equals ' . $_POST["year"];
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
  if (isset($_POST["sgenre"]) && trim($_POST["sgenre"]) != '') {
    $criteria = $criteria . $by . ' GE.genre_name ' . $_POST["ogenre"];
    $by = ', ';
    $order_criteria = $order_criteria . $sep . ' GENRE ' . $_POST["ogenre"];
    $sep = ', ';
  }
  if (isset($_POST["srating"]) && trim($_POST["srating"]) != '') {
    $criteria = $criteria . $by . ' G.rating_code ' . $_POST["orating"];
    $by = ', ';
    $order_criteria = $order_criteria . $sep . ' RATING ' . $_POST["orating"];
    $sep = ', ';
  }
  if (isset($_POST["splatform"]) && trim($_POST["splatform"]) != '') {
    $criteria = $criteria . $by . ' P.platform_name ' . $_POST["oplatform"];
    $by = ', ';
    $order_criteria = $order_criteria . $sep . ' PLATFORM ' . $_POST["oplatform"];
    $sep = ', ';
  }
  if (isset($_POST["spublisher"]) && trim($_POST["spublisher"]) != '') {
    $criteria = $criteria . $by . ' PU.publisher_name ' . $_POST["opublisher"];
    $by = ', ';
    $order_criteria = $order_criteria . $sep . ' PUBLISHER ' . $_POST["opublisher"];
    $sep = ', ';
  }
  if (isset($_POST["syear"]) && trim($_POST["syear"]) != '') {
    $criteria = $criteria . $by . ' GP.release_year ' . $_POST["oyear"];
    $by = ', ';
    $order_criteria = $order_criteria . $sep . ' YEAR ' . $_POST["oyear"];
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
            <h2>Search our video game database</h2>
            <!-- FILTER and SORT selection form  -->
            <form action="/report_games.php" method="post">
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
                        <td>Genre:</td>
                        <td>
                          <select name="genre" id="genre">
                            <option value=""></option>
                            <?php while ($row1 = $q1->fetch()) :
                              echo '<option value="' . $row1['genre_name'] . '">' . $row1['genre_name'] . '</option>';
                            endwhile;
                            ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Rating:</td>
                        <td>
                          <select name="rating" id="rating">
                            <option value=""></option>
                            <?php while ($row2 = $q2->fetch()) :
                              echo '<option value="' . $row2['rating_code'] . '">' . $row2['rating_code'] . ': ' . $row2['rating_description'] . '</option>';
                            endwhile;
                            ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Platform:</td>
                        <td>
                          <select name="platform" id="platform">
                            <option value=""></option>
                            <?php while ($row3 = $q3->fetch()) :
                              echo '<option value="' . $row3['platform_code'] . '">' . $row3['platform_code'] . ': ' . $row3['platform_name'] . '</option>';
                            endwhile;
                            ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Publisher:</td>
                        <td>
                          <select name="publisher" id="publisher">
                            <option value=""></option>
                            <?php while ($row4 = $q4->fetch()) :
                              echo '<option value="' . $row4['publisher_name'] . '">' . $row4['publisher_name'] . '</option>';
                            endwhile;
                            ?>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td>Year:</td>
                        <td>
                          <select name="year" id="year">
                            <option value=""></option>
                            <?php while ($row5 = $q5->fetch()) :
                              echo '<option value="' . $row5['release_year'] . '">' . $row5['release_year'] . '</option>';
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
                          <input type="checkbox" id="sgenre" name="sgenre" value="1">
                          <span style="width: 90px; display: inline-block;">Genre</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="ogenre" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="ogenre" value="DESC">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="checkbox" id="srating" name="srating" value="1">
                          <span style="width: 90px; display: inline-block;">Rating</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="orating" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="orating" value="DESC">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="checkbox" id="splatform" name="splatform" value="1">
                          <span style="width: 90px; display: inline-block;">Platform</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="oplatform" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="oplatform" value="DESC">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="checkbox" id="spublisher" name="spublisher" value="1">
                          <span style="width: 90px; display: inline-block;">Publisher</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="opublisher" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="opublisher" value="DESC">
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <input type="checkbox" id="syear" name="syear" value="1">
                          <span style="width: 90px; display: inline-block;">Year</span>&nbsp;&nbsp;&nbsp;&nbsp;
                          <span>Asc</span>
                          <input type="radio" name="oyear" value="ASC" checked>
                          <span>Desc</span>
                          <input type="radio" name="oyear" value="DESC">
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <div style="margin-top: 10px; margin-left: 250px;">
                <input type="submit" value="SEARCH & SORT">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="report_games.php">Reset List</a>
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
                    <th style="min-width: 350px;">Name</th>
                    <th>Genre</th>
                    <th>Rating</th>
                    <th>Platform</th>
                    <th>Publisher</th>
                    <th>Year</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $q->fetch()) : ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['game_name']) ?></td>
                  <td><?php echo htmlspecialchars($row['genre_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['rating_code']); ?></td>
                  <td><?php echo htmlspecialchars($row['platform_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['publisher_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['release_year']); ?></td>
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
