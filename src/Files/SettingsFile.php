<?php

namespace JeroenNoten\LaravelNewsletter\Files;

use Illuminate\Foundation\Application;

abstract class SettingsFile
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public abstract function getFilename();

    public function write($contents)
    {
        $path = $this->getPath();

        $this->ensureFolderExists($path);

        file_put_contents($path, $contents);
    }

    public function read()
    {
        $path = $this->getPath();
        if (!file_exists($path)) {
            return null;
        }
        return file_get_contents($path);
    }

    private function getPath()
    {
        return $this->app->storagePath() . DIRECTORY_SEPARATOR . "newsletter/settings/{$this->getFilename()}";
    }

    private function ensureFolderExists($path)
    {
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
    }
}