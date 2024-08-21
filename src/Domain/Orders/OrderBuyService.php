<?php

namespace RedJasmine\Shopping\Domain\Orders;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Order\Application\Services\OrderCommandService;
use RedJasmine\Order\Application\UserCases\Commands\Data\OrderProductData;
use RedJasmine\Order\Application\UserCases\Commands\OrderCreateCommand;
use RedJasmine\Order\Domain\Models\Enums\OrderTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PayTypeEnum;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Shopping\Domain\Data\OrdersData;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderCreateHook;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderToOrderDomainCommandHook;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderToOrderDomainCreateHook;
use RedJasmine\Support\Exceptions\AbstractException;
use RedJasmine\Support\Facades\Hook;
use RedJasmine\Support\Foundation\Service\Service;
use Throwable;

class OrderBuyService extends Service
{

    public function __construct(
        protected StockCommandService $stockCommandService,
        protected OrderCommandService $orderCommandService,

    ) {


    }

    /**
     * @param  OrdersData  $ordersData
     *
     * @return Collection<Order>
     * @throws AbstractException
     * @throws Throwable
     */
    public function buy(OrdersData $ordersData) : \Illuminate\Support\Collection
    {


        $orders = collect([]);
        try {
            DB::beginTransaction();
            foreach ($ordersData->orders as $orderData) {
                $orders[] = ShoppingOrderCreateHook::hook($orderData, fn() => $this->createOrderCore($orderData));
            }
            DB::commit();

        } catch (AbstractException $exception) {
            DB::rollBack();
            Log::info('下单失败:'.$exception->getMessage(), $ordersData->toArray());
            throw  $exception;
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw  $throwable;
        }
        return $orders;

    }

    /**
     * 订单创建核心流程
     *
     * @param  OrderData  $orderData
     *
     * @return Order
     * @throws Throwable
     */
    protected function createOrderCore(OrderData $orderData) : Order
    {
        // 1、下订单
        //  转换 订单领域 OrderCommand
        $orderCommand = ShoppingOrderToOrderDomainCommandHook::hook(
            $orderData,
            fn($orderData) => $this->toOrderCommand($orderData)
        );
        // 调用 订单领域 创建
        $order = ShoppingOrderToOrderDomainCreateHook::hook(
            $orderCommand,
            fn($orderCommand) => $this->orderCommandService->create($orderCommand)
        );
        // 扣减库存
        $this->handleProductStock($orderData);
        return $order;
    }

    protected function toOrderCommand(
        OrderData $orderData
    ) : \RedJasmine\Order\Application\UserCases\Commands\Data\OrderData {

        $order                = new OrderCreateCommand();
        $order->buyer         = $orderData->buyer;
        $order->seller        = $orderData->products->first()->getProduct()->owner;
        $order->contact       = $orderData->contact;
        $order->password      = $orderData->password;
        $order->title         = '';
        $order->outerOrderId  = $orderData->outerOrderId;
        $order->clientIp      = $orderData->clientIp;
        $order->clientType    = $orderData->clientType;
        $order->clientVersion = $orderData->clientVersion;
        $order->orderType     = OrderTypeEnum::SOP;
        $order->payType       = PayTypeEnum::ONLINE;
        // TODO
        $order->channel  = null;
        $order->store    = null;
        $order->address  = null; // TODO
        $order->products = collect();
        foreach ($orderData->products as $productData) {
            // 获取价格
            $additionalData            = $productData->getAdditionalData();
            $price                     = Amount::make($additionalData['price']);
            $product                   = new OrderProductData();
            $product->num              = $productData->num;
            $product->orderProductType = $productData->getProduct()->product_type;
            $product->shippingType     = $productData->getProduct()->shipping_type;
            $product->title            = $productData->getProduct()->title;
            $product->skuName          = $productData->getSku()->properties_name;
            $product->productType      = 'product';
            $product->productId        = $productData->getProduct()->id;
            $product->skuId            = $productData->getSku()->id;
            $product->price            = $price;
            $product->costPrice        = new Amount($productData->getSku()->cost_price);
            $product->categoryId       = $productData->getProduct()->category_id;
            $product->sellerCategoryId = $productData->getProduct()->seller_category_id;
            $product->image            = $productData->getSku()->image ?? $productData->getProduct()->image ?? null;
            $product->outerId          = $productData->getProduct()->outer_id;
            $product->outerSkuId       = $productData->getSku()->outer_id;
            $product->barcode          = $productData->getSku()->barcode ?? $productData->getProduct()->barcode ?? null;
            $product->promiseServices  = $productData->getProduct()->promise_services ?? null;
            $product->buyerMessage     = $productData->buyerMessage ?? null;
            $product->buyerRemarks     = $productData->buyerRemarks ?? null;
            $product->buyerExpands     = $productData->buyerExpands ?? null;
            $product->otherExpands     = null; // TODO
            $product->tools            = $productData->tools ?? null;


            $product->additional([
                'sku'     => $productData->getSku(),
                'product' => $productData->getProduct(),

            ]);
            $order->products->push($product);

        }

        return $order;
    }

    /**
     * @param  OrderData  $orderData
     *
     * @return void
     * @throws Throwable
     */
    protected function handleProductStock(OrderData $orderData) : void
    {
        foreach ($orderData->products as $productData) {
            $stockCommand             = new StockCommand();
            $stockCommand->productId  = $productData->productId;
            $stockCommand->skuId      = $productData->skuId;
            $stockCommand->stock      = $productData->num;
            $stockCommand->changeType = ProductStockChangeTypeEnum::SELLER;
            // 锁定库存
            $this->stockCommandService->sub($stockCommand);
        }
    }

    protected function toOrderCommands(OrdersData $ordersData)
    {
        $orderDTOList = [];
        foreach ($ordersData->orders as $orderData) {

            $orderDTOList[] = $orderDTO = $this->toOrderCommand($orderData);
            $order          = Hook::execute('shopping.order.create', $orderDTO,
                fn() => $this->orderCommandService->create($orderDTO)
            );

            // 扣减库存


            //


        }

    }

}
