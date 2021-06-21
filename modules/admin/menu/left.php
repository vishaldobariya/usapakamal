<?php

/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */
if(Yii::$app->user->identity->role == 'admin' || Yii::$app->user->identity->role == 'developer') {
	return [
		[
			'label' => 'Dashboard',
			'icon'  => 'mdi mdi-view-dashboard',
			'url'   => ['/admin/dashboard/index'],
		],
		[
			'label' => 'Brands',
			'icon'  => 'mdi mdi-diamond',
			'url'   => ['/shop/brand/index'],
		],
		[
			'label' => 'Categories',
			'icon'  => 'mdi mdi-marker-check',
			'url'   => ['/shop/category/index'],
		],
		[
			'label' => 'Products',
			'icon'  => 'mdi mdi-martini',
			'url'   => ['/shop/product/index'],
		],
		//[
		//	'label'   => 'Stores',
		//	'icon'    => 'mdi mdi-diamond',
		//
		//	'url'     => ['/shop/store/index'],
		//],
		[
			'label' => 'Providers',
			'icon'  => 'mdi mdi-star',
			'url'   => ['/provider/store/index'],
		],
		[
			'label' => 'Customers',
			'icon'  => 'mdi mdi-puzzle',
			'url'   => ['/shop/customer/index'],
		],
		[
			'label' => 'Orders',
			'icon'  => 'mdi mdi-flash',
			'url'   => ['/shop/order/index'],
			'sub'   => [
				[
					'label' => 'All Orders',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/index'],
				],
				[
					'label' => 'New Paid',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 0],
				],
				[
					'label' => 'Accepted',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 1],
				],
				[
					'label' => 'Shipped',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 2],
				],
				[
					'label' => 'Delivered',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 3],
				],
				[
					'label' => 'Problematic',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 4],
				],
				[
					'label' => 'Refused',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 5],
				],
				[
					'label' => 'Not Paid',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 6],
				],
				[
					'label' => 'Done',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/by-status', 'OrderSearch[status][]' => 7],
				],
			],
		],
		[
			'label' => 'Coupons',
			'icon'  => 'mdi mdi-cash',
			'url'   => ['/shop/coupon/index'],
		],
		[
			'label' => 'Subscribe',
			'icon'  => 'mdi mdi-script',
			'url'   => ['/subscribe/subscribe/index'],
		],
		[
			'label' => 'Contacts',
			'icon'  => 'mdi mdi-contacts',
			'url'   => ['/shop/contact/index'],
		],
		['section' => 'System'],
		[
			'label' => 'Header Slider',
			'icon'  => 'mdi mdi-file-outline',
			'url'   => ['/settings/banner/slider'],
		],
		[
			'label' => 'Mobile Slider',
			'icon'  => 'mdi mdi-file-outline',
			'url'   => ['/settings/banner/mobile-slider'],
		],
		[
			'label' => 'Middle Banner',
			'icon'  => 'mdi mdi-file-outline',
			'url'   => ['/settings/banner/middle-images'],
		],
		[
			'label' => 'Footer Banner',
			'icon'  => 'mdi mdi-file-outline',
			'url'   => ['/settings/banner/footer-images'],
		],
		[
			'label' => 'Users',
			'icon'  => 'mdi mdi-account-multiple',
			'url'   => ['/user/default/index'],
		],
		[
			'label' => 'Settings',
			'icon'  => 'fa fa-cogs',
			'url'   => ['/settings/view/index'],
		],
		[
			'label' => 'Zip',
			'icon'  => 'fa fa-cogs',
			'url'   => ['/settings/zip/index'],
		],
		[
			'label' => 'Shipping Rates',
			'icon'  => 'fa fa-cogs',
			'url'   => ['/settings/view/ship-rate'],
		],
		[
			'label'   => 'Logs',
			'icon'    => 'fa fa-file',
			'visible' => Yii::$app->user->can('developer'),
			'url'     => ['/system/log/index'],
		],
	];
} elseif(Yii::$app->user->identity->role == 'distributor') {
	return [
		[
			'label' => 'Profile',
			'icon'  => 'mdi mdi-diamond',
			'url'   => ['/admin/dashboard/profile'],
		],
		[
			'label' => 'My Products',
			'icon'  => 'mdi mdi-diamond',
			'url'   => ['/shop/store/my-products'],
		],
		[
			'label' => 'Orders',
			'icon'  => 'mdi mdi-flash',
			'url'   => ['/shop/order/provider-index'],
			'sub'   => [
				[
					'label' => 'All Orders',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index'],
				],
				[
					'label' => 'New Paid',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index', 'OrderSearch[status][]' => 0],
				],
				[
					'label' => 'Accepted',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index', 'OrderSearch[status][]' => 1],
				],
				[
					'label' => 'Shipped',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index', 'OrderSearch[status][]' => 2],
				],
				[
					'label' => 'Delivered',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index', 'OrderSearch[status][]' => 3],
				],
				[
					'label' => 'Problematic',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index', 'OrderSearch[status][]' => 4],
				],
				[
					'label' => 'Not Paid',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index', 'OrderSearch[status][]' => 6],
				],
				[
					'label' => 'Done',
					'icon'  => 'mdi mdi-diamond',
					'url'   => ['/shop/order/provider-index', 'OrderSearch[status][]' => 7],
				],
			],
		],
		[
			'label' => 'Shipstation',
			'icon'  => 'mdi mdi-diamond',
			
			'url' => ['/shipstation/shipstation/index'],
		],
		[
			'label' => 'States',
			'icon'  => 'mdi mdi-diamond',
			'url'   => ['/settings/state/index'],
		],
		[
			'label'   => 'Go to Admin Dashboard',
			'icon'    => 'mdi mdi-diamond',
			'visible' => Yii::$app->session->has('role'),
			'url'     => ['/user/default/switch-admin'],
		],
	];
} else {
	return [
		[
			'label' => 'Back to Website',
			'icon'  => 'mdi mdi-diamond',
			'url'   => ['/site/index'],
		],
		[
			'label' => 'Special Offers',
			'icon'  => 'mdi mdi-diamond',
			'url'   => ['/admin/user-back/special-offers'],
		],
		[
			'label' => 'History Orders',
			'icon'  => 'mdi mdi-diamond',
			
			'url' => ['/shop/order/user-index'],
		],
		[
			'label' => 'Saved Orders',
			'icon'  => 'mdi mdi-diamond',
			
			'url' => ['/shop/order/user-saved-index'],
		],
		[
			'label' => 'Profile',
			'icon'  => 'mdi mdi-diamond',
			'url'   => ['/admin/dashboard/user-profile'],
		],
		//[
		//	'label' => 'Contact Us',
		//	'icon'  => 'mdi mdi-diamond',
		//	'url'   => ['/admin/user-back/contact'],
		//],
		//[
		//	'label' => 'Support',
		//	'icon'  => 'mdi mdi-diamond',
		//	'url'   => ['/admin/user-back/support'],
		//],
		[
			'label'   => 'Go to Admin Dashboard',
			'icon'    => 'mdi mdi-diamond',
			'visible' => Yii::$app->session->has('role'),
			'url'     => ['/user/default/switch-admin'],
		],
	
	];
}
