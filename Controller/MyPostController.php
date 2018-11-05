<?php

class MyPostController{

    public function indexAction()
    {
        return new Response( Templating::getInstance()->render('./templates/myPostsForm.php', [
            'result' => $this->getUserid(),
            'usertweets' => $this->getUserposts()
        ]));
    }

    /**
     * @return mixed
     */
    private function getUserposts()
    {
        return Database::getInstance()->query(
            "SELECT * FROM Entries WHERE postid = :userid ORDER BY datum",[
            'userid' => $_SESSION['userid']
        ]);
    }
    /**
     * @return mixed
     */
    private function getUserid()
    {
        return Database::getInstance()->query(
            "SELECT  Login.Username, Login.Email, Entries.text, Entries.datum, Entries.id  FROM Entries INNER JOIN Login   ON Entries.postid = Login.id WHERE postid = :userid ORDER BY datum",[
            'userid' => $_SESSION['userid']
        ]);
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
        $update = true;
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
        return new Response(Templating::getInstance()->render('./templates/myPostsForm.php', [
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

        return new ResponseRedirect("./index.php?controller=MyPostController&action=indexAction ");
    }











}