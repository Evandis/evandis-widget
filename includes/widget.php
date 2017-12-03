<?php

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

/*
 * WIDGET
 */
class evandw_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        
        $this->textdomain = 'evandis-widget';
        
        parent::__construct(
            'evandis_widget', // Base ID
            __('The Evandis widget ', $this->textdomain), // Name
            array( 'description' => __( 'Displays the Evandis in a widget.', $this->textdomain ), ) // Args
        );
        
        // This is where we add the style and script
        add_action( 'admin_enqueue_scripts', array( $this, 'evandw_load' ) );
        add_action( 'admin_footer-widgets.php', array( $this, 'evandw_print_scripts' ), 9999 );

    }

    /**
	 * Enqueue scripts.
	 *
	 * @since 1.0
	 *
	 * @param string $hook_suffix
	 */
    
    function evandw_load( $hook_suffix ) {
		if ( 'widgets.php' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'underscore' );
    }
    
    
    public function evandw_print_scripts() {
		?>
		<script type="text/javascript">
			( function( $ ){
				function initColorPicker( widget ) {
					widget.find( '.color-picker' ).wpColorPicker( {
						change: _.throttle( function() { // For Customizer
							$(this).trigger( 'change' );
						}, 3000 )
					});
				}

				function onFormUpdate( event, widget ) {
					initColorPicker( widget );
				}

				$( document ).on( 'widget-added widget-updated', onFormUpdate );

				$( document ).ready( function() {
					$( '#widgets-right .widget:has(.color-picker)' ).each( function () {
						initColorPicker( $( this ) );
					} );
				} );
			}( jQuery ) );
        </script>
        <?php
	}
    
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {

        global $post;
        global $current_user;
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $rubrique = apply_filters( 'widget_rubrique', $instance['rubrique'] );
        $typetext = apply_filters( 'widget_typetex', $instance['typetext'] );
        $colortext = apply_filters( 'widget_colortext', $instance[ 'colortext' ] );
        $color = apply_filters( 'widget_color', $instance['color'] );
        $color1 = apply_filters( 'widget_color1', $instance['color1'] );
        $bgcolor = apply_filters( 'widget_background_color', $instance['background_color'] );
        $iframe_width = apply_filters( 'widget_iframe_width', $instance['iframe_width'] );
        $iframe_height = apply_filters( 'widget_iframe_height', $instance['iframe_height'] );
        $iframe_border = apply_filters( 'widget_iframe_border', $instance['iframe_border'] );
        $iframe_scroll = apply_filters( 'widget_iframe_scroll', $instance['iframe_scroll'] );
        
        $add = array();
        $add['id'] = $rubrique;
        if( isset($colortext) && $colortext != '' ) { $add['colortext'] = str_replace('#', '', $colortext); }
        if( isset($color) && $color != '' ) { $add['color'] = str_replace('#', '', $color); }
        if( isset($color1) && $color1 != '' ) { $add['color1'] = str_replace('#', '', $color1); }
        if( isset($typetext) && $typetext != '' ) { $add['typetext'] = $typetext; }
        if( isset($add['typetext']) && $add['typetext'] == 1) { $iframe_height = 200;}
        
        $campagne = array( 
            1 => 'la-manne-daujourdhui',
            10 => 'le-message-de-la-semaine',
            9 => 'le-verset-du-jour'
        );
        
        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

        //echo '<div class="widget">';
        echo '<iframe frameborder="0" src="https://newsletter.evandis.fr/widget/?tab='.base64_encode(serialize($add)).'" name="evandis-widget" id="evandis-widget" scrolling="'.$iframe_scroll.'" frameborder="'.$iframe_border.'" width="'.$iframe_width.'" height="'.$iframe_height.'" style="margin-bottom:0px!important;"></iframe>';
        echo '<div class="logos" style="text-align:center;"><a href="https://www.evandis.fr/?utm_source=widget&utm_medium=logo&utm_campaign='.$campagne[$rubrique].'" class="img" target="_blank" title="'.__( 'Proposed by Evandis.fr', $this->textdomain ).'" alt="'.__( 'Proposed by Evandis.fr', $this->textdomain ).'"><img src="'.plugins_url('evandis-widget/images/logo-evandis-petit.png').'" class="img" /></a></div>';
        //echo '</div>';
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['rubrique'] = strip_tags( $new_instance['rubrique'] );
        $instance['typetext'] = strip_tags( $new_instance['typetext'] );
        $instance['colortext'] = strip_tags( $new_instance['colortext'] );
        $instance['color'] = strip_tags( $new_instance['color'] );
        $instance['color1'] = strip_tags( $new_instance['color1'] );
        $instance['background_color'] = strip_tags( $new_instance['background_color'] );
        $instance['iframe_width'] = strip_tags( $new_instance['iframe_width'] );
        $instance['iframe_height'] = strip_tags( $new_instance['iframe_height'] );
        $instance['iframe_border'] = strip_tags( $new_instance['iframe_border'] );
        $instance['iframe_scroll'] = strip_tags( $new_instance['iframe_scroll'] );
        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        
        $defaults = array(
            'background_color' => '#e3e3e3',
            'typetext' => 2,
            'title' => __( 'Découvrez Evandis', $this->textdomain ),
            'rubrique' => 1,
            'colortext' => '#000000',
            'color' => '#ea5b0c',
            'color1' => '#94c11f',
            'iframe_width' => 450,
            'iframe_height' => 550,
            'iframe_border' => 0,
            'iframe_scroll' => 'no',
        );

        // Merge the user-selected arguments with the defaults
        $instance = wp_parse_args( (array) $instance, $defaults );
        
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><strong><?php _e( 'Title:', $this->textdomain ); ?></strong></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'rubrique' ); ?>"><strong><?php _e( 'Choose your category:', $this->textdomain ); ?></strong></label> 
            <select class="widefat" id="<?php echo $this->get_field_id( 'rubrique' ); ?>" name="<?php echo $this->get_field_name( 'rubrique' ); ?>" >
                <option value="1" <?php if( esc_attr( $instance['rubrique'] ) == 1 ) { echo 'selected'; } ?>>La Manne d'Aujourd'hui</option>
                <option value="9" <?php if( esc_attr( $instance['rubrique'] ) == 9 ) { echo 'selected'; } ?>>Le verset du jour</option>
            </select><br />
            <label for="<?php echo $this->get_field_id( 'typetext' ); ?>"><strong><?php _e( 'Choose your format:', $this->textdomain ); ?></strong></label> 
            <select class="widefat" id="<?php echo $this->get_field_id( 'typetext' ); ?>" name="<?php echo $this->get_field_name( 'typetext' ); ?>" >
                <option value="1" <?php if( esc_attr( $instance['typetext'] ) == 1 ) { echo 'selected'; } ?>><?php _e( 'Excerpt', $this->textdomain ); ?></option>
                <option value="2" <?php if( esc_attr( $instance['typetext'] ) == 2 ) { echo 'selected'; } ?>><?php _e( 'Full', $this->textdomain ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'colortext' ); ?>"><strong><?php _e( 'Color Text:' , $this->textdomain); ?></strong></label><input class="widefat color-picker" data-default-color="#424242" id="<?php echo $this->get_field_id( 'colortext' ); ?>" name="<?php echo $this->get_field_name( 'colortext' ); ?>" type="text" value="<?php echo esc_attr( $instance['colortext'] ); ?>" />
            <label for="<?php echo $this->get_field_id( 'color' ); ?>"><strong><?php _e( 'Color link:' , $this->textdomain); ?></strong></label> 
            <input class="widefat color-picker" data-default-color="#ea5b0c" id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" type="text" value="<?php echo esc_attr( $instance['color'] ); ?>" />
            <label for="<?php echo $this->get_field_id( 'color1' ); ?>"><strong><?php _e( 'Color link hover:' , $this->textdomain); ?></strong></label>
            <input class="widefat color-picker" data-default-color="#94c11f" id="<?php echo $this->get_field_id( 'color1' ); ?>" name="<?php echo $this->get_field_name( 'color1' ); ?>" type="text" value="<?php echo esc_attr( $instance['color1'] ); ?>" />
            <label for="<?php echo $this->get_field_id( 'background_color' ); ?>"><strong><?php _e( 'Background Color:', $this->textdomain ); ?></strong></label>
            <input class="widefat color-picker" type="text" id="<?php echo $this->get_field_id( 'background_color' ); ?>" name="<?php echo $this->get_field_name( 'background_color' ); ?>" value="<?php echo esc_attr( $instance['background_color'] ); ?>" />                            
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'iframe_width' ); ?>"><strong><?php _e( 'Width:', $this->textdomain ); ?></strong></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'iframe_width' ); ?>" name="<?php echo $this->get_field_name( 'iframe_width' ); ?>" type="text" style="width:50px;" value="<?php echo esc_attr( $instance['iframe_width'] ); ?>" />&nbsp;&nbsp;
            
            <label for="<?php echo $this->get_field_id( 'iframe_height' ); ?>"><strong><?php _e( 'Height:', $this->textdomain ); ?></strong></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'iframe_height' ); ?>" type="text" style="width:50px;" value="<?php echo esc_attr( $instance['iframe_height'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'iframe_border' ); ?>"><strong><?php _e( 'Border:', $this->textdomain ); ?></strong></label> 
            <select class="widefat" style="width:58px;" id="<?php echo $this->get_field_id( 'iframe_border' ); ?>" name="<?php echo $this->get_field_name( 'iframe_border' ); ?>" >
                <option value="0" <?php if( esc_attr( $instance['iframe_border'] ) == 0 ) { echo 'selected'; } ?>><?php _e( 'No', $this->textdomain ); ?></option>
                <option value="1" <?php if( esc_attr( $instance['iframe_border'] ) == 1 ) { echo 'selected'; } ?>><?php _e( 'Yes', $this->textdomain ); ?></option>
            </select>
            &nbsp;&nbsp;
            <label for="<?php echo $this->get_field_id( 'iframe_scroll' ); ?>"><strong><?php _e( 'Scrolling:', $this->textdomain ); ?></strong></label> 
            <select class="widefat" style="width:58px;" id="<?php echo $this->get_field_id( 'iframe_scroll' ); ?>" name="<?php echo $this->get_field_name( 'iframe_scroll' ); ?>" >
                <option value="no" <?php if( esc_attr( $instance['iframe_scroll'] ) == 'no' ) { echo 'selected'; } ?>><?php _e( 'No', $this->textdomain ); ?></option>
                <option value="yes" <?php if( esc_attr( $instance['iframe_scroll'] ) == 'yes' ) { echo 'selected'; } ?>><?php _e( 'Yes', $this->textdomain ); ?></option>
            </select>
        </p>
        <?php 
    }

} // class Foo_Widget
//
// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "evandw_widget" );' ) );