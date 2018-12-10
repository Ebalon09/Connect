<?php

namespace Test\Controller;

use Test\Model\Tweet;
use Test\Repository\CommentRepository;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Database;
use Test\Services\Request;
use Test\Services\Response;
use Test\Services\ResponseRedirect;
use Test\Services\Session;
use Test\Services\Templating;

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
     * @var LikeRepository
     */
    protected $likeRepository;
    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    /**
     * TwitterController constructor.
     */
    public function __construct()
    {
        $this->tweetRepository = new TweetRepository();
        $this->userRepository = new UserRepository();
        $this->likeRepository = new LikeRepository();
        $this->commentRepository = new CommentRepository();
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);

        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

        $commentarray = array();
        $likearray = array();
        foreach($tweets as $tweet)
        {
            $likearray[$tweet->getId()] = $this->likeRepository->countLikes($tweet);
            $commentarray[$tweet->getId()] = $this->commentRepository->countComments($tweet);
        }

        return new Response( Templating::getInstance()->render('./templates/twitterFeed.php', [
            'result' => $tweets,
            'action' => "index.php?controller=TwitterController&action=createAction",
            'form' => 'tweetForm.php',
            'likes' => $likes,
            'countLikes' => $likearray,
            'user' => $user,
            'countcomments' => $commentarray,
        ]));
    }

    /**
     * @return mixed
     */
    public function getLastTweet()
    {
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
            $user = $this->userRepository->findOneBy([
                'id' => $_SESSION['userid']
            ]);

            $tweet = new Tweet();
            $text = $request->getPost()->get('text');

            preg_match_all('/https:\/\/www\.youtube\.com\/watch\?v=([^\s]*)( |$)/', $text, $matches);
            foreach($matches[1] as $key => $id)
            {
                $tweet->setLinkID($id);
                $text = str_replace($matches[1][$key] , "" , $text);
            }

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
     * @return ResponseRedirect
     * @throws \Exception
     */
    public function reTweetAction(Request $request)
    {
        $tweet = $this->tweetRepository->findOneBy([
            'id' => $request->getQuery()->get('id')
        ]);

        $user = $this->userRepository->findOneBy([
            'id' => $_SESSION['userid']
        ]);

        if($request->isPostRequest())
        {

            $text = $request->getPost()->get('text');

            $reTweet = new Tweet();
            preg_match_all('/https:\/\/www\.youtube\.com\/watch\?v=([^\s]*)( |$)/', $text, $matches);
            foreach ($matches[1] as $key => $id) {
                $reTweet->setLinkID($id);
                $text = str_replace($matches[1][$key], "", $text);
            }

            $reTweet->setReTweet($tweet);
            $reTweet->setText($text);
            $reTweet->setUser($user);

            $this->tweetRepository->add($reTweet);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');
            return new ResponseRedirect("./index.php");
        }
        return new Response(Templating::getInstance()->render('./templates/twitterFeed.php', [
            'result' => $this->tweetRepository->findAll(),
            'reTweet' => $tweet,
            'user' => $user,
            'id' => $tweet->getId()
        ]));
    }

    /**
     * @param Request $request
     *
     * @return Response|ResponseRedirect
     */
    public function updateAction(Request $request)
    {
        $tweet = $this->tweetRepository->findBy([
            'id' => $request->getQuery()->get('id')
        ])[0];

        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

        if ($request->isPostRequest())
        {
            $tweet->setText($request->getPost()->get('text'));

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
            'result' => $this->tweetRepository->findAll(),
            'update' => $tweet,
            'user' => $user,
            'id' => $tweet->getId()
        ]));
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function deleteAction(Request $request)
    {
        $tweet = $this->tweetRepository->findOneBy([
            'id' => $request->getQuery()->get('id')
        ]);

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
        if ($_FILES['my_upload']['name'] != null)
        {
            $_FILES['my_upload']['name'] = $this->getLastTweet() + 1 . ".jpg";
            $upload_file = $_FILES['my_upload']['name'];
            $dest = './uploads/' . $upload_file;

            move_uploaded_file($_FILES['my_upload']['tmp_name'], $dest);

            $tweet->setDestination($dest);
        }
    }
}