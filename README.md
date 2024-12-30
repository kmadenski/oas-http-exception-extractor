# OAS HTTP Exception Extractor

A PHP package that extracts HTTP exceptions from your controllers to help with OpenAPI/Swagger documentation generation.

## Installation

You can install the package via composer:

```bash
composer require krzysztofmadenski/oas-http-exception-extractor
```

## Usage

The package provides an `ExceptionExtractor` class that analyzes PHP files to extract thrown exceptions:

```php
use App\ExceptionExtractor;


$extractor = new ExceptionExtractor();
$exceptions = $extractor->extract('/src/Controller/UserController.php');
```

## Examples

The package includes example files demonstrating different exception scenarios:

### Multiple Method Controller Example

```php
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MyClass
{
    public function methodFour()
    {
        throw new NotFoundHttpException("Resource not found.");
    }

    public function methodFive()
    {
        throw new AccessDeniedHttpException("Access denied.");
    }
}
```
## Development

The package uses Docker for development. To set up the development environment:

```bash
docker-compose up -d
```

## Note

The `index.php` file in the root directory is only for demonstration purposes and can be safely removed when using this package as a dependency.

## License

This package is open-source software licensed under the MIT license.
