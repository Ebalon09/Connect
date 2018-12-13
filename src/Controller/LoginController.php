<?php

namespace Test\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * LoginController constructor.
     */
    public function __construct ()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * @return Response
     */
    public function indexAction ()
    {
        return new Response(Templating::getInstance()->render('./templates/registerForm.php'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function loginAction (Request $request)
    {

        if ($request->get('username') && $request->get('password')) {
            $data = $this->userRepository->findOneBy([
                'username' => $request->get('username'),
            ]);

            if ($data == null) {
                $session = Session::getInstance();
                $session->write('danger', 'Keine Daten für den eingegebenen benutzer gefunden!');

                return new RedirectResponse('./index.php?controller=TwitterController&action=indexAction');
            }
            if (password_verify($request->get('password'), $data->getPassword()) == false) {
                $session = Session::getInstance();
                $session->write('danger', 'passwort falsch!');

                return new RedirectResponse('./index.php?controller=TwitterController&action=indexAction');
            }
            $_SESSION['username'] = $data->getUsername();
            $_SESSION['userid'] = $data->getId();
            $_SESSION['email'] = $data->getEmail();

            $session = Session::getInstance();
            $session->write('success', 'Erfolgreich angemeldet!');

            return new RedirectResponse('./index.php?controller=TwitterController&action=indexAction');
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
        $pw1 = $_POST['regPassword'];
        $pw2 = $_POST['regre-Password'];

        if ($_FILES['name'] == '') {
            $pic = "./uploads/ProfilePics/fill.jpg";
        }
        if ($pw1 == $pw2) {
            if ($request->isMethod(Request::METHOD_POST)) {
                if (isset($_POST['regUsername']) && isset($_POST['regEmail'])) {

                    $data = $this->userRepository->findOneBy([
                        'email' => $request->get('regEmail'),
                    ]);

                    if ($data->getEmail() !== null) {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Email bereits vergeben!');

                        return new RedirectResponse("index.php?controller=LoginController&action=indexAction");
                    }

                    $data = null;
                    $data = $this->userRepository->findOneBy([
                        'username' => $request->get('regUsername'),
                    ]);

                    if ($data->getUsername() !== null) {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Nutzername bereits vergeben!');

                        return new RedirectResponse("./index.php?controller=LoginController&action=indexAction");
                    }
                }
            }

            $user = new User();

            if (isset($pic)) {
                $user->setPicture($pic);
            } else {
                $user->setPicture(strip_tags($this->handlefileupload($request)));
            }
            $user->setUsername(strip_tags($request->get('regUsername')));
            $user->setPassword(password_hash($request->get('regPassword'), PASSWORD_DEFAULT));
            $user->setEmail(strip_tags($request->get('regEmail')));

            $this->userRepository->add($user);

            $session = Session::getInstance();
            $session->write('success', 'Erfolgreich Registriert! bitte anmelden');

            return new RedirectResponse("./index.php?controller=LoginController&action=indexAction");
        } else {
            $session = Session::getInstance();
            $session->write('danger', 'Passwörter stimmen nicht überein!');

            return new RedirectResponse("./index.php?controller=LoginController&action=indexAction");
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

        return new RedirectResponse("./index.php");
    }

    /**
     * @param $request
     *
     * @return string
     */
    private function handleFileUpload ($request)
    {
        if ($_FILES['my_upload']['name'] != null) {
            $_FILES['my_upload']['name'] = $request->get('Username').".jpg";
            $upload_file = $_FILES['my_upload']['name'];
            $dest = './uploads/ProfilePics/'.$upload_file;

            move_uploaded_file($_FILES['my_upload']['tmp_name'], $dest);

            return $dest;
        }
    }
}
