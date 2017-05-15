<?php

namespace HostGetter;

class Result
{
    protected $orgData;
    protected $netData;

    public function __construct($netData, $orgData)
    {
        $this->netData = $netData;
        $this->orgData = $orgData;
    }
}
