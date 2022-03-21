<?php

namespace App\Http\Controllers\api\products;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Subcategory;
use App\traits\Generaltrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;

class productController extends Controller
{   use Generaltrait;
    public function index()
    {
        try {
            $products = Product::all();
            foreach($products as $key=>$product){
                $product['image']=url('images/products/'.$product['image']);
            }
            return response()->json(["data" => $products, "status" => 200, 'message' => "All products has been retrieved"]);
        } catch (Exception $e) {
            return response()->json(["data" => [], "status" => 500, 'message' => "Something went wrong"]);
        }
    }
    public function create()
    {
        try {
            $brands = Brand::select('name_en', 'id')->orderby('name_en')->get();
            $subcategories = Subcategory::select('name_en', 'id')->orderby('name_en')->get();
            return response()->json(["data" => ['brands' => $brands, 'subcategories' => $subcategories], "status" => 200, 'message' => ""]);
        } catch (Exception $e) {
            return response()->json(["data" => [], "status" => 500, 'message' => "Something went wrong"]);
        }
    }
    public function edit($id){
        try{
          $product=product::where('id',$id)->first();
          if($product){
            $brands = Brand::select('name_en', 'id')->orderby('name_en')->get();
            $subcategories = Subcategory::select('name_en', 'id')->orderby('name_en')->get();
            return response()->json(["data" => ['brands' => $brands, 'subcategories' => $subcategories,'product' => $product], "status" => 200, 'message' => ""]);
          }
          else{
            return response()->json(["data" => "", "status" => 404, 'message' => "Not Found"]);
          }
        }
        catch(Exception $e){
            return response()->json(["data" => "", "status" => 500, 'message' => "Something went wrong"]);

        }
    }
    public function store(Request $request)
    {
        try {
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
                 'image'=>['required','max:1000','mimes:jpg,bmp,png']
            ];
            $data=$request->except('image');
            $validator = Validator::make($request->all(), $rules);
            if($validator->fails())
            {     $errors=$validator->errors();
                return response()->json(["data" => "['errors' => $errors]", "status" => 400, 'message' => "Validation Error has occured"]);}
            else{
            $data['image']=  $this->uploadImage($request->image,'products');
            $product=product::create($data);
            return response()->json(["data" => ['product' => $product], "status" => 200, 'message' => "Product has been created"]);
            }
        } catch (Exception $e) {
            return response()->json(["data" => [], "status" => 500, 'message' => "something went wrong"]);
        }
    }
    public function update(Request $request,$id){
       try{
        $product=product::where('id',$id)->first();
        if($product){
            $rules=[
                'name_en'=>['required','string','max:255'],
                'name_ar'=>['required','string','max:255'],
                'price'=>['required','numeric','min:1','max:50000'],
                'amount'=>['required','integer','min:1'],
                 'code'=>['required','string','max:10','unique:products,code,'.request()->route('id')],
                 'cond'=>['required','string',Rule::in(['new', 'ordinary'])],
                 'status'=>['required','integer',Rule::in([0, 1])],
                 'brand_id'=>['nullable','integer','exists:brands,id'],
                 'subcategory_id'=>['required','integer','exists:subcategories,id'],
                 'details_en'=>['nullable','string','max:100'],
                 'details_ar'=>['nullable','string','max:100'],
                 'image'=>['nullable','max:1000', 'mimes:jpg,bmp,png']
            ];
            $data=$request->except('image');
            $validator=validator::make($request->all(),$rules);
            if($validator->fails()){
            $errors=$validator->errors();
            return response()->json(["data" => ["errors" => $errors], "status" => 400, 'message' => "Validation Error has occured"]);
            }
            else{
               if($request->hasFile('image')){
              $image=  $this->uploadImage( $request->all()['image'],'products');
             $data['image'] =$image;    }
               product::where('id',$id)->update($data);
               $product=product::find($id);
               return response()->json(["data" => ['product' => $product], "status" => 200, 'message' => "Product has been updated"]);
            }
        }
        else{
            return response()->json(["data" => [] ,"status" => 404, 'message' => "Not Found"]);
        }
       }catch(Exception $e){
        return response()->json(["data" => [], "status" => 500, 'message' => "Something went wrong"]);

       }
    }
    public function destroy($id){
      try{
        $product=product::find($id);
        if($product){
            product::find($id)->delete();
            $path=public_path('images\products\\'.$product->image);
            if(file_exists($path))
              unlink($path);
        return response()->json(["data" => ['product' => $product], "status" => 200, 'message' => "product has been deleted"]);
        }
        else{
            return response()->json(["data" => [], "status" => 400, 'message' => "Not Found"]);

        }
      }
      catch(Exception $e){
        return response()->json(["data" => [], "status" => 500, 'message' => "Something went wrong"]);
      }
    }
}
