<?php

if (!defined('W3TC')) {
    die();
}

w3_require_once(W3TC_LIB_W3_DIR . '/UI/PluginView.php');

class W3_UI_AboutAdminView extends W3_UI_PluginView
{
    /**
     * Current page
     *
     * @var string
     */
    var $_page = 'w3tc_about';


    /**
     * About tab
     *
     * @return void
     */
    function view()
    {
        include W3TC_INC_DIR . '/options/about.php';
    }
}