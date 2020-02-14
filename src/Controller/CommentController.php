<?php

namespace Test\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Model\Comment;
use Test\Model\Likes;
use Test\Model\Tweet;
use Test\Model\User;
use Test\Repository\CommentRepository;
use Test\Repository\LikeRepository;
use Test\Repository\TweetRepository;
use Test\Repository\UserRepository;
use Test\Services\Session;
use Test\Services\Templating;

/**
 * Class CommentController
 *
 * @author Florian Stein <fstein@databay.de>
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
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * CommentController constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct (
        EntityManagerInterface $manager
    ) {
        $this->tweetRepository = $manager->getRepository(Tweet::class);
        $this->likeRepository = $manager->getRepository(Likes::class);
        $this->userRepository = $manager->getRepository(User::class);
        $this->commentRepository = $manager->getRepository(Comment::class);
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction (Request $request)
    {
        $allTweets = $this->tweetRepository->findAll();
        $likes = $this->likeRepository->findBy(['id' => $_SESSION['userid']]);

        return new Response(Templating::getInstance()->render('tweet/twitterFeed.html.twig', [
            'result'        => $allTweets,
            'tweet'         => $this->tweetRepository->findOneBy(['id' => $request->get('tweet')]),
            'likes'         => $likes,
            'user'          => $this->userRepository->findOneBy(['id' => $_SESSION['userid']]),
            'c'             => $request->get('c'),
        ]));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function commentFeed (Request $request)
    {
        $tweet = $this->tweetRepository->findOneBy(['id' => $request->get('tweet')]);
        $comments = $this->commentRepository->findBy(['Tweet' => $request->get('tweet')]);
        array_multisort($comments, SORT_DESC);

        return new Response(Templating::getInstance()->render('comment/commentFeed.html.twig', [
            'tweet'      => $tweet,
            'comments'   => $comments,
            'likes'      => $this->likeRepository->findBy(['user' => $_SESSION['userid']]),
            'user'       => $this->userRepository->findOneBy(['id' => $_SESSION['userid']]),
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
            $tweet = $this->tweetRepository->findOneBy(['id' => $request->get('tweet')]);

            $comment = new Comment();

            $comment->setComment(trim(strip_tags($request->get('text'))));
            $comment->setUser($user);
            $comment->setTweet($tweet);

            if ($request->get('text') != '') {
                $this->manager->persist($comment);
                $this->manager->flush();

                $session = Session::getInstance();
                $session->write('success', 'Kommentar erfolgreich gepostet');
            } else {
                $session = Session::getInstance();
                $session->write('danger', 'Du kannst keinen leeren Kommentar posten!');
            }

            $id = $request->get('tweet');
            if ($request->get('c') == 1) {
                return new RedirectResponse("/feed/comments/$id/c");
            }

            return new RedirectResponse('/feed');
        }
    }

    /**
     * @param Request $request
     *
     * @return Response|RedirectResponse
     * @throws \Exception
     */
    public function updateAction (Request $request)
    {
        $comment = $this->commentRepository->findOneBy(['id' => $request->get('idc'),]);
        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);
        $tweet = $this->tweetRepository->findBy(['id' => $comment->getTweet()->getId()])[0];

        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->userRepository->findOneBy([
                'id' => $_SESSION['userid'],
            ]);

            $comment->setComment(trim(strip_tags($request->get('text'))));
            $comment->setUser($user);
            $comment->setTweet($tweet);

            $this->manager->persist($comment);
            $this->manager->flush();

            $session = Session::getInstance();
            $session->write('success', 'Erfolgreich gepostet');

            $id = $comment->getTweet()->getId();
            $idc = $request->get('idc');
            if ($request->get('c') == true) {
                return new RedirectResponse("/feed/comments/$id/true/$idc");
            }

            return new RedirectResponse("/feed");
        }

        return new Response(Templating::getInstance()->render('comment/commentFeed.html.twig', [
            'result'   => $this->commentRepository->findBy(['Tweet' => $request->get('id')]),
            'update'   => $comment,
            'comments' => $this->commentRepository->findBy(['Tweet' => $tweet->getId()]),
            'user'     => $user,
            'id'       => $comment->getId(),
            'tweet'    => $tweet,
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAction (Request $request)
    {
        $comment = $this->commentRepository->findOneBy([
            'id' => $request->query->get('idc'),
        ]);
        $id = $comment->getTweet()->getId();

        $this->manager->remove($comment);
        $this->manager->flush();

        $session = Session::getInstance();
        $session->write('success', 'Erfolgreich gelöscht');

        $idc = $request->get('idc');
        if ($request->get('c') == true) {
            return new RedirectResponse("/feed/comments/$id/true/$idc");
        }

        return new RedirectResponse("/feed");
    }
}