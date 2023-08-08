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



public function MappingProductName(){
    $params = ['index' => 'products_name'];
    $this->deleteIndex($params['index']);
    $params = [
        'index' => $params['index'],
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
                        ]
                    ]
                ]
            ]
    ];

    //echo "<pre>"; print_r($params); //die;
    $this->elasticclient->index($params);

}

public function MappingProductCategory(){
        $params = ['index' => 'products_category'];
        $this->deleteIndex($params['index']);
        $params = [
            'index' => $params['index'],
            'body' => [
                'mappings' => [
                    '_doc' => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'id_prod' => [
                                'type' => 'integer'
                            ],
                            'id_category' => [
                                'type' => 'integer'
                            ],
                            'name_category' => [
                                'type' => 'string'
                            ],
                            'main_category' => [
                                'type' => 'integer'
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



//    $params = [
//        'index' => 'products_name',
//        'type'  => '_doc',
//
//        'body'  => [
//            'query' => [
//                "bool" => [
//                    "must" => [ ],
//                    "should" => [
//                        [
//                            "multi_match"=> [
//                                "query"=> "перчатки",
//                                "fields"=> [
//                                    "name^10",
//                                ],
//                                "boost"=> 4
//                            ]
//                        ],
//                        [
//                            "wildcard"=> [
//                                "name"=>[
//                                    "value"=>"перчатки*",
//                                    "boost"=> 2,
//                                    "rewrite"=>"constant_score",
//                                ]
//                            ],
//                        ],
//                        [
//                            "wildcard"=> [
//                                "name"=>[
//                                    "value"=>"*перчатки*",
//                                    "boost"=> 1,
//                                    "rewrite"=>"constant_score",
//                                ]
//                            ],
//                        ],
//                    ],
//                    "minimum_should_match" => 1
//
//
//                ],
//            ],
//            "size"=>30,
//        ],
//    ];

    $params = [
        'index' => 'products_category',
        'type'  => '_doc',

        'body'  => [
            'query' => [
                "match"=> [
                    "name_category"=> 'инструменты',
                ]
            ],
        ],
        "size"=>1,
    ];

        $response = $client->search($params);

//        printf("Total docs: %d\n", $response['hits']['total']['value']);
//        printf("Max score : %.4f\n", $response['hits']['max_score']);
//        printf("Took      : %d ms\n", $response['took']);

        print_r($response['hits']['hits']); // documents
}


public function InsertDataProduct(){
    $this->MappingProductName();
    $client = $this->elasticclient;
//    $stmt = "SELECT * FROM `table_name` limit 1";
//    $result = $this->con->query($stmt);
    $result=[];
    $products=DB::connection('mysql2')->table('sd_product_description')->select('sd_product_description.product_id','sd_product_description.name')
        ->join('sd_product','sd_product.product_id','=','sd_product_description.product_id')
        ->where('sd_product.quantity','>',0)
        ->where('sd_product.price','>',0)
        ->get();

    $products->each(function ($item) use(&$result){

        $result[]=['id'=>$item->product_id,'name'=>$item->name];
    });



    $params = ['body' => []];
    foreach($result as $row){

        $params['body'][] = [
            'index' => [
                '_index' => 'products_name',
                '_id'    => $row['id']
            ]
        ];

        $params['body'][] = [
            'id'     => $row['id'],
            'name' => $row['name'],
        ];

    }

// Send the last batch if it exists
    if (!empty($params['body'])) {
        $responses = $client->bulk($params);
    }

    //echo "<pre>"; print_r($responses); die;
    return true;
}

public function InsertDataProductCategory(){
        $this->MappingProductCategory();
        $client = $this->elasticclient;
//    $stmt = "SELECT * FROM `table_name` limit 1";
//    $result = $this->con->query($stmt);
        $result=[];
        $productsCategory=DB::connection('mysql2')->table('sd_product_to_category')->select('.sd_product_to_category.product_id','sd_product_to_category.category_id','sd_product_to_category.main_category','sd_category_description.name')
            ->join('sd_category_description', 'sd_category_description.category_id', '=', 'sd_product_to_category.category_id')
            ->join('sd_category','sd_category.category_id','=','sd_product_to_category.category_id')
            ->join('sd_product','sd_product.product_id','=','sd_product_to_category.product_id')
            ->where('sd_category.status','=','1')
            ->where('sd_category.top', '=', '1')
            ->where('sd_product.status','=','1')
            ->get();





    $productsCategory->each(function ($item) use(&$result){
            $result[]=['id_prod'=>$item->product_id,'id_category'=>$item->category_id,'name_category'=>$item->name,'main_category'=>$item->main_category];
        });


        $params = ['body' => []];
        foreach($result as $row){

            $params['body'][] = [
                'index' => [
                    '_index' => 'products_category',
                    '_id'    => $row['id_prod'].$row['id_category']
                ]
            ];

            $params['body'][] = [
                'id_prod'     => $row['id_prod'],
                'id_category' => $row['id_category'],
                'name_category' => $row['name_category'],
                'main_category' => $row['main_category'],
            ];

        }

// Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = $client->bulk($params);
        }

        //echo "<pre>"; print_r($responses); die;
        return true;
}


public function GetSearchProductName($name){
        $client = $this->elasticclient;
        $result = array();

        $params = [
            'index' => 'products_name',
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
                                    ],
                                    "boost"=> 4
                                ]
                            ],
                            [
                                "wildcard"=> [
                                    "name"=>[
                                        "value"=>$name."*",
                                        "boost"=> 2,
                                        "rewrite"=>"constant_score",
                                    ]
                                ],
                            ],
                            [
                                "wildcard"=> [
                                    "name"=>[
                                        "value"=>"*".$name."*",
                                        "boost"=> 1,
                                        "rewrite"=>"constant_score",
                                    ]
                                ],
                            ],
                        ],
                        "minimum_should_match" => 1


                    ],
                ],
                "size"=>30,
            ],
        ];




        $response = $client->search($params);

        $resuil=[];
        foreach ($response['hits']['hits'] as $hits){
            $resuil[]=['product_id'=>$hits['_source']['id']];//'name'=>$hits['_source']['name']
        }
        return $resuil;

