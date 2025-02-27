@extends('layouts.admin')
@section('content')
<style>
    .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;
    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Order details</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Details</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Back</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Order No</th>
                        <td>{{$order->id}}</td>
                        <th>Mobile</th>
                        <td>{{$order->phone}}</td>
                        <th>ZIP code</th>
                        <td>{{$order->zip}}</td>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <td>{{$order->created_at}}</td>
                        <th>Delivery date</th>
                        <td>{{$order->delivered_date}}</td>
                        <th>Cancel date </th>
                        <td>{{$order->canceled_date}}</td>
                    </tr>
                    <tr>
                        <th>Order status</th>
                        <td colspan="2">
                            @if($order->status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($order->status == 'canceled')
                                <span class="badge bg-danger">Canceled</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Items</h5>
                </div>
                <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Back</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Options</th>
                            <th class="text-center">Return Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $item)
                            <tr>
                                <td class="pname">
                                    <div class="image">
                                        <img src="{{asset('uploads/products/thumbnails')}}/{{$item->product->image}}" alt="{{$item->product->name}}" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="{{route('shop.product.details', ['product_slug' => $item->product->slug])}}" target="_blank"
                                            class="body-title-2">{{$item->product->name}}</a>
                                    </div>
                                </td>
                                <td class="text-center">${{$item->price}}</td>
                                <td class="text-center">{{$item->quantity}}</td>
                                <td class="text-center">{{$item->product->SKU}}</td>
                                <td class="text-center">{{$item->product->category->name}}</td>
                                <td class="text-center">{{$item->product->brand->name}}</td>
                                <td class="text-center">{{$item->options}}</td>
                                <td class="text-center">{{$item->rstatus == 0 ? 'No' : 'Yes' }}</td>
                                <td class="text-center">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$orderItems->links('pagination::bootstrap-5')}}
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Shipping Address</h5>
            <div class="my-account__address-item col-md-6">
                <div class="my-account__address-item__detail">
                    <p>{{$order->name}}</p>
                    <p>{{$order->address}}</p>
                    <p>{{$order->locality}}</p>
                    <p>{{$order->city}}, {{$order->country}}</p>
                    <p>{{$order->landmark}}</p>
                    <p>{{$order->zip}}</p>
                    <br>
                    <p>Mobile : {{$order->phone}}</p>
                </div>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Transactions</h5>
            <table class="table table-striped table-bordered table-transaction">
                <tbody>
                    <tr>
                        <th>Subtotal</th>
                        <td>${{$order->subtotal}}</td>
                        <th>Tax</th>
                        <td>${{$order->taX}}</td>
                        <th>Discount</th>
                        <td>${{$order->discount}}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>${{$order->total}}</td>
                        <th>Payment Mode</th>
                        <td>{{$transaction->mode}}</td>
                        <th>Status</th>
                        <td>
                            @if($transaction->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($transaction->status == 'declined')
                                <span class="badge bg-danger">Declined</span>
                            @elseif($transaction->status == 'refunded')
                                <span class="badge bg-secondary">Refunded</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection