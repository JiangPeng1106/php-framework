<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 15:08
 */
namespace Paf;

use Paf\Container\Container;
class Application extends Container
{
    use Route\RoutesRequests;
    protected $basePath;
    public function __construct($basePath = NUll)
    {
        $this->basePath = $basePath;
    }

    public function make($abstract){
        return parent::make($abstract);
    }

}