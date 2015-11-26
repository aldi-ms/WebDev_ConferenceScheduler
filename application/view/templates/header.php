<!doctype html>
<html>
<head>
    <title>Conference Scheduler</title>
    <!-- META -->
    <meta charset="utf-8">
    <!-- send empty favicon fallback to prevent user's browser hitting the server for lots of favicon requests resulting in 404s -->
    <link rel="icon" href="data:;base64,=">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/style.css" />
</head>
<body>
<div class="wrapper">

    <!-- navigation -->
    <ul class="navigation">
    </ul>

    <!-- my account -->
    <ul class="navigation right">
        <?php if (Session::userIsLoggedIn()) : ?>
            <span>User is logged in.</span>
            <?php if (Session::get("user_account_type") == 7) : ?>
                <span>User is admin.</span>
            <?php endif; ?>
        <?php endif; ?>
    </ul>