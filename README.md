Symfony-API Test project:
========================

Symfony 3.3  
php 7.1  
mysql 5.7  


[FrameworkBundle Configuraton ("framework")](https://symfony.com/doc/current/reference/configuration/framework.html)

Framework skeleton to build API.

JSON is in use for output.

format example:
```json
{
  "success":true,
  "data": [{}, {}],
  "errors": null,
  "meta": {
      "total": 10,
      "count": 7,
      "limit": 7,
      "offset": 3
  }
  
}
```

JWT is used for authentication. Currently [php-jwt](https://github.com/firebase/php-jwt) package is in use.

Install:
--------

```bash
composer install
```

Usage:
-----

Run unit testing:
```bash
./vendor/bin/phpunit
```

By default JWT authentication is implemented in request listener 
under ```src/AppBundle/EventListener/AppRequestListener.php```
in case another authentication method is in use change parameter
*jwt_auth* to *false*

