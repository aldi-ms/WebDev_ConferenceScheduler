<div class="container">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="row text-center col-md-6 col-md-offset-2">
        <!-- login box  -->
        <div class="col-xs-6">
            <h2>Login here</h2>
            <form role="form" class="form-group" action="<?php echo Config::get('URL'); ?>login/login" method="post">
                <label class="control-label" for="username">Username
                    <input class="form-control" id="username" type="text" name="user_name" placeholder="Username" required />
                </label>
                <br />
                <label class="control-label" for="password">Password
                    <input class="form-control" id="password" type="password" name="user_password" placeholder="Password" required />
                </label>
                <br />
                <label for="cookie" class="control-label">Remember me
                    <input class="checkbox pull-left" id="cookie" type="checkbox" name="set_remember_me_cookie" />
                </label>
                <?php if (!empty($this->redirect)) { ?>
                    <input class="form-control" type="hidden" name="redirect" value="<?php echo $this->redirect ?>" />
                <?php } ?>
                <br />
                <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                <input type="submit" class="btn btn-primary" value="Log in"/>
            </form>
        </div>

        <!-- register box -->
        <div class="col-xs-6">
            <h2>No account yet ?</h2>
            <a class="btn btn-primary text-center" href="<?php echo Config::get('URL'); ?>login/register">Register</a>
        </div>
    </div>
</div>