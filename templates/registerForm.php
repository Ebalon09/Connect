<!DOCTYPE html>
<html lang="en">
    <html>
        <head>
            <?php include "head.php" ?>
            <title>TwitterClone Registration</title>
        </head>
        <body class="regForm bg-secondary text-white">
            <?php include "header.php" ?>
            <?php include "alerts.php" ?>
            <div class="container-fluid">
                <form action="./index.php?controller=LoginController&action=registerAction" method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <h1>Registrieren : </h1>

                        <label for="Email" class="sr-only">Email :</label>
                        <input name="Email" class="form-control mr-sm-2" type="text" placeholder="Email" aria-label="Email" required>

                        <label for="Username" class="sr-only">Username :</label>
                        <input name="Username" class="form-control mr-sm-2" type="text" placeholder="Nutzername" aria-label="Username" required>

                        <label for="Password" class="sr-only">Passwort :</label>
                        <input name="Password" class="form-control mr-sm-2" type="password" placeholder="Passwort" aria-label="Password" required>

                        <label for="re-Password" class="sr-only">Passwort erneut eingeben :</label>
                        <input name="re-Password" class="form-control mr-sm-2" type="password" placeholder="Passwort" aria-label="Password" required>

                        <label for="Picture">Profilbild</label>
                        <input id="my_upload" name="my_upload" class="upload" type="file" accept="image/*">
                        <br>
                        <button name="Login" class="btn btn-outline-light my-2 my-sm-3" type="submit">Registrieren</button>
                    </div>
                </form>
            </div>
        <?php include "scripts.php";?>
        </body>
    </html>