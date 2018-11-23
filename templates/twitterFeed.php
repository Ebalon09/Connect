<!DOCTYPE html>
<html lang="en">
    <html>
        <head>
            <link rel="icon" href="./favicon.ico">
            <meta charset="UTF-8">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            <link rel="stylesheet" type="text/css" href="./Style/style.css">
            <title>Twitter</title>
        </head>

        <body class="text-white">
            <header class="header">
                <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
                    <a class="navbar-brand">Twitter Clone</a>
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

            <div class="content">
                <?php
                if(isset($_SESSION['userid']) && $_SESSION['userid'])
                {
                    include "templates/" . $form;
                }
                if($_SESSION != null):  ?>
                    <div class="warnung">
                        <?php
                        foreach(Session::getInstance()->readMessage() as $type => $messages) {
                            foreach($messages as $message){ ?>
                                <div class="alert alert-<?= $type; ?>"><?= $message ?></div>
                            <?php    }
                        }
                        ?>
                    </div>
                    <?php
                endif; ?>
                <div class = "container">
                    <div class="main">
                        <?php /** @var Tweet $data */ ?>
                        <?php foreach ($result as $data) { ?>
                            <div class="item">
                                <div class="namecontainer">
                                    <?= $data->getUser()->getUsername()?>
                                </div>
                                <?php
                                if($data->getDestination() != ''){?>
                                <div class="imagecontainer">
                                    <img src=<?=$data->getDestination()?>>
                                </div>
                                <?php } ?>
                                <h5><?= $data->getDatum()->format('d.m.Y H:i:s'); ?></h5>
                                <p><?= $data->getText(); ?></p>
                                <?php if(isset($_SESSION['userid']) && $_SESSION['userid']){?>
                                    <a href="./index.php?controller=TwitterController&action=updateAction&id=<?= $data->getId() ?>" <button id="editButton" class="btn btn-primary" type="Submit" name="action" value="Edit" >Edit</button> </a>
                                    <a href="./index.php?controller=&id=<?= $data->getId() ?>" <button id="reTweetButton" class="btn btn-primary" type="submit" name="action" value="reTweet">reTweet</button> </a>
                                    <a href="./index.php?controller=TwitterController&action=deleteAction&id=<?= $data->getId() ?>" <button id="deleteButton" class="btn btn-danger" type="submit" name="action" value="Delete">Delete</button> </a>
                                <?php if(isset($_SESSION['userid']) && !($data->getUser()->getId() == $_SESSION['userid'])){?>
                                    <?php if($data->isLikedByUser($likes)){ ?>
                                        <a href="./index.php?controller=LikeController&action=dislikeAction&id=<?= $data->getId()?>" <button id="DislikeButton" class="btn btn-secondary" type="submit" name="action" value="Dislike">Dislike</button> </a>
                                    <?php }else{?>
                                        <a href="./index.php?controller=LikeController&action=likeAction&id=<?= $data->getId() ?>" <button id="likeButton" class="btn btn-secondary" type="submit" name="action" value="Like">Like</button> </a>
                                <?php } }} ?>
                                <b><?=$countLikes[$data->getId()]?></b>
                            </div>
                        <?php }?>
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        </body>
    </html>
