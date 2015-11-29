<div class="page-header">
    <h1>Create conference</h1>
</div>

<div class="container">
<form role="form" class="form-group" method="post" action="<?php echo Config::get('URL'); ?>conference/create_action">
        <label for="conf-title">Title
            <input id="conf-title" name="conference_title" type="text" class="form-control" placeholder="Conference title" aria-describedby="conf-title" required>
        </label>
        <br />
        <label for="conf-venue">Venue (will be create if not existent)
            <input id="conf-venue" name="conference_venue" type="text" class="form-control" placeholder="Conference venue" aria-describedby="conf-venue" required>
        </label>
        <br />
        <input type="submit" value="Create" class="btn btn-primary" />
</form>
</div>
