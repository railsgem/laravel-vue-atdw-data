<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getProductData()
    {
        $apiKey = config('app.atlas_api_key');
        $url = 'https://atlas.atdw-online.com.au/api/atlas/mlp?key='. $apiKey .'&productId=56b25d622cbcbe7073ad6a45&latlong=-27.892494,153.286743&dist=100&out=json';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $result = json_decode(mb_convert_encoding($response->getBody() , 'UTF-8' , 'UTF-16LE'));
        return response()
            ->json($result);
    }
}
