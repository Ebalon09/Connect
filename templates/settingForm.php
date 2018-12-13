<!DOCTYPE html>
<html lang="en">
    <html>
        <head>
            <?php include "head.php" ?>
            <title>Einstellungen</title>
        </head>

        <body class="regForm bg-secondary text-white">
            <?php include "header.php" ?>
            <?php include "alerts.php" ?>
            <div class="container-fluid" align="center">
                <h2>Benutzerdaten Ändern : </h2>

                    <form action="./index.php?controller=UserActionController&action=changeAction" method="POST">
                        <label for="email">Email :</label>
                        <input name="email" class="form-control mr-sm-2" type="text" value="<?=$_SESSION['email']?>" aria-label="Email" required>
                        <button name="changeMail" class="btn btn-outline-light my-2 my-sm-3" type="submit">Email ändern</button>
                    </form>

                    <form action="./index.php?controller=UserActionController&action=changeAction" method="POST">
                        <label for="username">Username :</label>
                        <input name="username" class="form-control mr-sm-2" type="text" value="<?=$_SESSION['username']?>" aria-label="username" required>
                        <button name="change" class="btn btn-outline-light my-2 my-sm-3" type="submit">Nutzernamen ändern</button>
                    </form>

                    <form action="./index.php?controller=UserActionController&action=changeAction" method="POST">
                        <label for="password">password</label>
                        <input name="password" class="form-control mr-sm-2" type="password" placeholder="Passwort" aria-label="password" required>
                        <label for="re-password">re-Password</label>
                        <input name="re-password" class="form-control mr-sm-2" type="password" placeholder="Passwort erneut eingeben" aria-label="password" required>
                        <button name="changePW" class="btn btn-outline-light my-2 my-sm-3" type="submit">Passwort ändern</button>
                    </form>

                    <form action="./index.php?controller=UserActionController&action=changeAction" method="POST" enctype="multipart/form-data">
                        <input id="my_upload" name="my_upload" class="upload" type="file" accept="image/*">

                        <button name="Picture" class="btn btn-outline-light my-2 my-sm-3" type="submit">Bild Hochladen</button>
                    </form>

                    <form action="./index.php?controller=UserActionController&action=finished">
                        <button name="change" class="btn btn-outline-light my-2 my-sm-3" type="submit">Fertig</button>
                    </form>
            </div>
        <?php include "scripts.php" ?>
        </body>
    </html>