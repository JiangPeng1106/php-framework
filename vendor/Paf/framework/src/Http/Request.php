<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/13
 * Time: 14:21
 */
namespace Paf\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{


    public static function capture(){
        static::enableHttpMethodParameterOverride();

        return static::createFromGlobals(SymfonyRequest::createFromGlobals());
    }

    /**
     * Return the Request instance.
     *
     * @return $this
     */
    public function instance()
    {
        return $this;
    }


    public static function createFromBase(SymfonyRequest $request){
        if ($request instanceof static) {
            return $request;
        }

        $content = $request->content;

        $request = (new static)->duplicate(
            $request->query->all(), $request->request->all(), $request->attributes->all(),
            $request->cookies->all(), $request->files->all(), $request->server->all()
        );

        $request->content = $content;

        $request->request = $request->getInputSource();

        return $request;
    }

    public function url(){
        var_dump($this->getUri());
//        var_dump(preg_replace('/\?.*/', '', $this->getUri()));exit;
//        rtrim( preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path(){
        $pattern = trim($this->getPathInfo(), '/');
        var_dump($pattern);exit;
        return $pattern == '' ? '/' : $pattern;
    }

}