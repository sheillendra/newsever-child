<?php
    /**
     * Functions which enhance the theme by hooking into WordPress
     *
     * @package Newsever
     */
    
    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     *
     * @return array
     */
    function newsever_child_body_classes($classes)
    {
        // Adds a class of hfeed to non-singular pages.
        if (!is_singular()) {
            $classes[] = 'hfeed';
        }
        
        
        $global_site_mode_setting = newsever_get_option('global_site_mode_setting');
        $classes[] = $global_site_mode_setting;
        
        
        $single_post_featured_image_view = newsever_get_option('single_post_featured_image_view');
        if ($single_post_featured_image_view == 'full') {
            $classes[] = 'aft-single-full-header';
        }

        if ($single_post_featured_image_view == 'within-content'){
            $classes[] = 'aft-single-within-content-header';
        }
        
        $global_hide_comment_count_in_list = newsever_get_option('global_hide_comment_count_in_list');
        if ($global_hide_comment_count_in_list == true) {
            $classes[] = 'aft-hide-comment-count-in-list';
        }
        
        $global_hide_min_read_in_list = newsever_get_option('global_hide_min_read_in_list');
        if ($global_hide_min_read_in_list == true) {
            $classes[] = 'aft-hide-minutes-read-in-list';
        }
        
        
        $global_hide_post_date_author_in_list = newsever_get_option('global_hide_post_date_author_in_list');
        if ($global_hide_post_date_author_in_list == true) {
            $classes[] = 'aft-hide-date-author-in-list';
        }
        
        $transparent_main_banner_boxes = newsever_get_option('transparent_main_banner_boxes');
        if ($transparent_main_banner_boxes == true) {
            $classes[] = 'aft-transparent-main-banner-box';
        }
        
        global $post;
        
        $global_layout = newsever_get_option('global_content_layout');
        if (!empty($global_layout)) {
            $classes[] = $global_layout;
        }
        
        
        $global_alignment = newsever_get_option('global_content_alignment');
        $page_layout = $global_alignment;
        $disable_class = '';
        $frontpage_content_status = newsever_get_option('frontpage_content_status');
        if (1 != $frontpage_content_status) {
            $disable_class = 'disable-default-home-content';
        }
        
        // Check if single.
        if ($post && is_singular()) {
            $post_options = get_post_meta($post->ID, 'newsever-meta-content-alignment', true);
            if (!empty($post_options)) {
                $page_layout = $post_options;
            } else {
                $page_layout = $global_alignment;
            }
        }
        
        
        if (is_front_page() || is_home() || is_page_template('tmpl-front-page.php')) {
            $frontpage_layout = newsever_get_option('frontpage_content_alignment');
            
            if (!empty($frontpage_layout)) {
                $page_layout = $frontpage_layout;
            } else {
                $page_layout = $global_alignment;
            }
            
        }
        
        
        if (is_front_page() && is_active_sidebar('home-content-widgets')) {
            
            if (is_active_sidebar('home-content-widgets') && is_active_sidebar('home-sidebar-1-widgets') && is_active_sidebar('home-sidebar-2-widgets')) {
                if ($page_layout == 'frontpage-layout-1') {
                    $classes[] = 'aft-frontpage-template content-with-two-sidebars ' . $page_layout;
                    
                }
                if ($page_layout == 'frontpage-layout-2') {
                    $classes[] = 'aft-frontpage-template content-with-two-sidebars ' . $page_layout;
                    
                }
                if ($page_layout == 'frontpage-layout-3') {
                    $classes[] = 'aft-frontpage-template ' . $page_layout;
                }
            } else {
                
                if (is_active_sidebar('home-content-widgets') && is_active_sidebar('home-sidebar-1-widgets')) {
                    $classes[] = 'aft-frontpage-template content-with-single-sidebar content-with-right-single-sidebar' . " " . $page_layout;
                } elseif (is_active_sidebar('home-content-widgets') && is_active_sidebar('home-sidebar-2-widgets')) {
                    
                    $classes[] = 'aft-frontpage-template content-with-single-sidebar content-with-left-single-sidebar' . " " . $page_layout;
                } else {
                    $classes[] = 'full-width-content ';
                }
            }
        } else if (is_page_template('tmpl-front-page.php')) {
            
            if (is_active_sidebar('home-content-widgets') && is_active_sidebar('home-sidebar-1-widgets') && is_active_sidebar('home-sidebar-2-widgets')) {
                
                if ($page_layout == 'frontpage-layout-1') {
                    
                    $classes[] = 'aft-frontpage-template content-with-two-sidebars ' . $page_layout;
                    
                }
                if ($page_layout == 'frontpage-layout-2') {
                    
                    $classes[] = 'aft-frontpage-template content-with-two-sidebars ' . $page_layout;
                    
                }
                if ($page_layout == 'frontpage-layout-3') {
                    
                    $classes[] = 'aft-frontpage-template ' . $page_layout;
                }
            } else {
                
                if (is_active_sidebar('home-content-widgets') && is_active_sidebar('home-sidebar-1-widgets')) {
                    $classes[] = 'aft-frontpage-template content-with-single-sidebar content-with-right-single-sidebar' . " " . $page_layout;
                } elseif (is_active_sidebar('home-content-widgets') && is_active_sidebar('home-sidebar-2-widgets')) {
                    
                    $classes[] = 'aft-frontpage-template content-with-single-sidebar content-with-left-single-sidebar' . " " . $page_layout;
                } else {
                    $classes[] = 'full-width-content ';
                }
            }
        } else {
            if (is_front_page() || is_home()) {
                if ($page_layout == 'frontpage-layout-1') {
                    if (is_active_sidebar('sidebar-1')) {
                        $classes[] = 'content-with-single-sidebar align-content-left';
                    } else {
                        $classes[] = 'full-width-content';
                    }
                    
                } elseif ($page_layout == 'frontpage-layout-2') {
                    if (is_active_sidebar('sidebar-1')) {
                        $classes[] = 'content-with-single-sidebar align-content-right';
                    } else {
                        $classes[] = 'full-width-content';
                    }
                } else {
                    $classes[] = 'full-width-content';
                }
            } else {
                
                if ($page_layout == "align-content-left") {
                    if (is_active_sidebar('sidebar-1')) {
                        $classes[] = 'content-with-single-sidebar align-content-left';
                    } else {
                        $classes[] = 'full-width-content';
                    }
                } elseif ($page_layout == 'align-content-right') {
                    if (is_active_sidebar('sidebar-1')) {
                        $classes[] = 'content-with-single-sidebar align-content-right';
                    } else {
                        $classes[] = 'full-width-content';
                    }
                    
                } else {
                    $classes[] = 'full-width-content';
                }
            }
        }
        
        return $classes;
        
    }
    
    add_filter('body_class', 'newsever_child_body_classes');
    