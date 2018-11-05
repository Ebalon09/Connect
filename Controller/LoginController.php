<?php

class LoginController{

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

            $data = Database::getInstance()->query("SELECT * FROM Login WHERE Username = :Username", [
                'Username' => $request->getPost()->get('Username')
                ])[0];

            if ($data == null)
            {
                $session = Session::getInstance();
                $session->write('danger', 'Keine Daten für den eingegebenen benutzer gefunden!');

                return new ResponseRedirect('./index.php?controller=TwitterController&action=indexAction');
            }

            if (password_verify($request->getPost()->get('Password'), $data['Password']) == false)
            {
                $session = Session::getInstance();
                $session->write('danger', 'Passwort falsch!');

                return new ResponseRedirect('./index.php?controller=TwitterController&action=indexAction');
            }

            $_SESSION['Username'] = $data['Username'];
            $_SESSION['userid'] = $data['id'];
            $_SESSION['Email'] = $data['Email'];
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

        if ($pw1 == $pw2)
        {
            if ($request->isPostRequest())
            {
                if (isset($_POST['Username']) && isset($_POST['Email']))
                {
                    $data = Database::getInstance()->query("SELECT * FROM Login WHERE Email = :Email", [
                        'Email' => $request->getPost()->get('Email')
                        ]);

                    if ($data != null)
                    {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Email bereits vergeben!');

                        return new ResponseRedirect("index.php?controller=LoginController&action=indexAction");
                    }

                    $data = null;
                    $data = Database::getInstance()->query("SELECT * FROM Login WHERE Username = :Username", [
                        'Username' => $request->getPost()->get('Username'),
                    ]);

                    if ($data != null)
                    {
                        $session = Session::getInstance();
                        $session->write('danger', 'Fehler beim Registrieren : Nutzername bereits vergeben!');

                        return new ResponseRedirect("./index.php?controller=LoginController&action=indexAction");
                    }
                }
            }
                Database::getInstance()->insert(
                    "INSERT INTO Login SET username = :Username, password = :Password, email = :Email",
                        array(
                            'Username' => strip_tags($request->getPost()->get('Username')),
                            'Password' => password_hash($request->getPost()->get('Password'), PASSWORD_DEFAULT),
                            'Email' => strip_tags($request->getPost()->get('Email'))
                        ));

                $session = Session::getInstance();
                $session->write('success', 'Erfolgreich Registriert! bitte anmelden');
        }

        $session = Session::getInstance();
        $session->write('danger', 'Passwörter stimmen nicht überein!');

        return new ResponseRedirect("./index.php?controller=LoginController&action=indexAction");

    }

    /**
     * @return ResponseRedirect
     */
    public function logoutAction()
    {
        $_SESSION['Username'] = null;
        $_SESSION['userid'] = null;
        $_SESSION['Email'] = null;

        return new ResponseRedirect("./index.php");
    }
}
