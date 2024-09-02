<?php
/**
 * Plugin Name: SCB - Sticky Chat Button
 * Plugin URI: https://github.com/carmelyne/scb-sticky-chat-button
 * Description: Adds a customizable sticky chat FB Messenger Button in a bubble style to your WordPress site.
 * Version: 1.0.0
 * Author: Carmelyne M. Thompson
 * Author URI: https://carmelyne.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include WordPress GitHub Plugin Updater
if (!class_exists('Smashing_Updater')) {
    include_once(plugin_dir_path(__FILE__) . 'updater.php');
}

class SCB_Sticky_Chat_Button {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_button'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));

        // Initialize GitHub Updater
        if (class_exists('Smashing_Updater')) {
            $updater = new Smashing_Updater(__FILE__);
            $updater->set_username('yourusername');
            $updater->set_repository('scb-sticky-chat-button');
            $updater->initialize();
        }
    }

    public function enqueue_scripts() {
        wp_enqueue_style('scb-sticky-chat-button', plugin_dir_url(__FILE__) . 'css/scb-sticky-chat-button.css', array(), '1.0.0');
        wp_enqueue_script('scb-sticky-chat-button', plugin_dir_url(__FILE__) . 'js/scb-sticky-chat-button.js', array('jquery'), '1.0.0', true);
    }

    public function render_button() {
        $button_url = get_option('scb_button_url', '#');
        $bubble_color = get_option('scb_bubble_color', '#0084ff');
        $background_color = get_option('scb_background_color', '#ffffff');
    
        echo '<div id="scb-sticky-chat-button-wrapper">';
        echo '<a href="' . esc_url($button_url) . '" target="_blank" id="scb-sticky-chat-button" style="background-color: ' . esc_attr($background_color) . ';">';
        echo '<svg width="36" height="36" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="' . esc_attr($bubble_color) . '">';
        echo '<path d="M12 0C5.373 0 0 4.975 0 11.111c0 3.497 1.745 6.616 4.472 8.652V24l4.086-2.242c1.09.301 2.246.464 3.442.464 6.627 0 12-4.974 12-11.111C24 4.975 18.627 0 12 0zm1.193 14.963l-3.056-3.259-5.963 3.259 6.559-6.963 3.13 3.259 5.889-3.259-6.559 6.963z"/>';
        echo '</svg>';
        echo '</a>';
        echo '</div>';
    }

    public function add_admin_menu() {
        add_options_page('SCB - Sticky Chat Button Settings', 'SCB - Sticky Chat Button', 'manage_options', 'scb-sticky-chat-button', array($this, 'settings_page'));
    }

    public function register_settings() {
        register_setting('scb_settings_group', 'scb_button_url');
        register_setting('scb_settings_group', 'scb_bubble_color');
        register_setting('scb_settings_group', 'scb_background_color');
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>SCB - Sticky Chat Button Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('scb_settings_group'); ?>
                <?php do_settings_sections('scb_settings_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Button URL</th>
                        <td><input type="text" name="scb_button_url" value="<?php echo esc_attr(get_option('scb_button_url')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Bubble Color</th>
                        <td><input type="color" name="scb_bubble_color" value="<?php echo esc_attr(get_option('scb_bubble_color', '#0084ff')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Background Color</th>
                        <td><input type="color" name="scb_background_color" value="<?php echo esc_attr(get_option('scb_background_color', '#ffffff')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

// Initialize the plugin
new SCB_Sticky_Chat_Button();

// Activation hook
register_activation_hook(__FILE__, 'scb_sticky_chat_button_activate');
function scb_sticky_chat_button_activate() {
    // Set default options
    add_option('scb_button_url', '#');
    add_option('scb_bubble_color', '#0084ff');
    add_option('scb_background_color', '#ffffff');
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'scb_sticky_chat_button_deactivate');
function scb_sticky_chat_button_deactivate() {
    // Clean up options if needed
    // delete_option('scb_button_url');
    // delete_option('scb_bubble_color');
    // delete_option('scb_background_color');
}