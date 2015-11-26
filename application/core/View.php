<?php

declare(strict_types = 1);

class View
{
    /**
     * Render the view from the controller.
     * @param string $filename Path of the view, (eg. folder/file(.php))
     * @param array $data View data
     */
    public function render($filename, $data = null)
    {
        if ($data) {
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
        require Config::get('PATH_VIEW') . 'templates/header.php';
        require Config::get('PATH_VIEW') . $filename . '.php';
        require Config::get('PATH_VIEW') . 'templates/footer.php';
    }

}