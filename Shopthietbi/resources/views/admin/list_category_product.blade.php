@extends('admin_layout')
@section('main_admin')
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-wrapper-2 breadcrumb-contacts">
            <h1>Danh sách danh mục</h1>
            <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>Main Category
            </p>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="ec-cat-list card card-default">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="" class="table table_data">
                                <?php
                                $message = Session::get('message');
                                if ($message) {
                                    echo "<div class='alert alert-success'>$message</div>";
                                    Session::put('message', null);
                                }
                                ?>
                                <thead>
                                    <tr>
                                        <th>Thumb</th>
                                        <th>Tên</th>
                                        <th>Bậc danh mục</th>
                                        <th>Đường dẫn</th>
                                        <th>Trạng thái</th>

                                        <th>Hành động</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($all_category_product as $key => $category)
                                        <tr>
                                            <td><img class="cat-thumb" src="assets/img/category/clothes.png"
                                                    alt="Product Image" /></td>
                                            <td> {{ $category->category_name }}</td>


                                            <td>
                                                @if ($category->category_parent == 0)
                                                    ---Danh mục cha---
                                                @else
                                                    @foreach ($all_category_product as $category_parent)
                                                        @if ($category_parent->category_id == $category->category_parent)
                                                           Con của: {{ $category_parent->category_name }}
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </td>
                                            <td> {{ $category->category_slug }}</td>
                                            <td>
                                                <?php
                                                if ($category->category_status == 0) {
                                                    echo '<a class="badge badge-danger" href="' . URL::to('/active-category-product/' . $category->category_id) . '">Ẩn</a>';
                                                } else {
                                                    echo '<a class="badge badge-success" href="' . URL::to('/unactive-category-product/' . $category->category_id) . '">Hiện</a>';
                                                }
                                                ?>


                                            </td>


                                            <td>
                                                <a href="{{ URL::to('/edit-category-product/' . $category->category_id) }}"
                                                    class="btn btn-outline-success" ui-toggle-class="">
                                                    Sửa <i class="fa fa-pencil-square-o text-success text-active"></i>
                                                </a>
                                                <a onclick="return confirm('Bạn có chắc muốn xoá ?')"
                                                    href="{{ URL::to('/delete-category-product/' . $category->category_id) }}"
                                                    class="btn btn-outline-danger" ui-toggle-class="">
                                                    Xoá <i class="fa fa-times text-danger text"></i>
                                                </a>
                                            </td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
