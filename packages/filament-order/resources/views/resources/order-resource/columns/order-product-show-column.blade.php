<table class="w-full text-left px-2">
    <tbody class="w-full">
    @foreach($getRecord()->products as $orderProduct)
        @php

            $tooltipContent=  __('red-jasmine-order::order.fields.product.title').':'.$orderProduct->title."<br>";
            $tooltipContent.= __('red-jasmine-order::order.fields.product.sku_name').':'.$orderProduct->sku_name."<br>";
            $tooltipContent.= __('red-jasmine-order::order.fields.product.price').':'.$orderProduct->price."<br>";
            $tooltipContent.= __('red-jasmine-order::order.fields.product.quantity').':'.$orderProduct->quantity."<br>";
            $tooltipContent.= __('red-jasmine-order::order.fields.product.product_id').':'.$orderProduct->product_id."<br>";
            $tooltipContent.= __('red-jasmine-order::order.fields.product.sku_id').':'.$orderProduct->sku_id."<br>";
        @endphp

        <tr class="w-full"
            x-tooltip="{
                allowHTML: true,
                content: '{{$tooltipContent}}',
                theme: $store.theme,
            }"
        >
            <td name="product" class="p2 w-72">
                <div class="flex items-center gap-x-2">
                    <img class="object-cover w-8 h-8" src="{{$orderProduct->image}}" alt="">
                    <div class="w-72">
                        <p class="text-xs font-medium text-gray-800
                        overflow-hidden dark:text-white break-words  line-clamp-1 ">
                            {{$orderProduct->title}}
                        </p>
                        <p class="text-xs font-normal text-gray-700 dark:text-gray-400">
                            {{$orderProduct->sku_name}}
                        </p>
                    </div>
                </div>
            </td>
            <td name="price" class="p2">
                <div class="flex items-center gap-x-6">
                    <p class="text-sm font-medium text-gray-800 dark:text-white ">￥ {{$orderProduct->price}}</p>
                    <p class="text-sm font-medium text-gray-700 dark:text-white "> {{$orderProduct->quantity}}</p>
                </div>
            </td>
            <td name="alter_sale_service" class="p2">

                <span></span>
            </td>

        </tr>
    @endforeach
    </tbody>

</table>


