@extends('layouts.layout')
@section("title",'Create New Product')
@section('content')
<form class='form-row' method="POST" action="{{route('products.store')}}" enctype="multipart/form-data">
        @csrf
              <div class="form-group col-6">
                <label for="name_En">Name_en</label>
                <input type="text" class="form-control" name="name_en" id="name_En"  value='{{old('name_en')}}'>
              </div>
              @error('name_en')
    <div class="alert alert-danger col-6">{{ $message }}</div>
@enderror
              <div class="form-group col-6">
                    <label for="name_Ar">Name_ar</label>
                    <input type="text" class="form-control" name="name_ar" id="name_Ar" value='{{old('name_ar')}}' >
                  </div>
                  @error('name_ar')
                  <div class="alert alert-danger col-6 ">{{ $message }}</div>
              @enderror
             <div class="form-group col-4">
             <label for="price">price</label>
            <input type="numbwe" class="form-control" name='price' id="price" value='{{old('price')}}' >
          </div>
          @error('price')
          <div class="alert alert-danger col-8">{{ $message }}</div>
      @enderror
          <div class="form-group col-4">
                <label for="amount">Amount</label>
               <input type="number" class="form-control" name="amount" id="amount" value='{{old('amount')}}'  >
             </div>
             @error('amount')
             <div class="alert alert-danger col-8">{{ $message }}</div>
         @enderror
             <div class="form-group col-4">
                    <label for="code">Code</label>
                   <input type="text" class="form-control" name="code" id="code" value='{{old('code')}}'  >
                 </div>
                 @error('code')
                 <div class="alert alert-danger col-8">{{ $message }}</div>
             @enderror
          <div class="form-group col-4">
            <label for="image">Image upload</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" name="image" class="custom-file-input" id="image">
                <label class="custom-file-label" for="image">Choose file</label>
              </div>
              <div class="input-group-append">
                <span class="input-group-text form-control">Upload</span>
              </div>
            </div>
          </div>
          @error('image')
          <div class="alert alert-danger col-8">{{ $message }}</div>
      @enderror
           <div class="form-group col-md-4">
      <label for="cond">Condition</label>
      <select id="cond" name="cond" class="form-control">
        <option value="new"  {{old('cond') == "new" ? 'selected':''}}  >new</option>
        <option value="ordinary"  {{old('cond') == "ordinary" ? 'selected':''}} >Ordinary</option>
      </select>
    </div>
    @error('cond')
    <div class="alert alert-danger col-8">{{ $message }}</div>
@enderror
      <div class="form-group col-md-4">
            <label for="brand">Brand</label>
            <select id="brand" name="brand_id" class="form-control">
                    <option value="">No-Brand</option>
            @forelse($brands as $key=>$brand)
              <option  {{old('brand_id') == $brand->id ? 'selected':''}} value="{{$brand->id}}" >{{$brand->name_en}}</option>
              @empty
            <option disabled>Brand</option>
           @endforelse
            </select>
        </div>
        @error('brand_id')
        <div class="alert alert-danger col-8">{{ $message }}</div>
    @enderror

          <div class="form-group col-6">
                <label for="details_en">Details_en</label>
          <textarea class="form-control" id="details_en"  name="details_en" rows="3">{{old('details_en')}}</textarea>
              </div>
              @error('details_en')
              <div class="alert alert-danger col-6>{{ $message }}</div>
          @enderror
              <div class="form-group col-6" >
                    <label for="details_ar">Details_ar</label>
                    <textarea class="form-control" id="details_ar" name="details_ar" rows="3"{{old('details_ar')}}></textarea>
                  </div>
                  @error('details_ar')
                  <div class="alert alert-danger col-6">{{ $message }}</div>
              @enderror
                  <div class="form-group col-md-4">
                        <label for="subcat">Subcategory</label>
                        <select id="subcat" name="subcategory_id" class="form-control">
                                @forelse($subcategories as $key=>$subcategory)
                                <option  {{old('subcategory_id') == $subcategory->id ? 'selected':''}} value="{{$subcategory->id}}" >{{$subcategory->name_en}}</option>
                                @empty
                                <option disabled>Subcategory</option>
                             @endforelse
                        </select>
                    </div>
                    @error('subcategory_id')
                    <div class="alert alert-danger col-8">{{ $message }}</div>
                @enderror
                <div class="form-group col-md-4">
                    <label for="cond">Staus</label>
                    <select id="cond" name="status" class="form-control">
                      <option value="1"  {{old('status') == "1" ? 'selected':''}} >Active</option>
                      <option value="0"  {{old('status')== "0" ? 'selected':''}} >Not Active</option>
                    </select>
                  </div>
                  @error('status')
                  <div class="alert alert-danger col-8">{{ $message }}</div>
                  @enderror



        <!-- /.card-body -->
        <div class="card-footer col-12">
                <button type="submit" class="btn btn-warning">Create Product</button>
            </div>
      </form>
@endsection
