<div id="add offer">
    <h1>Add Offer</h1>
    <?php
    echo validation_errors();

    echo form_open('business/register');
    ?>
    <input placeholder="date" type="date" name="date" value="" id="date" />
    <input placeholder="ticket count" type="number" name="ticket_count" value="" id="ticket_count"/>
    <input placeholder="offer description" type="textarea" name="offer_descr" value="" id="toffer_descr"/>

    <br/>
    <input type="submit" name="submit" value="Register" id="register_button" maxlength="100"/>

    <?php
    echo form_close(); ?>
</div>