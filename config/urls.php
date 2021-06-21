<?php

/**
 * @link      http://industrialax.com/
 * @email     xristmas365@gmail.com
 * @author    isteil
 * @copyright Copyright (c) 2020 INDUSTRIALAX SOLUTIONS LLC
 * @license   https://industrialax.com/license
 */
return [
	'/'                         => '/site/index',
	'/contact'                  => '/site/contactus',
	'/delivery'                 => '/site/delivery',
	'/return-policy'            => '/site/policy',
	'/privacy-policy'           => '/site/privacy',
	'/terms-of-service-and-use' => '/site/terms',
	'/dashboard'                => '/admin/dashboard/index',
	'/sign/pass-user/<hash>'    => '/sign/pass-user',
	'/cart'                     => '/shop/cart/cart',
	'/cart/info'                => '/shop/cart/information',
	'/cart/shippnig'            => '/shop/cart/ship',
	'/cart/payment'             => '/shop/cart/payment',
	'/success'                  => '/shop/cart/success',
	'/collections'              => '/shop/shop/collections',
	
	'<slug>' => '/shop/shop/product',
];
