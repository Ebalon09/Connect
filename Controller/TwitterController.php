<?php

class TwitterController
{
    /**
     * @var TweetRepository
     */
    protected $tweetRepository;
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * TwitterController constructor.
     */
    public function __construct()
    {
        $this->tweetRepository = new TweetRepository();
        $this->userRepository = new UserRepository();
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
        $data = Database::getInstance()->query("SELECT * FROM Tweet ORDER BY id DESC", [
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

            $user = $this->userRepository->findOneById($_SESSION['userid']);
            $tweet = new Tweet();

            $tweet->setText(trim(strip_tags($request->getPost()->get('text'))));
            $tweet->setUser($user);

            $this->handleFileUpload($tweet);

            $this->tweetRepository->add($tweet);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            return new ResponseRedirect("./index.php");
        }
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
    public function deleteAction(Request $request)
    {

        $tweet = $this->tweetRepository->findOneById($request->getQuery()->get('id'));

        $this->tweetRepository->remove($tweet) ;

        $session = Session::getInstance();
        $session->write('success','Tweet erfolgreich gelöscht');

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