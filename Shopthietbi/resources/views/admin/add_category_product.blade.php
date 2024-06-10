@extends('admin_layout')
@section('main_admin')
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-wrapper-2 breadcrumb-contacts">
            <h1>Danh mục</h1>
            <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>Main Category
            </p>
        </div>
        <div class="ec-cat-list card card-default mb-24px" id="addUser" tabindex="-1" role="dialog">
            <div class=" modal-dialog-centered " role="document">
                <div class="col-lg-12" >
                    <?php
                    $message = Session::get('message');
                    if ($message) {
                        echo "<div class='alert alert-success'>$message</div>";
                        Session::put('message', null);
                    }
                    ?>

                    <form role="form" id="form-all" action="{{ URL::to('/save-category-product') }}" method="post">
                        {{ csrf_field() }}

                        <div class="modal-header px-4">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Thêm danh mục</h5>
                        </div>

                        <div class="modal-body px-4">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="ec-cat-list card card-default mb-24px">

                                        <div class="card-body">
                                            <div class="row ec-vendor-uploads">
                                                <div class="col-lg-5">
                                                    <div class="ec-vendor-img-upload">
                                                        <div class="ec-vendor-main-img">
                                                            <div class="avatar-upload">
                                                                <div class="avatar-edit">
                                                                    <input type='file' id="imageUpload"
                                                                        name="category_image" class="ec-image-upload"
                                                                        accept=".png, .jpg, .jpeg" />
                                                                    <label for="imageUpload"><img
                                                                            src="{{ asset('public/backend/assets/img/icons/edit.svg') }}"
                                                                            class="svg_img header_svg"
                                                                            alt="edit" /></label>
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
                                                <div class="col-lg-7">
                                                    <div class="ec-vendor-upload-detail">

                                                        <div class="col-md-12">
                                                            <label for="inputEmail4" class="form-label">Tên danh mục</label>
                                                            <input type="text" data-validation="required"
                                                                data-validation-error-msg="Tên danh mục không được để trống"
                                                                name="category_product_name" class="form-control slug-title"
                                                                id="inputEmail4">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="inputEmail4" class="form-label">Đường dẫn</label>
                                                            <input type="text" data-validation="required"
                                                                data-validation-error-msg="Đường dẫn không được để trống"
                                                                name="category_product_slug" class="form-control slug-title"
                                                                id="inputEmail4">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label for="inputEmail4" class="form-label">Danh mục cha</label>
                                                            <select name="category_parent" class="form-select">
                                                                @foreach ($all_category_product as $v_all_category_product)
                                                                    @if ($v_all_category_product->category_parent == 0)
                                                                        <option
                                                                            value="{{$v_all_category_product->category_id}}">
                                                                            ---{{$v_all_category_product->category_name}}---
                                                                        </option>
                                                                    @endif
                                                                @endforeach


                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="inputEmail4" class="form-label">Trạng thái</label>
                                                            <select name="category_product_status" class="form-select">
                                                                <option value="0">Ẩn</option>
                                                                <option value="1">Hiện</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mt-1 text-right">
                                                            <button name="submit" type="submit"
                                                                class="btn btn-primary">Thêm</button>
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
            </div>
        </div>
    </div>
@endsection
