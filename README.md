# Mauticommerce
[![WordPress plugin](https://img.shields.io/wordpress/plugin/v/mauticommerce.svg)](https://wordpress.org/plugins/mauticommerce/)
[![WordPress rating](https://img.shields.io/wordpress/plugin/r/mauticommerce.svg)]()
You can get WooCommerce Order Information in Mautic.

##Reporting bugs
We try to fix as many bugs we can, this is a graph of our recent activity: 
[![Throughput Graph](https://graphs.waffle.io/amimoto-ami/mauticommerce/throughput.svg)](https://waffle.io/amimoto-ami/mauticommerce/metrics/throughput)

##How to use
Do following commands.
```
$ cd /path/to/wordpress/wp-content/plugins
$ git clone git@github.com:megumiteam/mauticommerce.git
```
And Activate "Mauticommerce" Plugin in wp-admin.

###Initialize Setting
You have to set following information.

|Name|Descrtiption|Example|
|:--|:--|:--|
|Mautic URL|Your Mautic Domain|https://mautic.example.com/|
|ID|Your Mautic Form ID|1|

##Form Mapping in Mautic
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

## Customize
### Add form param

use `mauticommerce_query_mapping` filter.
```
add_filter( 'mauticommerce_query_mapping', 'add_mapping_query', 10, 2 );
function add_mapping_query( $query, $order ) {
        //Add $order Param to $query
        $query['customer_ip_address'] = $order->customer_ip_address;
        return $query;
}
```
