<!DOCTYPE html>
<html lang="en">
    <html>
        <head>
            <link rel="icon" href="./favicon.ico">
            <meta charset="UTF-8">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            <link rel="stylesheet" type="text/css" href="./Style/TwitterStyle.css"
            <title>Twitter</title>
        </head>

        <body>
            <?php include 'header.php'?>

            <div class="container-fluid">

                <?php if($_SESSION['userid'] != null):?>
                <div class="warnung">
                    <?php
                    foreach(Test\Services\Session::getInstance()->readMessage() as $type => $messages) {
                        foreach($messages as $message){ ?>
                            <div class="alert alert-<?= $type; ?>"><?= $message ?></div>
                        <?php    }
                    }
                    ?>
                </div>
                <?php
                endif; ?>

                <div class="container">

                    <div class="row">
                        <div class="col-sm-3">

                            <!--User-->
                            <!--Show logged in User, with profile pic and data-->
                            <?php if(isset($_SESSION['userid'])){?>
                            <div class="panel panel-default">
                                <div    class="panel-body">
                                    <a href="#"><img class="img-responsive" alt="" src="<?= $user->getPicture()?>"></a>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <h5>
                                                <small>TWEETS</small>
                                                <a href="#">1,545</a>
                                            </h5>
                                        </div>
                                        <div class="col-xs-4">
                                            <h5>
                                                <small>FOLLOWING</small>
                                                <a href="#">251</a>
                                            </h5>
                                        </div>
                                        <div class="col-xs-5">
                                            <h5>
                                                <small>FOLLOWERS</small>
                                                <a href="#">153</a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <!--trends-->
                            <div class="panel panel-default panel-custom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        Trends
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <ul class="list-unstyled">
                                        <!--foreach trend new list element(if implemented)-->
                                        <li><a href="#">#hashtag trends filler</a></li>
                                        <li><a href="#">#hashtag trends filler 2</a></li>
                                    </ul>
                                </div>
                            </div>
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



                                            <ul id="buttons" class="nav nav-pills nav-pills-custom">
                                                <label for="comment" class="sr-only">comment</label>
                                                <li><a id="comment" href="./index.php?controller=CommentController&action=indexAction&id=<?= $data->getId()?>&idc=<?=$data->getId()?>&c=true" ><i class="far fa-comment-alt"></i></button></a></li>
                                                <li><a href="./index.php?controller=CommentController&action=commentFeed&id=<?= $data->getId()?>&c=true"><?= $countcomments[$data->getId()]?></li>
                                                <label for="reTweet" class="sr-only">ReTweet</label>
                                                <li><a id="reTweet" href="./index.php?controller=TwitterController&action=reTweetAction&id=<?= $data->getId() ?>&idc=<?=$data->getId()?>" <i class="fas fa-retweet"></i></button> </a></li>
                                                <label for="delete" class="sr-only">delete</label>
                                                <li><a id="delete" href="./index.php?controller=TwitterController&action=deleteAction&id=<?= $data->getId() ?>" <i class="far fa-trash-alt"></i></button> </a></li>
                                                <label for="edit" class="sr-only">edit</label>
                                                <li><a id="edit" href="./index.php?controller=TwitterController&action=updateAction&id=<?= $data->getId() ?>" <i class="far fa-edit"></i></button> </a></li>


                                                <?php if(isset($_GET['c'])){?>
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


                            <!-- Foot panel-->
                            <div class="panel panel-default">
                                <div class="panel-heading">Hier ist das Ende :(</div>
                                <div class="panel-body">
                                    <ul class="nav nav-pills">
                                        <li role="presentation" class="active"><a href="./index.php">Refreshe mal deine Seite, vielleicht ist ja was neues da!</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- right panel-->
                        <div class="col-sm-3">
                            <div class="panel panel-default panel-custom">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        Vorgeschlagen
                                        <small><a href="#">Refresh</a> ● <a href="#">View all</a></small>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <!--put recommended persons in there-->
                                    Fill1
                                    <br>
                                    Fill2
                                    <br>
                                    Fill3
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        </body>
    </html>