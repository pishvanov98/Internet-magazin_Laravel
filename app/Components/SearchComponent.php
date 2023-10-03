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
                            'category_id' => [
                                'type' => 'integer'
                            ],
                            'quantity' => [
                                'type' => 'integer'
                            ],
                            'category_name' => [
                                'type' => 'string'
                            ],
                            'model' => [
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
                            'quantity' => [
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



public function InsertDataProduct(){
    $this->MappingProductName();
    $client = $this->elasticclient;
//    $stmt = "SELECT * FROM `table_name` limit 1";
//    $result = $this->con->query($stmt);
    $result=[];
    $products=DB::connection('mysql2')->table('sd_product_description')->select('sd_product_description.product_id','sd_product_description.name','sd_product.model','sd_product_to_category.category_id','sd_product.quantity','sd_category_description.name as category_description_name')
        ->join('sd_product','sd_product.product_id','=','sd_product_description.product_id')
//        ->where('sd_product.quantity','>',0)
        ->where('sd_product.price','>',0)
        ->where('sd_product.status','=',1)
        ->where('sd_product_to_category.main_category','=',1)
        ->leftJoin('sd_product_to_category','sd_product_to_category.product_id','=','sd_product_description.product_id')
        ->join('sd_category_description','sd_category_description.category_id','=','sd_product_to_category.category_id')
        ->get();

    $products->each(function ($item) use(&$result){

        $result[]=['id'=>$item->product_id,'name'=>$item->name,'category_id'=>$item->category_id,'quantity'=>$item->quantity,'category_name'=>$item->category_description_name,'model'=>mb_substr($item->model, -5)];
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
            'category_id'     => $row['category_id'],
            'quantity'     => $row['quantity'],
            'category_name'   => $row['category_name'],
            'name' => $row['name'],
            'model' => $row['model'],
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
        $productsCategory=DB::connection('mysql2')->table('sd_product_to_category')->select('.sd_product_to_category.product_id','sd_product_to_category.category_id','sd_product_to_category.main_category','sd_category_description.name','sd_product.quantity')
            ->join('sd_category_description', 'sd_category_description.category_id', '=', 'sd_product_to_category.category_id')
            ->join('sd_category','sd_category.category_id','=','sd_product_to_category.category_id')
            ->join('sd_product','sd_product.product_id','=','sd_product_to_category.product_id')
            ->where('sd_category.status','=','1')
//            ->where('sd_category.top', '=', '1')
            ->where('sd_product.status','=','1')
            ->get();





    $productsCategory->each(function ($item) use(&$result){
            $result[]=['id_prod'=>$item->product_id,'id_category'=>$item->category_id,'name_category'=>$item->name,'main_category'=>$item->main_category,'quantity'=>$item->quantity];
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
                'quantity' => $row['quantity'],
            ];

        }

// Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = $client->bulk($params);
        }

        //echo "<pre>"; print_r($responses); die;
        return true;
}


    public function InsertDataManufacturerProduct(){
        $this->MappingManufacturerProduct();
        $client = $this->elasticclient;
//    $stmt = "SELECT * FROM `table_name` limit 1";
//    $result = $this->con->query($stmt);
        $result=[];
        $query=DB::connection('mysql2')->table('sd_product')
            ->select('sd_product.product_id','sd_product.manufacturer_id','sd_product.quantity' )
            ->where('sd_product.status','=',1)
            ->where('sd_product.manufacturer_id','!=','')
            ->latest('sd_product.product_id');

        $manufacturer_product= $query->get();




        $manufacturer_product->each(function ($item) use(&$result){
            $result[]=['product_id'=>$item->product_id,'manufacturer_id'=>$item->manufacturer_id,'quantity'=>$item->quantity];
        });


        $params = ['body' => []];
        foreach($result as $row){

            $params['body'][] = [
                'index' => [
                    '_index' => 'manufacturer_product',
                    '_id'    => $row['product_id']
                ]
            ];

            $params['body'][] = [
                'product_id'     => $row['product_id'],
                'manufacturer_id' => $row['manufacturer_id'],
                'quantity' => $row['quantity']
            ];

        }

// Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = $client->bulk($params);
        }

        //echo "<pre>"; print_r($responses); die;
        return true;
    }

    public function MappingManufacturerProduct(){
        $params = ['index' => 'manufacturer_product'];
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
                            'product_id' => [
                                'type' => 'integer'
                            ],
                            'manufacturer_id' => [
                                'type' => 'integer'
                            ],
                            'quantity' => [
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

public function MappingProductAttr(){

    $params = ['index' => 'products_attr'];
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
                        'product_id' => [
                            'type' => 'integer'
                        ],
                        'category_id' => [
                            'type' => 'integer'
                        ],
                        'attribute_id' => [
                            'type' => 'integer'
                        ],
                        'name' => [
                            'type' => 'string'
                        ],
                        'text' => [
                            'type' => 'string'
                        ],
                        'all_text_attr' => [
                            'type' => 'string'
                        ],
                        'quantity' => [
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


public function InsertDataProductAttr(){

    $this->MappingProductAttr();
    $client = $this->elasticclient;
    $result=[];
    $all_text_attr=[];
    $productsAttr=DB::connection('mysql2')->table('sd_product_to_category')->select('.sd_product_to_category.product_id','sd_product_to_category.category_id','sd_product_attribute.attribute_id','sd_attribute_description.name','sd_product_attribute.text','sd_product.quantity')
        ->join('sd_category','sd_category.category_id','=','sd_product_to_category.category_id')
        ->join('sd_product','sd_product.product_id','=','sd_product_to_category.product_id')
        ->join('sd_product_attribute','sd_product_attribute.product_id','=','sd_product_to_category.product_id')
        ->join('sd_attribute_description','sd_attribute_description.attribute_id','=','sd_product_attribute.attribute_id')
        ->where('sd_category.status','=','1')
        ->where('sd_product_to_category.main_category', '=', '1')
        ->where('sd_product.status','=','1')
        ->where('sd_product_attribute.attribute_id','!=','90')
        ->where('sd_product_attribute.attribute_id','!=','91')
        ->where('sd_product_attribute.attribute_id','!=','86')
        ->where('sd_product_attribute.attribute_id','!=','112')
        ->get();


    $productsAttr->each(function ($item) use(&$result,&$all_text_attr){
        $result[]=['product_id'=>$item->product_id,'category_id'=>$item->category_id,'attribute_id'=>$item->attribute_id,'name'=>$item->name,'text'=>$item->text,'quantity'=>$item->quantity];

            $all_text_attr[$item->product_id][]=$item->text;


    });

    $params = ['body' => []];
    foreach($result as $row){

        $params['body'][] = [
            'index' => [
                '_index' => 'products_attr',
                '_id'    => $row['product_id'].$row['category_id'].$row['attribute_id']
            ]
        ];
//        $myArray_all_text_attr = collect($all_text_attr[$row['product_id']]);
//        $myArray_all_text_attr=$myArray_all_text_attr->sort();
//       $myArray_all_text_attr->implode(' ')
        $params['body'][] = [
            'product_id'     => $row['product_id'],
            'category_id' => $row['category_id'],
            'attribute_id' => $row['attribute_id'],
            'name' => $row['name'],
            'text' => $row['text'],
            'all_text_attr' => implode(' ',$all_text_attr[$row['product_id']]),
            'quantity' => $row['quantity'],
        ];

    }

// Send the last batch if it exists
    if (!empty($params['body'])) {
        $responses = $client->bulk($params);
    }

    //echo "<pre>"; print_r($responses); die;
    return true;
}

public function GetSearchCategoryAttr($id){
    $client = $this->elasticclient;
    $result = array();

    $params = [
        'index' => 'products_attr',
        'type'  => '_doc',

        'body'  => [
            'query' => [
                "match"=> [
                    "category_id"=> $id,
                ],
            ],
        ],
        "size"=>10000,
    ];




    $response = $client->search($params);

    $result=[];
    $resuil_out=[];
    foreach ($response['hits']['hits'] as $hits){
        $result[$hits['_source']['attribute_id']][]=['attribute_id'=>$hits['_source']['attribute_id'],'attribute_name'=>$hits['_source']['name'],'attribute_text'=>$hits['_source']['text']];
    }
    foreach ($result as $key=>$val){
        $data = array_column($result[$key],'attribute_text');//получил уникальные значения атрибута
        $data= array_unique($data);

        $resuil_out[$key]=['attribute_id'=>$result[$key][0]['attribute_id'],'attribute_name'=>$result[$key][0]['attribute_name'],'attribute_text'=>$data];
    }
    return $resuil_out;
}

    public function GetSearchProductAttr($category_id,$mass_art_explode,$count=10000){
        $client = $this->elasticclient;
        $result = array();
        $mass_attr=[];

        foreach ($mass_art_explode as $item){
            $mass_attr['must'][]['match_phrase']=['all_text_attr'=>$item[1]];
        }
        $mass_attr['must'][]['match']=['category_id'=>$category_id];
        $params = [
            'index' => 'products_attr',
            'type'  => '_doc',

            'body'  => [
                'query' => [
                    "bool"=> [
                "must"=> $mass_attr['must'],
                 ]
                ],
                "sort"=> [
                "quantity"=> [
                "order"=> "desc"
                    ]
                    ]
            ],
            "size"=>$count,
        ];
//        $params = [
//            'index' => 'products_attr',
//            'type'  => '_doc',
//
//            'body'  => [
//                'query' => [
//                    "bool"=> [
//                        "must"=> [
//                            ["match"=>[
//                                "category_id"=> $category_id
//                            ]],
//                            ["match_phrase"=>[
//                                "all_text_attr"=> "Алмазный Цилиндр с плоским концом",
//                            ]],
//                        ]
//                    ]
//                ],
//            ],
//            "size"=>10000,
//        ];


        $response = $client->search($params);

        $result=[];
        foreach ($response['hits']['hits'] as $hits){
            $result[]=$hits['_source']['product_id'];
        }

        return $result;

    }

    public function GetSearchCategoryName($category_id){
        $client = $this->elasticclient;
        $result = array();


        foreach ($category_id as $item){

            $params = [
                'index' => 'products_category',
                'type'  => '_doc',

                'body'  => [
                    'query' => [
                        "match"=> [
                            "id_category"=> $item,
                        ]
                    ],
                ],
                "size"=>1,
            ];


            $response = $client->search($params);
            if(!empty($response['hits']['hits'][0]['_source']['name_category'])){
                $result[$item]=[$item,$response['hits']['hits'][0]['_source']['name_category']];
            }
        }

        return $result;

    }


public function GetSearchProductName($name,$size=30){
        $client = $this->elasticclient;
        $result = array();
    $query_string=trim($name);
    $query_string = str_replace(" ", " AND ", $query_string);

        $params = [
            'index' => 'products_name',
            'type'  => '_doc',

            'body'  => [
                'query' => [
                    "bool" => [
                        "must" => [ ],
                        "should" => [
                            [
                                "match_phrase"=> [
                                    "name"=>$name
                                ],
                            ],
                            [
                                "query_string"=> [
                                    "query"=>$query_string."*"
                                ],
                            ],
                            [
                                "multi_match"=> [
                                    "query"=> $name,
                                    "fields"=> [
                                        "name^10",
                                        "category_name^8",
                                        "model^5",
                                    ],
                                    "boost"=> 4,
                                    "operator"=> "and",
                                ]
                            ],
                            [
                                "wildcard"=> [
                                    "category_name"=>[
                                        "value"=>$name."*",
                                        "boost"=> 2,
                                        "rewrite"=>"constant_score",
                                    ]
                                ],
                            ],
                        ],
                        "minimum_should_match" => 1


                    ],
                ],
                "sort"=> [
                    "quantity"=> [
                        "order"=> "desc"
                    ]
                ],
                "size"=>$size,
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

    public function GetSearchProductNameSortToCategory($name,$category,$size=30){
        $client = $this->elasticclient;
        $result = array();

        $query_string=trim($name);
        $query_string = str_replace(" ", " AND ", $query_string);

        $params = [
            'index' => 'products_name',
            'type'  => '_doc',

            'body'  => [
                'query' => [
                    "bool" => [
                        "must" => [
                            "term"=> [
                                "category_id"=>$category
                            ],
                            ],
                        "should" => [
                            [
                                "match_phrase"=> [
                                    "name"=>$name
                                ],
                            ],
                            [
                                "query_string"=> [
                                    "query"=>$query_string."*"
                                ],
                            ],
                            [
                                "multi_match"=> [
                                    "query"=> $name,
                                    "fields"=> [
                                        "name^10",
                                        "category_name^8",
                                        "model^5",
                                    ],
                                    "boost"=> 4,
                                    "operator"=> "and",
                                ]
                            ],
                            [
                                "wildcard"=> [
                                    "category_name"=>[
                                        "value"=>$name."*",
                                        "boost"=> 2,
                                        "rewrite"=>"constant_score",
                                    ]
                                ],
                            ],
                        ],
                        "minimum_should_match" => 1


                    ],
                ],
                "sort"=> [
                    "quantity"=> [
                        "order"=> "desc"
                    ]
                ],
                "size"=>$size,
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


    public function GetSearchManufacturerProduct($id){
        $client = $this->elasticclient;
        $result = array();

        $params = [
            'index' => 'manufacturer_product',
            'type'  => '_doc',

            'body'  => [
                'query' => [
                    "match"=> [
                        "manufacturer_id"=> $id,
                    ]
                ],
                "sort"=> [
                    "quantity"=> [
                        "order"=> "desc"
                    ]
                ]
            ],
            "size"=>10000,
        ];




        $response = $client->search($params);

        $resuil=[];
        foreach ($response['hits']['hits'] as $hits){
            $resuil[]=$hits['_source']['product_id'];
        }
        return $resuil;

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
                "sort"=> [
                    "quantity"=> [
                        "order"=> "desc"
                    ]
                ]
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
