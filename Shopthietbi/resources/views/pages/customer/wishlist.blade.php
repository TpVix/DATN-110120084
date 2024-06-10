
@extends('layout')
@section('content')
<main class="main">
    <div class="page-header">
        <div class="container d-flex flex-column align-items-center">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="demo4.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Wishlist
                        </li>
                    </ol>
                </div>
            </nav>

            <h1>Danh sách mong đợi</h1>
        </div>
    </div>

    <div class="container">
        <div class="wishlist-table-container">
            <table class="table table-wishlist mb-0">
                <thead>
                    <tr>
                        <th class="thumbnail-col"></th>
                        <th class="product-col">Tên sản phẩm</th>
                        <th class="price-col">Giá</th>
                        <th class="status-col">Trạng thái</th>
                        <th class="status-col">Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product_wishlist as $key =>$product_wishlist )
                    <tr class="product-row">
                        <td>
                            <figure class="product-image-container">
                                <a href="{{URL::to('/san-pham/'.$product_wishlist-> product_slug)}}" class="product-image">
                                    <img src="{{asset('public/upload/'.$product_wishlist-> product_image)}}" alt="product">
                                </a>

                                <a href="{{URL::to('/delete-wishlist/'.$product_wishlist-> product_id)}}" class="btn-remove icon-cancel" title="Remove Product"></a>
                            </figure>
                        </td>
                        <td>
                            <h5 class="product-title">
                                <a href="{{URL::to('/san-pham/'.$product_wishlist-> product_slug)}}">{{($product_wishlist -> product_name)}}</a>
                            </h5>
                        </td>
                        <td class="price-box">
                            @if ($product_wishlist-> product_status == '1') 
                                <del>{{number_format($product_wishlist-> product_price).' '.'VNĐ'}}</del>
                                <P>{{number_format($product_wishlist-> product_sale_price).' '.'VNĐ'}}</P>
                            @else
                                <p>{{number_format($product_wishlist-> product_price).' '.'VNĐ'}}</p>
                            @endif
                        </td>
                        
                        <td>
                            <span class="stock-status">
                            @if ($product_wishlist -> product_quantity=="0")
                                Hết hàng
                            @else
                                Còn hàng
                            @endif    
                            
                            
                            </span>
                        </td>
                        <td>
                            <span class="stock-status">
                        
                                Còn lại: {{$product_wishlist -> product_quantity}}
                            
                            
                            
                            </span>
                        </td>
                    </tr>
                    @endforeach
                   
                </tbody>
            </table>
        </div><!-- End .cart-table-container -->
    </div><!-- End .container -->
</main>
@endsection