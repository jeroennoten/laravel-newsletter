<?php namespace JeroenNoten\LaravelNewsletter;

use Collective\Bus\BusServiceProvider;
use GuzzleHttp\Client as Guzzle;
use Http\Adapter\Guzzle6\Client;
use Illuminate\Contracts\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\ServiceProvider as AdminLteServiceProvider;
use JeroenNoten\LaravelCkEditor\ServiceProvider as CkEditorServiceProvider;
use JeroenNoten\LaravelFormat\ServiceProvider as FormatServiceProvider;
use JeroenNoten\LaravelNewsletter\Counters\BriteVerifyCounter;
use JeroenNoten\LaravelNewsletter\EmailValidation\BriteVerify;
use JeroenNoten\LaravelNewsletter\Mailgun\CachingMailgun;
use JeroenNoten\LaravelNewsletter\Mailgun\Mailgun;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;
use JeroenNoten\LaravelPackageHelper\ServiceProviderTraits;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\ExcelServiceProvider;
use Mailgun\Mailgun as BaseMailgun;

class ServiceProvider extends BaseServiceProvider
{
    use ServiceProviderTraits;

    public function boot(Router $router, Dispatcher $events)
    {
        $this->loadViews();

        $viewsPath = "{$this->path()}/resources/views/mails";

        $this->publishes([
            $viewsPath => base_path("resources/views/vendor/{$this->name()}/mails"),
        ], 'mails');

        $this->publishPublicAssets();

        $this->publishConfig();
        $this->publishMigrations();
        $this->routes($router);
        $this->menu($events);
    }

    public function register()
    {
        $this->app->singleton(BaseMailgun::class, function () {
            return new BaseMailgun(
                config('services.mailgun.secret'),
                new Client(),
                config('services.mailgun.api_endpoint', 'api.mailgun.net')
            );
        });

        $this->app->singleton(Mailgun::class, function () {
            return new Mailgun(
                app(BaseMailgun::class),
                config('services.mailgun.domain')
            );
        });

        $this->app->singleton(MailgunInterface::class, CachingMailgun::class);

        $this->app->singleton(BriteVerify::class, function () {
            return new BriteVerify(
                new Guzzle(),
                config('newsletter.brite_verify_secret'),
                $this->app->make(BriteVerifyCounter::class)
            );
        });

        $this->app->register(AdminLteServiceProvider::class);
        $this->app->register(CkEditorServiceProvider::class);
        $this->app->register(FormatServiceProvider::class);
        $this->app->register(BusServiceProvider::class);
        $this->app->register(ExcelServiceProvider::class);

        $this->app->alias('excel', Excel::class);
    }

    protected function path(): string
    {
        return __DIR__ . '/..';
    }

    protected function name(): string
    {
        return 'newsletter';
    }

    private function routes(Router $router)
    {
        $router->group([
            'prefix' => 'api/newsletter',
            'namespace' => __NAMESPACE__ . '\Http\Controllers',
            'middleware' => 'api',
        ], function (Router $router) {
            $router->post('members', 'NewsletterController@subscribe');
        });

        $router->group([
            'prefix' => 'admin',
            'namespace' => __NAMESPACE__ . '\Http\Controllers\Admin',
            'middleware' => 'web',
            'as' => 'admin.newsletters.'
        ], function (Router $router) {

            $router->group([
                'prefix' => 'newsletters'
            ], function (Router $router) {
                $router->get('/', 'Newsletters@index')->name('index');
                $router->get('/create', 'Newsletters@create')->name('create');
                $router->get('/{newsletter}', 'Newsletters@show')->name('show');
                $router->get('/{newsletter}/body', 'Newsletters@body')->name('show.body');
                $router->post('/', 'Newsletters@store')->name('store');
                $router->post('/preview', 'Newsletters@preview')->name('preview');
                $router->post('/send', 'Newsletters@storeAndSend')->name('store_and_send');
                $router->post('/{newsletter}', 'Newsletters@update')->name('update');
                $router->post('/{newsletter}/send', 'Newsletters@updateAndSend')->name('update_and_send');
                $router->delete('/{newsletter}', 'Newsletters@destroy')->name('destroy');
            });

            $router->group([
                'prefix' => 'newsletter-lists',
                'as' => 'lists.'
            ], function (Router $router) {

                $router->get('/', 'Lists@index')->name('index');
                $router->get('/create', 'Lists@create')->name('create');
                $router->post('/', 'Lists@store')->name('store');
                $router->put('/default', 'Lists@setDefault')->name('default');
                $router->get('/{list}', 'Lists@show')->name('show');
                $router->get('/{list}/edit', 'Lists@edit')->name('edit');
                $router->put('/{list}', 'Lists@update')->name('update');
                $router->delete('/{list}', 'Lists@destroy')->name('destroy');

                $router->get('/{list}/members', 'Members@index');
                $router->post('/{list}/members', 'Members@store')->name('members.store');
                $router->delete('/{list}/members/{member}', 'Members@destroy')->name('members.destroy');

                $router->post('/parse', 'Parser@parse');

            });

        });
    }

    private function menu(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text' => 'Nieuwsbrieven',
                'icon' => 'newspaper-o',
                'submenu' => [
                    [
                        'text' => 'Nieuwsbrieven versturen',
                        'url' => 'admin/newsletters',
                        'icon' => 'send',
                    ],
                    [
                        'text' => 'Verzendlijsten',
                        'url' => 'admin/newsletter-lists',
                        'icon' => 'list',
                    ]
                ]
            ]);
        });
    }

    /**
     * Return the container instance
     *
     * @return Container
     */
    protected function getContainer()
    {
        return $this->app;
    }
}
