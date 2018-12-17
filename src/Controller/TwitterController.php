<?php

namespace Test\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Model\Tweet;
use Test\Repository\CommentRepository;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Database;
use Test\Services\Session;
use Test\Services\Templating;

/**
 * Class TwitterController
 *
 * @author Florian Stein <fstein@databay.de>
 */
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
    public function __construct ()
    {
        $this->tweetRepository = new TweetRepository();
        $this->userRepository = new UserRepository();
        $this->likeRepository = new LikeRepository();
        $this->commentRepository = new CommentRepository();
    }

    /**
     * @return Response
     */
    public function indexAction ()
    {
        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);
        $user = $this->userRepository->currentUser();

        $commentarray = array();
        $likearray = array();
        foreach ($tweets as $tweet)
        {
            $likearray[$tweet->getId()] = $this->likeRepository->countLikes($tweet);
            $commentarray[$tweet->getId()] = $this->commentRepository->countComments($tweet);
        }

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'        => $tweets,
            'action'        => "index.php?controller=TwitterController&action=createAction",
            'likes'         => $likes,
            'countLikes'    => $likearray,
            'user'          => $user,
            'countcomments' => $commentarray,
            'userid' => $_SESSION['userid'],
            'username' => $_SESSION['username'],
            'userimage' => $user->getPicture(),
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function createAction (Request $request)
    {
        if ($request->isMethod(Request::METHOD_POST))
        {
            $user = $this->userRepository->currentUser();

            $tweet = new Tweet();
            $text = $request->get('text');

            preg_match_all('/https:\/\/www\.youtube\.com\/watch\?v=([^\s]*)( |$)/', $text, $matches);
            foreach ($matches[1] as $key => $id)
            {
                $tweet->setLinkID($id);
                $text = str_replace($matches[1][$key], "", $text);
            }
            $tweet->setText(trim(strip_tags($request->get('text'))));
            $tweet->setUser($user);
            $this->handleFileUpload($tweet);
            $this->tweetRepository->add($tweet);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            return new RedirectResponse("./index.php");
        }
    }

    /**
     * @param Request $request
     *
     * @return Response|RedirectResponse
     * @throws \Exception
     */
    public function reTweetAction (Request $request)
    {
        $tweet = $this->tweetRepository->findOneBy([
            'id' => $request->query->get('id'),
        ]);
        $user = $this->userRepository->currentUser();


        if ($request->isMethod(Request::METHOD_POST))
        {
            $text = $request->get('text');

            $reTweet = new Tweet();
            preg_match_all('/https:\/\/www\.youtube\.com\/watch\?v=([^\s]*)( |$)/', $text, $matches);

            foreach ($matches[1] as $key => $id)
            {
                $reTweet->setLinkID($id);
                $text = str_replace($matches[1][$key], "", $text);
            }
            $reTweet->setReTweet($tweet);
            $reTweet->setText($text);
            $reTweet->setUser($user);


            $this->tweetRepository->add($reTweet);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            return new RedirectResponse("./index.php");
        }
        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'  => $this->tweetRepository->findAll(),
            'reTweet' => $tweet,
            'user'    => $user,
            'id'      => $tweet->getId(),
            'userid'  => $_SESSION['userid'],
            'username'=> $_SESSION['username'],
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function updateAction (Request $request)
    {
        $tweet = $this->tweetRepository->findBy(['id' => $request->query->get('id')])[0];
        $user = $this->userRepository->currentUser();


        if ($request->isMethod(Request::METHOD_POST))
        {
            $tweet->setText($request->get('text'));

            $result = $this->tweetRepository->add($tweet);
            if (!$result)
            {
                Session::getInstance()->write('danger', 'Ungültige Abfrage!');
            }
            else
            {
                Session::getInstance()->write('success', 'Eintrag erfolgreich geupdatet');
            }
            return new RedirectResponse('./index.php?controller=TwitterController&action=indexAction');
        }
        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result' => $this->tweetRepository->findAll(),
            'update' => $tweet,
            'user'   => $user,
            'id'     => $tweet->getId(),
            'userid'  => $_SESSION['userid'],
            'username'=> $_SESSION['username'],
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction (Request $request)
    {
        $tweet = $this->tweetRepository->findOneBy([
            'id' => $request->query->get('id'),
        ]);
        $this->tweetRepository->remove($tweet);

        $session = Session::getInstance();
        $session->write('success', 'Tweet erfolgreich gelöscht');

        return new RedirectResponse("./index.php");
    }

    /**
     * @return mixed
     */
    public function getLastTweet ()
    {
        $data = Database::getInstance()->query("SELECT * FROM Tweet ORDER BY id DESC", [
            'postid' => $_SESSION['userid'],
        ]);
        $data2 = $data[0]['id'];

        return $data2;
    }

    /**
     * @param $tweet
     */
    private function handleFileUpload ($tweet)
    {
        if ($_FILES['my_upload']['name'] != null)
        {
            $_FILES['my_upload']['name'] = $this->getLastTweet()+1 .".jpg";
            $upload_file = $_FILES['my_upload']['name'];
            $dest = './uploads/'.$upload_file;

            move_uploaded_file($_FILES['my_upload']['tmp_name'], $dest);

            $tweet->setDestination($dest);
        }
    }
}