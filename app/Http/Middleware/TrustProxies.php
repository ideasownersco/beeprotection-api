<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
//    protected $proxies = ['162.243.164.32'];
    protected $proxies = '*';
//    protected $proxies = ['10.136.158.173'];

    /**
     * The headers that should be used to detect proxies.
     *
     *  @var string
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
