@extends('layouts.layout')
@section('title','index')
@section('content')
<div class="row">
        <div class="col-md-8">
          <p class="text-center">
            <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
          </p>

          <div class="chart">
            <!-- Sales Chart Canvas -->
            <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
          </div>
          <!-- /.chart-responsive -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
          <p class="text-center">
            <strong>Goal Completion</strong>
          </p>

          <div class="progress-group">
            Add Products to Cart
            <span class="float-right"><b>160</b>/200</span>
            <div class="progress progress-sm">
              <div class="progress-bar bg-primary" style="width: 80%"></div>
            </div>
          </div>
          <!-- /.progress-group -->

          <div class="progress-group">
            Complete Purchase
            <span class="float-right"><b>310</b>/400</span>
            <div class="progress progress-sm">
              <div class="progress-bar bg-danger" style="width: 75%"></div>
            </div>
          </div>

          <!-- /.progress-group -->
          <div class="progress-group">
            <span class="progress-text">Visit Premium Page</span>
            <span class="float-right"><b>480</b>/800</span>
            <div class="progress progress-sm">
              <div class="progress-bar bg-success" style="width: 60%"></div>
            </div>
          </div>

          <!-- /.progress-group -->
          <div class="progress-group">
            Send Inquiries
            <span class="float-right"><b>250</b>/500</span>
            <div class="progress progress-sm">
              <div class="progress-bar bg-warning" style="width: 50%"></div>
            </div>
          </div>
          <!-- /.progress-group -->
        </div>
        <!-- /.col -->
      </div>
@endsection
