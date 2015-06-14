<div id="messages">
<?php
    if ($message) {
        echo $message;
    }
?>
</div>

<div id="login_form">
    <h1>Login</h1>
    <?php
    echo form_open('business/login');
    ?>

    <input placeholder="email" type="text" name="email" value="" id="login_uid" maxlength="100"/>
    <input placeholder="password" type="password" name="password" value="" id="login_pwd" maxlength="100"/>
    <br/>
    <input type="submit" name="submit" value="Log In" id="login_button" maxlength="100"/>

    <?php
    echo form_close(); ?>
</div>