<?php
namespace ElementsKit_Lite;

defined( 'ABSPATH' ) || exit;


/**
 * ElementsKit - the God class.
 * Initiate all necessary classes, hooks, configs.
 *
 * @since 1.0.0
 */
class Plugin{


	/**
	 * The plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
    public static $instance = null;

    /**
     * Construct the plugin object.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct() {

        // Enqueue frontend scripts.
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_frontend'] );

        // Enqueue admin scripts.
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin'] );

        // Enqueue inline scripts
        Core\Build_Inline_Scripts::instance();

        // Register plugin settings pages
        Libs\Framework\Attr::instance();

        // Register default widgets
        Core\Build_Widgets::instance();

        // Register default modules
        Core\Build_Modules::instance();

        // Register ElementsKit supported widgets to Elementor from 3rd party plugins.
        add_action( 'elementor/widgets/widgets_registered', [$this, 'register_widgets'], 1050);

        // Register wpml compability module
        Compatibility\Wpml\Init::instance();
        Compatibility\Conflicts\Init::instance();

        // Register data migration class
         //Compatibility\Data_Migration\Translate_File::instance()->init();
         //Libs\Xs_Migration\Initiator::instance()->init();

        add_action('wp_head', [$this, 'add_meta_for_search_excluded']);

        // Add banner class
        add_action('admin_head', function() {

            $filter_string = ''; // elementskit,metform-pro
            $filter_string .= ((!in_array('elementskit/elementskit.php', apply_filters('active_plugins', get_option('active_plugins')))) ? '' : ',elementskit');
            $filter_string .= (!class_exists('\MetForm\Plugin') ? '' : ',metform');
            $filter_string .= (!class_exists('\MetForm_Pro\Plugin') ? '' : ',metform-pro');

            //die('filter test:: '.$filter_string);

	        \Wpmet\Libs\Banner\Init::instance('elementskit-lite')
                // ->is_test(true)
		        ->set_filter(ltrim($filter_string, ','))
		        ->set_api_url('https://api.wpmet.com/public/jhanda/index.php')
		        ->set_plugin_screens('edit-elementskit_template')
		        ->set_plugin_screens('toplevel_page_elementskit')
                ->call();
        });

        // Adding pro lebel
        if(\ElementsKit_Lite::package_type() == 'free'){
            new Libs\Pro_Label\Init();
        }

        // Asking rating service
        // require_once 'libs/rating/rating.php';
        // (new \Wpmet\Rating\Rating())
        //     ->plugin_name('elementskit')
        //     ->first_appear_day(7)
        //     ->condition($this->should_show_rating_notice())
        //     ->rating_url('https://wordpress.org/plugins/elementskit-lite/')
        //     ->init();

        /**
         * Show WPMET announcements widget in dashboard
         */
        \Wpmet\Libs\Announcements\Init::instance('elementskit-lite')
        // ->is_test(true)
        // ->set_filter('elementskit')
        ->set_plugin('ElementsKit', 'https://wpmet.com/plugin/elementskit/?ref=wpmet')
        ->set_api_url('https://api.wpmet.com/public/announcements/index.php')
        ->call();

        
        // pro menu
        \Wpmet\Libs\Pro_Awareness\Init::instance('elementskit-lite')
        ->set_parent_menu_slug('elementskit')
        ->set_pro_link((
            (\ElementsKit_Lite::package_type() != 'free') ? '' :
            'https://wpmet.com/plugin/elementskit/?utm_source=elementskit&utm_medium=inplugin_campaign&utm_campaign=go_pro_menu'
            )
        )
        ->set_default_grid_thumbnail(\ElementsKit_Lite::lib_url() . 'pro-awareness/assets/support.png')
        ->set_grid([
            'url' => 'https://go.wpmet.com/facebook-group',
            'title' => 'Join the Community',
            'thumbnail' => \ElementsKit_Lite::lib_url() . 'pro-awareness/assets/community.png',
        ])
        ->set_grid([
            'url' => 'https://www.youtube.com/playlist?list=PL3t2OjZ6gY8MVnyA4OLB6qXb77-roJOuY',
            'title' => 'Video Tutorials',
            'thumbnail' => \ElementsKit_Lite::lib_url() . 'pro-awareness/assets/videos.png',
        ])
        // ->set_default_grid_thumbnail(WSLU_LOGIN_PLUGIN_URL . 'assets/get-help/test.jpg')
        ->call();

    }

    /**
     * Check the admin screen and show the rating notice if eligible
     *
     * @access private
     * @return boolean
     */
    private function should_show_rating_notice() {

        if(\ElementsKit_Lite::package_type() == 'free'){
            return true;
        }

        if( !function_exists('get_current_screen') ) {
            return false;
        }

        $current_screen = (get_current_screen())->base;
        $current_post_type = (get_current_screen())->post_type;
        $eligible_post_type = ['elementskit_template'];
        $eligible_screens = ['plugins', 'dashboard', 'elementskit', 'themes'];

        if (in_array($current_post_type, $eligible_post_type)){
            return true;
        }


        if (in_array($current_screen, $eligible_screens)){
            return true;
        }


        return false;
    }

    /**
     * Enqueue scripts
     *
     * Enqueue js and css to frontend.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue_frontend(){
        wp_enqueue_style( 'elementor-icons-ekiticons', \ElementsKit_Lite::module_url() . 'controls/assets/css/ekiticons.css', \ElementsKit_Lite::version() );
        wp_enqueue_script( 'elementskit-framework-js-frontend', \ElementsKit_Lite::lib_url() . 'framework/assets/js/frontend-script.js', ['jquery'], \ElementsKit_Lite::version(), true );
    }

    /**
     * Enqueue scripts
     *
     * Enqueue js and css to admin.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue_admin(){
        $screen = get_current_screen();

        if(!in_array($screen->id, ['nav-menus', 'toplevel_page_elementskit', 'edit-elementskit_template', 'elementskit_page_elementskit-license'])){
            return;
        }

        wp_register_style( 'fontawesome', \ElementsKit_Lite::widget_url() . 'init/assets/css/font-awesome.min.css', \ElementsKit_Lite::version() );
        wp_register_style( 'elementskit-font-css-admin', \ElementsKit_Lite::module_url() . 'controls/assets/css/ekiticons.css', \ElementsKit_Lite::version() );
        wp_register_style( 'elementskit-lib-css-admin', \ElementsKit_Lite::lib_url() . 'framework/assets/css/framework.css', \ElementsKit_Lite::version() );
        wp_register_style( 'elementskit-init-css-admin', \ElementsKit_Lite::lib_url() . 'framework/assets/css/admin-style.css', \ElementsKit_Lite::version() );
        wp_register_style( 'elementskit-init-css-admin-ems', \ElementsKit_Lite::lib_url() . 'framework/assets/css/admin-style-ems-dev.css', \ElementsKit_Lite::version() );

        wp_enqueue_style( 'fontawesome' );
        wp_enqueue_style( 'elementskit-font-css-admin' );
        wp_enqueue_style( 'elementskit-lib-css-admin' );
        wp_enqueue_style( 'elementskit-lib-css-admin' );
        wp_enqueue_style( 'elementskit-init-css-admin' );
        wp_enqueue_style( 'elementskit-init-css-admin-ems' );

        wp_enqueue_script( 'ekit-admin-core', \ElementsKit_Lite::lib_url() . 'framework/assets/js/ekit-admin-core.js', ['jquery'], \ElementsKit_Lite::version(), true );

        $data['rest_url'] = get_rest_url();
	    $data['nonce']    = wp_create_nonce('wp_rest');

	    wp_localize_script('ekit-admin-core', 'rest_config', $data);
    }

    /**
     * Control registrar.
     *
     * Register the custom controls for Elementor
     * using `elementskit/widgets/widgets_registered` action.
     *
     * @since 1.0.0
     * @access public
     */
    public function register_control($widgets_manager){
        do_action('elementskit/widgets/widgets_registered', $widgets_manager);
    }


    /**
     * Widget registrar.
     *
     * Retrieve all the registered widgets
     * using `elementor/widgets/widgets_registered` action.
     *
     * @since 1.0.0
     * @access public
     */
    public function register_widgets($widgets_manager){
        do_action('elementskit/widgets/widgets_registered', $widgets_manager);
    }

    /**
     * Excluding ElementsKit template and megamenu content from search engine.
     * See - https://wordpress.org/support/topic/google-is-indexing-elementskit-content-as-separate-pages/
     *
     * @since 1.4.5
     * @access public
     */
	public function add_meta_for_search_excluded(){
        if ( in_array(get_post_type(),
                ['elementskit_widget', 'elementskit_template', 'elementskit_content'])
            ){
			echo '<meta name="robots" content="noindex,nofollow" />', "\n";
		}
	}

    /**
     * Autoloader.
     *
     * ElementsKit autoloader loads all the classes needed to run the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private static function registrar_autoloader() {
        require_once \ElementsKit_Lite::plugin_dir() . '/autoloader.php';
        Autoloader::run();
    }

    /**
     * Instance.
     *
     * Ensures only one instance of the plugin class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return Plugin An instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            // Call the method for ElementsKit lite autoloader.
            self::registrar_autoloader();

            do_action( 'elementskit_lite/before_loaded' );

            // Fire when ElementsKit instance.
            self::$instance = new self();

            do_action( 'elementskit/loaded' ); // legacy support
            do_action( 'elementskit_lite/after_loaded' );
        }

        return self::$instance;
    }
}

// Run the instance.
Plugin::instance();
