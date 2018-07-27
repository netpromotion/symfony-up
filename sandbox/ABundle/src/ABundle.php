<?php

namespace Sandbox\ABundle;

use Sandbox\ABundle\DependencyInjection\AnExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ABundle extends Bundle
{
    public function getContainerExtension()
    {
        return new AnExtension();
    }
}
