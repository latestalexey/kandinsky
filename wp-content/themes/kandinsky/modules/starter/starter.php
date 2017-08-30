<?php if( !defined('WPINC') ) die;

require get_template_directory().'/modules/starter/class-demo.php';
require get_template_directory().'/modules/starter/menus.php';
require get_template_directory().'/modules/starter/sidebars.php';
require get_template_directory().'/vendor/parsedown/Parsedown.php';
require get_template_directory().'/modules/starter/plot_data_builder.php';
require get_template_directory().'/modules/starter/import_remote_content.php';

function knd_import_starter_data_from_csv($file, $post_type = 'post') {

    $input_file = get_template_directory() . '/modules/starter/csv/' . $file;
    knd_import_posts_from_csv($input_file, $post_type);

}

function knd_update_posts() {

    global $wpdb;
    
    // set thumbnail for sample page
    $thumb_id = false;
    $thumbnail_url = 'https://ngo2.ru/kandinsky-files/knd-img2.jpg';
    if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
        $thumb_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
    }
    if($thumb_id) {
        $hello_world_posts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_name IN (%s, %s) LIMIT 1", 'hello-world', '%d0%bf%d1%80%d0%b8%d0%b2%d0%b5%d1%82-%d0%bc%d0%b8%d1%80'));
        foreach($hello_world_posts as $hello_world_post) {
            update_post_meta( $hello_world_post->ID, '_thumbnail_id', $thumb_id );
        }
    }
    
}

function knd_setup_site_icon() {
    
    if(has_site_icon()) {
        return;
    }
    
    $site_icon_id = false;
    $thumbnail_url = 'https://ngo2.ru/kandinsky-files/favicon-small.png';
    
    if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
        $site_icon_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
    }
    
    if($site_icon_id) {
        update_option('site_icon', $site_icon_id);
    }

}

function knd_set_sitename_settings($scenario_data) {
    switch(get_theme_mod('knd_site_scenario')) {

        case 'problem-oriented-org':
            update_option('blogname', $scenario_data['name']);
            update_option('blogdescription', $scenario_data['description']);
            break;
        case 'fundraising-oriented-org':
            update_option('blogname', $scenario_data['name']);
            update_option('blogdescription', $scenario_data['description']);
            break;
        case 'public-campaign':
            update_option('blogname', $scenario_data['name']);
            update_option('blogdescription', $scenario_data['description']);
            break;

        default:
    }
}

function knd_set_theme_options() {
    $thumb_id = TST_Import::get_instance()->maybe_import( 'https://ngo2.ru/kandinsky-files/knd-img1.jpg' );
    
    if($thumb_id) {
        set_theme_mod('knd_hero_image', $thumb_id);
    }

    set_theme_mod('knd_hero_image_support_title', 'Помоги бороться с алкогольной зависимостью!');
    set_theme_mod('knd_hero_image_support_url', get_permalink(get_page_by_path('donate')));
    set_theme_mod('knd_hero_image_support_text', 'В Нашей области 777 человек, которые страдают от алкогольной зависимости. Ваша поддержка поможет организовать для них реабилитационную программу.');
    set_theme_mod('knd_hero_image_support_button_caption', 'Помочь сейчас');
}

function knd_set_theme_options_from_import($imp) {
    $hero_img_data = $imp->get_fdata('5.jpg', 'img');
    if($hero_img_data && isset($hero_img_data['attachment_id']) && $hero_img_data['attachment_id']) {
        set_theme_mod('knd_hero_image', $hero_img_data['attachment_id']);
    }
}

function knd_setup_menus() {

    KND_StarterMenus::knd_setup_our_work_menu();
    KND_StarterMenus::knd_setup_news_menu();

    KND_StarterSidebars::setup_footer_sidebar();
    KND_StarterSidebars::setup_homepage_sidebar();

}

function knd_setup_starter_data($plot_name) {
    
    $imp = new KND_Import_Remote_Content($plot_name);
    $data = $imp->import_content();
    
//     print_r($data);
//     exit();
    
//     $piece = $imp->get_piece('footer');
//     var_dump($piece); echo "\n<br />\n";
//     $title = $imp->get_val('article1', 'title', 'articles');
//     var_dump($title); echo "\n<br />\n";
//     exit();
    
    $pdb = KND_Plot_Data_Builder::produce_builder($imp);
    $pdb->build_all();
    
    knd_update_posts();

    knd_set_theme_options();
    knd_set_theme_options_from_import($imp);

    do_action('knd_save_demo_content');

    knd_setup_menus();  // all menus except main nav menu

    knd_setup_site_icon();
}

function knd_ajax_setup_starter_data() {

    $res = array('status' => 'ok');

    $plot_name = get_theme_mod('knd_site_scenario'); // color-line, withyou, dubrovino

    if($plot_name) {
        try {
            knd_setup_starter_data($plot_name);
        } catch(Exception $ex) {
            error_log($ex);
            $res = array('status' => 'error');
        }
    }

    wp_send_json($res);

}
add_action('wp_ajax_setup_starter_data', 'knd_ajax_setup_starter_data');
