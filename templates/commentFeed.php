<html>
<head>
    <?php include "head.php" ?>
    <title>Twitter</title>
</head>
    <body>
    <?php include 'header.php'?>
        <div id="commentcontainerfluid" class="container-fluid">
            <?php include "alerts.php" ?>
            <div class = "container">
                <div class="row">
                    <div class="col-sm-3">
                        <?php include "profile.php"?>
                        <?php include "trends.php";?>
                    </div>
                <!--Tweet-->
                <div class="col-sm-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <!--Text Box-->
                            <div class="media">
                                <a class="media-left" href="#fake">
                                    <img id="userpictext" alt="" class="media-object img-rounded" src="<?=$tweet->getUser()->getPicture()?>">
                                </a>
                                <div id="commenttweet" class="media-body">
                                    <?= $tweet->getText(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tweets-->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <!--Text Box-->
                            <div class="media">
                                <a class="media-left" href="#">
                                    <img id="userpictext" alt="" class="media-object img-rounded" src="<?=$user->getPicture()?>">
                                </a>
                                <div class="media-body">
                                    <?php if(isset($update)){?>
                                        <form action="./index.php?controller=CommentController&action=updateAction&idc=<?= $update->getId();?>&c=true&id=<?= $update->getTweet()->getId() ?>" method="POST">
                                            <label class="control-label sr-only" for="Userinput">edit</label>
                                            <input id="Userinput" name="text" type="text" class="form-control" autocomplete="off" value="<?=$update->getComment()?>">
                                        </form>
                                    <?php } else { ?>
                                        <form action="./index.php?controller=CommentController&action=createAction&id=<?= $_GET['id']?>&c=true" method="POST">
                                            <label class="control-label sr-only" for="Userinput">Userinput</label>
                                            <input id="Userinput" name="text" type="text" class="form-control" autocomplete="off">
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <?php /** @var Comment $data */?>
                            <?php foreach($comments as $data) { ?>
                                <div class="media">
                                    <a class="media-left" href="#fake">
                                        <img id="userpicpost" alt="" class="media-object img-rounded" src="<?=$data->getUser()->getPicture()?>">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="media-heading" id="Username"><?=$data->getUser()->getUsername() ?></h4>
                                        <p id="Text"><?=$data->getComment() ?></p>
                                        <ul id="buttons" class="nav nav-pills nav-pills-custom">
                                            <?php if($_SESSION['userid'] == $data->getUser()->getId()){?>
                                            <label for="delete" class="sr-only">delete</label>
                                            <li><a id="delete" href="./index.php?controller=CommentController&action=deleteAction&idc=<?= $data->getId() ?>&c=true&id=<?= $data->getTweet()->getId() ?>" <i class="far fa-trash-alt"></i></button> </a></li>
                                            <label for="edit" class="sr-only">edit</label>
                                            <li><a id="edit" href="./index.php?controller=CommentController&action=updateAction&idc=<?= $data->getId() ?>&c=true&id=<?= $data->getTweet()->getId() ?>" <i class="far fa-edit"></i></button> </a></li>
                                            <?php }?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php include "footPanel.php" ?>
                </div>
                <?php include "rightPanel.php" ?>
            </div>
        </div>
        <?php include "scripts.php" ?>
    </body>
</html>