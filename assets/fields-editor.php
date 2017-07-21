<div ng-app="checkoutFieldsEditor" >
    <ul class="subsubsub" ng-controller="menuCtrl">
        <li><a ng-class="selectedBillingFields ? 'current' : ''" href="" class="hyperlink" ng-click="showBillingFields()">Billing Fields</a> | </li>
        <li><a ng-class="selectedShippingFields ? 'current' : ''" href="" class="hyperlink" ng-click="showShippingFields()">Shipping Fields</a> | </li>
        <li><a ng-class="selectedAccountFields ? 'current' : ''" href="" class="hyperlink" ng-click="showAccountFields()">Account Fields</a> | </li>
        <li><a ng-class="selectedOrderFields ? 'current' : ''" href="" class="hyperlink" ng-click="showOrderFields()">Order Fields</a></li>
    </ul>
    <br/>
    <br/>
    <div ng-view></div>
</div>

<?php

if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

class FWCFE_Resources_Loader {

    static function init() {
        FWCFE_Resources_Loader::load_vendors();
        FWCFE_Resources_Loader::load_app();
        FWCFE_Resources_Loader::init_client_storage();
    }

    static function init_client_storage() {
        wp_localize_script('appControllers', 'checkoutFieldsEditor', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'billingFieldsOptions' => get_option("billingFieldsOptions"),
            'shippingFieldsOptions' => get_option("shippingFieldsOptions"),
            'accountFieldsOptions' => get_option("accountFieldsOptions"),
            'orderFieldsOptions' => get_option("orderFieldsOptions"),
            "pluginUrl" => str_replace("\\", "\\\\", plugin_dir_url(__FILE__)),
            "allProducts" => FWCFE_Resources_Loader::get_json_products(),
            "allCategories" => FWCFE_Resources_Loader::get_json_categories())
        );
    }

    static function load_app() {
        wp_register_script('wooCheckoutFieldsEditorJs', plugin_dir_url(__FILE__) . 'adminJs/WooCheckoutFieldsEditor.js');
        wp_enqueue_script('wooCheckoutFieldsEditorJs');

        wp_register_style('wooCheckoutFieldsEditorStyles', plugin_dir_url(__FILE__) . 'css/wooCheckoutFieldsEditor.css');
        wp_enqueue_style('wooCheckoutFieldsEditorStyles');

        wp_register_script('app', plugin_dir_url(__FILE__) . 'adminJs/App.js');
        wp_enqueue_script('app');

        wp_register_script('appServices', plugin_dir_url(__FILE__) . 'adminJs/Services.js');
        wp_enqueue_script('appServices');

        wp_register_script('appControllers', plugin_dir_url(__FILE__) . 'adminJs/Controllers.js');
        wp_enqueue_script('appControllers');
    }

    static function load_vendors() {
        wp_enqueue_script("jquery-ui-sortable");
        
        wp_register_script('angularjs', plugin_dir_url(__FILE__) . 'vendors/angular/angular.min.js');
        wp_enqueue_script('angularjs');

        wp_register_script('angularroute', plugin_dir_url(__FILE__) . 'vendors/angular/angular-route.min.js');
        wp_enqueue_script('angularroute');

        wp_register_style('flexWhaleLookupStyles', plugin_dir_url(__FILE__) . 'vendors/FlexWhaleLookup/lookup.min.css');
        wp_enqueue_style('flexWhaleLookupStyles');

        wp_register_script('flexWhaleLookupJs', plugin_dir_url(__FILE__) . 'vendors/FlexWhaleLookup/lookup.min.js');
        wp_enqueue_script('flexWhaleLookupJs');

        wp_register_style('fontAwesome', plugin_dir_url(__FILE__) . 'vendors/FontAwesome/css/font-awesome.min.css');
        wp_enqueue_style('fontAwesome');

        wp_register_style('bootstrap', plugin_dir_url(__FILE__) . 'vendors/bootstrap/bootstrap.min.css');
        wp_enqueue_style('bootstrap');
    }

    static function get_products() {
        $args = array('post_type' => 'product',
            'posts_per_page' => -1);
        $products = get_posts($args);

        return $products;
    }

    static function get_json_products() {

        $idsProducts = array();
        $jsonObjects = array();

        $products = FWCFE_Resources_Loader::get_products();

        for ($indexProduct = 0; $indexProduct < count($products); $indexProduct++) {
            $product = $products[$indexProduct];
            if (in_array($product->ID, $idsProducts)) {
                continue;
            }
            array_push($idsProducts, $product->ID);
            array_push($jsonObjects, '{ "id":' . $product->ID . ', "title":"#'.$product->ID .' '. $product->post_title . '"}');
        }

        $strProducts = "[" . implode(",", $jsonObjects) . "]";
        return $strProducts;
    }

    static function get_all_categories() {
        $args = array();
        $product_categories = get_terms('product_cat', $args);
        return $product_categories;
    }

    static function get_json_categories() {

        $categories = FWCFE_Resources_Loader::get_all_categories();
        $strCategories = '[';
        for ($index = 0; $index < count($categories); $index++) {
            $category = $categories[$index];
            $strCategories = $strCategories . '{ "id":' . $category->term_id . ', "title":"' . $category->name . '"}';
            if ($index + 1 !== count($categories))
                $strCategories = $strCategories . ',';
        }
        $strCategories = $strCategories . ']';
        return $strCategories;
    }

}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    FWCFE_Resources_Loader::init();
}
?>

