<?php


namespace JeroenNoten\LaravelNewsletter\MailingLists;


use Illuminate\Foundation\Application;
use JeroenNoten\LaravelNewsletter\Mailgun\MailingList;

class Manager
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function setDefaultId($listId)
    {
        $path = $this->getDefaultSettingPath();

        $this->ensureFolderExists($path);

        file_put_contents($path, $listId);
    }

    public function getDefaultId()
    {
        $path = $this->getDefaultSettingPath();
        if (!file_exists($path)) {
            return null;
        }
        return file_get_contents($path);
    }

    private function getDefaultSettingPath()
    {
        return $this->app->storagePath() . DIRECTORY_SEPARATOR . 'newsletter/settings/default_list.txt';
    }

    private function ensureFolderExists($path)
    {

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
    }
}