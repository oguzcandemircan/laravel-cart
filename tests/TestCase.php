<?php

namespace OguzcanDemircan\LaravelCart\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use OguzcanDemircan\LaravelCart\LaravelCartServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use OguzcanDemircan\LaravelCart\LaravelCart;
use OguzcanDemircan\LaravelCart\Models\Cart;
use OguzcanDemircan\LaravelCart\Tests\Models\TestProduct;
use OguzcanDemircan\LaravelCart\Tests\Models\TestUser;

use Spatie\LaravelPackageTools\Package;

class TestCase extends Orchestra
{
    public $user;

    public function setUp(): void
    {
        parent::setUp();

        // $this->refreshApplication();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'OguzcanDemircan\\LaravelCart\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        // Note: this also flushes the cache from within the migration
        $this->setUpDatabase($this->app);

        $this->user = TestUser::first();
        $this->product = TestProduct::first();
        $this->cart = new Cart;

        $this->laravelCart = app(LaravelCart::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelCartServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        include_once __DIR__ . '/../database/migrations/create_laravel-cart_table.php.stub';
        $app['db']->connection()->getSchemaBuilder()->create('test_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price');
            $table->json('options');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('test_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });

        (new \CreateLaravelCartTable)->up();

        Artisan::call('migrate');
        TestUser::create([
            'name' => 'test_user',
            'email' => 'user@test.com',
        ]);

        TestProduct::create([
            'name' => '1test',
            'price' => 100,
            'quantity' => 10,
            'options' => [],
        ]);
    }
}
