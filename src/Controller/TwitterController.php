<?php

namespace Test\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     *
     * @param TweetRepository   $tweetRepository
     * @param UserRepository    $userRepository
     * @param LikeRepository    $likeRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct (
        TweetRepository $tweetRepository,
        UserRepository $userRepository,
        LikeRepository $likeRepository,         //only in indexAction
        CommentRepository $commentRepository    //only in indexAction
    ) {
        $this->tweetRepository = $tweetRepository;
        $this->userRepository = $userRepository;
        $this->likeRepository = $likeRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @return Response
     */
    public function indexAction ()
    {
        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);
        $user = $this->userRepository->currentUser();

        $commentarray = [];
        $likearray = [];
        foreach ($tweets as $tweet) {
            $likearray[$tweet->getId()] = $this->likeRepository->countLikes($tweet);
            $commentarray[$tweet->getId()] = $this->commentRepository->countComments($tweet);
        }

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'        => $tweets,
            'likes'         => $likes,
            'countlikes'    => $likearray,
            'user'          => $user,
            'countcomments' => $commentarray,
            'userid'        => $_SESSION['userid'],
            'username'      => $_SESSION['username'],
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
        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->userRepository->currentUser();

            $tweet = new Tweet();
            $text = $request->get('text');

            preg_match_all('/https:\/\/www\.youtube\.com\/watch\?v=([^\s]*)( |$)/', $text, $matches);
            foreach ($matches[1] as $key => $id) {
                $tweet->setLinkID($id);
                $text = str_replace($matches[1][$key], "", $text);
            }
            $tweet->setText(trim(strip_tags($request->get('text'))));
            $tweet->setUser($user);
            $this->handleFileUpload($tweet, $request);

            $this->tweetRepository->add($tweet);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            return new RedirectResponse("/feed");
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
            'id' => $request->query->get('tweet'),
        ]);
        $user = $this->userRepository->currentUser();

        if ($request->isMethod(Request::METHOD_POST)) {
            $text = $request->get('text');

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

            return new RedirectResponse("/feed");
        }

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'    => $this->tweetRepository->findAll(),
            'reTweet'   => $tweet,
            'user'      => $user,
            'id'        => $tweet->getId(),
            'userid'    => $_SESSION['userid'],
            'username'  => $_SESSION['username'],
            'userimage' => $user->getPicture(),
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
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->query->get('tweet')]);
        $user = $this->userRepository->currentUser();

        if ($request->isMethod(Request::METHOD_POST)) {
            $tweet->setText($request->get('text'));

            $result = $this->tweetRepository->add($tweet);
            if (!$result) {
                Session::getInstance()->write('danger', 'Ungültige Abfrage!');
            } else {
                Session::getInstance()->write('success', 'Eintrag erfolgreich geupdatet');
            }

            return new RedirectResponse('/feed');
        }

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'   => $this->tweetRepository->findAll(),
            'update'   => $tweet,
            'user'     => $user,
            'id'       => $tweet->getId(),
            'userid'   => $_SESSION['userid'],
            'username' => $_SESSION['username'],
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
            'id' => $request->query->get('tweet'),
        ]);
        $this->tweetRepository->remove($tweet);

        $session = Session::getInstance();
        $session->write('success', 'Tweet erfolgreich gelöscht');

        return new RedirectResponse("/feed");
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
    private function handleFileUpload (Tweet $tweet, Request $request)
    {
        if ($request->files->get("img-upload") != null) {
            $uploadedFile = $request->files->get("img-upload");
            $filename = md5(uniqid("image_")).".jpg";
            $uploadedFile->move('./uploads/', $filename);
            $tweet->setDestination('/uploads/'.$filename);
        }
    }
}