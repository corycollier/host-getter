<?php

namespace HostGetter;

class ServerFactory
{
    public function factory($response)
    {
        foreach ($response as $answer) {
            return new Server($answer);
        }
    }
}
