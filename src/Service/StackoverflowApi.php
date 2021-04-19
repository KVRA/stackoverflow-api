<?php


namespace App\Service;


use DateTime;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StackoverflowApi
{
    /**
     * @var HttpClientInterface
     */
    private $http;

    /**
     * StackoverflowApiService constructor.
     * @param HttpClientInterface $http
     */
    public function __construct(HttpClientInterface $http)
    {
        $this->http = $http;
    }
    /**
     * @param $tagged
     * @param DateTime|null $from_date
     * @param DateTime|null $to_date
     * @param String|null $sort
     * @param String|null $order
     * @param string $site
     * @return array { status: integer, data: string }
     */
    public function get($tagged, Datetime $from_date = null, Datetime $to_date=null, String $sort =null, String $order=null, String $site="stackoverflow"): array
    {
        $stackoverflow = [
            'site'=>$site,
            'tagged'=>$tagged
        ];
        if ($from_date !== null) {
            $stackoverflow['fromdate'] = $from_date->format('U');
        }
        if ($to_date !== null ) {
            $stackoverflow['todate'] = $to_date->format('U');
        }
        if ($sort !== null ) {
            $stackoverflow['sort'] = $sort;
        }
        if ($order !== null ) {
            $stackoverflow['order'] = $order;
        }
        try {
            $response = $this->http->request(
                'GET',
                'https://api.stackexchange.com/2.2/questions', [
                    'query' => $stackoverflow
                ]
            );
            $content['data'] = json_decode($response->getContent(false), true);
            $content['status']=$response->getStatusCode();
            return $content;
        } catch (TransportExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $e) {
            return [
                "status"=>404
            ];
        }
    }
}