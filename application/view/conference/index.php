<div class="page-header text-center">
    <h1>Conferences list</h1>
</div>
<div class="container">
    <?php
    foreach($data as $row) { ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="btn-group btn-group-xs pull-right" role="group" aria-label="...">
                    <a href="<?php echo Config::get('URL'); ?>conference/show/<?= $row["conference_id"] ?>" class="btn btn-default">View</a>
                    <?php if (Session::get('user_id') == $row["user_id"] || Session::get('user_account_type') == 7) { ?>
                    <a href="<?php echo Config::get('URL'); ?>conference/edit/<?= $row["conference_id"] ?>" class="btn btn-default">Edit</a>
                    <a href="<?php echo Config::get('URL'); ?>conference/delete/<?= $row["conference_id"] ?>" class="btn btn-danger">Delete</a>
                    <?php } ?>
                </div>
                <h3 class="panel-title"><?= $row['title']; ?></h3>
            </div>
            <div class="panel-body">Venue: <?= $row['venue_name']; ?></div>
            <div class="panel-footer"><small>Created by: <?= $row["user_name"]; ?></small></div>
        </div>
    <?php } ?>
</div>
