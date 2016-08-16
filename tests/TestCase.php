<?php


use Illuminate\Foundation\Auth\User;
use JeroenNoten\LaravelNewsletter\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);

        $this->withFactories(__DIR__.'/../database/factories');
    }

    public function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function login()
    {
        $this->actingAs(new User);
    }
}