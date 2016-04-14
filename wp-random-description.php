<?php

/*
 *  Plugin Name: WP Random Description
 *  Plugin URI:
 *  Description: Display a random site description from a list.
 *  Version: 0.1.0
 *  Author: Peter Grassberger
 *  Author URI: http://petergrassberger.com
 *  License: MIT
 */

function wp_random_description_install() {
    global $wpdb;

    $tableName = $wpdb->prefix . 'random_descriptions';

    $charsetCollate = $wpdb->get_charset_collate();

    $sql = 'CREATE TABLE ' . $tableName . ' (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      time datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
      username tinytext NOT NULL,
      text text NOT NULL,
      UNIQUE KEY id (id)
    ) ' . $charsetCollate . ';';

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

register_activation_hook( __FILE__, 'wp_random_description_install' );






// add the admin options page
add_action('admin_menu', 'wp_random_description_admin_add_page');

function wp_random_description_admin_add_page() {
    add_options_page('WP Random Description', 'WP Random Description', 'manage_options', 'wp_random_description', 'wp_random_description_options_page');
}

// display the admin options page
function wp_random_description_options_page() {
    ?>
    <div>
        <h1>WP Random Description Settings</h1>
        <p>
            bla
        </p>

        <form action="options.php" method="post">
            <?php settings_fields('wp_random_description_options'); ?>
            <?php do_settings_sections('wp_random_description'); ?>



            <p class="submit">
                <input type="text" name="text" />
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Add'); ?>"  />
            </p>
        </form>
    </div>
    <?php
}

function wp_random_description_section_text() {
    echo '<p>bla</p>';
}

function wp_random_description_table() {
    global $wpdb;
    $results = $wpdb->get_results('SELECT * FROM '. $wpdb->prefix .'random_descriptions');
?>

    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>time</th>
                <th>username</th>
                <th>text</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $row) { ?>
            <tr>
                <td><?php echo $row->id; ?></td>
                <td><?php echo $row->time; ?></td>
                <td><?php echo $row->username; ?></td>
                <td><?php echo $row->text; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php
}

// add the admin settings and such
add_action('admin_init', 'wp_random_description_admin_init');
function wp_random_description_admin_init() {
    register_setting('wp_random_description_options', 'wp_random_description_options', 'wp_random_description_options_validate');

    add_settings_section('wp_random_description_section', 'Random Descriptions', 'wp_random_description_section_text', 'wp_random_description');
    add_settings_field('wp_random_description_table', 'Something something', 'wp_random_description_table', 'wp_random_description', 'wp_random_description_section');
}

// validate our options
function wp_random_description_options_validate($input) {
    // FIXME throw an error, not just return an empty string
    /*$newinput['api_url_string'] = esc_url($input['api_url_string'], array('http', 'https'));
    $newinput['textstatus_open_string']   = $input['textstatus_open_string']; //FIXME validate
    $newinput['textstatus_closed_string'] = $input['textstatus_closed_string']; //FIXME validate
    $newinput['textstatus_unknown_string'] = $input['textstatus_unknown_string']; //FIXME validate
    $newinput['use_spaceapi_icons'] = $input['use_spaceapi_icons']; //FIXME validate
    $newinput['icon_open_url']    = $input['icon_open_url']; //FIXME validate
    $newinput['icon_closed_url']  = $input['icon_closed_url']; //FIXME validate
    $newinput['icon_unknown_url'] = $input['icon_unknown_url']; //FIXME validate

    $newinput['lastchange_date_format'] = $input['lastchange_date_format']; //FIXME validate
    return $newinput;*/
}








$descriptionList = array(
    'test1',
    'test2',
    'test3'
);

function wp_random_description($value) {
    global $descriptionList;

    $randomIndex = rand(0, count($descriptionList) - 1);
    return $descriptionList[$randomIndex];
}

add_filter('option_blogdescription', 'wp_random_description', 10, 1);
