# Mauticommerce
You can get WooCommerce Order Information in Mautic.

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
