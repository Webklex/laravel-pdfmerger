# Laravel PDFMerger

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]


## Install

Via Composer

``` bash
$ composer require webklex/laravel-pdfmerger
```

## Setup

Add the service provider to the providers array in `config/app.php`.

``` php
'providers' => [
    ...
    Webklex\PDFMerger\Providers\PDFMergerServiceProvider::class
],

'aliases' => [
    ...
    'PDFMerger' => Webklex\PDFMerger\Facades\PDFMergerFacade::class
]
```

## Usage

``` php
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

$oPDF = PDFMerger::init();

$oPDF->setFileName('example.pdf');
$oPDF->addPDF('first_pdf.pdf', '1');
$oPDF->addPDF('second_pdf.pdf', 'all');
$oPDF->merge();
$oPDF->save('/somewhere/merged_result.pdf');

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email github@webklex.com instead of using the issue tracker.

## Credits

- [Webklex][link-author]
- All Contributors

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/Webklex/PDFMerger.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Webklex/translator/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Webklex/PDFMerger.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Webklex/PDFMerger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Webklex/PDFMerger.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Webklex/PDFMerger
[link-travis]: https://travis-ci.org/Webklex/PDFMerger
[link-scrutinizer]: https://scrutinizer-ci.com/g/Webklex/PDFMerger/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Webklex/PDFMerger
[link-downloads]: https://packagist.org/packages/Webklex/PDFMerger
[link-author]: https://github.com/webklex