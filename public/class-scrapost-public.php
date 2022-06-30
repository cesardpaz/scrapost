<?php 
class SCRAPOST_Public {
    private $theme_name;
    private $version;

    public function __construct( $theme_name, $version ) {
        $this->theme_name = $theme_name;
        $this->version    = $version;
    }

    public function enqueue_styles() {
        
    }
    
    public function enqueue_scripts() {
        
    }
}