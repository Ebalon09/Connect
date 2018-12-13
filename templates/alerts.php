<div class="warnung">
    <?php
    foreach(Test\Services\Session::getInstance()->readMessage() as $type => $messages) {
        foreach($messages as $message){ ?>
            <div class="alert alert-<?= $type; ?>"><?= $message ?></div>
        <?php    }
    }
    ?>
</div>