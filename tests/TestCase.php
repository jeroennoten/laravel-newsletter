<?php


use Illuminate\Foundation\Auth\User;
use JeroenNoten\LaravelNewsletter\Mailgun\Mailgun;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;
use JeroenNoten\LaravelNewsletter\Mailgun\MailingList;
use JeroenNoten\LaravelNewsletter\Models\Newsletter;
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

    protected function mockMailgun()
    {
        $mailgunMock = Mockery::mock(Mailgun::class);
        $mailgunMock->shouldReceive('getList')->andReturn(new MailingList((object)[
            'name' => '',
            'description' => '',
            'members_count' => '',
            'address' => '',
        ]));
        $mailgunMock->shouldReceive('lists')->andReturn([]);
        $this->app->instance(MailgunInterface::class, $mailgunMock);
    }

    /**
     * @return Newsletter
     */
    protected function createNewsletter()
    {
        return factory(Newsletter::class)->create();
    }
}