<?php
/**
 * Created by PhpStorm.
 * User: fstein
 * Date: 26.11.18
 * Time: 17:43
 */

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
        $comments = $this->commentRepository->findAll();

        $tweet = $this->tweetRepository->findOneBy(['id' => $request->getQuery()->get('id')]);

        $likes = $this->likeRepository->findBy(['userid' => $_SESSION['userid']]);


        $array = array();

        $array[$tweet->getId()] = $this->likeRepository->countLikes($tweet);


        return new Response(Templating::getInstance()->render('./templates/commentFeed.php', [
            'result' => $comments,
            'tweet' => $tweet,
            'likes' => $likes,
            'countLikes' => $array,
        ]));
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     * @throws Exception
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

            return new ResponseRedirect('./index.php');

        }
    }

    /**
     * @param Request $request
     */
    public function updateAction(Request $request)
    {

    }

    public function deleteAction(Request $request)
    {
        $comment = $this->commentRepository->findOneBy([
            'id' => $request->getQuery()->get('id')
        ]);

        $this->commentRepository->remove($comment);


        $session = Session::getInstance();
        $session->write('success','Tweet erfolgreich gelöscht');

        return new ResponseRedirect("./index.php");


    }



}