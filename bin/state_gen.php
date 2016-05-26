<?php
require_once( '../woocommerce/i18n/states/US.php');
require_once( '../woocommerce/i18n/states/AR.php');
require_once( '../woocommerce/i18n/states/AU.php');
require_once( '../woocommerce/i18n/states/BD.php');
require_once( '../woocommerce/i18n/states/BG.php');
require_once( '../woocommerce/i18n/states/BR.php');
require_once( '../woocommerce/i18n/states/CA.php');
require_once( '../woocommerce/i18n/states/CN.php');
require_once( '../woocommerce/i18n/states/ES.php');
require_once( '../woocommerce/i18n/states/GR.php');
require_once( '../woocommerce/i18n/states/HK.php');
require_once( '../woocommerce/i18n/states/HU.php');
require_once( '../woocommerce/i18n/states/ID.php');
require_once( '../woocommerce/i18n/states/IN.php');
require_once( '../woocommerce/i18n/states/IR.php');
require_once( '../woocommerce/i18n/states/IT.php');
require_once( '../woocommerce/i18n/states/JP.php');
require_once( '../woocommerce/i18n/states/MX.php');
require_once( '../woocommerce/i18n/states/MY.php');
require_once( '../woocommerce/i18n/states/NP.php');
require_once( '../woocommerce/i18n/states/NZ.php');
require_once( '../woocommerce/i18n/states/PE.php');
require_once( '../woocommerce/i18n/states/PH.php');
require_once( '../woocommerce/i18n/states/TH.php');
require_once( '../woocommerce/i18n/states/TR.php');
require_once( '../woocommerce/i18n/states/ZA.php');

function __( $name, $text_domain ) {
	return $name;
}
function _x( $name, $text_domain ) {
	return $name;
}

function get_mautic_region() {
	$json = file_get_contents( 'https://raw.githubusercontent.com/mautic/mautic/e146b2eb2a2a153c0097e7ab8bdce201fb5f24ce/app/bundles/CoreBundle/Assets/json/regions.json' );
	$array = json_decode( $json, true );
	return $array;
}

$json = json_encode( $states );
file_put_contents( './inc/assets/json/states.json', $json );
