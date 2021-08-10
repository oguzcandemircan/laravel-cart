# This is my package LaravelCart

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oguzcandemircan/laravel-cart.svg?style=flat-square)](https://packagist.org/packages/oguzcandemircan/laravel-cart)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/oguzcandemircan/laravel-cart/run-tests?label=tests)](https://github.com/oguzcandemircan/laravel-cart/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/oguzcandemircan/laravel-cart/Check%20&%20fix%20styling?label=code%20style)](https://github.com/oguzcandemircan/laravel-cart/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/oguzcandemircan/laravel-cart.svg?style=flat-square)](https://packagist.org/packages/oguzcandemircan/laravel-cart)

Simple Laravel Eloquent Cart Package

## Installation

You can install the package via composer:

```bash
composer require oguzcandemircan/laravel-cart
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="OguzcanDemircan\LaravelCart\LaravelCartServiceProvider" --tag="laravel-cart-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="OguzcanDemircan\LaravelCart\LaravelCartServiceProvider" --tag="laravel-cart-config"
```

This is the contents of the published config file:

```php
return [
    'model_identity_column' => 'id',
    'model_price_column' => 'price',
    'model_quantity_column' => 'quantity',
];
```

## Usage

Add item:
```php
use OguzcanDemircan\LaravelCart\LaravelCart;
use OguzcanDemircan\LaravelCart\LaravelCartItem;
use App\Models\Product;

//Short way:
LaravelCart::item()->make(Product::class, $id)->save();
//Long way:
LaravelCart::item()->make(Product::class, request()->product_id)
    ->quantity(5) // Get default column name from the config or model property and requests the value from request
    ->price(100)  // Get default column name from the config or model property and requests the value from request
    ->options(['size' => 'small', 'color' => 'red']) // Get default column name from the config or model property and requests the value from request
    ->save() // default database
    //or
    ->database()->save()
    ->session()->save()
    ->cookie()->save();
```
Get items:
```php
$items = LaravelCart::items();
//usage
foreach($items as $item) {
    echo $item->id;
    echo $item->model->name;
    echo $item->price;
    echo $item->quantity;
    echo $item->options;
}

```

Remove item:
```php
LaravelCart::item()->remove($id);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Oğuzcan Demircan](https://github.com/oguzcandemircan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
