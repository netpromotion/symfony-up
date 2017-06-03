<?php

namespace Netpromotion\SymfonyUp\Test\SomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SomeController extends Controller
{
    /**
     * @Route(path="/some.url")
     */
    public function someAction()
    {
        return new Response('Some response');
    }
}
