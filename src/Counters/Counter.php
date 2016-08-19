<?php

namespace JeroenNoten\LaravelNewsletter\Counters;

use JeroenNoten\LaravelNewsletter\Files\SettingsFile;

abstract class Counter extends SettingsFile
{
    public function add($count)
    {
        $this->set($this->get() + $count);
    }

    private function set($count)
    {
        $this->write($count);
    }

    private function get()
    {
        return (int)$this->read();
    }

    public function getFilename()
    {
        return $this->getName();
    }

    protected abstract function getName();
}