<?php

class LoginController{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        return new Response( Templating::getInstance()->render('./templates/registerForm.php'));
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function loginAction(Request $request)
    {
        if($request->isPostRequest())
        {
            $data = $this->userRepository->findOneBy([
                'username' => $request->getPost()->get('username')
            ]);

            if ($data == null)
            {
                $session = Session::getInstance();
                $session->write('danger', 'Keine Daten für den eingegebenen benutzer gefunden!');
                return new ResponseRedirect('./index.php?controller=TwitterController&action=indexAction');
            }
            if (password_verify($request->getPost()->get('password'), $data->getPassword()) == false)
            {
                $session = Session::getInstance();
                $session->write('danger', 'passwort falsch!');
                return new ResponseRedirect('./index.php?controller=TwitterController&action=indexAction');
            }
            $_SESSION['username'] = $data->getUsername();
            $_SESSION['userid'] = $data->getId();
            $_SESSION['email'] = $data->getEmail();

            $session = Session::getInstance();
            $session->write('success', 'Erfolgreich angemeldet!');
            return new ResponseRedirect('./index.php?controller=TwitterController&action=indexAction');
        }
    }

    /**
     * @param Request $request
     * @return ResponseRedirect
     */
    public function registerAction(Request $request)
    {
        $pw1 = $_POST['Password'];
        $pw2 = $_POST['re-Password'];


        if($_FILES['name'] == '') {
            $pic = "./uploads/ProfilePics/fill.jpg";
        }

        if ($pw1 == $pw2)
        {
            if ($request->isPostRequest())
            {
                if (isset($_POST['Username']) && isset($_POST['Email']))
                {
                    $data = $this->userRepository->findOneBy([
                        'email' => $request->getPost()->get('Email')
                    ]);

                    if ($data->getEmail() != null)
                    {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Email bereits vergeben!');
                        return new ResponseRedirect("index.php?controller=LoginController&action=indexAction");
                    }
                    $data = null;
                    $data = $this->userRepository->findOneBy([
                        'username' => $request->getPost()->get('Username')
                    ]);

                    if ($data->getUsername() != null)
                    {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Nutzername bereits vergeben!');
                        return new ResponseRedirect("./index.php?controller=LoginController&action=indexAction");
                    }
                }
            }


            $user = new User();

            if(isset($pic)){
                $user->setPicture($pic);
            }else {
                $user->setPicture(strip_tags($this->handlefileupload($request)));
            }
            $user->setUsername(strip_tags($request->getPost()->get('Username')));
            $user->setPassword(password_hash($request->getPost()->get('Password'), PASSWORD_DEFAULT));
            $user->setEmail(strip_tags($request->getPost()->get('Email')));

            $this->userRepository->add($user);

            $session = Session::getInstance();
            $session->write('success', 'Erfolgreich Registriert! bitte anmelden');
            return new ResponseRedirect("./index.php?controller=LoginController&action=indexAction");
        }
        else
        {
            $session = Session::getInstance();
            $session->write('danger', 'Passwörter stimmen nicht überein!');
            return new ResponseRedirect("./index.php?controller=LoginController&action=indexAction");
        }
    }

    /**
     * @return ResponseRedirect
     */
    public function logoutAction()
    {
        $_SESSION['username'] = null;
        $_SESSION['userid'] = null;
        $_SESSION['email'] = null;

        return new ResponseRedirect("./index.php");
    }

    /**
     * @param $request
     * @return string
     */
    private function handleFileUpload($request)
    {


        if ($_FILES['my_upload']['name'] != null)
        {


            $_FILES['my_upload']['name'] = $request->getPost()->get('Username'). ".jpg";
            $upload_file = $_FILES['my_upload']['name'];
            $dest = './uploads/ProfilePics/' . $upload_file;


            move_uploaded_file($_FILES['my_upload']['tmp_name'], $dest);

            return $dest;
        }
    }

}
