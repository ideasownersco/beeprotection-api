<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
//    protected $proxies = ['162.243.164.32'];
    protected $proxies = ['167.99.233.51'];


    /**
     * The headers that should be used to detect proxies.
     *
     *  @var string
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
