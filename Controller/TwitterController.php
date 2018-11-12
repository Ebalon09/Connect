<?php

class TwitterController
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        $result = Database::getInstance()->query(
            "SELECT  Login.Username, Login.Email, Entries.text, Entries.datum, Entries.id, Entries.Destination  FROM Entries INNER JOIN Login   ON Entries.postid = Login.id ORDER BY datum DESC",
            array());

        return new Response( Templating::getInstance()->render('./templates/twitterFeed.php', [
            'result' => $result,
            'action' => "index.php?controller=TwitterController&action=createAction",
            'form' => 'tweetForm.php',
        ]));
    }


    public function getLastTweet(){
        $data = Database::getInstance()->query("SELECT * FROM Entries ORDER BY id DESC", [
            'postid' => $_SESSION['userid']
        ]);

        $data2 = $data[0]['id'];
        return $data2;

    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function createAction(Request $request)
    {
        if ($request->isPostRequest())
        {

            if ($_FILES['my_upload']['name'] == null) {


                Database::getInstance()->insert(
                    "INSERT INTO Entries SET text = :text, postid = :postid",
                    array(
                        'text' => strip_tags($request->getPost()->get('text')),
                        'postid' => $_SESSION['userid'],
                    ));

                $session = Session::getInstance();
                $session->write('success', 'Tweet erfolgreich gepostet');

                return new ResponseRedirect("./index.php");
            }
            else {



                $_FILES['my_upload']['name'] = $this->getLastTweet()+1 . ".jpg";
                    $upload_file = $_FILES['my_upload']['name'];
                    $dest = './uploads/' . $upload_file;

                    move_uploaded_file($_FILES['my_upload']['tmp_name'], $dest);

                    Database::getInstance()->insert(
                        "INSERT INTO Entries SET text = :text, postid = :postid, Destination = :Destination",
                        array(
                            'text' => strip_tags($request->getPost()->get('text')),
                            'postid' => $_SESSION['userid'],
                            'Destination' => $dest
                        ));


                    $session = Session::getInstance();
                    $session->write('success', 'Tweet erfolgreich gepostet');


                    return new ResponseRedirect("./index.php");

                }
        }
    }

    /**
     * @return mixed
     */
    private function getUserid()
    {









        return Database::getInstance()->query(
            "SELECT  Login.Username, Login.Email, Entries.text, Entries.datum, Entries.id, Entries.Destination  FROM Entries INNER JOIN Login   ON Entries.postid = Login.id ORDER BY datum DESC",
                array());
    }

    /**
     * @return mixed
     */
    private function getAllTweets()
    {
       return Database::getInstance()->query("SELECT * FROM Entries Order by datum");
    }


    /**
     * @param Request $request
     *
     * @return Response|ResponseRedirect
     */
    public function updateAction(Request $request)
    {
        $data = Database::getInstance()->query("SELECT * FROM Entries WHERE id = :id", [
            'id' => $request->getQuery()->get('id')
        ])[0];

        if ($request->isPostRequest())
        {
            $result = Database::getInstance()->update(
                "UPDATE Entries SET text = :text WHERE id = :id",
                    array(
                        'text' => strip_tags($request->getPost()->get('text')),
                        'id' => $request->getQuery()->get('id')
                    ));
            if (!$result)
            {
                Session::getInstance()->write( 'danger', 'Ungültige Abfrage!');
            }
            else
            {
                Session::getInstance()->write('success', 'Eintrag erfolgreich geupdatet');
            }

            return new ResponseRedirect('./index.php?controller=TwitterController&action=indexAction');
        }
        return new Response(Templating::getInstance()->render('./templates/twitterFeed.php', [
            'tweet' => $data,
            'id' => $data['id'],
            'result' => $this->getAllTweets(),
            'form' => '../templates/updateForm.php'
        ]));
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */


    //Nacco file service -- upload delete usw.
    public function deleteAction(Request $request)
    {
        Database::getInstance()->query("DELETE FROM Entries WHERE id = :id", [
            'id' => $request->getQuery()->get('id')
        ])[0];

        Database::getInstance()->query("DELETE * WHERE id = :id", [
            'id' => $request->getQuery()->get('id')
        ]);


        $session = Session::getInstance();
        $session->write('danger','Tweet erfolgreich gelöscht');

        return new ResponseRedirect("./index.php");
    }
}