<div class="tweetForm">
    <div class="row justify-content-center">
        <div class="col-sm-6 ">
            <h5 class="card-title">neuer Eintrag:</h5>
                <div class="form">
                    <form action="./index.php?controller=TwitterController&action=createAction" method="POST" enctype="multipart/form-data">
                        <input id="my_upload" name="my_upload" class="upload" type="file" accept="image/*">

                        <button class="btn btn-outline-dark" type="Submit" name="action" value="Absenden" >Absenden</button>
                    </form>
                </div>
        </div>
    </div>
</div>
