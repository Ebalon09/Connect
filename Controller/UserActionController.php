<?php

class UserActionController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new userRepository();
    }

    /**
     * @return Response
     */
    public function settingsIndex()
    {
        return new Response(Templating::getInstance()->render('./templates/settingForm.php'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeAction(Request $request)
    {
        $result = null;

        if ($request->isPostRequest())
        {
            if ($request->getPost()->get('email') != null)
            {
                $user = $this->userRepository->findOneByEmail($_SESSION['Email']);

                $user->setEmail($request->getPost()->get('email'));
                $result = $this->userRepository->add($user);
            }

            if ($request->getPost()->get('username') != null)
            {
                $user = $this->userRepository->findOneByUsername($_SESSION['Username']);
                $user->setUsername($request->getPost()->get('username'));
                $result = $this->userRepository->add($user);

            }
            if ($request->getPost()->get('password') != null)
            {
                $pw1 = $_POST['Password'];
                $pw2 = $_POST['re-Password'];
                if ($pw1 == $pw2)
                {
                    $user = $this->userRepository->findOneById($_SESSION['userid']);
                    $user->setPassword(password_hash($request->getPost()->get('password'), PASSWORD_DEFAULT));
                    $result = $this->userRepository->add($user);
                }  else {
                    Session::getInstance()->write('danger', 'Passwörter müssen übereinstimmen!');
                }
            }


            if (!$result)
            {
                Session::getInstance()->write( 'danger', 'Fehler!');
            }
            else
            {
                Session::getInstance()->write('success', 'erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt');
            }

            return new Response(Templating::getInstance()->render('./templates/settingForm.php', [
                'form' => 'settingForm.php'
            ]));
        }
    }


    /**
     * @return ResponseRedirect
     */
    public function finished()
    {
        return new ResponseRedirect('./index.php?controller=TwitterController&action=indexAction');
    }

    /**
     * @return ResponseRedirect
     */
    public function deleteAcc()
    {
        {
            $user = $this->userRepository->findOneById($_SESSION['userid']);

            $this->userRepository->remove($user);

            Session::getInstance()->write('success', 'Account erfolgreich Gelöscht!');

            $_SESSION['Username'] = null;
            $_SESSION['userid'] = null;
            $_SESSION['Email'] = null;

            return new ResponseRedirect("./index.php");
        }
    }
}

