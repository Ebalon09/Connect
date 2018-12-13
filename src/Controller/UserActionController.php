<?php

namespace Test\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * UserActionController constructor.
     */
    public function __construct ()
    {
        $this->commentRepository = new CommentRepository();
        $this->userRepository = new userRepository();
        $this->tweetRepository = new TweetRepository();
        $this->likeRepository = new LikeRepository();
    }

    /**
     * @return Response
     */
    public function settingsIndex ()
    {
        return new Response(Templating::getInstance()->render('./templates/settingForm.php'));
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

        if ($request->isMethod(Request::METHOD_POST)) {
            if ($request->get('email') != null) {
                $user = $this->userRepository->findOneBy([
                    'email' => $_SESSION['email'],
                ]);
                $user->setEmail($request->get('email'));
                $result = $this->userRepository->add($user);
            }
            if ($request->get('username') != null) {
                $user = $this->userRepository->findOneBy([
                    'username' => $_SESSION['username'],
                ]);
                $user->setUsername($request->get('username'));
                $result = $this->userRepository->add($user);
            }
            if ($request->get('password') != null) {
                $pw1 = $_POST['password'];
                $pw2 = $_POST['re-password'];

                if ($pw1 == $pw2) {
                    $user = $this->userRepository->findOneBy([
                        'id' => $_SESSION['userid'],
                    ]);
                    $user->setPassword(password_hash($request->get('password'), PASSWORD_DEFAULT));
                    $result = $this->userRepository->add($user);
                } else {
                    Session::getInstance()->write('danger', 'Passwörter müssen übereinstimmen!');
                }
            }
            if ($_FILES != null) {
                $user = $this->userRepository->findOneBy([
                    'id' => $_SESSION['userid'],
                ]);

                $user->setPicture($this->handleFileUpload($user));
                $result = $this->userRepository->add($user);
            }
            if (!$result) {
                Session::getInstance()->write('danger', 'Fehler!');
            } else {
                $_SESSION['username'] = null;
                $_SESSION['userid'] = null;
                $_SESSION['email'] = null;
                Session::getInstance()->write('success',
                    'erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt');
            }

            return new Response(Templating::getInstance()->render('./templates/settingForm.php', [
                'form' => 'settingForm.php',
            ]));
        }
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAcc ()
    {
        {
            $user = $this->userRepository->currentUser();

            $this->tweetRepository->removeAllBy(['userid' => $user->getId()]);
            $this->commentRepository->removeAllBy(['userid' => $user->getId()]);
            $this->likeRepository->removeAllBy(['userid' => $user->getId()]);

            $this->userRepository->remove($user);

            Session::getInstance()->write('success', 'Account erfolgreich Gelöscht!');

            $_SESSION['username'] = null;
            $_SESSION['userid'] = null;
            $_SESSION['email'] = null;

            return new RedirectResponse("./index.php");
        }
    }

    /**
     * @param $user
     *
     * @return string
     */
    private function handleFileUpload ($user)
    {
        if ($_FILES['my_upload']['name'] != null) {
            $user = $this->userRepository->findOneBy(['id' => $_SESSION['userid']]);
            $_FILES['my_upload']['name'] = $user->getUsername().".jpg";
            $upload_file = $_FILES['my_upload']['name'];
            $dest = './uploads/ProfilePics/'.$upload_file;

            move_uploaded_file($_FILES['my_upload']['tmp_name'], $dest);

            return $dest;
        }
    }

}

