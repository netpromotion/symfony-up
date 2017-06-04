<div style="background: #0071B8 url('README.header.png') repeat-x 0 0; padding: 0; margin: 0"><div style="background: transparent url('README.logo.png') no-repeat 0 0; height: 201px; padding-left: 250px; color: #A0CAE4; display: table-cell; vertical-align: middle;">
<h1 style="border-width: 0 0 0 0">Symfony Up!</h1>
<p style="font-size: larger;">Package for rapid simplification of Symfony applications and their development</p>
</div></div>


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

require_once __DIR__ . '/../vendor/netpromotion/symfony-up/src/autoload.php';

SymfonyUp::viaKernelClass(Kernel::class)->runWeb();
```

### `bin/console.php`

```php
#!/usr/bin/env php
<?php

use Netpromotion\SymfonyUp\SymfonyUp;

require_once __DIR__ . '/../vendor/netpromotion/symfony-up/src/autoload.php';

SymfonyUp::viaKernelClass(Kernel::class)->runConsole();
```
