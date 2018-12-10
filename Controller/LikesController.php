<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 19.11.18
 * Time: 09:10
 */
namespace Test\Controller;


use Test\Model\Likes;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Request;
use Test\Services\Response;
use Test\Services\ResponseRedirect;
use Test\Services\Session;
use Test\Services\Templating;

class LikesController{

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
     * TwitterController constructor.
     */
    public function __construct()
    {
        $this->tweetRepository = new TweetRepository();
        $this->userRepository = new UserRepository();
        $this->likeRepository = new LikeRepository();
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy([ 'userid' => $_SESSION['userid']]);

        return new Response( Templating::getInstance()->render('./templates/twitterFeed.php', [
            'result' => $tweets,
            'action' => "index.php?controller=TwitterController&action=createAction",
            'form' => 'tweetForm.php',
            'likes' => $likes,
        ]));
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function likeAction(Request $request)
    {
        $likes = $this->likeRepository->findBy([
            'userid' => $_SESSION['userid']
        ]);

        foreach((array)$likes as $data)
        {
            if($data->getTweet()->getId() == $request->getQuery()->get('id'))
            {
                Session::getInstance()->write( 'danger', 'Bereits Geliked!');
                return new ResponseRedirect('./index.php');
            }
        }
        $likes = new Likes();
        $likes->setUser($this->userRepository->findOneBy([
            'username' => $_SESSION['username']
        ]));
        $likes->setTweet($this->tweetRepository->findOneBy([
            'id' => $request->getQuery()->get('id')
        ]));
        $this->likeRepository->add($likes);

        $id = $request->getQuery()->get('id');
        if($request->getQuery()->get('c') == true){
            return new ResponseRedirect("./index.php?controller=CommentController&action=indexAction&id=$id&c=true");
        }
        return new ResponseRedirect("./index.php");
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function dislikeAction(Request $request)
    {
        $like = $this->likeRepository->findOneBy(['tweetid' => $request->getQuery()->get('id')]);
        $this->likeRepository->remove($like);

        $id = $request->getQuery()->get('id');
        if($request->getQuery()->get('c') == true){
            return new ResponseRedirect("./index.php?controller=CommentController&action=indexAction&id=$id&c=true");
        }
        return new ResponseRedirect('./index.php');
    }
}