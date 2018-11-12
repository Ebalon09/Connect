<div class="updateForm">
    <div class="row justify-content-center">
        <div class="col-sm-6 ">
            <h5 class="card-title">Edit:</h5>
            <div class="form">
                <form action="./index.php?controller=TwitterController&action=updateAction&id=<?= $tweet->getId() ?>" method="POST">
                    <textarea class="form-control" name="text" maxlength="100" cols="50" rows="5"><?php if($_GET['action'] == "updateAction") {  echo $tweet->getText(); } ?></textarea>
                    <button class="btn btn-primary" type="Submit" name="action" value="Absenden" >Absenden</button>
                </form>
            </div>
        </div>
    </div>
</div>