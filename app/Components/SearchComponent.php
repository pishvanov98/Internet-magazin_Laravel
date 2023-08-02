<?php
namespace App\Components;
use Elastic\Elasticsearch\ClientBuilder;

class SearchComponent
{
private $elasticclient = null;

public function __construct(){
//      //http://127.0.0.1:9200/shop/_doc/3
//      ////http://127.0.0.1:9200/_search?index=shop
//      ///http://127.0.0.1:9200/_all
    $hosts = [
        'http://sitelight-es01:9200'        // SSL to localhost
    ];

    $this->elasticclient = ClientBuilder::create()->setHosts($hosts)->build();


}
    public function deleteIndex($index){
        $ch = curl_init('http://sitelight-es01:9200/'.$index);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
    }



public function Mapping(){
    $params = ['index' => 'products'];
    $this->deleteIndex($params['index']);
//    $response = $this->elasticclient->indices()->delete($params);
    $params = [
        'index' => 'products',
        'body' => [
                'mappings' => [
                    '_doc' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'code' => [
                                'type' => 'integer'
                            ],
                            'name' => [
                                'type' => 'string'
                            ],
                        ]
                    ]
                ]
            ]
    ];

    //echo "<pre>"; print_r($params); //die;
    $this->elasticclient->index($params);

}

public function Search(){
    $client = $this->elasticclient;
    $result = array();

    $params = [
        'index' => 'products',
        'type'  => '_doc',

        'body'  => [
            'query' => [
                'query_string' => [
                    'query'=>'*трав*'
                ],
            ],
        ],
    ];

        $response = $client->search($params);

        printf("Total docs: %d\n", $response['hits']['total']['value']);
//        printf("Max score : %.4f\n", $response['hits']['max_score']);
        printf("Took      : %d ms\n", $response['took']);

        print_r($response['hits']['hits']); // documents
}

public function InsertData(){
    $this->Mapping();
    $client = $this->elasticclient;
//    $stmt = "SELECT * FROM `table_name` limit 1";
//    $result = $this->con->query($stmt);

    $result = [
            ['id' => 1, 'name' => 'На дворе трава'],
            ['id' => 3, 'name' => 'На траве дрова'], ];

    $params = ['body' => []];
    foreach($result as $row){

        $params['body'][] = [
            'index' => [
                '_index' => 'products',
                '_id'    => $row['id']
            ]
        ];

        $params['body'][] = [
            'code'     => $row['id'],
            'name' => $row['name']
        ];

    }





// Send the last batch if it exists
    if (!empty($params['body'])) {
        $responses = $client->bulk($params);
    }

    //echo "<pre>"; print_r($responses); die;
    return true;
}

}
