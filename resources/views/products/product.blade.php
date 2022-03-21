@extends('layouts.layout')
@section("title",'All Products')
@section('css')
<link rel="stylesheet" href="{{('plugins/datatables-bs4/css/dataTables.bootstrap4.min.js')}}">
<link rel="stylesheet" href="{{('plugins/datatables-responsive/css/responsive.bootstrap4.min.js')}}">
<link rel="stylesheet" href="{{('plugins/datatables-buttons/css/buttons.bootstrap4.min.js')}}">
@endsection
@section('content')
@include('includes.message')
<table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
                <th>Id</th>
                <th>Name En</th>
                <th>Price</th>
                <th>Amount</th>
                <th>Product Code</th>
                <th>Condition</th>
                <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $key=>$product)
        <tr>
          {{-- <td>{{$product->id}}</td> --}}
          <td>{{$loop->iteration}}</td>
          <td>{{$product->name_en}}</td>
          <td>{{$product->price}}</td>
          <td> {{$product->amount}}</td>
          <td>{{$product->code}}</td>
         <td>{{$product->cond}}</td>
         <td>
         <a href="{{route('products.edit',$product->id)}}" class="btn btn-outline-warning rounded mb-2" style='width:50%;'>Edit</a>
         <form action="{{route('products.destroy',$product->id)}}" method="POST" >
            @method('DELETE')
            @csrf
            <input type='hidden' name='image' value="{{$product->image}}">
            <input type='submit' class="btn btn-outline-danger rounded" value="Delete" style='width:50%;'>
         </form>
         </td>
        </tr>
        @empty
        <tr class='text-center'>
                <td colspan="7">No products</td>
              </tr>
              @endforelse
        </tbody>
      </table>
@endsection
@section('js')
<script src="{{('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script>
        $(function () {
          $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
      </script>
@endsection
