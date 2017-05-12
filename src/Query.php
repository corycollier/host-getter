<?php

namespace HostGetter;

use GuzzleHttp\Client;

class Query
{
    const ERR_INVALID_TYPE = 'The type provided [%s] is not a valid domain type';
    const REST_URI = 'https://whois.arin.net/rest/nets;q=%s?showDetails=true&showARIN=false';

    protected $serverFactory;

    public function __construct()
    {
        $this->serverFactory = new ServerFactory();
    }

    public function getServerFactory()
    {
        return $this->serverFactory;
    }

    public function find($domain, $type = DNS_A)
    {
        $server = $this->getServer($domain, $type);
        $whois = $this->getWhois($server);
        return new Result($whois['net'], $whois['org']);
    }

    public function getServer($domain, $type = DNS_A)
    {
        $serverFactory = $this->getServerFactory();
        $types = $this->getTypes();
        if (! in_array($type, $types)) {
            throw new Exception(sprintf(self::ERR_INVALID_TYPE, $type));
        }

        $records = $this->getDns($domain, $type);
        return $serverFactory->factory($records, $type);
    }

    protected function getDns($domain, $type = DNS_A)
    {
        return dns_get_record($domain, $type);
    }

    protected function getClient()
    {
        return new Client();
    }

    public function getWhois(Server $server)
    {
        $netData = $this->getDataFromArin(sprintf(self::REST_URI, $server->getAddress()));
        $url     = $this->getOrgRef($netData);
        $orgData = $this->getDataFromArin($url);

        return [
            'net' => $netData,
            'org' => $orgData,
        ];
    }

    protected function getOrgRef($data)
    {
        $net = $data['nets']['net'];
        if (array_key_exists('orgRef', $net)) {
            return $net['orgRef']['$'];
        }
    }

    protected function getDataFromArin($url)
    {
        $client = $this->getClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function getTypes()
    {
        return [
            DNS_A,
            DNS_CNAME,
            DNS_HINFO,
            DNS_MX,
            DNS_NS,
            DNS_PTR,
            DNS_SOA,
            DNS_TXT,
            DNS_AAAA,
            DNS_SRV,
            DNS_NAPTR,
            DNS_A6,
            DNS_ALL
        ];
    }
}
