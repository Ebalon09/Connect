<?php

class TwitterController
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        return new Response( Templating::getInstance()->render('./templates/twitterFeed.php', [
            'result' => $this->getUserid(),
            'action' => "index.php?controller=TwitterController&action=createAction",
            'form' => 'tweetForm.php'
        ]));
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function createAction(Request $request)
    {
        if ($request->isPostRequest())
        {
            Database::getInstance()->insert(
                "INSERT INTO Entries SET text = :text, postid = :postid",
                    array(
                        'text' => strip_tags($request->getPost()->get('text')),
                        'postid' => $_SESSION['userid'],
                    ));
        }
        $session = Session::getInstance();
        $session->write('success','Tweet erfolgreich gepostet');

        return new ResponseRedirect("./index.php");
    }

    /**
     * @return mixed
     */
    private function getUserid()
    {
        return Database::getInstance()->query(
            "SELECT  Login.Username, Login.Email, Entries.text, Entries.datum, Entries.id  FROM Entries INNER JOIN Login   ON Entries.postid = Login.id ORDER BY datum",
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