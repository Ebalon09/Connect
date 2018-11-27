<div class="commentForm">
    <div class="row justify-content-center">
        <div class="col-sm-6 ">
            <div class="form">
                <form action="./index.php?controller=CommentController&action=createAction&id=<?= $tweet->getId() ?>" method="POST">
                    <textarea class="form-control" name="text" maxlength="100" cols="50" rows="5"></textarea>
                    <button class="btn btn-outline-dark" type="Submit" name="action" value="Absenden" >Absenden</button>
                </form>
            </div>
        </div>
    </div>
</div>
