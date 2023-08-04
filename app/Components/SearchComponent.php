<?php
namespace App\Components;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\DB;

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
                            'id' => [
                                'type' => 'integer'
                            ],
                            'name' => [
                                'type' => 'string'
                            ],
                            'description' => [
                                'type' => 'string'
                            ],
                            'tag' => [
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

public function SearchProduct(){
    $client = $this->elasticclient;
    $result = array();

    $params = [
        'index' => 'products',
        'type'  => '_doc',

        'body'  => [
            'query' => [
                "bool" => [
                    "must" => [ ],
                    "should" => [
                        [
                            "multi_match"=> [
                                "query"=> 'перчатк',
                                "fields"=> [
                                    "name^10",
                                    "description^5",
                                    "tag^3"
                                ]
                            ]
                        ],
                        [
                            "query_string"=> [
                                "default_field"=> "name",
                                "query"=> "перчатк*"
                            ]
                        ],
//                    [ "match" => [ "tag" => "перчатки" ] ],
                    ],
                    "minimum_should_match" => 1


                ],
            ],
            "size"=>15,

        ],
    ];

        $response = $client->search($params);

//        printf("Total docs: %d\n", $response['hits']['total']['value']);
//        printf("Max score : %.4f\n", $response['hits']['max_score']);
//        printf("Took      : %d ms\n", $response['took']);

        print_r($response['hits']['hits']); // documents
}

public function InsertDataProduct(){
    $this->Mapping();
    $client = $this->elasticclient;
//    $stmt = "SELECT * FROM `table_name` limit 1";
//    $result = $this->con->query($stmt);
    $result=[];
    $products=DB::connection('mysql2')->table('sd_product_description')->select('product_id','name','description','tag')->get();

    $products->each(function ($item) use(&$result){
        if(!empty($item->tag)){
            $explode_tag=explode(',',$item->tag);
        }else{
            $explode_tag='';
        }
        $result[]=['id'=>$item->product_id,'name'=>$item->name,'description'=>strip_tags($item->description),'tag'=>$explode_tag];
    });


//    $result = [
//            ['id' => 1, 'name' => '2162 Матрицы Стальные, 0.03, высота 6.3мм  (50шт) КЕRR', 'description' => 'Идеальное решение для макрореставраций.', 'tag' => 'Эндодонтия, Товар дня (для отчета), 2022-12-7'],
//            ['id' => 2, 'name' => '2162 Матрицы Стальные, 0.03, высота 6.3мм  (50шт) КЕRR', 'description' => 'Идеальное решение для макрореставраций.', 'tag' => 'Эндодонтия, Товар дня (для отчета), 2022-12-7'],
//            ['id' => 3, 'name' => '2162 Матрицы Стальные, 0.03, высота 6.3мм  (50шт) КЕRR', 'description' => 'Идеальное решение для макрореставраций.', 'tag' => 'Эндодонтия, Товар дня (для отчета), 2022-12-7'],
//             ];

    $params = ['body' => []];
    foreach($result as $row){

        $params['body'][] = [
            'index' => [
                '_index' => 'products',
                '_id'    => $row['id']
            ]
        ];

        $params['body'][] = [
            'id'     => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'tag' => $row['tag'],
        ];

    }





// Send the last batch if it exists
    if (!empty($params['body'])) {
        $responses = $client->bulk($params);
    }

    //echo "<pre>"; print_r($responses); die;
    return true;
}


    public function GetSearchProduct($name){
        $client = $this->elasticclient;
        $result = array();
//multi_match
////query_string
        $params = [
            'index' => 'products',
            'type'  => '_doc',

            'body'  => [
                'query' => [
                    "bool" => [
                        "must" => [ ],
                        "should" => [
                            [
                                "multi_match"=> [
                                    "query"=> $name,
                                    "fields"=> [
                                        "name^10",
                                        "description^5",
                                        "tag^3"
                                    ],
                                ]
                            ],
                            [
                                "query_string"=> [
                                    "default_field"=> "name",
                                    "query"=> $name."*",
                                ]
                            ],
//                    [ "match" => [ "tag" => "перчатки" ] ],
                        ],
                        "minimum_should_match" => 1


                    ],
                ],
                "size"=>15,

            ],
        ];


        $response = $client->search($params);

        $resuil=[];
        foreach ($response['hits']['hits'] as $hits){
            $resuil[]=['product_id'=>$hits['_source']['id'],'name'=>$hits['_source']['name']];
        }
        return $resuil;

//        printf("Total docs: %d\n", $response['hits']['total']['value']);
//        printf("Max score : %.4f\n", $response['hits']['max_score']);
//        printf("Took      : %d ms\n", $response['took']);

//        print_r($response['hits']['hits']); // documents
    }

}
