<div id="login_form">
    <h1>Login</h1>
    <?php
    echo validation_errors();
    echo form_open('business/register');
    ?>
    <input placeholder="afm" type="number" name="afm" value="" id="afm" maxlength="9"/> <br/>
    <input placeholder="email" type="email" name="email" value="" id="email" maxlength="500"/> <br/>
    <input placeholder="password" type="password" name="password" value="" id="password" maxlength="30"/> <br/>
    <input placeholder="retype password" type="password" name="re-password" value="" id="re-password" maxlength="30"/> <br/>
    <input placeholder="taxis username" type="text" name="taxis-uname" value="" id="taxis-uname" maxlength="30"/> <br/>
    <input placeholder="taxis password" type="text" name="taxis-password" value="" id="taxis-password" maxlength="30"/><br/>


    <br/>
    <input type="submit" name="submit" value="Register" id="register_button" maxlength="100"/>

    <?php
    echo form_close(); ?>
</div>