<?php


namespace Clue;


class PasswordValidateController
{
    const INVALID_VALIDATOR = "v";
    const INVALID_EMAIL = "e";
    const INVALID_VALIDATOR_EMAIL = "h";
    const PASSWORDS_NOTEQUAL = "p";
    const PASSWORD_SHORT = "s";

    /**
     * PasswordValidateController constructor.
     * @param Site $site The Site object
     * @param array $post $_POST
     */
    public function __construct(Site $site, array $post) {
        $root = $site->getRoot();
        $this->redirect = "../";
        if(isset($post['ok'])){
            //
            // 1. Ensure the validator is correct! Use it to get the user ID.
            //
            $validators = new Validators($site);
            $validator = strip_tags($post['validator']);
            $userid = $validators->get($validator);
            if($userid === null) {
                $this->redirect = "../password-validate.php?v=$validator&e=" . self::INVALID_VALIDATOR;
                return;
            }
            //
            // 2. Ensure the email matches the user.
            //
            $users = new Users($site);
            $editUser = $users->get($userid);
            if($editUser === null) {
                // User does not exist!
                $this->redirect = "../password-validate.php?v=$validator&e=" . self::INVALID_VALIDATOR_EMAIL;
                return;
            }
            $email = trim(strip_tags($post['email']));
            if($email !== $editUser->getEmail()) {
                // Email entered is invalid
                $this->redirect = "../password-validate.php?v=$validator&e=" . self::INVALID_EMAIL;
                return;
            }

            //
            // 3. Ensure the passwords match each other
            //
            $password1 = trim(strip_tags($post['password']));
            $password2 = trim(strip_tags($post['password2']));
            if($password1 !== $password2) {
                // Passwords do not match
                $this->redirect = "../password-validate.php?v=$validator&e=" . self::PASSWORDS_NOTEQUAL;
                return;
            }

            if(strlen($password1) < 8) {
                // Password too short
                $this->redirect = "../password-validate.php?v=$validator&e=" . self::PASSWORD_SHORT;
                return;
            }
            //
            // 4. Create a salted password and save it for the user.
            //
            $users->setPassword($userid, $password1);
            //
            // 5. Destroy the validator record so it can't be used again!
            //
            $validators->remove($userid);
        }

    }

    /**
     * @return mixed
     */
    public function getRedirect() {
        return $this->redirect;
    }


    private $redirect;	///< Page we will redirect the user to.

}