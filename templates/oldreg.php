<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <?php include "head.php" ?>
    <title>TwitterClone Registration</title>
</head>
<body class="regForm bg-secondary text-white">
<?php include "header.php" ?>
<div class="container-fluid">
    <form action="./index.php?controller=LoginController&action=registerAction" method="POST" enctype="multipart/form-data">
        <div class="container">
            <?php include "alerts.php" ?>
            <h1>Registrieren : </h1>
            <div class="form-group">
                <label for="regEmail" class="sr-only">Email :</label>
                <input name="regEmail" class="form-control mr-sm-2" type="text" placeholder="Email" aria-label="Email" required>
            </div>
            <div class="form-group">
                <label for="regUsername" class="sr-only">Username :</label>
                <input name="regUsername" class="form-control mr-sm-2" type="text" placeholder="Nutzername" required>
            </div>
            <div class="form-group">
                <label for="regPassword" class="sr-only">Passwort :</label>
                <input name="regPassword" class="form-control mr-sm-2" type="password" placeholder="Passwort" aria-label="Password" required>
            </div>
            <div class="form-group">
                <label for="regre-Password" class="sr-only">Passwort erneut eingeben :</label>
                <input name="regre-Password" class="form-control mr-sm-2" type="password" placeholder="Passwort" aria-label="Password" required>
            </div>
            <div class="form-group">
                <label for="Picture" class="sr-only">Profilbild</label>
                <input id="my_upload" name="my_upload" class="upload" type="file" accept="image/*">
                <br>
                <button name="Login" class="btn btn-outline-light my-2 my-sm-3" type="submit">Registrieren</button>
            </div>
    </form>
</div>
<?php include "scripts.php";?>
</body>
</html>