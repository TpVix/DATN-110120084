@extends('admin_layout')
@section('main_admin')

<div class="content">
  <div class="breadcrumb-wrapper breadcrumb-wrapper-2">
    <h1>Chi tiết đơn hàng</h1>
   
  </div>
  <div class="row">
    <div class="col-12">
      <div class="ec-odr-dtl card card-default">
        <div class="card-header card-header-border-bottom d-flex justify-content-between">
          <h2 class="ec-odr">Chi tiết đơn hàng: {{ $order_detail -> order_name}}<br>
            <span class="small">ID đơn hàng: {{ $order_detail -> order_id}}</span>
          </h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-xl-3 col-lg-6">
              <address class="info-grid">
                <div class="info-title"><strong>Khách hàng</strong></div><br>
                <div class="info-content">
                  Tài khoản: {{ $order_detail -> customer_name}}<br>
                  Tên người đặt: {{ $order_detail -> shipping_name}}<br>
                  
                  
                </div>
              </address>
            </div>
            <div class="col-xl-3 col-lg-6">
              <address class="info-grid">
                <div class="info-title"><strong>Địa chỉ</strong></div><br>
                <div class="info-content">
                  Địa chỉ: {{ $order_detail -> shipping_address}}<br>
                  Số điện thoại: {{ $order_detail -> shipping_phone}}<br>
                 
                </div>
              </address>
            </div>
            <div class="col-xl-3 col-lg-6">
              <address class="info-grid">
                <div class="info-title"><strong>Phương thức thanh toán</strong></div><br>
                <div class="info-content">
                  Phương thức: {{ $order_detail -> payment_method}}<br>
                  Trạng thái: {{ $order_detail -> payment_status}}<br>
                  @if ($order_detail -> payment_status!=0)
                  Tiền thanh toán: {{number_format ($order_detail -> payment_status,0,',','.')}}
                  @endif
                </div>
              </address>
            </div>
            <div class="col-xl-3 col-lg-6">
              <address class="info-grid">
                <div class="info-title"><strong>Thông tin khác</strong></div><br>
                @php
                    use Carbon\Carbon;
                @endphp
                <div class="info-content">
                  Ghi chú: {{ $order_detail -> shipping_note}}<br>
                  Ngày đặt hàng: {{ $order_detail->created_at ? Carbon::parse($order_detail->created_at)->format('d/m/Y') : 'N/A' }}
                </div>
              </address>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3 class="tbl-title">Thông tin sản phẩm</h3>
              <div class="table-responsive">
                <table class="table table-striped o-tbl">
                  <thead>
                    <tr class="line">
                      <th>Hình ảnh</th>
                      <th>Tên sản phẩm</th>
                      <th>Số lượng</th>
                      <th>Giá</th>
                      <th>Tổng phụ</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $total=0;
                    @endphp
                    @foreach ($order_product_detail as $key=> $order_product_detail)
                    @php
                    $subtotal = $order_product_detail-> product_price*$order_product_detail-> product_quantity;
                    $total += $subtotal;
                    @endphp
                    <tr>
                     
                      <td><img style="width:100px;height:100px;" class="product-img"
                          src="{{URL::to('public/upload/'.$order_product_detail -> product_image)}}" alt="" /></td>
                      <td><strong>{{ $order_product_detail -> product_name}}</strong></td>
                      <td >{{ $order_product_detail -> product_quantity}}</td>
                      <td >
                        {{number_format($order_product_detail-> product_price,0,',','.')}}đ
                      
                      </td>
                      <td >
                        {{number_format($subtotal,0,',','.')}}đ
                      
                      </td>
                      
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="3"></td>
                      <td class="text-right"><strong>Tổng tiền</strong></td>
                      <td class="text-right"><strong> {{number_format($total,0,',','.')}}đ</strong></td>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td class="text-right"><strong>Tiền ship</strong></td>
                      <td class="text-right"><strong> {{number_format($order_detail-> shipping_fee,0,',','.')}}đ</strong></td>
                    </tr>
                    <tr>
                      <td colspan="3">
                      </td>
                      <td class="text-right"><strong>Tổng thanh toán</strong></td>
                      <td class="text-right"><strong> {{number_format($order_detail-> order_total,0,',','.')}}đ</strong></td>
                    </tr>

                    <tr>
                      <td colspan="3">
                      </td>
                      <td class="text-right"><strong>Trạng thái</strong></td>
                      <td class="text-right"><strong>{{ $order_detail -> order_status}}</strong></td>
                    </tr>
                    
                  </tbody>
                </table>
                <div class="text-right">
                  @if ($order_detail -> order_status=='Đã huỷ' || $order_detail -> order_status=='Đã giao hàng' ||$order_detail -> order_status=='Đặt thành công, Đang giao hàng')
                                
                  @else
                  <a href="{{url('/acept-order/'.$order_detail->order_id)}}" class="btn btn-outline-success" ui-toggle-class="">
                    Xác nhận đơn hàng <i class="fa fa-pencil-square-o text-success text-active"></i></a>
                  @endif
                  <a target="_blank" href="{{url('/print-order/'.$order_detail->order_id)}}" class="btn btn-outline-success" ui-toggle-class="">
                    In đơn hàng <i class="fa fa-pencil-square-o text-success text-active"></i> </a> 
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Tracking Detail -->
      <div class="card mt-4 trk-order">
        <div class="p-4 text-center text-white text-lg bg-dark rounded-top">
          <span class="text-uppercase">Tracking Order No - </span>
          <span class="text-medium">34VB5540K83</span>
        </div>
        <div
          class="d-flex flex-wrap flex-sm-nowrap justify-content-between py-3 px-2 bg-secondary">
          <div class="w-100 text-center py-1 px-2"><span class="text-medium">Shipped
              Via:</span> UPS Ground</div>
          <div class="w-100 text-center py-1 px-2"><span class="text-medium">Status:</span>
            Checking Quality</div>
          <div class="w-100 text-center py-1 px-2"><span class="text-medium">Expected
              Date:</span> DEC 09, 2021</div>
        </div>
        <div class="card-body">
          <div
            class="steps d-flex flex-wrap flex-sm-nowrap justify-content-between padding-top-2x padding-bottom-1x">
            <div class="step completed">
              <div class="step-icon-wrap">
                <div class="step-icon"><i class="mdi mdi-cart"></i></div>
              </div>
              <h4 class="step-title">Confirmed Order</h4>
            </div>
            <div class="step completed">
              <div class="step-icon-wrap">
                <div class="step-icon"><i class="mdi mdi-tumblr-reblog"></i></div>
              </div>
              <h4 class="step-title">Processing Order</h4>
            </div>
            <div class="step completed">
              <div class="step-icon-wrap">
                <div class="step-icon"><i class="mdi mdi-gift"></i></div>
              </div>
              <h4 class="step-title">Product Dispatched</h4>
            </div>
            <div class="step">
              <div class="step-icon-wrap">
                <div class="step-icon"><i class="mdi mdi-truck-delivery"></i></div>
              </div>
              <h4 class="step-title">On Delivery</h4>
            </div>
            <div class="step">
              <div class="step-icon-wrap">
                <div class="step-icon"><i class="mdi mdi-hail"></i></div>
              </div>
              <h4 class="step-title">Product Delivered</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection