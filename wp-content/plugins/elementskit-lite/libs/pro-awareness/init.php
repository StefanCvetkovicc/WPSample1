<?php

namespace Wpmet\Libs\Pro_Awareness;

defined( 'ABSPATH' ) || exit;

if(!class_exists('\Wpmet\Libs\Pro_Awareness\Init')):
    
class Init {

    private static $instance;

    private $text_domain;
    private $parent_menu_slug;
    private $default_grid_link = 'https://help.wpmet.com/';
    private $default_grid_title = 'Support Center';
    private $default_grid_thumbnail = '';
    private $default_grid_desc = '';
    private $pro_link_conf = [];
    private $grids = [];

    protected $script_version = '1.0.0';

    /**
    * Get version of this script
    *
    * @return string Version name
    */
    public function get_version() {
        return $this->script_version;
    }

    /**
    * Get current directory path
    *
    * @return string
    */
    public function get_script_location() {
        return dirname(__FILE__);
    }
    

    public static function instance($text_domain) {

        self::$instance = new self();

		return self::$instance->set_text_domain($text_domain);
    }
    
    protected function set_text_domain($val) {

        $this->text_domain = $val;

        return $this;
    }

    private function default_grid() {
        
        return [
            'url' => $this->default_grid_link,
            'title' => $this->default_grid_title,
            'thumbnail' => $this->default_grid_thumbnail,
            'description' => $this->default_grid_desc,
        ];
    }

    public function set_default_grid_link($url) {

        $this->default_grid_link = $url;

        return $this;
    }

    public function set_default_grid_title($title) {

        $this->default_grid_title = $title;

        return $this;
    }

    public function set_default_grid_desc($title) {

        $this->default_grid_desc = $title;

        return $this;
    }

    public function set_default_grid_thumbnail($thumbnail) {

        $this->default_grid_thumbnail = $thumbnail;

        return $this;
    }

    public function set_parent_menu_slug($slug) {

        $this->parent_menu_slug = $slug;

        return $this;
    }

    public function set_pro_link($url, $conf = []) {

        if($url == ''){
            return $this;
        }

        $this->pro_link_conf[] = [
            'url' => $url,
            'anchor' => empty($conf['anchor']) ? '<span style="color: #FCB214;" class="pro_aware pro">Upgrade To Premium</span>' : $conf['anchor'],
            'permission' => empty($conf['permission']) ? 'manage_options' : $conf['permission'],
        ];

        return $this;
    }

    public function set_grid($conf = []) {

        if(!empty($conf['url'])) {

            $this->grids[] = [
                'url' => $conf['url'],
                'title' => empty($conf['title']) ? esc_html__('Default Title', $this->text_domain) : $conf['title'],
                'thumbnail' => empty($conf['thumbnail'])? '' : esc_url($conf['thumbnail']) ,
                'description' => empty($conf['description'])? '' : $conf['description'] ,
            ];
        }
       
        return $this;
    }

    protected function prepare_pro_links() {

        if(!empty($this->pro_link_conf)) {

            foreach($this->pro_link_conf as $conf) {

                add_submenu_page($this->parent_menu_slug, $conf['anchor'], $conf['anchor'], $conf['permission'], $conf['url'], '');
            }
        }
    }

    protected function prepare_grid_links() {

        if(!empty($this->grids)) {

            add_submenu_page($this->parent_menu_slug, 'Get Help', 'Get Help', 'manage_options', $this->text_domain.'_get_help', [$this, 'generate_grids']);
        }
    }


    public function generate_grids() {

        /**
         * Adding default grid at first position
         */
        array_unshift($this->grids, $this->default_grid());

        ?>
        

        <div class="pro_aware grid_container <?php //echo $this->text_domain ?> wpmet_pro_a-grid-container">
            <div class="wpmet_pro_a-row">
            <?php
                foreach($this->grids as $grid) {
                    ?>
                <div class="grid wpmet_pro_a-grid">
                    <div class="wpmet_pro_a-grid-inner">
                        <a target="_blank" href="<?php echo esc_url( $grid['url'] ); ?>" class="wpmet_pro_a_wrapper" title="<?php echo esc_attr( $grid['title'] ); ?>" title="<?php echo esc_attr( $grid['title'] ); ?>">
                            <div class="wpmet_pro_a_thumb">
                                <img src="<?php echo esc_attr($grid['thumbnail']); ?>" alt="Thumbnail">
                            </div>
                            <!-- // thumbnail -->
                            
                            <h4 class="wpmet_pro_a_grid_title"><?php echo esc_attr( $grid['title'] ); ?></h4>
                            <?php if(!empty($grid['description'])) { ?>
                            <p class="wpmet_pro_a_description"><?php echo esc_html( $grid['description'] ); ?></p>
                            <!-- // description -->
                            <?php } ?>
                            <!-- // title -->
                        </a>
                    </div>
                </div>
                <?php
                } ?>
            </div>
        </div>

        <?php
    }

    public static function enqueue_scripts() {
		echo "
			<script>

            </script>

            <style>
            .wpmet_pro_a-grid-container {
                max-width: 1140px;
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
                box-sizing: border-box;
                margin-top: 50px;
            }
        
            .wpmet_pro_a-grid-inner {
                margin-bottom: 20px;
                background-color: #fff;
                border-radius: 4px;
                box-shadow: 0px 2px 5px 10px rgba(0,0,0,.01);
                transition: all .4s ease;
            }
            .wpmet_pro_a-grid-inner .wpmet_pro_a_wrapper {
                padding: 35px 50px;
                display: block;
            }
        
            .wpmet_pro_a-grid-inner:hover {
                transform: translateY(-3px);
                box-shadow: 0px 10px 15px 15px rgba(0,0,0,.05);
            }
        
            .wpmet_pro_a-row {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
                box-sizing: border-box;
            }
        
            .wpmet_pro_a-grid {
                padding-right: 15px;
                padding-left: 15px;
                position: relative;
                width: 100%;
                min-height: 1px;
                box-sizing: border-box;
            }
        
            .wpmet_pro_a_thumb {
                min-height: 76px;
                margin-bottom: 10px;
                display: block;
                border-radius: inherit;
            }
        
            .wpmet_pro_a_grid_title {
                font-size:1.6rem;
                margin:0;
                color: #222;
                display: inline-block;
                line-height: normal;
                text-decoration: none;
            }
        
            .wpmet_pro_a_description {
                margin-bottom: 0;
            }
            .wp-submenu > li > a{
                position: relative;
            }
        
            @media (min-width: 991px) {
                .wpmet_pro_a-grid {
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 33.333333%;
                    flex: 0 0 33.333333%;
                    max-width: 33.333333%;
                }
            }
        
            @media (max-width: 991px) and (min-width: 768px) {
                .wpmet_pro_a-grid {
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 50%;
                    flex: 0 0 50%;
                    max-width: 50%;
                }
            }
        
            @media (max-width: 767px) {
                .wpmet_pro_a-grid {
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 100%;
                    flex: 0 0 100%;
                    max-width: 100%;
                }
                .wpmet_pro_a_grid_title {
                    font-size: 1.2rem;
                }
            }
        </style>
		";
    }

    public function generate_menus() {

        if(!empty($this->parent_menu_slug)) {            
            $this->prepare_grid_links();
            $this->prepare_pro_links();
        }
    }

    public static function init() {
        add_action( 'admin_head', [ __CLASS__, 'enqueue_scripts' ] );
    }

    public function call() {

        add_action('admin_menu', [$this, 'generate_menus'], 99999);                
    }

}

endif;