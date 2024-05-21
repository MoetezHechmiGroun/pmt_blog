<?php
/*
Plugin Name: PMT Connector
Description: Connect to external PMT database and fetch data
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

function get_category_labels() {
    return [
        1 => 'Nature',
        2 => 'Photographie',
        3 => 'Sport',
        4 => 'Shopping',
        5 => 'Gastronomie',
        6 => 'Festif',
        7 => 'Mode',
        8 => 'Romantique',
        9 => 'Incontournable',
        10 => 'Insolite',
        11 => 'Musique',
        12 => 'Art & Design',
        13 => 'Histoire',
        14 => 'Cinéma',
        15 => 'Bien-être'
    ];
}





function pmt_connector_enqueue_styles() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
    wp_enqueue_style('pimper-style', plugin_dir_url(__FILE__) . 'pimper-style.css');
}
add_action('wp_enqueue_scripts', 'pmt_connector_enqueue_styles');


function get_pimper_introduction($pimperID) {
    $pmt_db = pmt_db_connect();
    if ($pmt_db && !empty($pimperID)) {
        $introduction = $pmt_db->get_var($pmt_db->prepare("SELECT introduction FROM pimper WHERE pimperID = %d", $pimperID));
        return $introduction;
    } else {
        return '';
    }
}
function get_category_colors() {
    return [
        'Nature' => '#a3d977',
        'Photographie' => '#f29b34',
        'Sport' => '#ff6347',
        'Shopping' => '#ff1493',
        'Gastronomie' => '#ffa07a',
        'Festif' => '#8a2be2',
        'Mode' => '#ff69b4',
        'Romantique' => '#ffb6c1',
        'Incontournable' => '#00ced1',
        'Insolite' => '#daa520',
        'Musique' => '#9acd32',
        'Art & Design' => '#cd5c5c',
        'Histoire' => '#4682b4',
        'Cinéma' => '#ff4500',
        'Bien-être' => '#3cb371'
    ];
}

function get_pimper_rates($pimperID) {
    $pmt_db = pmt_db_connect();
    if ($pmt_db) {
        $results = $pmt_db->get_results($pmt_db->prepare("SELECT * FROM rate WHERE pimperID = %d", $pimperID));
        return $results;
    } else {
        return [];
    }
}



function pmt_db_connect() {
    $pmt_db = new wpdb(PMT_DB_USER, PMT_DB_PASSWORD, PMT_DB_NAME, PMT_DB_HOST);

    if ($pmt_db->has_cap('collation')) {
        error_log('Connected to PMT database');
        return $pmt_db;
    } else {
        error_log('Failed to connect to PMT database');
        return null;
    }
}

function get_pimpeurs($limit, $offset) {
    $pmt_db = pmt_db_connect();
    if ($pmt_db) {
        $results = $pmt_db->get_results($pmt_db->prepare("SELECT * FROM pimper LIMIT %d OFFSET %d", $limit, $offset));
        foreach ($results as $result) {
            $user = $pmt_db->get_row($pmt_db->prepare("SELECT * FROM user WHERE userID = %d", $result->userID));
            $result->user = $user;
        }
        return $results;
    } else {
        return [];
    }
}

function get_total_pimpeurs() {
    $pmt_db = pmt_db_connect();
    if ($pmt_db) {
        $count = $pmt_db->get_var("SELECT COUNT(*) FROM pimper");
        return $count;
    } else {
        return 0;
    }
}

function get_pimper_activity_count($pimperID) {
    $pmt_db = pmt_db_connect();
    if ($pmt_db && !empty($pimperID)) {
        $count = $pmt_db->get_var($pmt_db->prepare("SELECT COUNT(*) FROM activity WHERE pimperID = %d", $pimperID));
        return $count;
    } else {
        return 0;
    }
}

function display_pimpeurs() {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $per_page = 12; 
    $offset = ($paged - 1) * $per_page;

    $pimpeurs = get_pimpeurs($per_page, $offset);
    $total_pimpeurs = get_total_pimpeurs(); 

    if (empty($pimpeurs)) {
        return '<p>Aucun pimpeur trouvé</p>';
    }

    $default_image_url = 'https://s3.eu-west-3.amazonaws.com/pimpmytrip.travel/pimpers/original/ebb2b0d6-ca63-4f35-a93c-d10485ce9b5e.jpeg';
    $category_labels = get_category_labels();
    $category_colors = get_category_colors();

    $output = '<div class="pimpeur-grid">';
    foreach ($pimpeurs as $pimper) {
        if (empty($pimper->pimperID)) {
            continue; 
        }

        $image_url = (!empty($pimper->user->photoProfile) && strpos($pimper->user->photoProfile, 'https') === 0) ? $pimper->user->photoProfile : $default_image_url;
        $output .= '<div class="pimpeur-card">';
        $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($pimper->pseudo) . '">';
        $output .= '<h2><a href="http://localhost:8888/wordpress/pimper_details/?pimpeur_id=' . $pimper->pimperID . '">' . esc_html($pimper->pseudo) . '</a></h2>';
        
        $thematics = json_decode($pimper->thematics);
        if (!empty($thematics)) {
            $output .= '<div class="pimper-thematics">';
            foreach ($thematics as $id) {
                if (isset($category_labels[$id])) {
                    $category_label = $category_labels[$id];
                    $category_color = isset($category_colors[$category_label]) ? $category_colors[$category_label] : '#000';
                    $output .= '<span class="pimper-category" style="background-color: ' . esc_attr($category_color) . ';">' . esc_html($category_label) . '</span>';
                }
            }
            $output .= '</div>';
        }

        $rates = get_pimper_rates($pimper->pimperID);
        if (!empty($rates)) {
            $average_rating = array_sum(array_column($rates, 'value')) / count($rates);
            $output .= '<div class="pimper-rates">';
            $output .= '<span class="rate-value">';
            $output .= '<i class="fas fa-star"></i> ' . number_format($average_rating, 1) . '/5 (' . count($rates) . ' avis)';
            $output .= '</span>';
            $output .= '</div>';
       
        } else {
            $output .= '<p class="no-rates">Aucun avis pour ce pimpeur</p>';
        }

    

        

       

        $output .= '</div>';
        
    }
    $output .= '</div>';

    $output .= '<div class="pagination">';
    $output .= paginate_links(array(
        'base' => get_pagenum_link(1) . '%_%',
        'format' => '?paged=%#%',
        'current' => $paged,
        'total' => ceil($total_pimpeurs / $per_page),
        'prev_text' => __('« Précédent'),
        'next_text' => __('Suivant »'),
    ));
    $output .= '</div>';

    return $output;
}

add_shortcode('pimpeurs', 'display_pimpeurs');



function display_activities() {
    $activities = get_activities();
    if (empty($activities)) {
        return '<p>No activities found</p>';
    }
    $output = '<ul>';
    foreach ($activities as $activity) {
        $output .= '<li>' . esc_html($activity->title) . '</li>';
    }
    $output .= '</ul>';
    return $output;
}
add_shortcode('activities', 'display_activities');
