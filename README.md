# Laravel Search Tools

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

This package provides a consistent user interface for filtering and searching through entries. This doesn't include any backend functionality to implement filtering or searching, but just provides a simple way of including the UI in your Laravel views.

## Requirements

At the moment, this package uses Bootstrap's dropdown component - see [http://getbootstrap.com/](http://getbootstrap.com/) for more information on how to install Bootstrap.

## Installation

To install, either run

``` bash
$ composer require bnjns/laravel-searchtools
```

or add the following to the `require` section of your `composer.json` file:

```
"bnjns/laravel-searchtools": "dev-master"
```

## Setup
 
1. Add the following to the `providers` entry in `config/app.php`:
    ``` php
    bnjns\SearchTools\SearchToolsServiceProvider::class,
    ```
2. Add the following to the `aliases` entry to use the Facade:
    ``` php
    ``` php
       use bnjns\SearchTools\Facades\SearchTools;'SearchTools' => SearchTools::class,
       ```
    ```
3. Run the following to publish the package's views and assets:
	``` bash
	$ php artisan vendor:publish --provider="bnjns\SearchTools\SearchToolsServiceProvider"
	```
	
	**Note:** Steps 1 and 2 are not required for Laravel 5.5+.

## Usage

Most of the settings for the package are automatically retrieved from the `Request` object. Customisation may be introduced in a later version.

### Showing the UI

To render the tools, simply include
 
 ``` php
 {!! SearchTools::render() !!}
 ```
 
 ### Setting the filter options
 
 To set the list of filter options, use `SearchTools::setFilterOptions($options)`. Make sure `$options` is an array where the key is the filter value, and the value is human-readable text.
 
 If you do not want to override any pre-existing options you may have set, you can use `SearchTools::addFilterOption($filter, $text)` to manually add a single option to the end of the array.
 
 ### Showing and hiding tools
 
 This package tries to only show the tools that are relevant, but you can customise which tools to show or hide using `SearchTools::show('name');` and `SearchTools::hide('name');`.
 
 ### Styling the tools
 To include the default styles, you can
 
 1. Include the SCSS file located in `resources/sass` in your own stylesheet
 2. Include the pre-built CSS with `{!! SearchTools::assets() !!}` - this outputs the necessary link tag to include the stylesheet
 
 Alternatively, you can style the tools yourself.
 
 ### Getting the Request values
 
 This package provides a simple way to get the filter or search values for use in your backend logic:
 
 1. Filter value: `SearchTools::filter()`
 2. Search value: `SearchTools::search()`

## License

This package is covered by the GNU General Public License v3. See the [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bnjns/laravel-searchtools.svg?style=flat
[ico-license]: https://img.shields.io/packagist/l/bnjns/laravel-searchtools.svg?style=flat


[link-packagist]: https://packagist.org/packages/bnjns/laravel-searchtools
[link-author]: https://github.com/bnjns
