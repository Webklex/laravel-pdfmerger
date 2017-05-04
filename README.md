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
A basic usage example:

``` php
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

$oMerger = PDFMerger::init();

$oMerger->addPDF('/path/to/project/vendors/webklex/laravel-pdfmerger/src/PDFMerger/examples/pdf_one.pdf', [2]);
$oMerger->addPDF('/path/to/project/vendors/webklex/laravel-pdfmerger/src/PDFMerger/examples/pdf_two.pdf', 'all');

$oMerger->merge();
$oMerger->save('merged_result.pdf');

```

...add raw content data:

``` php
$oMerger->addString(file_get_contents('/path/to/project/vendors/webklex/laravel-pdfmerger/src/PDFMerger/examples/pdf_two.pdf'), [1]);

```

...select the pages you want to merge:

``` php
$oMerger->addPDF($file, 'all');  //Add all pages
$oMerger->addPDF($file, [1]);    //Add page one only
$oMerger->addPDF($file, [2]);    //Add page two only
$oMerger->addPDF($file, [1, 3]); //Add page one and three only

```

...merge files together but add blank pages to support duplex printing:
```php
$oMerger->duplexMerge();
```

...stream the merged content:

``` php
$oMerger->stream();

```
...download the merged content:

``` php
$oMerger->download();

```
..get the raw content data:
``` php
echo $oMerger->output();

```
...set the filename if you don't want to do it later:

``` php
$oMerger->setFileName('example.pdf');

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

[ico-version]: https://img.shields.io/packagist/v/Webklex/laravel-pdfmerger.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Webklex/translator/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Webklex/laravel-pdfmerger.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Webklex/laravel-pdfmerger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Webklex/laravel-pdfmerger.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/Webklex/laravel-pdfmerger
[link-travis]: https://travis-ci.org/Webklex/laravel-pdfmerger
[link-scrutinizer]: https://scrutinizer-ci.com/g/Webklex/laravel-pdfmerger/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Webklex/laravel-pdfmerger
[link-downloads]: https://packagist.org/packages/Webklex/laravel-pdfmerger
[link-author]: https://github.com/webklex
