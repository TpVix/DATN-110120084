
@extends('pages.layout.layout')
@section('content')
<div  id="order" role="tabpanel">
    <div class="order-content">
        <h3 class="account-sub-title d-none d-md-block"><i
                class="sicon-social-dropbox align-middle mr-3"></i>Đơn hàng</h3>
        <div class="order-table-container text-center">
            <table class="table table-order text-left">
                <thead>
                    <tr>
                        <th class="order-id">Đơn hàng</th>
                        <th style="width: 15%;" class="order-date">Ngày đặt</th>
                        <th style="width: 15%;" class="order-status">Trạng thái</th>
                        <th class="order-price">Giá tiền</th>
                        <th class="order-action"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history_order as $key =>$history_order )
                    <?php
                    $message = Session::get('message');
                    if ($message) {
                        echo "<div class='alert alert-success'>$message</div>";
                        for ($i=1; $i <3 ; $i++) { 
                            Session::put('message', null);
                        }
                        
                    }
                    ?>
                    <tr>
                        <td class=" p-0" >
                            <p class="mb-3 mt-3">
                                {{($history_order -> order_name)}}
                            </p>
                        </td>
                        <td class=" p-0" >
                            <p class="mb-3 mt-3">
                                {{($history_order -> created_at)}}
                            </p>
                        </td>
                        <td class=" p-0" >
                            <p class="mb-3 mt-3">
                                {{($history_order -> order_status)}}
                            </p>
                        </td>
                        <td class=" p-0" >
                            <p class="mb-3 mt-3">
                                @if(is_numeric($history_order->order_total))
                                    {{ number_format($history_order->order_total, 0, ',', '.') }}đ
                                @else
                                    {{ $history_order->order_total }} <!-- Hoặc xử lý khác nếu cần -->
                                @endif
                            </p>
                        </td>
                        <td class=" p-0" >
                            <a class="btn btn-primary" href="{{URL::to('/order-detail/' . $history_order -> order_id)}}">Chi tiết</a>
                            @if ($history_order -> order_status=='Đã huỷ' || $history_order -> order_status=='Đã nhận hàng' ||$history_order -> order_status=='Đặt thành công, Đang giao hàng')
                                
                            @else
                            <a onclick="return confirm('Bạn có chắc muốn huỷ đơn hàng ?')" class="btn btn-danger" href="{{URL::to('/cancel-order/'.$history_order -> order_id)}}"><i class="fa fa-times"></i>Huỷ đơn</a>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <hr class="mt-0 mb-3 pb-2" />

            <a href="{{url('/')}}" class="btn btn-dark">Mua sắm</a>
        </div>
    </div>
</div>
@endsection