<?php

namespace Test\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Test\Model\Comment;
use Test\Model\Tweet;
use Test\Model\User;
use Test\Repository\CommentRepository;
use Test\Repository\TweetRepository;

/**
 * Class SecurityListener
 *
 * @author Florian Stein <fstein@databay.de>
 */
class SecurityListener
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var TweetRepository
     */
    protected $tweetRepository;

    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    /**
     * SecurityListener constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct (EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->tweetRepository = $manager->getRepository(Tweet::class);
        $this->commentRepository = $manager->getRepository(Comment::class);
    }


    public function onKernelRequest(GetResponseEvent $event)
    {
        $tweet = $this->tweetRepository->findOneBy(['id' => $event->getRequest()->get('tweet')]);
        $comment = $this->commentRepository->findOneBy(['id' => $event->getRequest()->get('comment')]);

        $action = substr($event->getRequest()->get('_controller'), strpos($event->getRequest()->get('_controller'), "::") +2);


        if(isset($tweet))
        {
            if ($tweet->getUser()->getId() !== $_SESSION['userid'] && $action == "updateAction") {
                $event->setResponse(new RedirectResponse("/error"));
            }
            if ($tweet->getUser()->getId() !== $_SESSION['userid'] && $action == "deleteAction") {
                $event->setResponse(new RedirectResponse("/error"));
            }
        }
        if(isset($comment))
        {
            if ($comment->getUser()->getId() !== $_SESSION['userid'] && $action == "updateAction")
            {
                $event->setResponse(new RedirectResponse("/error"));
            }
            if($comment->getUser()->getId() !== $_SESSION['userid'] && $action == "deleteAction")
            {
                $event->setResponse(new RedirectResponse("/error"));
            }

        }
    }
}