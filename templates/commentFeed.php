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
    <div class="content">
        <?php
        if($_SESSION['userid'] != null):  ?>
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
                    <div id="<?= $tweet->getId()-1?>" class="item">
                        <h5><?= $tweet->getDatum()->format('d.m.Y H:i:s'); ?></h5>
                        <div class="Buttons">
                            <?php if(isset($_SESSION['userid']) && !($tweet->getUser()->getId() == $_SESSION['userid'])){?>
                                <?php if($tweet->isLikedByUser($likes)){ ?>
                                    <a href="./index.php?controller=LikesController&action=dislikeAction&id=<?= $tweet->getId()?>&c=true#<?= $tweet->getId()?>" <button id="DislikeButton" class="btn btn-outline-dark" type="submit" name="action" value="Dislike"><i class="fas fa-heart"></i></button> </a>
                                    <br>
                                <?php }else{?>
                                    <a href="./index.php?controller=LikesController&action=likeAction&id=<?= $tweet->getId() ?>&c=true#<?= $tweet->getId()?>" <button id="likeButton" class="btn btn-outline-dark" type="submit" name="action" value="Like"><i class="far fa-heart"></i></button> </a>
                                    <br>
                                <?php } } ?>
                            <?php if(isset($_SESSION['userid']) && ($tweet->getUser()->getId() == $_SESSION['userid'])){ ?>
                            <a href="./index.php?controller=TwitterController&action=updateAction&id=<?= $tweet->getId() ?>&c=true" <button id="editButton" class="btn btn-outline-dark" type="Submit" name="action" value="Edit" ><i class="far fa-edit"></i></button> </a>
                            <br>
                            <a href="./index.php?controller=TwitterController&action=deleteAction&id=<?= $tweet->getId() ?>&c=true" <button id="deleteButton" class="btn btn-outline-dark" type="submit" name="action" value="Delete"><i class="far fa-trash-alt"></i></button> </a>
                            <br><?php } ?>
                            <a href="./index.php" ><button id="commentButton" class="btn btn-outline-dark" type="submit" name="action" value="Comment"><i class="far fa-comment-alt"></i></button></a>
                            <br>
                        </div>
                        <div class="namecontainer">
                            <?= $tweet->getUser()->getUsername()?>
                        </div>
                        <?php if($tweet->getDestination() != ''){?>
                            <p><?= $tweet->getText(); ?></p>
                            <div class="imagecontainer">
                                <img src=<?=$tweet->getDestination()?>>
                            </div>
                        <?php } ?>
                        <?php if($tweet->getLinkID() != null){?>
                            <div class="ytcontainer">
                                <iframe id="ytplayer" width="560" height="315" src="https://www.youtube.com/embed/<?= $tweet->getLinkID()?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        <?php } include './templates/commentForm.php';?>
                        <?php if(isset($_SESSION['userid']) && $_SESSION['userid']){?>
                            <p></p>
                            <h6><b><?="Likes : ".$countLikes[$tweet->getId()]?></b></h6>
                        <?php } ?>
                    </div>
            </div>
        </div>
        <h3 align="center">Kommentare :</h3>
        <div class="comments">
            <?php if($_SESSION['userid'] != null):  ?>
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
                    <?php /** @var comment $data */ ?>
                    <?php foreach ($result as $data) { ?>
                        <?php if($data->getTweet()->getId() === $_GET['id']){ ?>
                        <div id="<?= $data->getId()-1?>" class="item">
                            <div class="namecontainer">
                                <?= $data->getUser()->getUsername()?>
                            </div>
                            <div class="buttons">
                                <?php if(isset($_SESSION['userid']) && ($data->getUser()->getId() == $_SESSION['userid'])){ ?>
                                    <a href="./index.php?controller=CommentController&action=indexAction&id=<?= $data->getTweet()->getId() ?>&idc=<?=$data->getId()?>&c=true" <button id="editButton" class="btn btn-outline-dark" type="Submit" name="action" value="Edit" ><i class="far fa-edit"></i></button> </a>
                                    <?php } ?>
                                <br>
                                <?php if(isset($_SESSION['userid']) && ($data->getUser()->getId() == $_SESSION['userid'])){ ?>
                                    <a href="./index.php?controller=CommentController&action=deleteAction&id=<?= $data->getTweet()->getId() ?>&idc=<?=$data->getId()?>&c=true" <button id="deleteButton" class="btn btn-outline-dark" type="submit" name="action" value="Delete"><i class="far fa-trash-alt"></i></button> </a>
                                    <?php } ?>
                                <br>
                            </div>
                            <p><?= $data->getComment()?></p>
                        </div>
                    <?php } }?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>