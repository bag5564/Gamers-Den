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

$fname = '';
$lname = '';
$loginid = '';
$admin = 0;
$action = "/user_insert.php";
$title = "Insert a New User";

if ($_POST["operation"] == 'edit' && $_POST["loginid"] != "") {
  try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT lname, fname, loginid, admin FROM users WHERE loginid = "' . $_POST["loginid"] . '"';
    $q = $pdo->query($sql);
    $q->setFetchMode(PDO::FETCH_OBJ);
    $result = $q->fetch();
    $fname = $result->fname;
    $lname = $result->lname;
    $loginid = $result->loginid;
    $admin = $result->admin;
    $action = "/user_update.php";
    $title = "Edit User " . $loginid;
  }
  catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
  }
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
                var uid = document.forms["insert-form"]["loginid"].value.trim();
                var fname = document.forms["insert-form"]["fname"].value.trim();
                var lname = document.forms["insert-form"]["lname"].value.trim();
                if (uid == "" || fname == "" || lname == "") {
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
                <table>
                  <tr>
                    <td>First name:</td>
                    <td><input type="text" id="fname" name="fname" value="<?php echo $fname; ?>"></td>
                  </tr>
                  <tr>
                    <td>Last name:</td>
                    <td><input type="text" id="lname" name="lname" value="<?php echo $lname; ?>"></td>
                  </tr>
                  <tr>
                    <td>Login ID:</td>
                    <?php
                    if ($_POST["operation"] == 'edit') {
                      echo '<td><input type="hidden" id="loginid" name="loginid" value="' . $loginid . '" >' . $loginid . '</td> ';
                    }
                    else {
                      echo '<td><input type="text" id="loginid" name="loginid" value="' . $loginid . '" ></td>';
                    }
                    ?>

                  </tr>
                  <tr>
                    <td>Is Administrator?:</td>
                    <td>
                      <label for="Yes">Yes</label>
                      <input type="radio" id="Yes" name="admin" value="1"
                      <?php
                      if ($admin == 1) {
                        echo ' checked="checked"';
                      }
                      ?> >
                      <label for="No">No</label>
                      <input type="radio" id="No" name="admin" value="0"
                      <?php
                      if ($admin == 0) {
                        echo ' checked="checked"';
                      }
                      ?>>
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
                &nbsp;&nbsp;&nbsp;<a href = "users.php" >Go back without saving </a>
              </fieldset>
            </form>
            <br><br>
          </div>
        </main>
        <?php include 'footer.php'; ?>
      </div>
    </body>
</html>
