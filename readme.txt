=== Mauticommerce ===
Contributors: amimotoami,megumithemes,hideokamoto
Tags: marketing,mautic,woocommerce
Requires at least: 4.4.2
Tested up to:4.4.2
Stable tag: 0.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

You can get WooCommerce Order Information in Mautic.

== Description ==

This is the long description.  No limit, and you can use Markdown (as well as in the following sections).

== Installation ==

1. Upload this directlies to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. And You have to set following information.

|Name|Descrtiption|Example|
|:--|:--|:--|
|Mautic URL|Your Mautic Domain|https://mautic.example.com/|
|ID|Your Mautic Form ID|1|

=== Form Mapping in Mautic ===
You can receive following params.
Please make Mautic Form using following label name.
|Mautic Form Label Name|
|:--|
|address1|
|address2|
|city|
|company|
|country|
|email|
|firstname|
|lastname |
|phone|
|zipcode|
|state|
|order_id|

== Customize ==
=== Add form param ===
```
add_filter( 'mauticommerce_query_mapping', 'add_mapping_query', 10, 2 );
function add_mapping_query( $query, $order ) {
	//Add $order Param to $query
	$query['customer_ip_address'] = $order->customer_ip_address;
	return $query;
}
```

== Thanks for ==
* [.svnignore for WordPress plugins](https://github.com/miya0001/wp-svnignore)

== Changelog ==

= 0.0.3 =
* add filter query param

= 0.0.2 =
* Add pot file

= 0.0.1 =
* Initialize Release

== Upgrade Notice ==

= 0.0.3 =
* add filter query param
