# Laravel Visual Diff

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jamesking56/laravel-visual-diff.svg?style=flat-square)](https://packagist.org/packages/jamesking56/laravel-visual-diff)
[![Build Status](https://img.shields.io/travis/jamesking56/laravel-visual-diff/master.svg?style=flat-square)](https://travis-ci.org/jamesking56/laravel-visual-diff)
[![Quality Score](https://img.shields.io/scrutinizer/g/jamesking56/laravel-visual-diff.svg?style=flat-square)](https://scrutinizer-ci.com/g/jamesking56/laravel-visual-diff)
[![Total Downloads](https://img.shields.io/packagist/dt/jamesking56/laravel-visual-diff.svg?style=flat-square)](https://packagist.org/packages/jamesking56/laravel-visual-diff)

This package enables you to do local visual testing using [Laravel Dusk](https://laravel.com/docs/dusk). This does not use any third party services
such as Percy.

## Requirements

- PHP 7.3+
- Laravel 8+
- NPX (from NPM) installed and available in `$PATH`

## Installation

You can install the package via composer as a dev dependency:

```bash
composer require jamesking56/laravel-visual-diff
```

## Usage

1. Call the new method `->visualDiff()` for any loaded page in Dusk you want to check for visual changes:

```php
class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->visualDiff();
        });
    }
}
```
*Optionally you can name the screenshot: `->visualDiff($name)`*

2. On the first run, no screenshots will be available to be checked so no diff checking will be done.

3. On subsequent runs, the screenshot file will exist and therefore can be diff checked.

4. If you intentionally want to break the design, update the screenshots:

```
vendor/bin/phpunit -d --update-screenshots
```

5. **We recommend committing the screenshot files to Git**, so that the latest screenshot is always available as a reference.

### Testing

``` bash
composer tests
```

### Changelog

Please see [GitHub Releases]() for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email james@jamesking.dev instead of using the issue tracker.

## Credits

- [James King](https://github.com/Jamesking56)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
