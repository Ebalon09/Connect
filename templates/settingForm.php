<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./Style/css/setting.css">
    <title>Einstellungen</title>
    <?php include "scripts.php" ?>
</head>
<body>

    <?php include "alerts.php" ?>
    <!-- multistep form -->
    <form id="msform" action="./index.php?controller=UserActionController&action=changeAction" method="POST">
        <!-- progressbar -->
        <ul id="progressbar">
            <li class="active">Account Settings</li>
            <li>Validation</li>
            <li>submit</li>
        </ul>
        <!-- fieldsets -->
        <fieldset>
            <h2 class="fs-title">Settings</h2>
            <h3 class="fs-subtitle">This is step 1</h3>
            <input type="text" name="email" value="<?=$_SESSION['email']?>" />
            <input type="text" name="username" value="<?=$_SESSION['username']?>" />
            <input type="password" name="passwordchange" placeholder="Password" />
            <input id="my_upload" name="my_upload" class="upload" type="file" accept="image/*">
            <input id="back" type="button" name="back" class="action-button" value="Back" />
            <input type="button" name="next" class="next action-button" value="Next" />
        </fieldset>
        <fieldset>
            <h2 class="fs-title">Validation</h2>
            <h3 class="fs-subtitle">Confirm by Entering your Password</h3>
            <input type="password" name="PasswordVerify" placeholder="Password" required/>
            <input type="password" name="rePasswordVerify" placeholder="Password" required/>
            <input type="button" name="previous" class="previous action-button" value="Previous" />
            <input type="button" name="next" class="next action-button" value="Next" />
        </fieldset>
        <fieldset>
            <h2 class="fs-title">submit</h2>
            <h3 class="fs-subtitle">Your Account settings will be changed after submiting</h3>
            <input type="button" name="previous" class="previous action-button" value="Previous" />
            <input type="submit" name="submit" class="submit action-button" value="Submit" />
        </fieldset>
    </form>

    <script type="text/javascript" src="./Style/js/settingscript.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

</body>
</html>