<?php

$request_uri = $_SERVER['REQUEST_URI'];

global $wbp_shop_option;

if(!empty($wbp_pag_option)){

    function re_rewrite_rules() {

        global $wbp_pag_option;
        global $wbp_shop_option;

        global $wp_rewrite;

        $wp_rewrite->pagination_base = $wbp_pag_option;


        add_rewrite_rule(
            $wbp_shop_option."/(.+?)/".$wp_rewrite->pagination_base."/?([0-9]{1,})/?$",
            'index.php?post_name=$matches[2]&paged=$matches[2]',
            'top');

        $wp_rewrite->flush_rules();

    }
    add_action('init', 're_rewrite_rules');
}
