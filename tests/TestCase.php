<?php


use JeroenNoten\LaravelNewsletter\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}