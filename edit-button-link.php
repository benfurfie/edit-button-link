<?php
/**
 * Plugin Name:     Edit Button Link
 * Description:     Adds a floating button in the bottom right hand corner of the screen that allows a logged in user to quickly reach the edit screen for the current page.
 * Author:          Ben Furfie
 * Author URI:      https://www.benfurfie.co.uk
 * Text Domain:     edit-button-link
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         WordPress
 * @subpackage      Edit_Button_Link
 */
namespace BenFurfie\EditLink;

class EditLink {
    /**
     * Constructor function.
     * 
     * @since 0.1.0
     */
    function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'loadStyles'));
        add_action('wp_footer', array($this, 'createEditButton'));
    }

    /**
     * Load CSS for button styles.
     * 
     * @since 0.1.0
     */
    function loadStyles()
    {
        wp_enqueue_style('edit-link-button', plugin_dir_url(__FILE__) . 'css/edit-link-button.css');
    }

    /**
     * Create the button and pass in the edit link.
     * Checks to make sure the user isn't in the control panel.
     * 
     * @since 0.1.0
     */
    function createEditButton()
    {
        global $post, $wp_the_query, $user_id;
        $current_object = $wp_the_query->get_queried_object();

        /**
         * If the current page is not editable, bail.
         * 
         * @since 0.1.0
         */
        if(empty($current_object))
        {
            return;
        }
        /**
         * If the current object exists and is a post-type.
         */
        if(!empty($current_object->post_type) && ($post_type_object = get_post_type_object($current_object->post_type)) && current_user_can('edit_post', $current_object->ID) && $edit_post_link = get_edit_post_link($current_object->ID))
        {
            echo '<a href="' . $edit_post_link . '" target="_blank"  class="button button--edit-link"><img src="' . plugin_dir_url( __FILE__ ) . 'images/icon.svg" alt="Click to edit"></a>';

        }
        /**
         * If the current object exists and is a term.
         */
        elseif(!empty($current_object->taxonomy) && ($tax = get_taxonomy($current_object->taxonomy)) && current_user_can('edit_term', $current_object->term_id) && $edit_term_link = get_edit_term_link($current_object->term_id, $current_object->taxonomy))
        {
            echo '<a href="' . $edit_term_link . '" target="_blank"  class="button button--edit-link"><img src="' . plugin_dir_url( __FILE__ ) . 'images/icon.svg" alt="Click to edit"></a>';
        }
        /**
         * If the current object exists and is a user.
         */
        elseif(is_a($current_object, 'WP_User') && current_user_can('edit_user', $current_object->ID) && $edit_user_link = get_edit_user_link($current_object->ID))
        {
            echo '<a href="' . $edit_user_link . '" target="_blank"  class="button button--edit-link"><img src="' . plugin_dir_url( __FILE__ ) . 'images/icon.svg" alt="Click to edit"></a>';
        }
    }
}
new EditLink();