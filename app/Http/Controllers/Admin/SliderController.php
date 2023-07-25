<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Img;
use App\Models\Slider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(){

        $sliders=Slider::all();
        $title='Список слайдеров';
        return view('admin.slider.index',compact('title','sliders'));
    }

    public function create(){
        $title='Создание слайдера';
        return view('admin.slider.create',compact('title'));
    }


    public function store(Request $request){

        $validate= $request->validate([
           'name'=>'required',
           'location'=>'required',
           'status'=>'required',
        ]);

        $slider= new Slider();
        $slider->name=$validate['name'];
        $slider->location=$validate['location'];
        $slider->status=$validate['status'];
        $slider->save();
        return redirect()->route('admin.slider');
    }

    public function edit(Request $request){

        $slider=Slider::findOrFail($request->route('id'));
        $images=[];
        if (!empty($slider->id_image)){
            $images_explode=explode(',',$slider->id_image);

            $img = Img::whereIn('id', $images_explode)->orderBy('order','ASC')->get();
            foreach ($img as $val){
                $images[]=$val->toArray();
            }

        }

        $title='Редактирование слайдера';
        $id=$request->route('id');
        return view('admin.slider.update',compact('title','id','slider','images'));

    }

    public function destroy(Request $request){
        $slider=Slider::findOrFail($request->route('id'));
        $slider->delete();
        redirect()->route('admin.slider');
    }

    public function update(Request $request){

        $validate= $request->validate([
            'name'=>'required',
            'location'=>'required',
            'status'=>'required',
        ]);

        $slider=Slider::findOrFail($request->route('id'));
        $slider->name=$validate['name'];
        $slider->location=$validate['location'];
        $slider->status=$validate['status'];
        $slider->update();
        return redirect()->route('admin.slider');
    }
    public function storeImage(Request $request){

// Если начали на кнопку добавить фото слайдеру то добавляем
        if($request->file('image')){
            $data = $request->all();



            $filename    = $data['image']->getClientOriginalName();

            //Сохраняем оригинальную картинку
            $data['image']->move(public_path('/image/slider/').'origin/',$filename);
            if(!empty($data['width']) && !empty($data['height'])){//если задан формат то ресайзим
                //Создаем миниатюру изображения и сохраняем ее
                $thumbnail = Image::make(public_path('/image/slider/').'origin/'.$filename);
                $thumbnail->fit($data['width'], $data['height']);
                $thumbnail->save(public_path('/image/slider/').'thumbnail/'.$filename);
                $img= new Img();
                $img->path='image/slider/thumbnail/'.$filename;
            }else{
                $img= new Img();
                $img->path='image/slider/origin/'.$filename;
            }
            if(!empty($data['order'])){//если есть порядок то ставим
                $img->order=$data['order'];
            }
            //сохраняем картинку и записываем id в слайдер
            $img->save();
            $slider=Slider::findOrFail($data['id']);

            if(!empty($slider->id_image)){
                $id_image_list=$slider->id_image.','.$img->id;
                $slider->id_image=$id_image_list;
                $id_image_slider=$id_image_list;
            }else{
                $slider->id_image=$img->id;
                $id_image_slider=$img->id;
            }
            $slider->update();

            $id_image_slider=explode(',',$id_image_slider);
            $out='';
            $img = Img::whereIn('id', $id_image_slider)->orderBy('order','ASC')->get();
            foreach ($img as $val){
                $images=$val->toArray();
                $out=$out.'<a rel="gallery" data-fancybox class="photo m-1" href="'.asset($images['path']).'" title=""><img src="'.asset($images['path']).'" width="200" height="130" alt="" />
                            <span data-id="'.$images['id'].'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>
                        </a>';
            }

            return $out;
        }

    }



    public function destroyImage(Request $request){

        $img=Img::findOrFail($request->route('id'));
        $file = new Filesystem();
        if($file->exists(public_path($img->path))){
            $file->delete(public_path($img->path));
        }
        $img->delete();

        $slider=Slider::findOrFail($request->route('id_slider'));

        $images=$slider->id_image;
        if($images == $request->route('id')){
            $images="";
        }else{
            $images=str_replace(",".$request->route('id'), "", $images);
        }
        $slider->id_image=$images;
        $slider->update();
        $out='';
        if(!empty($images)){
            $id_image_slider=explode(',',$images);
            $img = Img::whereIn('id', $id_image_slider)->orderBy('order','ASC')->get();
            foreach ($img as $val){
                $images=$val->toArray();
                $out=$out.'<a rel="gallery" data-fancybox class="photo m-1" href="'.asset($images['path']).'" title=""><img src="'.asset($images['path']).'" width="200" height="130" alt="" />
                            <span data-id="'.$images['id'].'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>
                        </a>';
            }
        }


        return $out;

    }


}
