<div class="page-header text-center">
    <h1>Conference Showcase</h1>
</div>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Conference: <?= $data[0]['conference_title']; ?></h3>
        </div>
        <div class="panel-body">Venue: <?= $data[0]['venue_name']; ?><br />
            Lectures:
    <?php
    foreach($data as $row) { ?>
        <?php if (!empty($row['speaker_name']) && !empty($row['lecture_title'])) { ?>
            <br />
        <?= $row['lecture_title'] ?> with speaker <strong><?= $row['speaker_name']; ?></strong>, starts at
        <?=date('H:i d.m.Y', $row['lecture_start']); ?>, ends at <?= date('H:i d.m.Y', $row['lecture_end']); ?>
    <?php }
        else { ?>
            No lectures found
        <?php }
            } ?>
        </div>
        <div class="panel-footer"><small>Created by: <?= $data[0]["created_by_user_name"]; ?></small></div>
    </div>
</div>
