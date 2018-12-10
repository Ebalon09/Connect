<header class="header text-white">
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
                <?php if(!isset($_SESSION['userid'])) { ?>
                    <li class="nav-item active" >
                        <a class="nav-link" href = "./index.php?controller=LoginController&action=indexAction" > Register<span class="sr-only" > (current)</span ></a >
                    </li >
                <?php } ?>
                <?php if(isset($_SESSION['userid'])){?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Optionen</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="./index.php?controller=UserActionController&action=settingsIndex">Einstellungen</a>
                            <a class="dropdown-item" href="./index.php?controller=MyPostController&action=indexAction">Meine Posts</a>
                            <a class="dropdown-item" href="#">Fill2</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="./index.php?controller=UserActionController&action=deleteAcc">Account Löschen</a>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <?php if(!isset($_SESSION['userid'])){?>
                <form class="form-inline my-2 my-lg-0" action="./index.php?controller=LoginController&action=loginAction" method="POST">
                    <input name="username" class="form-control mr-sm-2" type="text" placeholder="Nutzername" aria-label="username">
                    <input name="password" class="form-control mr-sm-2" type="password" placeholder="Passwort" aria-label="password">
                    <button name="Login" class="btn btn-outline-light my-2 my-sm-0" type="submit">Login</button>
                </form>
            <?php }else{?>
                <div class="Username">
                    Angemeldet als : <?=$_SESSION['username'] ?>
                </div>
                <form class="form-inline my-2 my-lg-0" action="./index.php?controller=LoginController&action=logoutAction" method="POST">
                    <button name="Logout" class="btn btn-outline-light my-2 my-sm-0" type="submit">Logout</button>
                </form>
            <?php }?>
        </div>
    </nav>
</header>