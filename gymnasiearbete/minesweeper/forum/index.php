<?php
require_once 'assets/functions.php';
require_once 'assets/userdbhs.php';

session_start(); 

if (!isset($_SESSION['userid'])) {
    echo "Du måste vara inloggad för att komma åt den här sidan.";
    exit;
}

$username = $_SESSION['userid'];

$records = getForumPosts();
if ($_SESSION['is_admin'] == true) {
    echo "<h1><a href='admin/admin.php'>Admin</a></h1>";
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Forum</title>
    <link href="css/forum.css" rel="stylesheet" type="text/css">
</head>
<body>
  <h3><a href="profil.php">Profil</a></h3>
  <h3><a href="search.php">Sökning av användare</a></h3>
  <h3><a href="lsearch.php">Sökning av poster</a></h3>

  <form name="nypost" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <table>
          <tr>
              <td>Meddelande</td>
              <td>
                  <textarea class="textFiled" name="msg" rows="8" id="msg"></textarea>
              </td>
          </tr>
          <tr>
              <td colspan="2">
                  <input type="submit" name="skicka" id="skicka" value="Skicka">
              </td>
              <input type="submit" name="logga_ut" id="logga_ut" value="Logga ut">
          </tr>
      </table>
      <input type="hidden" name="MM_insert" value="nypost">
  </form>

  <table id="dbres">
      <tr>
          <th>Användare</th>
          <th>Meddelande</th>
          <th>Datum</th>
          <th>Länk till inlägg</th>
      </tr>
      <?php if (!empty($records)): ?>
          <?php foreach ($records as $row_Recordset1): ?>
              <tr>
                  <td>
                      <?php echo htmlspecialchars($row_Recordset1['username']); ?>
                      <!-- Link to search page for user's posts -->
                      
                  </td>
                  <td><?php echo nl2br(htmlspecialchars_decode($row_Recordset1['msg'])); ?></td>
                  <td><?php echo htmlspecialchars($row_Recordset1['tid']); ?></td>
                  <td><a href="lsearch.php?user=<?php echo urlencode($row_Recordset1['username']); ?>">Visa inlägg</a></td>
              </tr>
          <?php endforeach; ?>
      <?php else: ?>
          <tr>
              <td colspan="3">Inga meddelanden att visa.</td>
          </tr>
      <?php endif; ?>
  </table>
</body>
</html>