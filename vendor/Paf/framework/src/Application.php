<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 15:08
 */
namespace Paf;

use Paf\Container\Container;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Paf\Http\Request;

class Application extends Container
{
    use Route\RoutesRequests;
    protected $basePath;

    /**
     * Create a new Paf application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = NUll)
    {
        $this->basePath = $basePath;
        $this->bootstrapContainer();
    }

    /**
     * Bootstrap the application container.
     *
     * @return void
     */
    protected function bootstrapContainer(){
        static::setInstance($this);
        $this->instance('app' ,$this);
        $this->instance('Paf\Application', $this);
        $this->instance('path', $this->path());
        $this->registerContainerAliases();
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @return string
     */
    public function path(){
        return $this->basePath.DIRECTORY_SEPARATOR.'app';
    }

    public function registerContainerAliases(){
        $this->aliases = [
            'Paf\Container\Container' => 'app',
            'request' => 'Paf\Http\Request',
            'log' => 'Psr\Log\LoggerInterface'
        ];
    }


    public function make($abstract){
        return parent::make($abstract);
    }

    /**
     * Prepare the given request instance for use with the application.
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Paf\Http\Request
     */
    public function prepareRequest(SymfonyRequest $request){
          if(!$request instanceof Request){
              $request = Request::createFromBase($request);
          }
          return $request;
    }
}