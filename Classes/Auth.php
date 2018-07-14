<?php
class Auth {
    private $array;
    private $xml;
    private $salt = "1241ascavfzxfqwe43faFEFA";
    private $filename;

    public function __construct($file)
    {
        $this->filename = $file;
        $this->xml = simplexml_load_file($this->filename);
        $json = json_encode($this->xml);
        $this->array = json_decode($json,TRUE);
    }

    private function addToDB($login, $password, $name, $email) {
        $new_user = $this->xml->addChild('user');
        $new_user->addChild('name', $name);
        $new_user->addChild('login', $login);
        $new_user->addChild('password', md5($this->salt.$password));
        $new_user->addChild('email', $email);
        $new_user->addChild('token', 'null');

        $this->xml->saveXML($this->filename);
    }

    public function createAccount($login, $password, $name, $email) {
        $message = 'Account already exists';
        $contain = false;
        if (!isset($this->array['user'])) {
            $this->addToDB($login, $password, $name, $email);
            $message = 'Account created';
        } else {
            foreach ($this->array['user'] as $arr) {
                if (($arr['login'] === $login) && ($arr['email'] === $email)) {
                    $contain = true;
                    break;
                }
            }
            if ($contain === false) {
                $this->addToDB($login, $password, $name, $email);
                $message = 'Account created';
            }
        }
        return $message;
    }

    public function login($login, $password) {
        $message = 'Invalid login or username';
        $name = 'null';
        for ($i = 0; $i < count($this->array['user']); $i++) {
            if (($this->array['user'][$i]['login'] === $login) && ($this->array['user'][$i]['password'] === md5($this->salt.$password))) {
                $_SESSION['user'] = $this->array['user'][$i]['name'];
                $cstrong = true;
                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                setcookie('token', $token, time() + (60*60*24*14));
                $this->xml->user[$i]->token = $token;
                $this->xml->saveXML('db.xml');
                $message = 'Logged in';
                $name = (string)$this->xml->user[$i]->name;
                break;
            }
        }
        $response['message'] = $message;
        $response['name'] = $name;
        return $response;
    }

    public function cookieLogin($token) {
        $message = 'error';
        foreach ($this->array['user'] as $arr) {
            if ($arr['token'] === $token) {
                $_SESSION['user'] = $arr['name'];
                $message = 'logged in';
                break;
            }
        }
        return $message;
    }
}