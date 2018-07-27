<?php

namespace Netpromotion\SymfonyUp\Test\ABundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AController extends Controller
{
    /**
     * @Route(path="/a.url")
     */
    public function anAction()
    {
        return new Response('A response');
    }
}
