<?php


namespace Clue;


class RegisterView
{
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function present()
    {
        $html = <<<HTML
<header>
    <h1> Who Murdered My Grade? </h1>
</header>
<div class="login">
 <form method="post" action="post/register.php">
    <fieldset>
        <legend>Clue User Creation</legend>
        <p>
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" placeholder="Username" value="">
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" placeholder="Email" value="">
        </p>
        <p>
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" placeholder="Name" value="">
        </p>
        <p>
            <input type="submit" name="add" value="Submit"> <input type="submit" name="cancel" value="Cancel">
        </p>
    </fieldset>
</form>
</div>
HTML;
        return $html;
    }

    public $site;
}