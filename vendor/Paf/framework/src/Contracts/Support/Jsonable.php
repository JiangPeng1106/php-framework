<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 15:03
 */

namespace Paf\Contracts\Support;

interface Jsonable
{
    /**
     * Convert the object to  its json representation
     *
     * @param int $options
     * @return mixed
     */
    public function toJson($options = 0);
}