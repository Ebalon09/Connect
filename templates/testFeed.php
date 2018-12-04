<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="./favicon.ico">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../Style/TwitterStyle.css"
</head>



<body id="body">
<header class="header text-white" >
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
<div class="container-fluid">
    <div class="container">

        <div class="row">
            <div class="col-sm-3">

                <!-- User-->
                <!--Show logged in User, with profile pic and data-->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="#"><img class="img-responsive" alt="" src="http://placehold.it/800x500"></a>
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
                            <img alt="" class="media-object img-rounded" src="http://placehold.it/35x35">
                        </a>
                        <div class="media-body">
                            <div class="form-group has-feedback">
                                <label class="control-label sr-only" for="inputSuccess5">Hidden label</label>
                                <input type="text" class="form-control" id="search2" aria-describedby="search">
                                <span class="glyphicon glyphicon-camera form-control-feedback" aria-hidden="true"></span>
                                <span id="search2" class="sr-only">(success)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">

                    <!--here foreach with user posts,names,profile pics and buttons-->
                    <div class="media">
                        <a class="media-left" href="#fake">
                            <img alt="" class="media-object img-rounded" src="http://placehold.it/64x64">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">Fill Username</h4>
                            <p>Fill Tweet</p>
                            <ul class="nav nav-pills nav-pills-custom">
                                <li><a href="#"><span class="glyphicon glyphicon-share-alt"></span></a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-retweet"></span></a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-star"></span></a></li>
                                <li><a href="#"><span class="glyphicon glyphicon-option-horizontal"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


                <!-- Foot panel-->
                <div class="panel panel-default">
                <div class="panel-heading">Footer?</div>
                <div class="panel-body">
                    <ul class="nav nav-pills">
                        <li role="presentation" class="active"><a href="#">Home</a></li>
                        <li role="presentation"><a href="#">Profile</a></li>
                        <li role="presentation"><a href="#">Messages</a></li>
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
</body>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>