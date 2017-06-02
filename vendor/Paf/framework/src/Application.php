<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 15:08
 */
namespace Paf;

class Application
{
    use Route\RoutesRequests;
    protected $basePath;
    public function __construct($basePath = NUll)
    {
        $this->basePath = $basePath;
    }
    public function test(){
        echo 11;
    }

}