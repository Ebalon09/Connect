<?php

namespace Test\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Events\AccountDataChangedEvent;
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
 * Class UserActionController
 *
 * @author Florian Stein <fstein@databay.de>
 */
class UserActionController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var TweetRepository
     */
    protected $tweetRepository;

    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    /**
     * @var LikeRepository
     */
    protected $likeRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * UserActionController constructor.
     *
     * @param EntityManagerInterface $manager
     * @param EventDispatcher        $dispatcher
     */
    public function __construct (
        EntityManagerInterface $manager,
        EventDispatcher $dispatcher
    ) {
        $this->commentRepository = $manager->getRepository(Comment::class);
        $this->userRepository = $manager->getRepository(User::class);
        $this->tweetRepository = $manager->getRepository(Tweet::class);
        $this->likeRepository = $manager->getRepository(Likes::class);
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Response
     */
    public function settingsIndex ()
    {
        return new Response(Templating::getInstance()->render('account/settingForm.html.twig', [
            'usermail' => $_SESSION['email'],
            'username' => $_SESSION['username'],
        ]));
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function changeAction (Request $request)
    {
        $result = null;

        $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

        $mainpw = $user->getPassword();

        $pw1 = $request->get('PasswordVerify');
        $pw2 = $request->get('rePasswordVerify');

        if ($request->isMethod(Request::METHOD_POST) && $pw1 == $pw2 && password_verify($pw1, $mainpw) == true)
        {
            if ($request->get('email') != null)
            {
                $user = $this->userRepository->findOneBy([
                    'email' => $_SESSION['email'],
                ]);

                $user->setEmail($request->get('email'));

                $this->manager->persist($user);
                $this->manager->flush();
            }
            if ($request->get('username') != null)
            {
                $user = $this->userRepository->findOneBy([
                    'id' => $_SESSION['userid'],
                ]);
                $user->setUsername($request->get('username'));

                $this->manager->persist($user);
                $this->manager->flush();
            }
            if ($request->get('passwordchange') != null)
            {

                $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

                $user->setPassword(password_hash($request->get('passwordchange'), PASSWORD_DEFAULT));

                $this->manager->persist($user);
                $this->manager->flush();
            }

            if ($request->files->get("my_upload") != null)
            {
                $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

                $user->setPicture($this->handleFileUpload($request));

                $this->manager->persist($user);
                $this->manager->flush();
            }

            $this->dispatcher->dispatch('AccountSettings.Changed', new AccountDataChangedEvent($user, $request));

            $_SESSION['username'] = null;
                $_SESSION['userid'] = null;
                $_SESSION['email'] = null;
                Session::getInstance()->write('success',
                    'erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt');

                return new RedirectResponse("/feed");
        }
        Session::getInstance()->write('danger', 'Fehler bei der Verifizierung!');

        return new RedirectResponse("/feed");
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAcc ()
    {
        {
            $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);

            if($user->getPicture() !== "/uploads/ProfilePics/fill.jpg") {
                $img = '../test'.$user->getPicture();
                unlink($img);
            }

            $this->tweetRepository->removeAllBy(['userid' => $user->getId()]);
            $this->commentRepository->removeAllBy(['userid' => $user->getId()]);
            $this->likeRepository->removeAllBy(['userid' => $user->getId()]);

            $this->manager->remove($user);
            $this->manager->flush();

            Session::getInstance()->write('success', 'Account erfolgreich Gelöscht!');

            $_SESSION['username'] = null;
            $_SESSION['userid'] = null;
            $_SESSION['email'] = null;

            return new RedirectResponse("/feed");
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function handleFileUpload (Request $request)
    {
            $uploadedFile = $request->files->get("my_upload");
            $filename = md5(uniqid("image_")).".jpg";
            $uploadedFile->move('./uploads/ProfilePics/', $filename);
            $dest = '/uploads/ProfilePics/'.$filename;

            return $dest;
    }
}

