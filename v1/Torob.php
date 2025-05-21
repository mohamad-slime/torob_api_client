<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;


class Torob
{
    private $baseUrl = "https://api.torob.com/v4/";
    private $searchUrl = "base-product/search/";
    private $detailsUrl = "base-product/details/";
    private $specialOffersUrl = "special-offers/";
    private $sellerstUrl = "base-product/sellers/";

    public function search($q, $page = 0)
    {
        $params = ["q" => $q, "page" => $page];
        $result = $this->sendRequest($this->searchUrl, $params);
        // return $this->getSearchDataFromUrl($result);
        return $this->getCustomSearchDataFromUrl($result);
    }

    /**
     * Get detailed information about a specific product.
     */
    public function details($prk)
    {
        $params = ["prk" => $prk];
        return $this->sendRequest($this->detailsUrl, $params);
    }
    public function sellers($prk)
    {
        $params = ["prk" => $prk];
        return $this->sendRequest($this->sellerstUrl, $params);
    }

    /**
     * Get special offers for products.
     */
    public function specialOffers($page = 0)
    {
        $params = ["page" => $page];
        return  $this->sendRequest($this->specialOffersUrl, $params);
    }


    public function Client($model = "base-product/search/", array $params)
    {

        $fullUrl = $this->baseUrl  . $model . "?" . http_build_query($params);

        $client = new GuzzleClient([
            'verify' => false, // Disable SSL certificate verification
            // 'headers' => ["User-Agent" => "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Mobile Safari/537.36 Edg/132.0.0.0"],
            'base_uri' => $fullUrl,
            // 'http_errors' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 Edg/132.0.0.0',
            ],
            'timeout'  => 10.0,
        ]);
        return $client;
    }

    public function sendRequest($model = "base-product/search/", array $params)
    {
        $client = $this->Client($model, $params);
        try {
            $response = $client->request('GET');
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (ClientException $e) {
            return "ClientException: " . $e->getResponse()->getBody();
        } catch (Exception $e) {

            return "Exception: " . $e->getMessage();
        }
    }


    public function getCustomSearchDataFromUrl($data)
    {
        if ($data['results'] == null) {
            return $data;
        }

        $finalyData = [
            "search_results_for:" => $data['spellcheck']['initial_query'],
            "results" => []
        ];


        foreach ($data["results"] as $results) {
            $moreInfoUrl = $results["more_info_url"];
            parse_str(parse_url($moreInfoUrl, PHP_URL_QUERY), $queryParams);

            $sellerShop = $this->sellers($results['random_key']);
            $sellers = [];
            foreach ($sellerShop['results'] as $value) {
                $page_url = $value['page_url'];

                $sellers[$value['shop_name'] . "/" . $value['shop_name2']] = [

                    "price" => $value['price'],
                    "price_text" => $value['price_text'],
                    "shop_url" => $page_url

                ];
            }

            $finalyData['results'][] = [
                "item_name_FR" => $results['name1'],
                "item_name_EN" => $results['name2'],
                "price" => $results['price'],
                "price_text" => $results['price_text'],
                "shop_text" => $results['shop_text'],
                "prk" => $queryParams["prk"] ?? null,
                "search_id" => $queryParams["search_id"] ?? null,
                "id" => $results['random_key'],
                "seller" => $sellers,
            ];
        }
        return $finalyData;
    }
    public function getSearchDataFromUrl($data)
    {

        return $data;
    }
}
