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
        return new Response(Templating::getInstance()->render('account/settingForm.html.twig',[
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

        $user = $this->userRepository->currentUser();

        $mainpw = $user->getPassword();



        $pw1 = $request->get('PasswordVerify');
        $pw2 = $request->get('rePasswordVerify');



        if ($request->isMethod(Request::METHOD_POST) && $pw1 == $pw2 && password_verify($pw1, $mainpw) == true)
        {
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
            if ($request->get('passwordchange') != null) {

                    $user = $this->userRepository->currentUser();

                    $user->setPassword(password_hash($request->get('passwordchange'), PASSWORD_DEFAULT));
                    $result = $this->userRepository->add($user);

            }
            if ($_FILES != null)
            {
                $user = $this->userRepository->currentUser();

                $user->setPicture($this->handleFileUpload($user));
                $result = $this->userRepository->add($user);
            }
            if (!$result)
            {
                Session::getInstance()->write('danger', 'Fehler!');
            }
            else
            {
                $_SESSION['username'] = null;
                $_SESSION['userid'] = null;
                $_SESSION['email'] = null;
                Session::getInstance()->write('success',
                    'erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt');

                return new RedirectResponse("/feed");
            }
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
            $user = $this->userRepository->currentUser();

            $this->tweetRepository->removeAllBy(['userid' => $user->getId()]);
            $this->commentRepository->removeAllBy(['userid' => $user->getId()]);
            $this->likeRepository->removeAllBy(['userid' => $user->getId()]);

            $this->userRepository->remove($user);

            Session::getInstance()->write('success', 'Account erfolgreich Gelöscht!');

            $_SESSION['username'] = null;
            $_SESSION['userid'] = null;
            $_SESSION['email'] = null;

            return new RedirectResponse("/feed");
        }
    }

    /**
     * @param $user
     *
     * @return string
     */
    private function handleFileUpload ($user, Request $request)
    {
        if ($request->files->get("img-upload") != null)
        {
            $uploadedFile = $request->files->get("img-upload");
            $filename = md5(uniqid("image_")) .".jpg";
            $uploadedFile->move('./uploads/', $filename);
            $user->setDestination('/uploads/' . $filename);
        }
    }

}

