@php



@endphp


<table class="w-full text-left">
    <tbody class="w-full">
    @foreach($getRecord()->products as $orderProduct)
        <tr class="w-full ">
            <td  name="product" >
                <div class="flex items-center gap-x-2 ">
                    <img class="object-cover w-8 h-8" src="{{$orderProduct->image}}" alt="">
                    <div>
                        <h2 class="text-sm font-medium text-gray-800 dark:text-white ">{{$orderProduct->title}}</h2>
                        <p class="text-xs font-normal text-gray-700 dark:text-gray-400">{{$orderProduct->sku_name}}</p>
                        <p class="text-xs font-normal text-gray-700 dark:text-gray-400">
                            <span>{{__('red-jasmine-order::order.fields.product.product_id')}}:</span><span>{{$orderProduct->product_id}}</span>
                            <span>{{__('red-jasmine-order::order.fields.product.sku_id')}}:</span><span>{{$orderProduct->product_id}}</span>
                        </p>
                    </div>
                </div>
            </td>
            <td   name="price" >
                <div class="flex items-center gap-x-6">
                    <p class="text-sm font-medium text-gray-800 dark:text-white ">￥ {{$orderProduct->price}}</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-white "> {{$orderProduct->num}}</p>
                </div>
            </td>
            <td   name="alter_sale_service" >
                <span> {{$orderProduct->refund_status?->getLable()}} </span>
            </td>

        </tr>
    @endforeach
    </tbody>

</table>


