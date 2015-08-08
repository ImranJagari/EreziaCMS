<?php namespace App\Services;

class DofusForge
{
    static public function asset($url)
    {
        return config('dofus.web-api') . 'forge/' . $url;
    }

    static public function text($id)
    {
        return file_get_contents(config('dofus.web-api') . 'text/' . $id);
    }
}
