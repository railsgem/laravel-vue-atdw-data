<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getProductData(Request $request)
    {
        $query = $request->getQueryString();
        $apiKey = config('app.atlas_api_key');
        $url = 'https://atlas.atdw-online.com.au/api/atlas/products?key='. $apiKey .'&'. $query .'&out=json';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $result = json_decode(mb_convert_encoding($response->getBody() , 'UTF-8' , 'UTF-16LE'));
        return response()
            ->json($result);
    }

    public function getAreas(Request $request)
    {
        $apiKey = config('app.atlas_api_key');
        $query = $request->getQueryString();
        $url = 'https://atlas.atdw-online.com.au/api/atlas/areas?key='. $apiKey .'&'. $query .'&out=json';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $result = json_decode(mb_convert_encoding($response->getBody() , 'UTF-8' , 'UTF-16LE'));
        return response()
            ->json($result);
    }

    public function getRegions(Request $request)
    {
        $apiKey = config('app.atlas_api_key');
        $query = $request->getQueryString();
        $url = 'https://atlas.atdw-online.com.au/api/atlas/regions?key='. $apiKey .'&'. $query .'&out=json';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $result = json_decode(mb_convert_encoding($response->getBody() , 'UTF-8' , 'UTF-16LE'));
        return response()
            ->json($result);
    }

    // https://[host]/api/atlas/areas?key=[key]
    // https://[host]/api/atlas/regions?key=[key]&st=QLD
}
