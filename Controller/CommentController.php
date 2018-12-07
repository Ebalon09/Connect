<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 26.11.18
 * Time: 17:43
 */

namespace Test\Controller;



use Test\Model\Comment;
use Test\Model\Tweet;
use Test\Repository\CommentRepository;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Request;
use Test\Services\Response;
use Test\Services\ResponseRedirect;
use Test\Services\Session;
use Test\Services\Templating;

class CommentController
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
     * CommentController constructor.
     */
    public function __construct()
    {
        $this->tweetRepository = new TweetRepository();
        $this->likeRepository = new LikeRepository();
        $this->userRepository = new UserRepository();
        $this->commentRepository = new CommentRepository();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {

        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);
        $comments = $this->commentRepository->findAll();
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->getQuery()->get('id')]);

        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

        $array = array();
        $array[$tweet->getId()] = $this->likeRepository->countLikes($tweet);

        return new Response(Templating::getInstance()->render('./templates/twitterFeed.php', [
            'comments' => $comments,
            'result' => $tweets,
            'tweet' => $tweet,
            'likes' => $likes,
            'countLikes' => $array,
            'user' => $user
        ]));
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     * @throws \Exception
     */
    public function createAction(Request $request)
    {
        if($request->isPostRequest())
        {
            $user = $this->userRepository->findOneBy([
                'id' => $_SESSION['userid']
            ]);

            $tweet = $this->tweetRepository->findOneBy([
                'id' => $request->getQuery()->get('id')
            ]);

            $comment = new Comment();

            $comment->setComment(trim(strip_tags($request->getPost()->get('text'))));
            $comment->setUser($user);
            $comment->setTweet($tweet);
            $this->commentRepository->add($comment);

            $session = Session::getInstance();
            $session->write('success', 'Kommentar erfolgreich gepostet');

            $id = $request->getQuery()->get('id');
            if($request->getQuery()->get('c') == true){
                return new ResponseRedirect("./index.php?controller=CommentController&action=indexAction&id=$id&c=true");
            }
            return new ResponseRedirect('./index.php');
        }
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     * @throws \Exception
     */
    public function updateAction(Request $request)
    {
        if ($request->isPostRequest())
        {
            $user = $this->userRepository->findOneBy([
                'id' => $_SESSION['userid']
            ]);

            $tweet = new Tweet();

            $tweet->setText(trim(strip_tags($request->getPost()->get('text'))));
            $tweet->setUser($user);
            $this->tweetRepository->add($tweet);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            $id = $request->getQuery()->get('id');
            if($request->getQuery()->get('c') == true){
                return new ResponseRedirect("./index.php?controller=CommentController&action=indexAction&id=$id&c=true");
            }
            return new ResponseRedirect("./index.php");
        }
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function deleteAction(Request $request)
    {
        $comment = $this->commentRepository->findOneBy([
            'id' => $request->getQuery()->get('idc')
        ]);

        $this->commentRepository->remove($comment);

        $session = Session::getInstance();
        $session->write('success', 'Tweet erfolgreich gelöscht');

        $id = $request->getQuery()->get('id');
        if ($request->getQuery()->get('c') == true) {
            return new ResponseRedirect("./index.php?controller=CommentController&action=indexAction&id=$id&c=true");
        }
        return new ResponseRedirect("./index.php");
    }
}