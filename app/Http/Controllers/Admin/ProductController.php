<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CategoryAdmin;
use App\Models\CategoryDescriptionAdmin;
use App\Models\ProductAdmin;
use App\Models\ProductDescriptionAdmin;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){


        $products=DB::connection('mysql2')->table('sd_product_description')->select('sd_product_description.product_id','sd_product_description.name','sd_product.quantity','sd_product.status','sd_product.model')
            ->join('sd_product','sd_product.product_id','=','sd_product_description.product_id')
            ->orderBy('sd_product.product_id', 'desc')
            ->paginate(60);

        $title='Список товаров';
        $page="product";
        return view('admin.product.index',compact('title','products','page'));
    }

    public function create(){
        $title='Создание продукта';

        $manufacturers=DB::connection('mysql2')->table('sd_manufacturer')->select('sd_manufacturer.manufacturer_id','sd_manufacturer.name')
            ->orderBy('sd_manufacturer.name', 'asc')
            ->groupBy('sd_manufacturer.name')
            ->get();

        $categories=DB::connection('mysql2')->table('sd_category')->select('sd_category.category_id','sd_category_description.name')
            ->join('sd_category_description', 'sd_category.category_id','=','sd_category_description.category_id')
            ->where('sd_category.status','=','1')
            ->orderBy('sd_category_description.name', 'asc')
            ->groupBy('sd_category_description.name')
            ->get();


        $attributes=DB::connection('mysql2')->table('sd_attribute_description')->select('sd_attribute_description.attribute_id','sd_attribute_description.name')
            ->orderBy('sd_attribute_description.attribute_id', 'DESC')
            ->get();

        return view('admin.product.create',compact('title','manufacturers','categories','attributes'));
    }

    public function store(Request $request){



        $validate=$request->validate([
            'name'=>'required',
            'description'=>'required',
            'price1'=>'required|numeric',
            'price2'=>'required|numeric',
            'price3'=>'required|numeric',
            'model'=>'required',
            'sku'=>'required',
            'mpn'=>'required|numeric',
            'quantity'=>'required|numeric',
//            'tag'=>'required',
            'manufacturer'=>'required',
            'category'=>'required',
            'image'=>'required',
            'attribute'=>'required',
            'input_attribute'=>'required',
        ]);
        $now = Carbon::now();

    $product=new ProductAdmin();
    $product->model=$validate['model'];
    $product->sku=$validate['sku'];
    $product->upc=0;
    $product->ean=0;
    $product->jan=0;
    $product->isbn=0;
    $product->mpn=$validate['mpn'];
    $product->location=0;
    $product->quantity=$validate['quantity'];
    $product->stock_status_id =6;
    $filename = $validate['image']->getClientOriginalName();
    //Сохраняем оригинальную картинку
    $validate['image']->move(public_path('/image/product/'),$filename);
    $product->image='/'.$filename;
    $product->manufacturer_id=$validate['manufacturer'];
    $product->shipping =1;
    $product->price=$validate['price1'];
    $product->points=0;
    $product->tax_class_id=0;
    $product->date_available=$now->toDateString();
    $product->weight=0;
    $product->weight_class_id=1;
    $product->length=0;
    $product->width=0;
    $product->length_class_id=0;
    $product->height=0;
    $product->subtract=0;
    $product->minimum=1;
    $product->sort_order=1;
    $product->status=1;
    $product->viewed=0;
    $product->date_added=$now->toDateString();
    $product->SumNaL=0;
    $product->xml_active=0;
    $product->apteka=0;
    $product->min_sklad=0;
    $product->save();

    $productDescription=new ProductDescriptionAdmin();
    $productDescription->product_id=$product->product_id;
    $productDescription->language_id=1;
    $productDescription->name=$validate['name'];
    $productDescription->description=$validate['description'];
    $productDescription->meta_description='';
    $productDescription->meta_keyword='';
    $productDescription->seo_title='';
    $productDescription->seo_h1='';
    if(!empty($validate['tag'])){
        $productDescription->tag=$validate['tag'];
    }
    $productDescription->slug='';
    $productDescription->save();


     $product_to_category=DB::connection('mysql2')->table('sd_product_to_category')->insert(
         [
             'product_id'=>$product->product_id,
             'category_id'=>$validate['category'],
             'main_category'=>1,
         ]
     );

     $parent_category=DB::connection('mysql2')->table('sd_category')->select('category_id','parent_id')->where('parent_id','=',$validate['category'])->get();

     if(!empty($parent_category)){
         $parent_category->map(function ($item) use ($product){

             if(!empty($item->parent_id)){

                 DB::connection('mysql2')->table('sd_product_to_category')->insert(
                     [
                         'product_id'=>$product->product_id,
                         'category_id'=>$item->parent_id,
                         'main_category'=>0,
                     ]
                 );

             }

             DB::connection('mysql2')->table('sd_product_to_category')->insert(
                 [
                     'product_id'=>$product->product_id,
                     'category_id'=>$item->category_id,
                     'main_category'=>0,
                 ]
             );

         });
     }

     foreach ($validate['attribute'] as $key=>$attribute){

                 $product_to_attribute=DB::connection('mysql2')->table('sd_product_attribute')->insert(
            [
                'product_id'=>$product->product_id,
                'attribute_id'=>$attribute,
                'language_id'=>1,
                'text'=>$validate['input_attribute'][$key],
            ]
        );

     }

        $product_to_discount=DB::connection('mysql2')->table('sd_product_discount')->insert(
            [
                'product_id'=>$product->product_id,
                'customer_group_id'=>0,
                'quantity'=>0,
                'priority'=>1,
                'price'=>$validate['price1'],
            ]
        );
        $product_to_discount=DB::connection('mysql2')->table('sd_product_discount')->insert(
            [
                'product_id'=>$product->product_id,
                'customer_group_id'=>2,
                'quantity'=>0,
                'priority'=>0,
                'price'=>$validate['price2'],
            ]
        );
        $product_to_discount=DB::connection('mysql2')->table('sd_product_discount')->insert(
            [
                'product_id'=>$product->product_id,
                'customer_group_id'=>3,
                'quantity'=>0,
                'priority'=>0,
                'price'=>$validate['price3'],
            ]
        );
        app('Search')->InsertDataProduct();
        return redirect()->route('admin.product');
    }

    public function destroy(Request $request){
        $prod=ProductAdmin::findOrFail($request->route('id'));
        $file = new Filesystem();
        if($file->exists(public_path('/image/product'),$prod->image)){
            $file->delete(public_path('/image/product'),$prod->image);
        }
        $prod->delete();
        $prodDescription=ProductDescriptionAdmin::findOrFail($request->route('id'));
        $prodDescription->delete();

        $product_to_category=DB::connection('mysql2')->table('sd_product_to_category')->where('product_id','=',$request->route('id'))->delete();
        $product_to_attribute=DB::connection('mysql2')->table('sd_product_attribute')->where('product_id','=',$request->route('id'))->delete();
        $product_to_discount=DB::connection('mysql2')->table('sd_product_discount')->where('product_id','=',$request->route('id'))->delete();

        return redirect()->route('admin.product');
    }



}
