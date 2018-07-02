<div style="background: #0071B8 url('README.header.png') repeat-x 0 0; padding: 0; margin: 0"><div style="background: transparent url('README.logo.png') no-repeat 0 0; height: 201px; padding-left: 250px; color: #A0CAE4; display: table-cell; vertical-align: middle;">
<h1 style="border-width: 0 0 0 0">Symfony Up!</h1>
<p style="font-size: larger;">Up your Symfony App!</p>
</div></div>


## Getting started

Run `composer require netpromotion/symfony-up` and optionally `./vendor/bin/symfony-up` which creates following files:

### `.env.dist`

```ini
# This file is a "template" of which env vars need to be defined for your application
# Copy this file to .env file for development, create environment variables when deploying to production
# https://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration

###> symfony/framework-bundle ###
APP_ENV='dev'
APP_SECRET='ThisIsNotSoSecretChangeIt'
#TRUSTED_PROXIES='127.0.0.1,127.0.0.2'
#TRUSTED_HOSTS='localhost,example.com'
###< symfony/framework-bundle ###
```

### `config/packages/framework.yaml`

```yaml
framework:
  # secret is commonly used to add more entropy to security related operations
  secret: '%env(APP_SECRET)%'

  # session.save_path is the path where the session files are created
  session:
    save_path: '%kernel.project_dir%/var/session/%kernel.environment%'

  # http_method_override determines whether the _method request parameter is used as the intended HTTP method on POST requests
  http_method_override: true

  # php_errors.log determines whether application logger is used instead of the PHP logger for logging PHP errors
  php_errors:
    log: true
```

### `config/bundles.php`

```php
<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;

return [
    FrameworkBundle::class => ['all' => true],
    // TODO add more bundles here
];
```

### `config/routes/annotations.yaml`

```yaml
#controllers:
#  resource: ../../src/Controller/
#  type: annotation
```

### `config/services.yaml`

```yaml
services:
  # _defaults.autowire determines whether it automatically injects dependencies in your services
  # _defaults.autoconfigure determines whether it automatically registers your services as commands, event subscribers, etc.
  # _defaults.public determines whether it allows optimizing the container by removing unused services
  _defaults:
    autowire: true
    autoconfigure: true
    public: false

  App\:
      resource: '../src/*'
      exclude: '../src/{Entity,Migrations,Tests,Kernel.php}'

  App\Controller\:
      resource: '../src/Controller'
      tags: ['controller.service_arguments']
```

### `src/Kernel.php`

```php
<?php

namespace App;

use Netpromotion\SymfonyUp\UpKernelTrait;
use Netpromotion\SymfonyUp\UpKernel;

class Kernel extends UpKernel
{
    use UpKernelTrait;

    public function getProjectDir()
    {
        return __DIR__ . '/..';
    }
}
```

### `tests/TestCase.php`

```php
<?php

namespace App\Test;

use App\Kernel;
use Netpromotion\SymfonyUp\UpTestCase;

class TestCase extends UpTestCase
{
    protected static function getKernelClass()
    {
        return Kernel::class;
    }
}
```

### `public/index.php`

```php
<?php

use App\Kernel;
use Netpromotion\SymfonyUp\SymfonyUp;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

Request::enableHttpMethodParameterOverride(); // remove this line if kernel.http_method_override = false

SymfonyUp::createFromKernelClass(Kernel::class)
    ->loadEnvironmentIfNeeded(__DIR__ . '/../.env') // remove this line if you are using parameters instead of dotenv
    ->runWeb();
```

### `public/.htaccess`

```apacheconfig
DirectoryIndex index.php

# Uncomment the following line if you experience problems related to symlinks
# Options FollowSymlinks

<IfModule mod_negotiation.c>
    Options -MultiViews
</IfModule>

<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$
    RewriteRule ^(.*) - [E=BASE:%1]

    RewriteCond %{HTTP:Authorization} .
    RewriteRule ^ - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{ENV:REDIRECT_STATUS} ^$
    RewriteRule ^index\.php(?:/(.*)|$) %{ENV:BASE}/$1 [R=301,L]

    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^ - [L]

    RewriteRule ^ %{ENV:BASE}/index.php [L]
</IfModule>

<IfModule !mod_rewrite.c>
    <IfModule mod_alias.c>
        RedirectMatch 302 ^/$ /index.php/
    </IfModule>
</IfModule>
```

### `bin/console`

```php
#!/usr/bin/env php
<?php

use App\Kernel;
use Netpromotion\SymfonyUp\SymfonyUp;

require_once __DIR__ . '/../vendor/autoload.php';

SymfonyUp::createFromKernelClass(Kernel::class)
    ->loadEnvironmentIfNeeded(__DIR__ . '/../.env') // remove this line if you are using parameters instead of dotenv
    ->runConsole();
```

### `phpunit.xml`

```xml
<phpunit bootstrap="./vendor/autoload.php" colors="true">
    <testsuites>
        <testsuite>
            <directory suffix="Test.php">./tests/</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="FOO" value="bar" />
    </php>
</phpunit>
```
