<div class="page-header text-center">
    <h1>Conference Showcase</h1>
</div>
<div class="container">
    <form role="form" class="form-group" method="post" action="<?php echo Config::get('URL'); ?>conference/edit_action/<?= $data[0]["conference_id"]; ?>">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <label class="control-label" for="conf-title">Conference title
                <input id="conf-title" name="conference_title" type="text" class="form-control" value="<?= $data[0]['conference_title']; ?>" required>
            </label>
        </div>
        <div class="panel-body">
            <label class="control-label" for="conf-venue">Venue (will be created if not existent)
                <input id="conf-venue" name="conference_venue" type="text" class="form-control" value="<?= $data[0]['venue_name']; ?>" required>
            </label>
            <br />
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
        <div class="btn-group">
            <input type="submit" value="Save" class="btn btn-danger" />
            <a href="<?php echo Config::get('URL'); ?>conference" class="btn btn-primary">Cancel</a>
        </div>
        <a href="<?php echo Config::get('URL'); ?>lecture/create/<?= $data[0]["conference_id"]; ?>" class="btn btn-primary">Add Lecture</a>
    </form>
</div>