//        printf("Total docs: %d\n", $response['hits']['total']['value']);
//        printf("Max score : %.4f\n", $response['hits']['max_score']);
//        printf("Took      : %d ms\n", $response['took']);

//        print_r($response['hits']['hits']); // documents
    }

public function GetSearchProductCategory($name){
        $client = $this->elasticclient;
        $result = array();

    $params = [
        'index' => 'products_category',
        'type'  => '_doc',

        'body'  => [
            'query' => [
                "match"=> [
                    "name_category"=> $name,
                ]
            ],
        ],
        "size"=>1,
    ];




        $response = $client->search($params);

        $resuil=[];
        foreach ($response['hits']['hits'] as $hits){
            $resuil[]=['id_category'=>$hits['_source']['id_category']];//'name_category'=>$hits['_source']['name_category'],'main_category'=>$hits['_source']['main_category']
        }
        return $resuil;

//        printf("Total docs: %d\n", $response['hits']['total']['value']);
//        printf("Max score : %.4f\n", $response['hits']['max_score']);
//        printf("Took      : %d ms\n", $response['took']);

//        print_r($response['hits']['hits']); // documents
}


    public function GetSearchAllProductToCategory($id){
        $client = $this->elasticclient;
        $result = array();

        $params = [
            'index' => 'products_category',
            'type'  => '_doc',

            'body'  => [
                'query' => [
                    "match"=> [
                        "id_category"=> $id,
                    ],
                ],
            ],
            "size"=>10000,
        ];




        $response = $client->search($params);

        $resuil=[];
        foreach ($response['hits']['hits'] as $hits){
            $resuil[]=['id_product'=>$hits['_source']['id_prod']];//'name_category'=>$hits['_source']['name_category'],'main_category'=>$hits['_source']['main_category']
        }
        return $resuil;

    }


}
