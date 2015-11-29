<div class="container">
    <div class="page-header">
        <h1>Profile info</h1>
    </div>
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="pull-left col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Your profile</h3>
            </div>
            <div class="panel-body">
                <div>Your username: <strong style="padding-left:1em;"><?= $this->user_name; ?></strong></div>
                <div>Your email: <strong style="padding-left:1em;"><?= $this->user_email; ?></strong></div>
                <div>Your account type is: <strong style="padding-left:1em;"><?php if ($this->user_account_type == 1) {
                        echo 'user';
                    } elseif ($this->user_account_type == 7) {
                        echo 'site administrator';
                    } ?></strong></div>
            </div>
        </div>

    </div>
</div>