<?php

/**
 * @file
 * Start page.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
            <div id="herogame"></div>
            <div id="main-content">
              <h2>Gamers Den is THE Place to Be</h2>
              <p>A one-stop-spot for video game related news, to connect with other people in the video gamer community and share your opinions and recommendations.
              </p>
              <p>Use the navigation bar to the left to browse our listings and submit your reviews.
              </p>
              <p class="instructions">Data maintenance functions are reserved for Administrators.</p>
             </div>
          </main>
          <?php include 'footer.php'; ?>
        </div>
    </body>
</div>
</html>
