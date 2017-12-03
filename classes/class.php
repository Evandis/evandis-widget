<?php

class evandis_widget {
    
	public function hooks() {
     
        /* Version du plugin */
        $option['evandw_version'] = EVANW_VERSION;
        if( !get_option('evandw_version') ) {
            add_option('evandw_version', $option);
        } else if ( get_option('evandw_version') != EVANW_VERSION ) {
            update_option('evandw_version', EVANW_VERSION);
        }    
        add_filter( 'plugin_action_links', array( $this, 'evandw_plugin_actions'), 10, 2 );
        register_deactivation_hook(__FILE__, 'evandw_uninstall');
            
    }
    
    // Add "Réglages" link on plugins page
    function evandw_plugin_actions( $links, $file ) {
        //return array_merge( $links, $settings_link );
        if ( $file != EVANW_PLUGIN_BASENAME ) {
		  return $links;
        } else {
            $settings_link = '<a href="widgets.php">'
                . esc_html( __( 'Widgets Page', 'evandis-widget' ) ) . '</a>';

            array_unshift( $links, $settings_link );

            return $links;
        }
    }
    
    function evandw_uninstall() {
    
        global $wpdb;

        if(get_option('evandw_version')) { delete_option('evandw_version'); }

    }
    
       
}


?>