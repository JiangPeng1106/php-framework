<?php
/**
 * Created by PhpStorm.
 * User: 蒋鹏
 * Date: 2017/5/12
 * Time: 20:55
 */
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Paf\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {

    }

    public function home(Request $request){
            var_dump($request);
//            var_dump(Request::createFromGlobals());
//            $request = Request::createFromGlobals();
//            var_dump($request->query);
//        $request = Request::createFromGlobals();
//        var_dump($request->query);
//        $log = new Logger("name");
//        $log->pushHandler(new StreamHandler('a.log',Logger::INFO));
//        $log->addInfo("asdfafd");
    }
}