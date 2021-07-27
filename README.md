# wordpress-rest-api-client

> A Wordpress REST API client for PHP

## Installation

This library can be installed with [Composer](https://getcomposer.org):

```text
composer require reactmore/wordpress-rest-api-client
```

## Usage

Example:

```php
require 'vendor/autoload.php';

$client = new Reactmore\WordpressClient\Wordpress(new Reactmore\WordpressClient\Request\GuzzleAdapter(new GuzzleHttp\Client()), 'http://domain.com');

// iF WP API DISABLE YOU NEED TO PASS AUTH
// $client->setCredentials(new Reactmore\WordpressClient\Auth\WpBasicAuth('user', 'password'));

// Argument 1 id default Null
// Argumend 2 Array default Null
$posts = $client->posts()->get(1, ['params' => 'Key']);

echo '<pre>';
print_r($posts);
echo '</pre>';
```

Response:

Body = All respon from Wordpress api
Total = Total Item from X-WP-TOTAL
TotalPages = Total Page From X-WP-TOTALPAGE

```Array
Array
(

    [body] => Array
        (
            xxxxxxxxxx
            xxxxxxxxxx
        )

    [total] => Array
        (
         xxxxxxxxxx
        )

    [totalpages] => Array
        (
         xxxxxxxxxx
        )

)
```

## Screenshoot

![Backend](https://raw.githubusercontent.com/reactmore/wordpress-rest-api-client/master/preview.jpg)
Implementations Paginations
