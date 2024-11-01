<?php
/**
 * Template for displaying course audience
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package Tutor Divi Modules
 */

defined( 'ABSPATH' ) || exit;

do_action( 'tutor_course/single/before/audience' );

$target_audience = tutor_course_target_audience( $args['course'] );
$icon            = et_pb_process_font_icon( $args['icon'] );
if ( empty( $target_audience ) ) {
	return;
}

if ( is_array( $target_audience ) && count( $target_audience ) ) {
	?>

	<div class="tutor-single-course-segment  tutor-course-target-audience-wrap">

		<h4 class="tutor-segment-title tutor-color-text-primary tutor-text-medium-h6"><?php echo esc_html( $args['label'] ); ?></h4>

		<div class="tutor-course-target-audience-content">
			<ul class="tutor-course-target-audience-items">
				<?php
				foreach ( $target_audience as $audience ) {
					echo "<li class='tutor-color-text-primary tutor-text-regular-body tutor-mb-10'> <span class='et-pb-icon tutor-color-design-brand'> " . esc_html( $icon ) . " </span> <span class='list-item'>" . esc_html( $audience ) . '</span></li>';
				}
				?>
			</ul>
		</div>

	</div>

<?php } ?>

<?php do_action( 'tutor_course/single/after/audience' ); ?>
