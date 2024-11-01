<?php
/**
 * Course content template
 *
 * @package DTLMSCourseContent
 */

global $post;
$post = get_post( $args['course'] );
setup_postdata( $post );

$course_nav_items = apply_filters( 'tutor_course/single/nav_items', tutor_utils()->course_nav_items(), $args['course'] );

add_filter(
	'tutor_default_topics_active_tab',
	function() {
		return 'info';
	}
);
?>
<div class="tutor-wrap dtlms-course-curriculum">
		<?php do_action( 'tutor_course/single/before/inner-wrap' ); ?>
		<div class="tutor-course-details-tab tutor-mt-32">
			<?php tutor_load_template( 'single.course.enrolled.nav', array( 'course_nav_item' => $course_nav_items ) ); ?>
			<div class="tutor-tab tutor-pt-24">
				<?php
				foreach ( $course_nav_items as $key => $subpage ) {
					?>
						<div class="tutor-tab-item <?php echo esc_attr( 'info' === $key ? 'is-active' : '' ); ?>" id="tutor-course-details-tab-<?php echo esc_attr( $key ); ?>">
						<?php
						$method = $subpage['method'];
						if ( 'info' === $key ) {
							tutor_load_template_from_custom_path(
								dtlms_get_template( 'course/about' ),
								array( 'post_id' => $args['course'] ),
								false
							);
							include dtlms_get_template( 'course/benefits' );
							if ( function_exists( 'tutor_course_topics' ) ) {
								tutor_course_topics();
							}
						} elseif ( 'reviews' === $key ) {
							tutor_load_template_from_custom_path(
								dtlms_get_template( 'course/curriculum/reviews' ),
								array( 'post_id' => $args['course'] ),
								false
							);
						} else {
							if ( is_string( $method ) ) {
								$method();
							} else {
								$_object = $method[0];
								$_method = $method[1];
								$_object->$_method( get_the_ID() );
							}
						}
						?>
						</div>
						<?php
				}
				?>
			</div>
		</div>
		<?php do_action( 'tutor_course/single/after/inner-wrap' ); ?>
</div>

