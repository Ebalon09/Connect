<?php

namespace Test\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\Events\CreatedAccoutEvent;
use Test\Model\User;
use Test\Repository\UserRepository;
use Test\Services\Session;
use Test\Services\Templating;

/**
 * Class LoginController
 *
 * @author Florian Stein <fstein@databay.de>
 */
class LoginController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * LoginController constructor.
     *
     * @param EntityManagerInterface $manager
     * @param EventDispatcher        $dispatcher
     */
    public function __construct (EntityManagerInterface $manager, EventDispatcher $dispatcher)
    {
        $this->manager = $manager;
        $this->userRepository = $manager->getRepository(User::class);
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return Response
     */
    public function indexAction ()
    {
        return new Response(Templating::getInstance()->render('account/registerForm.html.twig'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function loginAction (Request $request)
    {
        if ($request->get('username') && $request->get('password'))
        {
            $data = $this->userRepository->findOneBy([
                'username' => $request->get('username'),
            ]);

            if ($data == null) {
                $session = Session::getInstance();
                $session->write('danger', 'Nutzer existiert nicht!');

                return new RedirectResponse('/feed');
            }


            if (password_verify($request->get('password'), $data->getPassword()) == false)
            {
                $session = Session::getInstance();
                $session->write('danger', 'passwort falsch!');

                return new RedirectResponse('/feed');
            }
            $_SESSION['username'] = $data->getUsername();
            $_SESSION['userid'] = $data->getId();
            $_SESSION['email'] = $data->getEmail();

            $session = Session::getInstance();
            $session->write('success', 'Erfolgreich angemeldet!');

            return new RedirectResponse('/feed');
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function registerAction (Request $request)
    {
        if ($request->files->get("my_upload") == null)
        {
            $pic = "/uploads/ProfilePics/fill.jpg";
        }

        if (isset($_POST['PASSWORD']) && $_POST['PASSWORD'] !== '') {
            if ($request->isMethod(Request::METHOD_POST)) {
                if (isset($_POST['USERNAME']) && isset($_POST['USERNAME'])) {

                    $data = $this->userRepository->findOneBy([
                        'email' => $request->get('EMAIL'),
                    ]);

                    if ($data != null && $data->getEmail() !== null) {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Email bereits vergeben!');

                        return new RedirectResponse("/register");
                    }

                    $data = null;
                    $data = $this->userRepository->findOneBy([
                        'username' => $request->get('USERNAME'),
                    ]);

                    if ($data != null && $data->getUsername() !== null) {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Nutzername bereits vergeben!');

                        return new RedirectResponse("/register");
                    }
                }
            }

            $user = new User();

            if (isset($pic)) {
                $user->setPicture($pic);
            } else {
                $user->setPicture($this->handlefileupload($request));
            }
            $user->setUsername(strip_tags($request->get('USERNAME')));
            $user->setPassword(password_hash($request->get('PASSWORD'), PASSWORD_DEFAULT));
            $user->setEmail(strip_tags($request->get('EMAIL')));

            $this->dispatcher->dispatch('Created.account', new CreatedAccoutEvent($user));

            $this->manager->persist($user);
            $this->manager->flush();

            $session = Session::getInstance();
            $session->write('success', 'Erfolgreich Registriert! bitte anmelden');

            return new RedirectResponse("/feed");
        } else {
            $session = Session::getInstance();
            $session->write('danger', 'Passwörter stimmen nicht überein!');

            return new RedirectResponse("/register");
        }
    }

    /**
     * @return RedirectResponse
     */
    public function logoutAction ()
    {
        $_SESSION['username'] = null;
        $_SESSION['userid'] = null;
        $_SESSION['email'] = null;

        return new RedirectResponse("/feed");
    }

    /**
     * @param $request
     *
     * @return string
     */
    private function handleFileUpload ($request)
    {
        if ($request->files->get("my_upload") != null) {
            $uploadedFile = $request->files->get("my_upload");
            $filename = md5(uniqid("image_")).".jpg";
            $uploadedFile->move('./uploads/ProfilePics/', $filename);
            $dest = '/uploads/ProfilePics/'.$filename;

            return $dest;
        }
    }
}
