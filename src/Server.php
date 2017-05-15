<?php

namespace HostGetter;

class Server
{
    protected $record;

    public function __construct($record = [])
    {
        $this->record = $record;
    }

    public function getAddress()
    {
        return $this->record['ip'];
    }
}
