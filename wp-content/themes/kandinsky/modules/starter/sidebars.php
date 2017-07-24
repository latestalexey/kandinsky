<?php

class KND_StarterSidebars {
    
    public static function setup_footer_sidebar() {
        
        $sidebars = get_option( 'sidebars_widgets' );
//         print_r($sidebars['knd-footer-sidebar']);
        
        // empty sidebar
        $sidebars['knd-footer-sidebar'] = array();
        update_option( 'sidebars_widgets', $sidebars );
        
        $menu_name = __( 'Kandinsky our work footer menu', 'knd' );
        $our_work_menu = wp_get_nav_menu_object( $menu_name );
        
        $menu_name = __( 'Kandinsky news footer menu', 'knd' );
        $news_menu = wp_get_nav_menu_object( $menu_name );
        
        if(empty($footer_sidebar)) {
            
            $text_widgets = get_option('widget_text');
//             print_r($text_widgets);

            $home_url = home_url('/');
            $text_widget_text = <<<EOT
<p>
<a href="{$home_url}">
«Линия Цвета» - сообщество помощи людям,
<br/>
которые борются с алкогольной зависимостью
</a>
</p>
<p>
г. Псков, ул. Советская, д. 85
<br/>
<a href="tel:+7-111-172-20-88">+7 (111) 172-20-88</a>
</p>
EOT;
            $text_widgets[] = array('title' => __('About Us', 'knd'), 'text' => trim(preg_replace('/\r\n|\r|\n/', '', $text_widget_text)), 'filter' => 'content' );
            $text_widgets_keys = array_keys($text_widgets);
            $widget_index = end($text_widgets_keys);
            $sidebars['knd-footer-sidebar'][] = 'text-' . $widget_index;
            
            $nav_menu_widgets = get_option('widget_nav_menu');
//             print_r($nav_menu_widgets);
            
            if( $our_work_menu ) {
                $nav_menu_widgets[] = array('title' => __('Our Work', 'knd'), 'nav_menu' => $our_work_menu->term_id);
                $nav_menu_widgets_keys = array_keys($nav_menu_widgets);
                $widget_index = end($nav_menu_widgets_keys);
                $sidebars['knd-footer-sidebar'][] = 'nav_menu-' . $widget_index;
            }
            
            if( $news_menu ) {
                $nav_menu_widgets[] = array('title' => __('News', 'knd'), 'nav_menu' => $news_menu->term_id);
                $nav_menu_widgets_keys = array_keys($nav_menu_widgets);
                $widget_index = end($nav_menu_widgets_keys);
                $sidebars['knd-footer-sidebar'][] = 'nav_menu-' . $widget_index;
            }
            
            // save options permanently
            update_option( 'widget_nav_menu', $nav_menu_widgets );
            update_option( 'widget_text', $text_widgets );
            update_option( 'sidebars_widgets', $sidebars );
        }
        
//         print_r($sidebars['knd-footer-sidebar']);
    }
    
    public static function setup_homepage_sidebar() {
        
        $sidebars = get_option( 'sidebars_widgets' );
//         print_r($sidebars['knd-homepage-sidebar']);
        
        // empty sidebar
        $sidebars['knd-homepage-sidebar'] = array();
        update_option( 'sidebars_widgets', $sidebars );
        
        
        // add text on home
        $widgets = get_option('widget_knd_ourorg');
        //         print_r($widgets);
        
        $widgets[] = Array('title' => __('Our organization «Color Line»', 'knd'), 'text' => __('We have been helping people with alcoholism in our city over 10 years<br />by organizing rehabilitation programs.', 'knd') );
        $widgets_keys = array_keys($widgets);
        $widget_index = end($widgets_keys);
        $sidebars['knd-homepage-sidebar'][] = 'knd_ourorg-' . $widget_index;
        
        update_option( 'widget_knd_ourorg', $widgets );
                
        
        // add news on home
        $news_widgets = get_option('widget_knd_news');
//         print_r($news_widgets);
        
        $news_widgets[] = Array('title' => __('News', 'knd'), 'num' => 4 ); 
        $news_widgets_keys = array_keys($news_widgets);
        $widget_index = end($news_widgets_keys);
        $sidebars['knd-homepage-sidebar'][] = 'knd_news-' . $widget_index;
        
        update_option( 'widget_knd_news', $news_widgets );
        
        
        update_option( 'sidebars_widgets', $sidebars );
    }
}