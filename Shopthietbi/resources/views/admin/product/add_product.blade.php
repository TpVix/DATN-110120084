@extends('admin_layout')
@section('main_admin')
    <div class="content">
        <form role="form" id="form-all" action="{{ URL::to('/save-product') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
                <div>
                    <h1>Thêm sản phẩm</h1>
                    <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                        <span><i class="mdi mdi-chevron-right"></i></span>Product
                    </p>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom">
                            <h2>Thêm sản phẩm</h2>
                        </div>



                        <?php
                        $message = Session::get('message');
                        if ($message) {
                            echo "<div class='alert alert-success'>$message</div>";
                            Session::put('message', null);
                        }
                        ?>
                        <div class="card-body">
                            <div class="row ec-vendor-uploads">
                                <div class="col-lg-4">
                                    <div class="ec-vendor-img-upload">
                                        <div class="ec-vendor-main-img">
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="product_image"
                                                        class="ec-image-upload" accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload"><img
                                                            src="{{ asset('public/backend/assets/img/icons/edit.svg') }}"
                                                            class="svg_img header_svg" alt="edit" /></label>
                                                </div>
                                                <div class="avatar-preview ec-preview">
                                                    <div class="imagePreview ec-div-preview">
                                                        <img class="ec-image-preview"
                                                            src="{{ asset('public/backend/assets/img/products/vender-upload-preview.jpg') }}"
                                                            alt="edit" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="ec-vendor-upload-detail">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="inputEmail4" class="form-label">Tên sản phẩm</label>
                                                <input data-validation="required" class="form-control slug-title"
                                                    data-validation-error-msg="Tên sản phẩm không được để trống"
                                                    type="text" name="product_name" id="exampleInputEmail1"
                                                    placeholder="Tên sản phẩm">

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Số lượng</label>
                                                <input type="text" data-validation="number"
                                                    data-validation-error-msg="Số lượng phải là số và không được để trống"
                                                    name="product_quantity" class="form-control" id="exampleInputEmail1"
                                                    placeholder="Số lượng sản phẩm">

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Danh mục</label>

                                                <select name="category_id" class="form-select">
                                                    <option value="#">
                                                        --Chọn danh mục--</option>
                                                    @foreach ($category as $cate)
                                                        @if ($cate->category_parent == 0)
                                                            <option value="{{ $cate->category_id }}">
                                                                {{ $cate->category_name }}</option>
                                                        @else
                                                            @foreach ($category as $cate_parent)
                                                                @if ($cate_parent->category_id == $cate->category_parent)
                                                                    <option value="{{ $cate->category_id }}">
                                                                        --{{ $cate->category_name }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach


                                                </select>

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Thương hiệu</label>

                                                <select name="brand_id" class="form-select">
                                                    @foreach ($brand as $key => $brand)
                                                        <option value="{{ $brand->brand_id }}">
                                                            {{ $brand->brand_name }}</option>
                                                    @endforeach

                                                </select>

                                            </div>
                                            <div class="col-md-12">
                                                <label for="slug" class="col-12 col-form-label">Đường dẫn</label>
                                                <div class="col-12">
                                                    <input data-validation="required"
                                                        data-validation-error-msg="Đường dẫn không được để trống"
                                                        type="text" name="product_slug"
                                                        class="form-control here set-slug" id="exampleInputEmail1"
                                                        placeholder="Đường dẫn sản phẩm">

                                                </div>
                                            </div>


                                            <div class="col-md-8 mb-25">


                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Giá </label>

                                                <input type="text" data-validation="number"
                                                    data-validation-error-msg="Giá phải là số và không được để trống"
                                                    name="product_price" class="form-control" id="exampleInputEmail1"
                                                    placeholder="Giá sản phẩm">

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Giá khuyến mãi</label>

                                                <input type="text" data-validation="number"
                                                    data-validation-error-msg="Giá khuyến mãi phải là số và không được để trống"
                                                    name="product_sale_price" class="form-control" id="exampleInputEmail1"
                                                    placeholder="Giá khuyến mãi sản phẩm">

                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label">Mô tả</label>
                                                <textarea class="form-control" rows="4" name="product_desc" id="editor1" placeholder="Mô tả"></textarea>

                                            </div>
                                            <div class="col-md-12">
                                                <label for="exampleInputEmail1">Khuyến mãi</label>
                                                <select name="product_status" class="form-control input-sm m-bot15">
                                                    <option value="1">Có</option>
                                                    <option value="0">Không</option>
                                            </div>
                                            </select>


                                            <div class="col-md-12" style="margin-top: 20px;">
                                                <button type="submit" class="btn btn-primary">Thêm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
