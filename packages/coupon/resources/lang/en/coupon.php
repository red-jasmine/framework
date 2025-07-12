<?php

return [

    
    'label' => [
        'threshold' => [
            'amount_over' => 'Over $:amount',
            'quantity_over' => 'Over :quantity items',
        ],
        'discount' => [
            'fixed_amount' => 'Save $:amount',
            'fixed_amount_yuan' => 'Save $:amount',
            'percentage' => ':rate% off',
        ],
    ],
    

    

    

    

    
    'enums'    => [
        'status'             => [
            'draft'     => 'Draft',
            'published' => 'Published',
            'paused'    => 'Paused',
            'expired'   => 'Expired',
        ],
        'discount_amount_type'      => [
            'fixed_amount' => 'Fixed Amount',
            'percentage'   => 'Percentage',
        ],
        'threshold_type'     => [
            'amount' => 'Amount',
            'quantity' => 'Quantity',
        ],
        'discount_target'     => [
            'order_amount'       => 'Order Amount',
            'product_amount'     => 'Product Amount',
            'shipping_amount'    => 'Shipping Amount',
            'cross_store_amount' => 'Cross Store Amount',
        ],
        'validity_type'      => [
            'absolute' => 'Absolute Time',
            'relative' => 'Relative Time',
        ],
        'issue_strategy'     => [
            'manual' => 'Manual Issue',
            'auto'   => 'Auto Issue',
            'code'   => 'Redemption Code',
        ],
        'cost_bearer_type'   => [
            'platform'    => 'Platform',
            'merchant'    => 'Merchant',
            'broadcaster' => 'Broadcaster',
        ],
        'user_coupon_status' => [
            'available' => 'Available',
            'used'      => 'Used',
            'expired'   => 'Expired',
        ],
    ],
    
    'messages' => [
        'created'         => 'Coupon created successfully',
        'updated'         => 'Coupon updated successfully',
        'deleted'         => 'Coupon deleted successfully',
        'published'       => 'Coupon published successfully',
        'paused'          => 'Coupon paused successfully',
        'issued'          => 'Coupon issued successfully',
        'received'        => 'Coupon received successfully',
        'used'            => 'Coupon used successfully',
        'expired'         => 'Coupon has expired',
        'not_found'       => 'Coupon not found',
        'not_available'   => 'Coupon not available',
        'already_used'    => 'Coupon already used',
        'already_expired' => 'Coupon already expired',
        'limit_reached'   => 'Coupon limit reached',
        'invalid_code'    => 'Invalid redemption code',
    ],
]; 