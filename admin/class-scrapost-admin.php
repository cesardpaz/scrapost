<?php 
class SCRAPOST_Admin {
    private $theme_name;
    private $version;
    private $build_menupage;
    
    public function __construct( $theme_name, $version ) {
        $this->theme_name     = $theme_name;
        $this->version        = $version;
        $this->build_menupage = new SCRAPOST_Build_Menupage();
    }
    
    public function enqueue_styles( $hook ) 
    {
        if( isset($_GET['page']) ) 
        {
            if( $_GET['page'] == 'scrapper_blog' || $_GET['page'] == 'scrapost_help' )
            {
                wp_enqueue_style( 'bootstrap5_admin_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '5.0.2', 'all' );
                wp_enqueue_style( 'material_icon_admin_css', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), '1.0.0', 'all' );
                wp_enqueue_style( 'scrapost_admin_css', SCRAPOST_DIR_URI . 'admin/css/admin_scrapost.css', array(), filemtime(SCRAPOST_DIR_PATH . 'admin/css/admin_scrapost.css'), 'all' );
            }
        }
    }
    public function enqueue_scripts( $hook ) 
    {
        if( isset($_GET['page']) ) 
        {
            if( $_GET['page'] == 'scrapper_blog' || $_GET['page'] == 'scrapost_help' )
            {
                wp_enqueue_script( 'materialize_js', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js', [], '1.0.0', true );

                wp_enqueue_script( 'scrapost_admin_js', SCRAPOST_DIR_URI . 'admin/js/scrapost_admin.js', [], filemtime(SCRAPOST_DIR_PATH . 'admin/js/scrapost_admin.js'), true );

                $scrapost_Public = [
                    'url'   => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'scrapp_seg' ),
                ];
                wp_localize_script( 'scrapost_admin_js', 'scrapost_Public', $scrapost_Public );
            }
        }
        
    }

    public function add_menu() 
    {
        $this->build_menupage->add_menu_page(
            __( 'Scrapper Blog', 'scrapost' ),
            __( 'Scrapper Blog', 'scrapost' ),
            'manage_options',
            'scrapper_blog',
            [ $this, 'scrapper_blog' ]
        );

        $this->build_menupage->add_submenu_page(
            'scrapper_blog',
            __( 'Help', 'scrapost' ),
            __( 'Help', 'scrapost' ),
            'manage_options',
            'scrapost_help',
            [ $this, 'scrapost_help' ]
        );

        $this->build_menupage->run();
    }

    public function scrapper_blog()
    {
        require_once SCRAPOST_DIR_PATH . 'admin/partials/scrapper_blog.php';
    }
    public function scrapost_help()
    {
        require_once SCRAPOST_DIR_PATH . 'admin/partials/scrapost_help.php';
    }


}