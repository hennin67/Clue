<?php


namespace Clue;

class UserController {
    public function __construct(Site $site, array $post) {
        $root = $site->getRoot();
        $this->redirect = "../login.php";

        if (isset($post['add'])) {

            if (isset($post['id'])) {
                $id = strip_tags($post['id']);
            } else {
                $id = 0;
            }

            $username = strip_tags($post['username']);
            $email = strip_tags($post['email']);
            $name = strip_tags($post['name']);

            $row = ['id' => $id, 'username'=> $username, 'email' => $email, 'name' => $name ];

            $editUser = new User($row);
            $users = new Users($site);
            if ($id == 0) {
                // This is a new user
                $mailer = new Email();
                $users->add($editUser, $mailer);
            }
        }
    }

    public function getRedirect() {
        return $this->redirect;
    }


    private $redirect;	///< Page we will redirect the user to.
}