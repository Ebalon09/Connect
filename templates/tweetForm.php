<div class="tweetForm">
    <div class="row justify-content-center">
        <div class="col-sm-6 ">
            <h5 class="card-title">neuer Eintrag:</h5>
                <div class="form">
                    <form action="./index.php?controller=TwitterController&action=createAction" method="POST">
                        <textarea class="form-control" name="text" maxlength="100" cols="50" rows="5"></textarea>
                        <button class="btn btn-primary" type="Submit" name="action" value="Absenden" >Absenden</button>
                    </form>
                </div>
        </div>
    </div>
</div>
