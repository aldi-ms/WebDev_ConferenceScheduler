<!doctype html>
<html>
<head>
    <title>Conference Scheduler</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon -->
    <link rel="icon" href="<?php echo Config::get('URL'); ?>favicon.ico">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/style.css">
</head>
<body>
<!-- jquery.min.js and bootstrap.min.js -->
<script src="<?php echo Config::get('URL'); ?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo Config::get('URL'); ?>bower_components/bootstrap/js/transition.js"></script>
<script src="<?php echo Config::get('URL'); ?>bower_components/bootstrap/js/collapse.js"></script>
<script src="<?php echo Config::get('URL'); ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<div class="wrapper">

    <!-- navigation -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo Config::get('URL'); ?>">Conference Scheduler</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <!-- selection is marked with class="active" -->
                    <?php if (!LoginModel::isUserLoggedIn()) {
                        ?><li><a href="<?php echo Config::get('URL'); ?>login">Login</a></li>
                        <li><a href="<?php echo Config::get('URL'); ?>login/register">Register</a></li><?php
                    } ?>
                    <li><a href="#">Placeholder</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo Config::get('URL'); ?>login/showProfile">Show profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo Config::get('URL'); ?>login/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- show logged in user here -->
                <ul class="nav navbar-nav navbar-right">
                    <?php if (LoginModel::isUserLoggedIn()) {
                        ?>
                        <p class="navbar-text">Signed in as <?php  ?></p><?php
                    } ?>
                    <li><a href="#">Placeholder</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Placeholder Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <!-- my account -->
    <ul class="navigation right">
        <?php if (Session::userIsLoggedIn()) : ?>
            <span class="label label-info">User is logged in.</span>
            <?php if (Session::get("user_account_type") == 7) : ?>
                <span class="label label-info">User is admin.</span>
            <?php endif; ?>
        <?php endif; ?>
    </ul>