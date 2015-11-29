<div class="container col-xs-offset-4">
<div class="page-header">
    <h1>Create conference</h1>
</div>

<form role="form" class="form-group" method="post" action="<?php echo Config::get('URL'); ?>conference/create_action">
        <label class="control-label" for="conf-title">Title
            <input id="conf-title" name="conference_title" type="text" class="form-control" placeholder="Conference title" required>
        </label>
        <br />
        <label class="control-label" for="conf-venue">Venue (will be created if not existent)
            <input id="conf-venue" name="conference_venue" type="text" class="form-control" placeholder="Conference venue" required>
        </label>
        <br />
        <input type="submit" value="Create" class="btn btn-primary" />
</form>
</div>
