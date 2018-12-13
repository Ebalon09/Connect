<!DOCTYPE html>
<html lang="en">
    <html>
        <head>
            <?php include "head.php" ?>
            <title>Twitter</title>
        </head>
        <body>
        <?php include 'header.php'?>
            <div class="container-fluid">
                <div class="container">
                    <?php include "alerts.php" ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <!--User-->
                            <?php include 'profile.php' ?>
                            <!--trends-->
                            <?php include 'trends.php' ?>
                        </div>
                        <!-- Tweets-->
                        <div class="col-sm-6">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <!--Text Box-->
                                    <div class="media">
                                        <a class="media-left" href="#fake">
                                            <img id="userpictext" alt="" class="media-object img-rounded" src="<?=$user->getPicture()?>">
                                        </a>
                                        <div class="media-body">
                                            <?php if(isset($_SESSION['userid'])){?>
                                            <?php if(isset($reTweet)){?>
                                            <form action="./index.php?controller=TwitterController&action=reTweetAction&id=<?= $reTweet->getId();?>" method="POST">
                                                <label for="Userinput" class="control-label sr-only">reTweet</label>
                                                <input id="Userinput" name="text" type="text" class="form-control" autocomplete="off" placeholder="ReTweet Text eingeben">
                                            </form>
                                            <?php } elseif(isset($update)){?>
                                                <form action="./index.php?controller=TwitterController&action=updateAction&id=<?= $update->getId();?>" method="POST">
                                                    <label for="Userinput" class="control-label sr-only">Edit</label>
                                                    <input id="Userinput" name="text" type="text" class="form-control" autocomplete="off" value="<?=$update->getText()?>">
                                                </form>
                                            <?php } else { ?>
                                                <form action="./index.php?controller=TwitterController&action=createAction" method="POST">
                                                    <label for="Userinput" class="control-label sr-only">Post</label>
                                                    <input id="Userinput" name="text" type="text" class="form-control" autocomplete="off">
                                                </form>
                                            <?php } ?>
                                            <?php } else{?>
                                            <form action="./index.php?controller=TwitterController&action=createAction" method="POST">
                                                <label for="Userinput" class="control-label sr-only">Post</label>
                                                <input id="Userinput" name="text" type="text" class="form-control" autocomplete="off" disabled>
                                            </form>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <!--here foreach with user posts,names,profile pics and buttons-->
                                    <label for="Text" class="sr-only">Posts</label>
                                    <?php /** @var Tweet $data */?>
                                    <?php foreach($result as $data) { ?>
                                    <div class="media">
                                        <a class="media-left" href="#fake">
                                            <img id="userpicpost" alt="" class="media-object img-rounded" src="<?=$data->getUser()->getPicture()?>">
                                        </a>
                                        <div class="media-body">
                                            <h4 class="media-heading" id="Username"><?=$data->getUser()->getUsername() ?></h4>
                                            <p id="Text"><?=$data->getText() ?></p>

                                            <?php if($data->getReTweet() !== null){ ?>

                                            <div class="media-body">
                                                <h4 class="media-heading" id="Username"><?=$data->getReTweet()->getUser()->getUsername() ?></h4>
                                                <p id="Text"><?=$data->getReTweet()->getText() ?></p>
                                            </div>
                                            <?php } ?>


                                            <?php if(isset($_SESSION['userid'])){?>
                                            <ul id="buttons" class="nav nav-pills nav-pills-custom">
                                                <label for="comment" class="sr-only">comment</label>
                                                <li><a id="comment" href="./index.php?controller=CommentController&action=indexAction&id=<?= $data->getId()?>&idc=<?=$data->getId()?>&c=true" ><i class="far fa-comment-alt"></i></button></a></li>
                                                <label for="commentCounter" class="sr-only">commentCounter</label>
                                                <li><a id="commentCounter" href="./index.php?controller=CommentController&action=commentFeed&id=<?= $data->getId()?>&c=true"><?= $countcomments[$data->getId()]?></li>
                                                <label for="reTweet" class="sr-only">ReTweet</label>
                                                <li><a id="reTweet" href="./index.php?controller=TwitterController&action=reTweetAction&id=<?= $data->getId() ?>&idc=<?=$data->getId()?>" <i class="fas fa-retweet"></i></button> </a></li>

                                                <?php if($_SESSION['userid'] == $data->getUser()->getId()){?>
                                                <label for="delete" class="sr-only">delete</label>
                                                <li><a id="delete" href="./index.php?controller=TwitterController&action=deleteAction&id=<?= $data->getId() ?>" <i class="far fa-trash-alt"></i></button> </a></li>
                                                <label for="edit" class="sr-only">edit</label>
                                                <li><a id="edit" href="./index.php?controller=TwitterController&action=updateAction&id=<?= $data->getId() ?>" <i class="far fa-edit"></i></button> </a></li>
                                                <?php } }?>

                                                <?php if(isset($_GET['c'])){?>
                                                    <label for="commentinput" class="sr-only">CommentInput</label>
                                                    <form action="./index.php?controller=CommentController&action=createAction&id=<?= $data->getId() ?>" method="POST">
                                                        <input id="commentinput" name="text" type="text" class="form-control" autocomplete="off">
                                                    </form>
                                                <?php } ?>
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