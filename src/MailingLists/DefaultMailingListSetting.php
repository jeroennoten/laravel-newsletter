<?php


namespace JeroenNoten\LaravelNewsletter\MailingLists;


use JeroenNoten\LaravelNewsletter\Files\SettingsFile;

class DefaultMailingListSetting extends SettingsFile
{
    public function getFilename()
    {
        return 'default_list.txt';
    }
}