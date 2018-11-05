<?php

class UserActionController
{

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
    public function changeEmail(Request $request)
    {
        $data = Database::getInstance()->query("SELECT * FROM Login WHERE id = :id", [
            'id' => $_SESSION['userid']
        ])[0];

        if ($request->isPostRequest())
        {
            $result = Database::getInstance()->update(
                "UPDATE Login SET Email = :email WHERE id = :id",
                    array(
                        'email' => strip_tags($request->getPost()->get('email')),
                        'id' => $_SESSION['userid']
                    ));

            if (!$result)
            {
                Session::getInstance()->write( 'danger', 'Fehler!');
            }
            else
            {
                Session::getInstance()->write('success', 'Email erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt');
            }
        }
        return new Response(Templating::getInstance()->render('./templates/settingForm.php', [
            'tweet' => $data,
            'id' => $data['id'],
        ]));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeUsername(Request $request)
    {
        {
            $data = Database::getInstance()->query("SELECT * FROM Login WHERE id = :id", [
                'id' => $_SESSION['userid']
            ])[0];

            if ($request->isPostRequest())
            {
                $result = Database::getInstance()->update(
                    "UPDATE Login SET Username = :username WHERE id = :id",
                        array(
                            'username' => strip_tags($request->getPost()->get('username')),
                            'id' => $_SESSION['userid']
                        ));

                if (!$result)
                {
                    Session::getInstance()->write( 'danger', 'Fehler!');
                }
                else
                {
                    Session::getInstance()->write('success', 'Nutzername erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt');
                }
            }
            return new Response(Templating::getInstance()->render('./templates/settingForm.php', [
                'tweet' => $data,
                'id' => $data['id'],
            ]));
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePW(Request $request)
    {
        $pw1 = $_POST['password'];
        $pw2 = $_POST['re-password'];

        $data = Database::getInstance()->query("SELECT * FROM Login WHERE id = :id", [
            'id' => $_SESSION['userid']
        ])[0];

        if ($pw1 == $pw2)
        {
            if ($request->isPostRequest())
            {
                $result = Database::getInstance()->update(
                    "UPDATE Login SET Password = :password WHERE id = :id",
                        array(
                            'password' => password_hash($request->getPost()->get('password'), PASSWORD_DEFAULT),
                            'id' => $_SESSION['userid']
                        ));

                if (!$result)
                {
                    Session::getInstance()->write('danger', 'Fehler!');
                } else {
                    Session::getInstance()->write('success', 'Passwort erfolgreich geupdatet, bitte neu einloggen damit die änderung in kraft tritt');
                }
            }
            return new Response(Templating::getInstance()->render('./templates/settingForm.php', [
                'tweet' => $data,
                'id' => $data['id'],
            ]));
        }
        else
        {
            Session::getInstance()->write('danger', 'Passwörter müssen übereinstimmen!');

            return new Response(Templating::getInstance()->render('./templates/settingForm.php', [
                'tweet' => $data,
                'id' => $data['id'],
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
            Database::getInstance()->query("DELETE FROM Login WHERE id = :id", [
                'id' => $_SESSION['userid']
            ])[0];

            Database::getInstance()->query("DELETE * WHERE id = :id", [
                'id' => $_SESSION['userid']
            ]);

            Session::getInstance()->write('success', 'Account erfolgreich Gelöscht!');

            $_SESSION['Username'] = null;
            $_SESSION['userid'] = null;
            $_SESSION['Email'] = null;

            return new ResponseRedirect("./index.php");
        }
    }
}

