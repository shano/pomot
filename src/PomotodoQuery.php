<?php
// src/PomotodoQuery.php
namespace Pomotodo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class PomotodoQuery
 *
 * @package Pomotodo
 */
class PomotodoQuery
{
    private $_client;
    private static $_url = 'https://api.pomotodo.com/1/';
    private $_auth_key;

    /**
     * PomotodoQuery constructor.
     *
     * @param Client $client   - Injecting Guzzle client
     * @param string $auth_key - Key required for authentication against server
     */
    public function __construct(Client $client, string $auth_key)
    {
        $this->_client = $client;
        $this->_auth_key = $auth_key;
    }

    /**
     * Get Data
     *
     * @param String $type Type of data to retrieve
     *
     * @return array
     */
    public function get(string $type) : array
    {
        $res = $this->_client->request(
            'GET', self::$_url . $type, [
                'headers' => ['Authorization' => "token $this->_auth_key"]
            ]
        );
        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * Create data
     *
     * @param string $type   - Type of data to create
     * @param array $fields  - Fields to populate
     *
     * @return array
     */
    public function create(string $type, array $fields) : array
    {
        $res = $this->_client->request(
            'POST', self::$_url . $type, [
                'headers' => ['Authorization' => "token $this->_auth_key"],
                'form_params' => $fields
            ]
        );

        return json_decode($res->getBody()->getContents(), true);
    }
}
