<div class="container col-xs-offset-4">
    <div class="page-header">
        <h1>Create lecture <br /><small>for conference <?= $data['confName']; ?></small></h1>
    </div>

    <form role="form" class="form-group" method="post" action="<?php echo Config::get('URL'); ?>lecture/create_action/<?= $data['confId']; ?>">
        <label class="control-label" for="lecture-title">Title
            <input id="lecture-title" name="lecture_title" type="text" class="form-control" placeholder="Lecture title" required>
        </label>
        <br />
        <label class="control-label" for="datetime-start">Start date and time
            <input id="datetime-start" name="datetime_start" type="datetime-local" class="form-control" required>
        </label>
        <br />
        <label class="control-label" for="datetime-end">Start date and time
            <input id="datetime-end" name="datetime_end" type="datetime-local" class="form-control" required>
        </label>
        <br />
        <label for="must-visit" class="control-label">Must visit!
            <input class="checkbox pull-left" id="must-visit" type="checkbox" name="must_visit" />
        </label>
        <input type="submit" value="Add" class="btn btn-primary" />
    </form>
</div>
