<?php

namespace App\Utils;


use Symfony\Component\Routing\RouterInterface;

trait RouterHandlerTrait
{
    /**
     * @var RouterInterface
     */
    private $router;
    public function generateUrl($route,$params = array())
    {
        return $this->router->generate($route,$params);
    }
}