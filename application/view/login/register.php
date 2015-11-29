<div class="container">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <!-- login box on left side -->
    <div class="col-xs-offset-4" style="width: 50%; display: block;">
        <h2>Register a new account</h2>

        <!-- register form -->
        <form role="form" class="form-group" method="post" action="<?php echo Config::get('URL'); ?>login/register_action">
            <!-- the user name input field uses a HTML5 pattern check -->
            <label for="user-name" class="control-label">
                <input id="user-name" class="form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Username" required />
            </label>
            <br />

            <label class="control-label" for="email">
                <input id="email" class="form-control" type="text" name="user_email" placeholder="email address" required />
            </label>
            <br />

            <label class="control-label" for="password">
                <input id="password" class="form-control" type="password" name="user_password" pattern=".{6,}" placeholder="Password (6+ characters)" required autocomplete="off" />
            </label>
            <br />

            <label class="control-label" for="password-repeat">
                <input id="password-repeat" class="form-control" type="password" name="user_password_repeat" pattern=".{6,}" required placeholder="Repeat your password" autocomplete="off" />
            </label>
            <br />

            <input type="submit" value="Register" class="btn btn-primary" />
        </form>
    </div>
</div>