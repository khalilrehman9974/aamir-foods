@extends('layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>Product Detail</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('product.products') }}">List of Products</a>
                            </li>
                            <li class="breadcrumb-item active">Product Detail</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card card-primary card-tabs">
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <table id="example1" class="table table-bordered"
                                       style="overflow-scrolling: auto; max-height: 5px">
                                    <tbody>
                                    <tr>
                                        <td><b>Id</b></td>
                                        <td class="project-actions text-left">
                                            {{ $product->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Brand</b></td>
                                        <td class="project-actions text-left">
                                            {{ $product->brandName }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Product Name</b></td>
                                        <td class="project-actions text-left">
                                            {{ $product->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Price</b></td>
                                        <td class="project-actions text-left">
                                            {{ $product->price }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Size</b></td>
                                        <td class="project-actions text-left">
                                            {{   $product->size  }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('product.products') }}" class="btn btn-secondary float-right">Back</a>
                    <!-- /.card -->
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
