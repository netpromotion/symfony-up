<div style="background: #0071B8 url('README.header.png') repeat-x 0 0; padding: 0; margin: 0"><div style="background: transparent url('README.logo.png') no-repeat 0 0; height: 201px; padding-left: 250px; color: #A0CAE4; display: table-cell; vertical-align: middle;">
<h1 style="border-width: 0 0 0 0">Symfony Up!</h1>
<p style="font-size: larger;">Package for rapid simplification of Symfony applications and their development</p>
</div></div>


## Getting started

Run `composer require netpromotion/symfony-up` and optionally `./vendor/bin/symfony-up` which creates following files:

### `app/config/parameters.yml`

```yaml
parameters:
  secret: ThisTokenIsNotSoSecretChangeIt
  trusted_hosts: ~
  assets.version: ~
```

### `app/config/config.yml`

```yaml
imports:
  - resource: parameters.yml

framework:
  # secret is commonly used to add more entropy to security related operations
  secret: %secret%

  # http_method_override determines whether the _method request parameter is used as the intended HTTP method on POST requests
  http_method_override: true

  # trusted_hosts are the hosts that application can respond to
  trusted_hosts: %trusted_hosts%

  # assets.version is used to bust the cache on assets
  # assets.version_format specifies a sprintf pattern that will be used with the version option to construct an asset's path
  assets:
    version: %assets.version%
    version_format: %%s?version=%%s

  # php_errors.log determines whether application logger is used instead of the PHP logger for logging PHP errors
  php_errors:
    log: true
```

### `app/config/config_dev.yml`

```yaml
imports:
  - resource: config.yml

framework:
  # profiler.enabled enables profiler for 'dev' environment
  profiler:
    enabled: true
```

### `app/autoload.php`

```php
<?php

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . '/../vendor/netpromotion/symfony-up/src/autoload.php';

$loader->addClassMap([
    AppKernel::class => __DIR__ . '/AppKernel.php',
    AppTestCase::class => __DIR__ . '/../tests/AppTestCase.php',
]);

return $loader;
```

### `app/AppKernel.php`

```php
<?php

use Netpromotion\SymfonyUp\UpKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;

class AppKernel extends UpKernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            // TODO add more bundles here
        ];
    }
}
```

### `tests/AppTestCase.php`

```php
<?php

use Netpromotion\SymfonyUp\UpTestCase;

class AppTestCase extends UpTestCase
{
    protected static function getKernelClass()
    {
        return AppKernel::class;
    }
}
```

### `web/app.php`

```php
<?php

use Netpromotion\SymfonyUp\SymfonyUp;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../app/autoload.php';

Request::enableHttpMethodParameterOverride(); // remove this line if kernel.http_method_override = false

SymfonyUp::createFromKernelClass(AppKernel::class)->runWeb();
```

### `web/app_dev.php`

```php
<?php

use Netpromotion\SymfonyUp\SymfonyUp;

require_once __DIR__ . '/../app/autoload.php';

SymfonyUp::createFromKernelClass(AppKernel::class)->runWeb('dev', true);
```

### `web/.htaccess`

```apacheconfig
DirectoryIndex app.php

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
    RewriteRule ^app\.php(?:/(.*)|$) %{ENV:BASE}/$1 [R=301,L]

    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^ - [L]

    RewriteRule ^ %{ENV:BASE}/app.php [L]
</IfModule>

<IfModule !mod_rewrite.c>
    <IfModule mod_alias.c>
        RedirectMatch 302 ^/$ /app.php/
    </IfModule>
</IfModule>
```

### `bin/console`

```php
#!/usr/bin/env php
<?php

use Netpromotion\SymfonyUp\SymfonyUp;

require_once __DIR__ . '/../app/autoload.php';

SymfonyUp::createFromKernelClass(AppKernel::class)->runConsole();
```

### `phpunit.xml`

```xml
<phpunit bootstrap="./app/autoload.php">
    <testsuites>
        <testsuite>
            <directory suffix="Test.php">./tests/</directory>
        </testsuite>
    </testsuites>
</phpunit>
```
