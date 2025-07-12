<?php

return [
    'labels' => [
        'user_coupon' => 'User Coupon',
        'my_coupons' => 'My Coupons',
        'coupon_wallet' => 'Coupon Wallet',
    ],
    
    'fields' => [
        'id' => 'ID',
        'coupon_id' => 'Coupon ID',
        'coupon_name' => 'Coupon Name',
        'coupon_no' => 'Coupon Number',
        'user_id' => 'User ID',
        'user_name' => 'User Name',
        'user_type' => 'User Type',
        'owner_type' => 'Owner Type',
        'owner_id' => 'Owner ID',
        'status' => 'Status',
        'issue_time' => 'Issue Time',
        'expire_time' => 'Expire Time',
        'used_time' => 'Used Time',
        'order_id' => 'Order ID',
        'order_no' => 'Order Number',
        'remaining_days' => 'Remaining Days',
        'remaining_hours' => 'Remaining Hours',
        'display_name' => 'Display Name',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],
    
    'enums' => [
        'status' => [
            'available' => 'Available',
            'used' => 'Used',
            'expired' => 'Expired',
        ],
    ],
    
    'commands' => [
        'receive' => 'Receive',
        'use' => 'Use',
        'view_detail' => 'View Detail',
        'batch_issue' => 'Batch Issue',
        'export' => 'Export',
        'expire' => 'Expire',
    ],
    
    'filters' => [
        'status' => 'Status',
        'user_id' => 'User ID',
        'coupon_id' => 'Coupon ID',
        'issue_time' => 'Issue Time',
        'expire_time' => 'Expire Time',
        'used_time' => 'Used Time',
        'owner_type' => 'Owner Type',
        'owner_id' => 'Owner ID',
    ],
    
    'tabs' => [
        'all' => 'All',
        'available' => 'Available',
        'used' => 'Used',
        'expired' => 'Expired',
        'expiring_soon' => 'Expiring Soon',
    ],
    
    'messages' => [
        'issued' => 'Coupon issued successfully',
        'received' => 'Coupon received successfully',
        'used' => 'Coupon used successfully',
        'expired' => 'User coupon has expired',
        'batch_issued' => 'Coupons issued in batch successfully',
        'not_available' => 'User coupon not available',
        'not_found' => 'User coupon not found',
        'already_used' => 'Coupon already used',
        'already_expired' => 'Coupon already expired',
        'remaining_time' => ':days days :hours hours remaining',
        'expiring_soon' => 'Coupon expiring soon',
        'operation_success' => 'Operation successful',
        'operation_failed' => 'Operation failed',
        'permission_denied' => 'Permission denied',
        'validation_failed' => 'Validation failed',
    ],
    
    'descriptions' => [
        'user_coupon' => 'User-owned coupons with issue time, expiration time, usage status and other information',
        'available_status' => 'Available coupons that users can use in orders',
        'used_status' => 'Used coupons that have been applied in orders',
        'expired_status' => 'Expired coupons that are past their validity period',
        'remaining_days' => 'Number of days remaining for the coupon validity',
        'remaining_hours' => 'Number of hours remaining for the coupon validity',
        'display_name' => 'Display name of the coupon including coupon name and status',
    ],
    
    'validations' => [
        'coupon_id_required' => 'Coupon ID is required',
        'user_id_required' => 'User ID is required',
        'issue_time_required' => 'Issue time is required',
        'expire_time_required' => 'Expire time is required',
        'expire_time_after_issue' => 'Expire time must be after issue time',
        'status_invalid' => 'Invalid status value',
        'coupon_not_exists' => 'Coupon does not exist',
        'user_not_exists' => 'User does not exist',
        'already_issued' => 'Coupon already issued to this user',
        'issue_limit_exceeded' => 'Issue limit exceeded',
    ],
]; 