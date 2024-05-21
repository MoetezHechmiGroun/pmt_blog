<?php
/* Template Name: Pimper Details */

get_header();

$pimper_id = isset($_GET['pimpeur_id']) ? intval($_GET['pimpeur_id']) : 0;
$pimper = null;

if ($pimper_id) {
    $pmt_db = pmt_db_connect();
    if ($pmt_db) {
        $pimper = $pmt_db->get_row($pmt_db->prepare("SELECT * FROM pimper WHERE pimperID = %d", $pimper_id));
        if ($pimper) {
            $pimper->user = $pmt_db->get_row($pmt_db->prepare("SELECT * FROM user WHERE userID = %d", $pimper->userID));
            $pimper->rates = get_pimper_rates($pimper->pimperID);
        }
    }
}

if ($pimper):
    $category_labels = get_category_labels();
    $category_colors = get_category_colors();
    $image_url = (!empty($pimper->user->photoProfile) && strpos($pimper->user->photoProfile, 'https') === 0) ? $pimper->user->photoProfile : $default_image_url;
?>

<div class="pimper-details">
    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($pimper->pseudo); ?>">
    <h1><?php echo esc_html($pimper->pseudo); ?></h1>

    <?php if (!empty($pimper->thematics)): ?>
        <div class="pimper-thematics">
            <?php foreach (json_decode($pimper->thematics) as $id):
                if (isset($category_labels[$id])) {
                    $category_label = $category_labels[$id];
                    $category_color = isset($category_colors[$category_label]) ? $category_colors[$category_label] : '#000';
                    echo '<span class="pimper-category" style="background-color: ' . esc_attr($category_color) . ';">' . esc_html($category_label) . '</span>';
                }
            endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($pimper->rates)): 
        $average_rating = array_sum(array_column($pimper->rates, 'value')) / count($pimper->rates); ?>
        <div class="pimper-rates">
            <span class="rate-value">
                <i class="fas fa-star"></i> <?php echo number_format($average_rating, 1); ?>/5 (<?php echo count($pimper->rates); ?> avis)
            </span>
        </div>
    <?php else: ?>
        <p class="no-rates">Aucun avis pour ce pimpeur</p>
    <?php endif; ?>

    <div class="pimper-description">
        <?php echo wpautop($pimper->introduction); ?>
    </div>
</div>

<?php else: ?>
    <p>Pimper not found.</p>
<?php endif; ?>

<?php get_footer(); ?>
