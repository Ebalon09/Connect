<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 19.11.18
 * Time: 09:10
 */

class LikeController{

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
    public function indexAction(){
        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findAllByUserId($_SESSION['userid']);

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
        $likes = $this->likeRepository->findAllByUserId($_SESSION['userid']);

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

        return new ResponseRedirect("./index.php");
    }


    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function dislikeAction(Request $request){

        $like = $this->likeRepository->findByTweetId($request->getQuery()->get('id'));

        $this->likeRepository->remove($like);

        return new ResponseRedirect('.index.php');
    }





    //TODO
    //userid und tweetid in like speichern,
    //userid == tweetid ausgeben und die häufigkeit counten
    //wenn userid bereits für einen tweet gegeben, like button deaktivieren





}