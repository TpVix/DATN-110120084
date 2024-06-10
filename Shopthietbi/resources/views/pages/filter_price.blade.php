
@extends('layout')
@section('content')

<main class="main">
    <div class="container">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}"><i class="icon-home"></i></a></li>
             
                
                <li class="breadcrumb-item">Kết quả lọc</li>
               
              
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-9 main-content">
              

                <div class="row">
                 
                   
                    @foreach ($filter_price as $key => $filter)



                    <div class="col-6 col-sm-4">
                        <div class="product-default">
                            <figure>
                                <a href="{{URL::to('/san-pham/'.$filter-> product_slug)}}">
                                    <img src="{{URL::to('public/upload/'.$filter -> product_image)}}" style="width: 280px; height: 280px;"alt="product">
                                    <img src="{{URL::to('public/upload/'.$filter -> product_image)}}" style="transform: scaleX(-1);width: 280px; height: 280px;" alt="product" />
                                </a>

                                @if ($filter-> product_status == '1')
                                <div class="label-group">
                                    <div class="product-label label-hot"> 
                                        Giảm: {{ round(100 - (($filter->product_sale_price / $filter->product_price) * 100)) }}%
                                    </div>                                
                                </div>
                                @else
                                
                                @endif
                            </figure>

                            <div class="product-details">
                                <div class="category-wrap">
                                    <div class="category-list">
                                        <a href="category.html" class="product-category">category</a>
                                    </div>
                                </div>

                                <h3 class="product-title"> <a href="product.html">{{($filter-> product_name)}}</a>
                                </h3>

                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:100%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->

                                @if ($filter-> product_status == '1')

                                <div class="price-box">
                                    <del class="old-price">{{number_format($filter-> product_price)}}</del>
                                    <span style="color:red;" class="product-price">{{number_format($filter-> product_sale_price).' '.'VNĐ'}}</span>
                                </div>
                                @else
                                <div class="price-box">
                                    <span style="color:red;" class="product-price">{{number_format($filter-> product_price).' '.'VNĐ'}}</span>
                                </div>
                                @endif
                                <!-- End .price-box -->

                                <div class="product-action">
                                           
                                            <form action="" method="post">
                                                @csrf
                                                <input type="hidden" name="" value="{{$filter-> product_id}}"
                                                 class="cart_product_id_{{$filter-> product_id}}">
                                                 <input type="hidden" name="" value="{{$filter-> product_name}}"
                                                 class="cart_product_name_{{$filter-> product_id}}">
                                                 <input type="hidden" name="" value="{{$filter-> product_image}}"
                                                 class="cart_product_image_{{$filter-> product_id}}">
                                                 @if ($filter-> product_status == '1')
                                                 <input type="hidden" name="" value="{{$filter-> product_sale_price}}"
                                                 class="cart_product_price_{{$filter-> product_id}}">
                                                 @else
                                                 <input type="hidden" name="" value="{{$filter-> product_price}}"
                                                 class="cart_product_price_{{$filter-> product_id}}">
                                                 @endif
                                                 <input type="hidden" name="" value="1"
                                                 class="cart_product_qty_{{$filter-> product_id}}">
                                                 <?php 
                                                    $customer_id = Session::get('customer_id');
                                                ?>
                                                @if ($customer_id == null)
                                                <a href="{{ URL::to('/login-register') }}"
                                                class="btn-icon btn-add-cart1 product-type-simple">ĐĂNG NHẬP ĐỂ ĐẶT HÀNG</a>
                                                @else
                                                <button type="submit" href="#" class="btn-icon btn-add-cart1 product-type-simple"><i
                                                    class="icon-shopping-cart"></i><span>THÊM VÀO GIỎ HÀNG</span></button>
                                                @endif
                                            </form>
                                </div>
                            </div>
                            <!-- End .product-details -->
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- End .row -->

                <nav style="justify-content:flex-end;" class="toolbox toolbox-pagination ">                    
                    <!-- End .toolbox-item -->

                    <ul class="pagination toolbox-item">
                       {{$filter_price->linkS()}}
                    </ul>
                </nav>
            </div>
            <!-- End .col-lg-9 -->

            <div class="sidebar-overlay"></div>
            <aside class="sidebar-shop col-lg-3 order-lg-first mobile-sidebar">
                <div class="sidebar-wrapper">
                    <div class="widget">
                        <h3 class="widget-title">
                            <a data-toggle="collapse" href="#widget-body-2" role="button" aria-expanded="true" aria-controls="widget-body-2">Danh mục</a>
                        </h3>

                        <div class="collapse show" id="widget-body-2">
                            <div class="widget-body">
                                <ul class="cat-list">
                                    @foreach ($category as $key => $category1)
                                    <li>
                                        <a href="{{URL::to('/danh-muc/'.$category1-> category_slug)}}"  role="button" aria-expanded="true" aria-controls="widget-category-1">
                                            {{($category1 -> category_name)}}
                                            {{-- <span class="products-count">(3)</span> --}}
                                            {{-- <span class="toggle"></span> --}}
                                        </a>
                                        {{-- <div class="collapse show" id="widget-category-1">
                                            <ul class="cat-sublist">
                                                <li>Caps<span class="products-count">(1)</span></li>
                                                <li>Watches<span class="products-count">(2)</span></li>
                                            </ul>
                                        </div> --}}
                                    </li>
                                    @endforeach
                                    
                                </ul>
                            </div>
                            <!-- End .widget-body -->
                        </div>
                        <!-- End .collapse -->
                    </div>
                    <div class="widget">
                        <h3 class="widget-title">
                            <a data-toggle="collapse" href="#widget-body-3" role="button" aria-expanded="true" aria-controls="widget-body-2">Thương hiệu</a>
                        </h3>

                        <div class="collapse show" id="widget-body-3">
                            <div class="widget-body">
                                <ul class="cat-list">
                                    @foreach ($brand as $key => $brand1)
                                    <li>
                                        <a href="{{URL::to('/thuong-hieu/'.$brand1-> brand_slug)}}"  role="button" aria-expanded="true" aria-controls="widget-category-1">
                                            {{($brand1 -> brand_name)}}
                                            {{-- <span class="products-count">(3)</span> --}}
                                            {{-- <span class="toggle"></span> --}}
                                        </a>
                                        {{-- <div class="collapse show" id="widget-category-1">
                                            <ul class="cat-sublist">
                                                <li>Caps<span class="products-count">(1)</span></li>
                                                <li>Watches<span class="products-count">(2)</span></li>
                                            </ul>
                                        </div> --}}
                                    </li>
                                    @endforeach
                                    
                                </ul>
                            </div>
                            <!-- End .widget-body -->
                        </div>
                        <!-- End .collapse -->
                    </div>
                    <div class="widget">
                        <h3 class="widget-title">
                            <a data-toggle="collapse" href="#widget-body-4" role="button" aria-expanded="true" aria-controls="widget-body-3">Lọc theo giá</a>
                        </h3>

                        <div class="collapse show" id="widget-body-4">
                            <div class="widget-body pb-0">
                                <form id="filter_priceForm" autocomplete="off" method="get" action="" onsubmit="setActionURL(event)">
                                    <div class="price-slider-wrapper">
                                        <div id="slider-range"></div>
                                        <!-- End #price-slider -->
                                    </div>
                                    <!-- End .price-slider-wrapper -->
                                    <input type="hidden" name="start_price" id="start_price" >
                                    <input type="hidden" name="end_price" id="end_price" >
                                    <div class="filter-price-action d-flex align-items-center justify-content-between flex-wrap">
                                        <div class="filter-price-text" style="width:100%;">
                                            Giá:<br>
                                            <input type="text" id="amount" style="border:0; color:#f61f1f; font-weight:bold;width:100%;">
                                            

                                        </div>
                                        <!-- End .filter-price-text -->

                                        
                                    </div>
                                    <button type="submit"  class="btn btn-primary float-right">Lọc</button>
                                    <!-- End .filter-price-action -->
                                </form>
                            </div>
                            <!-- End .widget-body -->
                        </div>
                        <!-- End .collapse -->
                    </div>
                    

                    <!-- End .widget -->

                    <div class="widget widget-featured">
                        <h3 class="widget-title">Featured</h3>

                        <div class="widget-body">
                            <div class="owl-carousel widget-featured-products">
                                <div class="featured-col">
                                    <div class="product-default left-details product-widget">
                                        <figure>
                                            <a href="product.html">
                                                <img src="assets/images/products/small/product-4.jpg" width="75" height="75" alt="product" />
                                                <img src="assets/images/products/small/product-4-2.jpg" width="75" height="75" alt="product" />
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-title"> <a href="product.html">Blue Backpack for
                                                    the Young - S</a> </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="product-price">$49.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                    <div class="product-default left-details product-widget">
                                        <figure>
                                            <a href="product.html">
                                                <img src="assets/images/products/small/product-5.jpg" width="75" height="75" alt="product" />
                                                <img src="assets/images/products/small/product-5-2.jpg" width="75" height="75" alt="product" />
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-title"> <a href="product.html">Casual Spring Blue
                                                    Shoes</a> </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="product-price">$49.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                    <div class="product-default left-details product-widget">
                                        <figure>
                                            <a href="product.html">
                                                <img src="assets/images/products/small/product-6.jpg" width="75" height="75" alt="product" />
                                                <img src="assets/images/products/small/product-6-2.jpg" width="75" height="75" alt="product" />
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-title"> <a href="product.html">Men Black Gentle
                                                    Belt</a> </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="product-price">$49.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                </div>
                                <!-- End .featured-col -->

                                <div class="featured-col">
                                    <div class="product-default left-details product-widget">
                                        <figure>
                                            <a href="product.html">
                                                <img src="assets/images/products/small/product-1.jpg" width="75" height="75" alt="product" />
                                                <img src="assets/images/products/small/product-1-2.jpg" width="75" height="75" alt="product" />
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-title"> <a href="product.html">Ultimate 3D
                                                    Bluetooth Speaker</a> </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="product-price">$49.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                    <div class="product-default left-details product-widget">
                                        <figure>
                                            <a href="product.html">
                                                <img src="assets/images/products/small/product-2.jpg" width="75" height="75" alt="product" />
                                                <img src="assets/images/products/small/product-2-2.jpg" width="75" height="75" alt="product" />
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-title"> <a href="product.html">Brown Women Casual
                                                    HandBag</a> </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="product-price">$49.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                    <div class="product-default left-details product-widget">
                                        <figure>
                                            <a href="product.html">
                                                <img src="assets/images/products/small/product-3.jpg" width="75" height="75" alt="product" />
                                                <img src="assets/images/products/small/product-3-2.jpg" width="75" height="75" alt="product" />
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-title"> <a href="product.html">Circled Ultimate
                                                    3D Speaker</a> </h3>
                                            <div class="ratings-container">
                                                <div class="product-ratings">
                                                    <span class="ratings" style="width:100%"></span>
                                                    <!-- End .ratings -->
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <!-- End .product-ratings -->
                                            </div>
                                            <!-- End .product-container -->
                                            <div class="price-box">
                                                <span class="product-price">$49.00</span>
                                            </div>
                                            <!-- End .price-box -->
                                        </div>
                                        <!-- End .product-details -->
                                    </div>
                                </div>
                                <!-- End .featured-col -->
                            </div>
                            <!-- End .widget-featured-slider -->
                        </div>
                        <!-- End .widget-body -->
                    </div>
                    <!-- End .widget -->

                    <div class="widget widget-block">
                        <h3 class="widget-title">Custom HTML Block</h3>
                        <h5>This is a custom sub-title.</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras non placerat mi. Etiam non tellus </p>
                    </div>
                    <!-- End .widget -->
                </div>
                <!-- End .sidebar-wrapper -->
            </aside>
            <!-- End .col-lg-3 -->
        </div>
        <!-- End .row -->
    </div>
    <!-- End .container -->

    <div class="mb-4"></div>
    <!-- margin -->
</main>

@endsection