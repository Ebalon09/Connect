<?php

class TwitterController
{

    /**
     * @var TweetRepository
     */
    protected $tweetRepository;

    public function __construct()
    {
        $this->tweetRepository = new TweetRepository();
    }


    /**
     * @return Response
     */
    public function indexAction()
    {
        $tweets = $this->tweetRepository->findAll();


        return new Response( Templating::getInstance()->render('./templates/twitterFeed.php', [
            'result' => $tweets,
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

            $tweet = new Tweet();

            $tweet->setText(trim(strip_tags($request->getPost()->get('text'))));
            $tweet->setPostid($_SESSION['userid']);

            $this->handleFileUpload($tweet);

            $this->tweetRepository->add($tweet);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            return new ResponseRedirect("./index.php");
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
     * @param Request $request
     *
     * @return Response|ResponseRedirect
     */
    public function updateAction(Request $request)
    {
        $tweet = $this->tweetRepository->findOneById($request->getQuery()->get('id'));


        if ($request->isPostRequest())
        {
            $tweet->setText(strip_tags($request->getPost()->get('text')));



            $result = $this->tweetRepository->add($tweet);


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
            'tweet' => $tweet,
            'result' => $this->tweetRepository->findAll(),
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

    /**
     * @param $tweet
     */
    private function handleFileUpload($tweet)
    {
        if ($_FILES['my_upload']['name'] != null) {
            $_FILES['my_upload']['name'] = $this->getLastTweet() + 1 . ".jpg";
            $upload_file = $_FILES['my_upload']['name'];
            $dest = './uploads/' . $upload_file;

            move_uploaded_file($_FILES['my_upload']['tmp_name'], $dest);

            $tweet->setDestination($dest);
        }
    }
}