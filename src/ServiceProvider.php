<?php namespace JeroenNoten\LaravelNewsletter;

use Http\Adapter\Guzzle6\Client;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\ServiceProvider as AdminLteServiceProvider;
use JeroenNoten\LaravelCkEditor\ServiceProvider as CkEditorServiceProvider;
use JeroenNoten\LaravelPackageHelper\ServiceProviderTraits\Config;
use JeroenNoten\LaravelPackageHelper\ServiceProviderTraits\Migrations;
use JeroenNoten\LaravelPackageHelper\ServiceProviderTraits\Views;
use Mailgun\Mailgun;

class ServiceProvider extends BaseServiceProvider
{
    use Views, Config, Migrations;

    public function boot(Registrar $route, Dispatcher $events)
    {
        $this->loadViews();

        $viewsPath = "{$this->path()}/resources/views/mails";

        $this->publishes([
            $viewsPath => base_path("resources/views/vendor/{$this->name()}/mails"),
        ], 'mails');

        $this->publishConfig();
        $this->publishMigrations();
        $this->routes($route);
        $this->menu($events);
    }

    public function register()
    {
        $this->app->singleton(Mailgun::class, function () {
            $client = new Client();
            return new Mailgun(config('services.mailgun.secret'), $client);
        });

        $this->app->register(AdminLteServiceProvider::class);
        $this->app->register(CkEditorServiceProvider::class);
    }

    protected function path(): string
    {
        return __DIR__ . '/..';
    }

    protected function name(): string
    {
        return 'newsletter';
    }

    private function routes(Registrar $route)
    {
        $route->group([
            'prefix' => 'api/newsletter',
            'namespace' => __NAMESPACE__ . '\Http\Controllers',
            'middleware' => 'api',
        ], function (Registrar $route) {
            $route->post('members', 'NewsletterController@subscribe');
        });

        $route->group([
            'prefix' => 'admin/newsletters',
            'namespace' => __NAMESPACE__ . '\Http\Controllers',
            'middleware' => 'web',
            'as' => 'admin.newsletters.'
        ], function (Registrar $route) {
            $route->get('/', 'NewsletterAdminController@index')->name('index');
            $route->get('/create', 'NewsletterAdminController@create')->name('create');
            $route->get('/{newsletter}', 'NewsletterAdminController@show')->name('show');
            $route->post('/', 'NewsletterAdminController@store')->name('store');
            $route->post('/send', 'NewsletterAdminController@storeAndSend')->name('store_and_send');
            $route->post('/{newsletter}', 'NewsletterAdminController@update')->name('update');
            $route->post('/{newsletter}/send', 'NewsletterAdminController@updateAndSend')->name('update_and_send');
        });
    }

    private function menu(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text' => 'Nieuwsbrieven',
                'url' => 'admin/newsletters'
            ]);
        });
    }
}