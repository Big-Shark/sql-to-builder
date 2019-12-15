# sql-to-builder

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package will help you convert from sql query to laravel query builder

## Install

Via Composer

``` bash
$ composer require big-shark/sql-to-builder
```

## Usage

``` php
$builder = new \BigShark\SQLToBuilder\BuilderClass('SELECT a, b, c  FROM some_table WHERE d > 5');
echo $builder->convert();//"DB::table('some_table')->select('a', 'b', 'c')->where('d', '>', 5)->get()"
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email BigShark666@gmail.com instead of using the issue tracker.

## Credits

- [Maxim Soloviev][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/big-shark/sql-to-builder.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Big-Shark/sql-to-builder/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/big-shark/sql-to-builder.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/big-shark/sql-to-builder.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/big-shark/sql-to-builder.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/big-shark/sql-to-builder
[link-travis]: https://travis-ci.org/big-shark/sql-to-builder
[link-scrutinizer]: https://scrutinizer-ci.com/g/big-shark/sql-to-builder/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/big-shark/sql-to-builder
[link-downloads]: https://packagist.org/packages/big-shark/sql-to-builder
[link-author]: https://github.com/Big-Shark
[link-contributors]: ../../contributors
