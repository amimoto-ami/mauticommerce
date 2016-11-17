=== Mauticommerce ===
Contributors: amimotoami,megumithemes,hideokamoto,2craig
Tags: marketing,mautic,woocommerce
Requires at least: 4.4.2
Tested up to:4.4.2
Stable tag: 0.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

You can get WooCommerce Order Information in Mautic.

== Description ==

Mauticommerce is used to bridge user data from WooCommerce to the Mautic open source marketing automation system.
When your customer enters an order in Woocommerce, their contact data is used to create a new Mautic Lead.

This plugin is available through the official Wordpress.org Plugin Directory.
Simply download and install the plugin as you would any other plugin.
Mautic is a free open source marketing automation tool.
It can be downloaded at: www.mautic.org/download.
Mautic may or may not be installed before the Mauticommerce plugin.
You will need a functioning Mautic installation and a new Mautic Form for this plugin to work.

## Document
- [API reference: https://amimoto-ami.github.io/mauticommerce/index.html](https://amimoto-ami.github.io/mauticommerce/index.html)

== Installation ==

1. Upload this directlies to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

You will need to create a Mautic form from within your Mautic installation.
Please add the following fields to the form:
1. address1 as a text field;
2. address2 as a text field;
3. city as a text field;
4. company as a text field;
5. country as a text field;
6. email as an email field;
7. firstname as a text field;
8. lastname as a text field;
9. phone as a phone field;
10. zipcode as a text field;
11. state as a text field; and
12. order_id as a text field.

Remember to Save & Close the form in Mautic.

Once you have created the form and saved it and note it’s Mautic Form ID (which can be found in the rightmost column on the general Forms page).
You will also need to know the directory where you installed Mautic (http://abc.net/Mautic/).

To set up this plugin, simply download it and install it into your Wordpress site. Then go to the General Settings options of your Wordpress site. Under Settings choose Mauticommerce. Here you will find two fields for your Mautic URL and your Mautic Form ID which you recorded in the steps above.

That’s all there is to setting up Mauticommerce!
1. Install the Mautic Commerce plugin
2. Create a Mautic Form ID
3. Enter the URL and Form ID into Mauticommerce under the Wordpress Setting page.
4. Click “Save Changes” and you are done!

You will never need to visit this form again, your customers data will simply appear as a Lead in Mautic.

Now when a customer creates a new order in Woocommerce, their data will be sent to Mautic (through the Mautic Form you created).

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
= 0.1.1 =
* Bug fix
* Create document site

= 0.1.0 =
* Country & State Mapping for Mautic

= 0.0.3 =
* add filter query param

= 0.0.2 =
* Add pot file

= 0.0.1 =
* Initialize Release

== Upgrade Notice ==

= 0.1.1 =
* Bug fix
* Create document site
