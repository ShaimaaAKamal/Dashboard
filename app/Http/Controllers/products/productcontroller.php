<?php

namespace App\Http\Controllers\products;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductRequest;
use App\traits\Generaltrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class productcontroller extends Controller
{  use Generaltrait;
    public function __construct()
    {
               $this->middleware('password.confirm')->only('edit');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    $products=DB::table('products')->select('*')->get();
        return view('products.product',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

     {  try{
        $brands=DB::table('brands')->select('name_en','id')->where('status',1)->orderby('name_en')->get();
        $subcategories=DB::table('subcategories')->select('name_en','id')->where('status',1)->orderby('name_en')->get();
         return view('products.create',compact('brands','subcategories') );
     }
     catch(Exception $e){
        return redirect()->route('products.index')->with('error','<div class="alert alert-alert text-center">Something went wrong </div');
     }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
         {//    var_dump($request->all());die;
        // print_R($request->all());die;   //
        $rules=[
            'name_en'=>['required','string','max:255'],
            'name_ar'=>['required','string','max:255'],
            'price'=>['required','numeric','min:1','max:50000'],
            'amount'=>['required','integer','min:1'],
             'code'=>['required','string','max:10','unique:products'],
             'cond'=>['required','string',Rule::in(['new', 'ordinary'])],
              'status'=>['required','integer',Rule::in([0, 1])],
             'brand_id'=>['nullable','integer','exists:brands,id'],
             'subcategory_id'=>['required','integer','exists:subcategories,id'],
             'details_en'=>['nullable','string','max:100'],
             'details_ar'=>['nullable','string','max:100'],
             'image'=>['required','max:1000', 'mimes:jpg,bmp,png']
        ];
        $request->validate($rules);
        $photoName=time().'.'.$request->image->extension();
        $path=public_path('images\products\\');
        $request->image->move($path,$photoName);
        $data=$request->except('image','_token');
        $data['image']= $photoName;
        try{
            DB::table('products')->insert($data);
            return redirect()->route('products.index')->with('success','<div class="alert alert-success text-center"> Successfull Operation </div>');
        }
        catch(exception $e){
            return redirect()->route('products.index')->with('error','<div class="alert alert-alert text-center">Something went wrong </div');
        }
          }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product=DB::table('products')->select('*')->where('id',$id)->first();
        if($product){
            try{
                $brands=DB::table('brands')->select('name_en','id')->where('status',1)->orderby('name_en')->get();
                $subcategories=DB::table('subcategories')->select('name_en','id')->where('status',1)->orderby('name_en')->get();
                return view('products.edit',compact('product','brands','subcategories'));
            }

            catch(Exception $e){
                return redirect()->route('products.index')->with('error','<div class="alert alert-alert text-center">Something went wrong </div');
            }
        }
        else{
         return view('errors.404');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $validated = $request->validated();
        if($request->hasFile('image')){
           $validated['image']=$this->uploadImage($request->image,'products');
        }
        try{
            DB::table('products')->where('id',$id)->update($validated);
            return redirect()->back()->with('success','<div class="alert alert-success text-center col-12">Update has been don successfully </div>');
        }catch(Exception $e){
             return redirect()->route('products.index')->with('error','<div class="alert alert-alert text-center col-12">Something went wrong </div');
        }

        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request , $id)
    {
        $product=DB::table('products')->select('*')->where('id',$id)->first();
        if($product){
            try{
                DB::table('products')->where('id',$id)->delete();
                $path=public_path('images\products\\'.$product->image);
                if(file_exists($path)){
                  unlink($path);
                }
                return redirect()->back()->with('success','<div class="alert alert-success text-center">Product has been deleted successfully </div>');

            }catch(exception $e){
             return redirect()->back()->with('error','<div class="alert alert-alert text-center">Something went wrong </div');
            }
        }
        else{
            return view('errors.404');
        }
    }
}
