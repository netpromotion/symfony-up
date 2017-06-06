<div style="background: #0071B8 url('README.header.png') repeat-x 0 0; padding: 0; margin: 0"><div style="background: transparent url('README.logo.png') no-repeat 0 0; height: 201px; padding-left: 250px; color: #A0CAE4; display: table-cell; vertical-align: middle;">
<h1 style="border-width: 0 0 0 0">Symfony Up!</h1>
<p style="font-size: larger;">Package for rapid simplification of Symfony applications and their development</p>
</div></div>


## Getting started

Run `composer require netpromotion/symfony-up` and optionally `./vendor/bin/symfony-up` which creates following files:

### `app/config/parameters.yml`

```yaml
parameters:
    # A secret key that's used to generate certain security-related tokens
    secret: ThisTokenIsNotSoSecretChangeIt
```

### `app/config/config.yml`

```yaml
imports:
  - resource: parameters.yml

framework:
  secret: "%secret%"
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
            new FrameworkBundle()
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

### `web/index.php`

```php
<?php

use Netpromotion\SymfonyUp\SymfonyUp;

require_once __DIR__ . '/../app/autoload.php';

SymfonyUp::createFromKernelClass(AppKernel::class)->runWeb();
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
