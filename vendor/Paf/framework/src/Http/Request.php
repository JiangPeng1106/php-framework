<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/13
 * Time: 14:21
 */
namespace Paf\Http;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{

    /**
     * Create a new Paf HTTP request from server variables.
     *
     * @return static
     */
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

    /**
     * Create an Illuminate request from a Symfony instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return \Paf\Http\Request
     */
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

    /**
     * Get the root URL for the application.
     *
     * @return string
     */
    public function root(){
        return rtrim($this->getSchemeAndHttpHost().$this->getBaseUrl().'/');
    }

    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url(){
        return rtrim( preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path(){
        $pattern = trim($this->getPathInfo(), '/');
        return $pattern == '' ? '/' : $pattern;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function method(){
        return $this->getMethod();
    }

    /**
     * Get the full URL for the request.
     *
     * @return string
     */
    public function fullUrl(){
        $query = $this->getQueryString();
        $question = $this->getBaseUrl().$this->getPathInfo() == '/'? '/?':'?';
        return $query ? $this->url().$question.$query : $this->url();
    }


//    public function fullUrlWithQuery(){
//        $question = $this->getBaseUrl().$this->getPathInfo() == '/' ? '/?' : '?';
//        var_dump($this->query());
//    }

    /**
     * Get the current encoded path info for the request.
     *
     * @return string
     */
    public function decodePath(){
        return rawurldecode($this->path());
    }

    public function is(){

    }

    /**
     * Determine if the request is the result of an AJAX call.
     *
     * @return bool
     */
    public function ajax(){
        return $this->isXmlHttpRequest();
    }

    /**
     * Determine if the request is the result of an PJAX call
     *
     * @return bool
     */
    public function pjax(){
        return $this->headers->get('X_PJAX') == true;
    }

    /**
     * Determine if the request is over HTTPS
     *
     * @return bool
     */
    public function secure(){
        return $this->isSecure();
    }

    /**
     * Returns the client IP address
     *
     * @return null|string
     */
    public function ip(){
        return $this->getClientIp();
    }

    /**
     * Returns the client IP addresses
     *
     * @return array
     */
    public function ips(){
        return $this->getClientIps();
    }

    /**
     * Merge new input into the current request's input array.
     *
     * @param  array  $input
     * @return void
     */
    public function merge(array $input){
        $this->getInputSource()->add($input);
    }

    public function getInputSource(){
        if($this->isJson()){
            return $this->json();
        }
    }

    public function json($key = null, $default = null){
        if(!isset($this->json)){
            $this->json = new ParameterBag((array) json_decode($this->getContent(), true));
        }

        if(is_null($key)){
            return $this->json;
        }
    }

}