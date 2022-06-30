<?php 
class SCRAPOST_Master 
{
    protected $charger;
    protected $theme_name;
    protected $version;
    public function __construct() 
    {
        $this->theme_name = 'SCRAPOST_Theme';
        $this->version = SCRAPOST_VERSION;
        $this->load_dependencies();
        $this->load_instances();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    private function load_dependencies() 
    {

        require_once SCRAPOST_DIR_PATH . 'includes/class-scrapost-charger.php';        
        require_once SCRAPOST_DIR_PATH . 'includes/class-scrapost-build-menupage.php';
        require_once SCRAPOST_DIR_PATH . 'admin/class-scrapost-admin.php';
        require_once SCRAPOST_DIR_PATH . 'public/class-scrapost-public.php';
        require_once SCRAPOST_DIR_PATH . 'includes/class-scrapost-ajax-admin.php';
       
    }
    private function load_instances() 
    {
        $this->charger             = new SCRAPOST_Charger;
        $this->scrapost_admin      = new SCRAPOST_Admin( $this->get_theme_name(), $this->get_version() );
        $this->scrapost_public     = new SCRAPOST_Public( $this->get_theme_name(), $this->get_version() );
        $this->scrapost_ajax_admin = new SCRAPOST_Ajax_Admin;
    }
    private function define_admin_hooks() {
        $this->charger->add_action( 'admin_enqueue_scripts', $this->scrapost_admin, 'enqueue_styles' );
        $this->charger->add_action( 'admin_enqueue_scripts', $this->scrapost_admin, 'enqueue_scripts' );
        $this->charger->add_action( 'admin_menu', $this->scrapost_admin, 'add_menu' );
        /* ajax */
        $this->charger->add_action('wp_ajax_action_scrapost', $this->scrapost_ajax_admin, 'scrapost');			  
    }
    private function define_public_hooks() {
        $this->charger->add_action( 'wp_enqueue_scripts', $this->scrapost_public, 'enqueue_styles' );
        $this->charger->add_action( 'wp_footer', $this->scrapost_public, 'enqueue_scripts' );
    }
    public function run() {
        $this->charger->run();
    }
    public function get_theme_name() {
        return $this->theme_name;
    }
    public function get_charger() {
        return $this->charger;
    }
    public function get_version() {
        return $this->version;
    }
}