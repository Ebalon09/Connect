<html>
<head>
    <?php include "head.php";?>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./Style/register.css">
    <?php include "scripts.php"?>
</head>
<body>
<!-- Start Main Container -->
<div class="main-container">
    <?php include "alerts.php"?>
    <!-- Start Pokemon Ball Top Part -->
    <div class="pokemon-top-part"></div>
    <!-- End Pokemon Ball Top Part -->
    <!-- Start Main Forms -->
    <div class="main-forms">
        <div class="signup-form">
            <form class="sign-back" action="./index.php?controller=LoginController&action=registerAction" method="POST" enctype="multipart/form-data">
                <h1>Sign up</h1>
                <div class="signup-row">
                    <i class="fa fa-user"></i>
                    <label for="USERNAME" class="sr-only">USERNAME</label>
                    <input type="text" name="USERNAME" value="" placeholder="USERNAME" autocomplete="off">
                </div>
                <div class="signup-row">
                    <i class="fa fa-envelope-o"></i>
                    <label for="EMAIL" class="sr-only">EMAIL</label>
                    <input type="text" name="EMAIL" value="" placeholder="EMAIL" autocomplete="off">
                </div>
                <div class="signup-row">
                    <i class="fa fa-key"></i>
                    <label for="PASSWORD" class="sr-only">PASSWORD</label>
                    <input type="password" name="PASSWORD" value="" placeholder="PASSWORD" autocomplete="off">
                </div>
                <div class="signup-row">
                    <label for="my_upload" class="sr-only">IMAGE</label>
                    <input id="my_upload" name="my_upload" class="upload" type="file" accept="image/*">
                </div>
                <div class="signup-row">
                    <button id="signupButton" class="fa btn btn-warning fa-arrow-circle-o-right" style="color: white" aria-hidden="true" type="submit"></button>
                </div>
                <div class="form-bottom">
                    <div class="remember">
                        <input type="checkbox" name="" value="">
                        <span>Remember me</span>
                    </div>
                    <div class="remember">
                        <a href="#">Already Have Account ?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Main Forms -->
    <!-- Start Pokemon Ball Bottom Part -->
    <div class="pokemon-bottom-part">
        <div class="white-part"></div>
        <div class="black-line"></div>
    </div>
    <!-- End Pokemon Ball Bottom Part -->
</div>
<!-- End Main Container -->
<!-- Start Scripts -->

<script type="text/javascript" src="./Style/js/regscript.js"></script>
<script src="https://use.fontawesome.com/7dddae9ad9.js"></script>



<!-- End Scripts -->
</body>
</html>
