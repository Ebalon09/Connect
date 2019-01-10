<?php

namespace Test\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Test\Events\SecurityEvent;
use Test\Model\Comment;
use Test\Model\Likes;
use Test\Model\Tweet;
use Test\Model\User;
use Test\Repository\CommentRepository;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Database;
use Test\Services\Session;
use Test\Services\Templating;
use Test\Services\TweetService;

/**
 * Class TwitterController
 *
 * @author Florian Stein <fstein@databay.de>
 */
class TwitterController
{
    /**
     * @var EntityRepository
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
     * @var TweetService
     */
    protected $tweetService;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;


    /**
     * TwitterController constructor.
     *
     * @param EntityManagerInterface $manager
     * @param TweetService           $tweetService
     * @param EventDispatcher        $dispatcher
     */
    public function __construct (
        EntityManagerInterface $manager,
        TweetService $tweetService,
        EventDispatcher $dispatcher
    ) {
        $this->tweetRepository = $manager->getRepository(Tweet::class);
        $this->userRepository = $manager->getRepository(User::class);
        $this->likeRepository = $manager->getRepository(Likes::class);
        $this->commentRepository = $manager->getRepository(Comment::class);
        $this->tweetService = $tweetService;
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Response
     */
    public function indexAction ()
    {

        $tweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['user' => $_SESSION['userid']]);
        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result' => $tweets,
            'likes'  => $likes,
            'user'   => $user,
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
            $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

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

            $this->manager->persist($tweet);
            $this->manager->flush();

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
            'id' => $request->get('tweet'),
        ]);

        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

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

            $this->manager->persist($reTweet);
            $this->manager->flush();

            $session = Session::getInstance();
            $session->write('success', 'Tweet erfolgreich gepostet');

            return new RedirectResponse("/feed");
        }

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'  => $this->tweetRepository->findAll(),
            'reTweet' => $tweet,
            'user'    => $user,
            'id'      => $tweet->getId(),
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
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->get('tweet')]);
        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);



        if ($request->isMethod(Request::METHOD_POST))
        {
            $tweet->setText($request->get('text'));

            $this->manager->persist($tweet);
            $this->manager->flush();

            Session::getInstance()->write('success', 'Eintrag erfolgreich geupdatet');

            return new RedirectResponse('/feed');
        }


        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result' => $this->tweetRepository->findAll(),
            'update' => $tweet,
            'user'   => $user,
            'id'     => $tweet->getId(),
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
            'id' => $request->get('tweet'),
        ]);
        $comments = $this->commentRepository->findBy(['Tweet' => $request->get('tweet')]);
        $likes = $this->likeRepository->findBy(['tweet' => $request->get('tweet')]);

        foreach($comments as $comment){
            $this->manager->remove($comment);
        }

        foreach($likes as $like){
            $this->manager->remove($like);
        }

        $this->manager->flush();

        $this->manager->remove($tweet);
        $this->manager->flush();

        $session = Session::getInstance();
        $session->write('success', 'Tweet erfolgreich gelöscht');

        return new RedirectResponse("/feed");
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

    /**
     * @return Response
     */
    public function errorAction()
    {
        return new Response(Templating::getInstance()->render('service/error.html.twig', [
        ]));
    }


}