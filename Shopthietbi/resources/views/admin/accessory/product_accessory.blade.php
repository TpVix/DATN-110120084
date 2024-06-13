@extends('admin_layout')
@section('main_admin')
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-wrapper-2 breadcrumb-contacts">
            <h1>Sản phẩm cho {{$accessory_name->accessory_name}}</h1>
            
        </div>
        <div class="row">
            <div class="col-xl-8 col-lg-12">
                <div class="ec-cat-list card card-default mb-24px">
                    <div class="card-body">
                        <?php
                            $chose = Session::get('chose_success');
                            if ($chose) {
                                echo "<div class='alert alert-success'>$chose</div>";
                                Session::put('chose_success', null);
                            }
                            ?>
                        <div class="table-responsive ">
                            <table id="" class="table table_data"
                              style="width:100%">
                              <thead>
                                <tr>
                                  <th>Hình ảnh</th>
                                  <th>Tên sản phẩm</th>
                          
                                  <th>Giá</th>
                              
                                  <th>Nhóm phụ kiện</th>
                                  <th>Tên thương hiệu</th>
                                 
                                  <th>Hành động</th>
                                </tr>
                              </thead>
                
                              <tbody>
                                @foreach ($all_product as $key => $product)
                                <tr>
                                  <td><img src="{{ url('public/upload/' . $product->product_image) }}" height="100" width="100" alt=""></td>
                              <td>{{ $product -> product_name}}</td>
                          
                              <td>{{ $product -> product_price}}</td>
                         
                              <td>{{ $product -> accessory_name}}</td>
                              <td>{{ $product -> brand_name}}</td>
                              
                
                              <td
                              
                              >
                                <a href="{{URL::to('/chose-product-accessory/'.$product->product_id)}}" class="btn btn-outline-success" ui-toggle-class="">
                                   Chọn <i class="fa fa-pencil-square-o text-success text-active"></i>
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
            <div class="col-xl-4 col-lg-12">
                <div class="ec-cat-list card card-default">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table_data">
                                <thead>
                                    <tr>
                                        <th>Hình</th>
                                        <th>Tên</th>
                                        

                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($product_accessory as $v_product_accessory)
                                        <tr>
                                            <td><img src="{{ url('public/upload/' . $v_product_accessory->product_image) }}" height="100" width="100" alt=""></td>

                                            <td>{{ $v_product_accessory->product_name }}</td>

                                            
                                            <td>
                                                <div class="btn-group">
                                                    <a onclick="return confirm('Bạn có chắc muốn xoá ?')" href="{{URL::to('/delete-product-accessory/'.$v_product_accessory->product_id)}}" class="btn btn-outline-danger">Xoá</a>
                                                    
                                                </div>
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
