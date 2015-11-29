<div class="page-header">
    <h1>Conferences list</h1>
</div>
<div class="container">
    <?php
    foreach($data as $row) { ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $row['title']; ?></h3>
            </div>
            <div class="panel-body">Venue: <?= $row['venue_name']; ?></div>
            <div class="panel-footer"><small>Created by: <?= $row["user_name"]; ?></small></div>
        </div>
    <?php } ?>
</div>
