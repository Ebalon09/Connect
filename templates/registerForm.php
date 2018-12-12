<!DOCTYPE html>
<html lang="en">
    <html>

        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            <link rel="stylesheet" type="text/css" href="./Style/style.css">
            <title>TwitterClone Registration</title>
        </head>

        <body class="regForm bg-secondary text-white">
            <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand">TwitterClone</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="./index.php"><i class="fas fa-home"></i> <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="./registerForm.php">Register<span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0" action="./index.php?controller=LoginController&action=loginAction" method="POST">
                        <input name="username" class="form-control mr-sm-2" type="text" placeholder="nutzername" aria-label="username">
                        <input name="password" class="form-control mr-sm-2" type="password" placeholder="passwort" aria-label="password">
                        <button name="login" class="btn btn-outline-light my-2 my-sm-0" type="submit">Login</button>
                    </form>
                </div>
            </nav>

            <?php if($_SESSION != null):  ?>
                <div class="container">
                    <?php foreach(Test\Services\Session::getInstance()->readMessage() as $type => $messages): ?>
                        <?php foreach($messages as $message): ?>
                            <div class="alert alert-<?= $type; ?>"><?= $message ?></div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

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

            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        </body>
    </html>