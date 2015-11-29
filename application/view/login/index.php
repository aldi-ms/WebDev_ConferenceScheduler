<div class="container">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="row text-center col-md-6 col-md-offset-2">
        <!-- login box  -->
        <div class="col-xs-6">
            <h2>Login here</h2>
            <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
                <input type="text" name="user_name" placeholder="Username or email" required />
                <br />
                <input type="password" name="user_password" placeholder="Password" required />
                <br />
                <label for="set_remember_me_cookie" class="remember-me-label">
                    <input type="checkbox" name="set_remember_me_cookie" class="remember-me-checkbox" />
                    Remember me
                </label>
                <?php if (!empty($this->redirect)) { ?>
                    <input type="hidden" name="redirect" value="<?php echo $this->redirect ?>" />
                <?php } ?>
                <br />
                <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                <input type="submit" class="login-submit-button" value="Log in"/>
            </form>
        </div>

        <!-- register box -->
        <div class="col-xs-6">
            <h2>No account yet ?</h2>
            <a href="<?php echo Config::get('URL'); ?>login/register">Register</a>
        </div>
    </div>
</div>