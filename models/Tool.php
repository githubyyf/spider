<?php

namespace app\models;


use GuzzleHttp\Client;

class Tool
{
    public static function translate(array $data)
    {
        $client = new Client();
        $response = $client->request('GET', 'http://api.map.baidu.com/geoconv/v1/', [
            'query' => [
                'coords' => implode(';', $data),
                'ak'     => 'gvTcwVrGTmHoIGXlT9k3QXfATs4gBeaA',
                'from'   => 1,
            ]
        ]);

        echo $response->getBody();

    }
}