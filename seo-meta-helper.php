<?php
/**
 * Plugin Name: SEO MetaMate
 * Description:  A simple plugin to add SEO meta title and description to posts/pages with light/dark theme toggle.
 * Version: 1.0
 * Author: Syeda Tanjina
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue CSS and JS
function smm_enqueue_assets() {
    wp_enqueue_style('seometa-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('seometa-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'smm_enqueue_assets');

// Add meta box 
function smm_add_meta_box() {
    add_meta_box(
        'seometa-meta-box',
        'SEO MetaMate',
        'smm_render_meta_box',
        ['post', 'page'], // Add more types if needed
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'smm_add_meta_box');

// Render meta box HTML
function smm_render_meta_box($post) {
    $meta_title = get_post_meta($post->ID, '_seometa_title', true);
    $meta_description = get_post_meta($post->ID, '_seometa_description', true);
    ?>
    <div class="seometa-wrapper" id="seometa-theme">
        <!-- <h2 class="seometa-title">SEO MetaMate</h2> -->
        <div class="seometa-field">
            <label>Meta Title:</label>
            <input type="text" name="seometa_title" value="<?php echo esc_attr($meta_title); ?>" />
        </div>
        <div class="seometa-field">
            <label>Meta Description:</label>
            <textarea name="seometa_description"><?php echo esc_textarea($meta_description); ?></textarea>
        </div>
        <button type="button" id="theme-toggle-btn">Dark</button>
    </div>
    <?php
}

// Save meta data
function smm_save_post($post_id) {
    if (array_key_exists('seometa_title', $_POST)) {
        update_post_meta($post_id, '_seometa_title', sanitize_text_field($_POST['seometa_title']));
    }
    if (array_key_exists('seometa_description', $_POST)) {
        update_post_meta($post_id, '_seometa_description', sanitize_textarea_field($_POST['seometa_description']));
    }
}
add_action('save_post', 'smm_save_post');

// Output meta tags 
function smm_output_meta_tags() {
    if (is_singular()) {
        global $post;
        $smm_title = get_post_meta($post->ID, '_seometa_title', true);
        $smm_desc = get_post_meta($post->ID, '_seometa_description', true);

        if ($smm_title) {
            echo '<meta name="title" content="' . esc_attr($smm_title) . '">' . "\n";
        }
        if ($smm_desc) {
            echo '<meta name="description" content="' . esc_attr($smm_desc) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'smm_output_meta_tags');
