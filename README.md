# Symfony Up!

> Package for rapid simplification of Symfony applications and their development


## Getting started

Run `composer require netpromotion/symfony-up` and create following files:

### `src/Kernel.php`

```php
<?php 

use Netpromotion\SymfonyUp\AppKernel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;

class Kernel extends AppKernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle()
        ];
    }
}
```

### `tests/TestCase.php`

```php
<?php

use Netpromotion\SymfonyUp\AppTestCase;

class TestCase extends AppTestCase
{
    protected static function getKernelClass()
    {
        return Kernel::class;
    }
}
```

### `web/index.php`

```php
<?php

use Netpromotion\SymfonyUp\SymfonyUp;

require_once __DIR__ . '/../vendor/netpromotion/symfony-up/autoload.php';

SymfonyUp::viaKernelClass(Kernel::class)->runWeb();
```

### `bin/console.php`

```php
#!/usr/bin/env php
<?php

use Netpromotion\SymfonyUp\SymfonyUp;

require_once __DIR__ . '/../vendor/netpromotion/symfony-up/autoload.php';

SymfonyUp::viaKernelClass(Kernel::class)->runConsole();
```
