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

    public function commentFeed(Request $request)
    {
        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);
        $comments = $this->commentRepository->findAll();
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->getQuery()->get('id')]);

        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

        $array = array();
        $array[$tweet->getId()] = $this->likeRepository->countLikes($tweet);

        return new Response(Templating::getInstance()->render('./templates/commentFeed.php', [
            'comments' => $comments,
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
                return new ResponseRedirect("./index.php?controller=CommentController&action=commentFeed&id=$id&c=true");
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

        $comment = $this->commentRepository->findBy([
            'id' => $request->getQuery()->get('idc')
        ])[0];




        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

        $tweet = $this->tweetRepository->findBy(['id' => $comment->getTweet()->getId()])[0];


        $comments = $this->commentRepository->findBy(['tweetid' => $tweet->getId()]);


        if ($request->isPostRequest())
        {
            $user = $this->userRepository->findOneBy([
                'id' => $_SESSION['userid']
            ]);

            $comment = new Comment();

            $comment->setComment(trim(strip_tags($request->getPost()->get('text'))));
            $comment->setUser($user);
            $comment->setTweet($tweet);
            $comment->setId($request->getQuery()->get('idc'));


            $this->commentRepository->add($comment);

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            $id = $comment->getTweet()->getId();
            $idc = $request->getQuery()->get('idc');
            if($request->getQuery()->get('c') == true){
                return new ResponseRedirect("./index.php?controller=CommentController&action=commentFeed&id=$id&c=true&idc=$idc");
            }
            return new ResponseRedirect("./index.php");
        }

        return new Response(Templating::getInstance()->render('./templates/commentFeed.php', [
            'result' => $this->commentRepository->findAll(),
            'update' => $comment,
            'comments' => $comments,
            'user' => $user,
            'id' => $comment->getId(),
            'tweet' => $tweet
        ]));
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

        $id = $comment->getTweet()->getId();

        $this->commentRepository->remove($comment);

        $session = Session::getInstance();
        $session->write('success', 'Tweet erfolgreich gelöscht');


        $idc = $request->getQuery()->get('idc');
        if ($request->getQuery()->get('c') == true) {
            return new ResponseRedirect("./index.php?controller=CommentController&action=commentFeed&id=$id&idc=$idc&c=true");
        }
        return new ResponseRedirect("./index.php");
    }
}