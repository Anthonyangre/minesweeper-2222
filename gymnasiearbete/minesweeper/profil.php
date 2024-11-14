<?php 
require_once '../dbhs.php';
require_once '../assets/uppgifter.php';

?>
    <?php
    // Display errors if there are any
    if (!empty($errors)) {
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
    }
    ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
</head>
<body>
    <h3><a href="andra.php">Forum Posts</a></h3>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <fieldset>
     <legend>Redigera dina användar uppgifter</legend>
        <ol>
            <li>
           
            <h4>Användarnamn: <?php echo htmlspecialchars(string: $username); ?></h4>
            
              
              
            </li>
            <li> 
                <label for="email3">Email</label>
            <input type="mail" id="email3" name="email3" value="<?php echo htmlspecialchars($email); ?>" >
        </li>
           
            <li>
            <label for="name3">Name</label>
            <input type="text" id="name3" name="name3" value="<?php echo htmlspecialchars($name); ?>" ></li>
            
            <li>
             <label for="password3">Lösenord</label>
             <input type="password" id="password3" name="password3" placeholder="•••••••••" >
            
             
            </li>
        </ol>
  </fieldset>
    <input type="submit" name="Ändra" value="Ändra" class="button1">
    </form>
</body>
</html>