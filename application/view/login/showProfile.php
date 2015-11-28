<div class="container">
    <h1>LoginController/showProfile</h1>

    <div class="box">
        <h2>Your profile</h2>

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <div>Your username: <?= $this->user_name; ?></div>
        <div>Your email: <?= $this->user_email; ?></div>
        <div>Your account type is: <?php if ($this->user_account_type == 1) {
                echo 'user';
            } elseif ($this->user_account_type == 7) {
                echo 'site administrator';
            } ?></div>
    </div>
</div>