<?php


namespace Clue;


class PasswordValidateView
{
    public function __construct(Site $site, array $get)
    {
        $this->site = $site;
        $this->validator = strip_tags($get['v']);
    }

    public function present()
    {
        $html = <<<HTML
<header>
    <h1> Who Murdered My Grade? </h1>
</header>
<div class="login">
<form method="post" action="post/password-validate.php">
        <fieldset>
            <legend>Change Password</legend>
HTML;
        if(isset($_GET['e'])){
            $html .= $this->presentError($_GET['e']);
        }

        $html .= <<<HTML
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" placeholder="Email">
            </p>
            <p>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" placeholder="password">
            </p>
            <p>
                <label for="password2">Password (again):</label><br>
                <input type="password" id="password2" name="password2" placeholder="password">
            </p>
            <p>
                <input type="submit" name="ok" value="OK"> <input type="submit" name="cancel" value="Cancel">
            </p>
            <input type="hidden" name="validator" value="$this->validator">
        </fieldset>
    </form>
</div>
HTML;
        return $html;
    }

    public function presentError($errorCode){
        $html = '';
        if($errorCode == 'v'){
            $html = <<<HTML
<p class="msg">Invalid or unavailable validator</p>
HTML;
        }
        else if($errorCode == 'e'){
            $html = <<<HTML
<p class="msg">Email address is not for a valid user</p>
HTML;
        }
        else if($errorCode == 'h'){
            $html = <<<HTML
<p class="msg">Email address does not match validator</p>
HTML;
        }
        else if($errorCode == 'p'){
            $html = <<<HTML
<p class="msg">Passwords did not match</p>
HTML;
        }

        else if($errorCode == 's'){
            $html = <<<HTML
<p class="msg">Password too short</p>
HTML;
        }
        return $html;
    }

    private $site;	///< The Site object
    private $validator;
}