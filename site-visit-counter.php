<?php
/*
Plugin Name: Site Visit Counter
Description: A simple plugin to count and display site visits on the homepage with an animation.
Version: 1.2
Author: Your Name
*/

// Hook to initialize the plugin
add_action('init', 'svc_initialize_counter');

// Hook to display the counter on the homepage
add_action('wp_footer', 'svc_display_counter');

// Hook to add settings menu
add_action('admin_menu', 'svc_add_settings_menu');

// Register settings
add_action('admin_init', 'svc_register_settings');

// Function to initialize the counter
function svc_initialize_counter() {
    if (!get_option('svc_site_visit_count')) {
        add_option('svc_site_visit_count', 0);
    }
    if (!get_option('svc_display_settings')) {
        add_option('svc_display_settings', array(
            'background_color' => '#f1f1f1',
            'text_color' => '#000000',
            'position' => 'bottom-right',
        ));
    }
}

// Function to increment the counter
function svc_increment_counter() {
    $count = get_option('svc_site_visit_count');
    $count++;
    update_option('svc_site_visit_count', $count);
}

// Function to display the counter
function svc_display_counter() {
    if (is_front_page()) {
        $count = get_option('svc_site_visit_count');
        $settings = get_option('svc_display_settings');
        
        $position_styles = '';
        switch ($settings['position']) {
            case 'top-left':
                $position_styles = 'top: 10px; left: 10px;';
                break;
            case 'top-right':
                $position_styles = 'top: 10px; right: 10px;';
                break;
            case 'bottom-left':
                $position_styles = 'bottom: 10px; left: 10px;';
                break;
            case 'bottom-right':
                $position_styles = 'bottom: 10px; right: 10px;';
                break;
        }

        echo '<div id="site-visit-counter" style="background-color: ' . esc_attr($settings['background_color']) . '; color: ' . esc_attr($settings['text_color']) . '; position: fixed; ' . $position_styles . ' padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; display: none;">';
        echo 'This site has been visited <span id="site-visit-count">' . $count . '</span> times.';
        echo '</div>';
        
        // Enqueue jQuery if not already loaded
        wp_enqueue_script('jquery');
        // Enqueue custom script for the animation
        wp_enqueue_script('svc-counter-animation', plugins_url('svc-counter-animation.js', __FILE__), array('jquery'), false, true);
    }
}

// Increment counter on every page load
add_action('wp', 'svc_increment_counter');

// Function to add settings menu
function svc_add_settings_menu() {
    add_options_page(
        'Site Visit Counter Settings',
        'Site Visit Counter',
        'manage_options',
        'svc-settings',
        'svc_settings_page'
    );
}

// Function to register settings
function svc_register_settings() {
    register_setting('svc_settings_group', 'svc_display_settings');
}

// Function to render settings page
function svc_settings_page() {
    ?>
    <div class="wrap">
        <h1>Site Visit Counter Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('svc_settings_group'); ?>
            <?php $settings = get_option('svc_display_settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Background Color</th>
                    <td><input type="text" name="svc_display_settings[background_color]" value="<?php echo esc_attr($settings['background_color']); ?>" class="my-color-field" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Text Color</th>
                    <td><input type="text" name="svc_display_settings[text_color]" value="<?php echo esc_attr($settings['text_color']); ?>" class="my-color-field" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Position</th>
                    <td>
                        <select name="svc_display_settings[position]">
                            <option value="top-left" <?php selected($settings['position'], 'top-left'); ?>>Top Left</option>
                            <option value="top-right" <?php selected($settings['position'], 'top-right'); ?>>Top Right</option>
                            <option value="bottom-left" <?php selected($settings['position'], 'bottom-left'); ?>>Bottom Left</option>
                            <option value="bottom-right" <?php selected($settings['position'], 'bottom-right'); ?>>Bottom Right</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Enqueue color picker
add_action('admin_enqueue_scripts', 'svc_enqueue_color_picker');
function svc_enqueue_color_picker($hook_suffix) {
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('my-script-handle', plugins_url('my-script.js', __FILE__), array('wp-color-picker'), false, true);
}

// JavaScript for color picker
add_action('admin_footer', 'svc_color_picker_script');
function svc_color_picker_script() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.my-color-field').wpColorPicker();
        });
    </script>
    <?php
}
?>
