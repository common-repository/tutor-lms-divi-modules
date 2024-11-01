<?php
/**
 * Template for displaying single course
 *
 * @since v.1.0.0
 *
 * @author Themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

$topics = tutor_utils()->get_topics( $args['course'] );
$course_id = $args['course'];
$is_enrolled = tutor_utils()->is_enrolled($course_id);
$index = 0;

do_action('tutor_course/single/before/topics');
?>

<div class="tutor-course-topics-header">
	<div class="tutor-course-topics-header-left">
		<div class="text-medium-h6 color-text-primary">
			<span>
				<?php
					$title = __( 'Course Curriculm', 'tutor' );
					echo esc_html( apply_filters( 'tutor_course_topics_title', $title ) );
				?>
			</span>
		</div>
		<div class="text-regular-body color-text-subsued tutor-mt-12">
			Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptas quaerat fuga consectetur. Quia voluptatibus ullam beatae nesciunt provident voluptas est!
		</div>
	</div>
</div>

<?php if($topics->have_posts()): ?>
	<div class="tutor-accordion tutor-mt-40">
	<?php while($topics->have_posts()): ?>
		<?php 
			$topics->the_post(); 
			$topic_summery = get_the_content();
			$index++;
			?>
			<div class="tutor-accordion-item">
				<div class="tutor-accordion-item-header">
					<?php the_title(); ?>
				</div>
				<?php 
					$topic_contents = tutor_utils()->get_course_contents_by_topic(get_the_ID(), -1);
					if ($topic_contents->have_posts()){
						?>
						<div class="tutor-accordion-item-body">
							<div class="tutor-accordion-item-body-content">
								<ul class="tutor-courses-lession-list">
									<?php while($topic_contents->have_posts()): ?>
										<?php 
											$topic_contents->the_post(); 
											global $post;

											// Get Lesson video information if any
											$video = tutor_utils()->get_video_info();
											$play_time = $video ? $video->playtime : false;
											
											// Determine topic content icon based on lesson, video, quiz etc.
											$topic_content_icon = $play_time ? 'tutor-icon-youtube' : 'tutor-icon-document-alt';
											$post->post_type === 'tutor_quiz' ? $topic_content_icon = 'tutor-icon-doubt' : 0;
											$post->post_type === 'tutor_assignments' ? $topic_content_icon = 'tutor-icon-clipboard' : 0;
											$post->post_type === 'tutor_zoom_meeting' ? $topic_content_icon = 'ttr-zoom-brand' : 0;

											// 
											$is_locked = false;
										?>
										<li>
											<div>
												<span class="<?php echo $topic_content_icon; ?> tutor-icon-24 color-black-30 tutor-mr-14"></span>
												<span class="text-regular-body color-text-primary">
													<?php 
														$lesson_title = '';

														// Add zoom meeting countdown info
														$countdown = '';
														if ($post->post_type === 'tutor_zoom_meeting'){
															$zoom_meeting = tutor_zoom_meeting_data($post->ID);
															$countdown = '<div class="tutor-zoom-lesson-countdown tutor-lesson-duration" data-timer="'.$zoom_meeting->countdown_date.'" data-timezone="'.$zoom_meeting->timezone.'"></div>';
														}
														
														// Show clickable content if enrolled
														// Or if it is public and not paid, then show content forcefully
														if ($is_enrolled || (get_post_meta($course_id, '_tutor_is_public_course', true)=='yes' && !tutor_utils()->is_course_purchasable($course_id))){
															$lesson_title .= "<a href='".get_the_permalink()."'> ".get_the_title()." </a>";

															if ($countdown) {
																if ($zoom_meeting->is_expired) {
																	$lesson_title .= '<span class="tutor-zoom-label">'.__('Expired', 'tutor').'</span>';
																} else if ($zoom_meeting->is_started) {
																	$lesson_title .= '<span class="tutor-zoom-label tutor-zoom-live-label">'.__('Live', 'tutor').'</span>';
																}
																$lesson_title .= $countdown;
															}

															echo $lesson_title;
														}else{
															$lesson_title .= get_the_title();
															echo apply_filters('tutor_course/contents/lesson/title', $lesson_title, get_the_ID());
														}
													?>
												</span>
											</div>
											<div>
												<span class="text-regular-caption color-text-hints">
													<?php echo $play_time ? tutor_utils()->get_optimized_duration($play_time) : ''; ?>
												</span>
												<!-- <span class="<?php echo $is_locked ? ' ttr-lock-stroke-filled' : 'ttr-eye-filled'; ?> tutor-icon-24 color-black-20 tutor-ml-20"></span> -->
											</div>
										</li>
									<?php endwhile; ?>
								</ul>
							</div>
						</div>
						<?php
						//$topic_contents->reset_postdata();
					}
				?>
			</div>
			<?php endwhile; ?>
		</div>
<?php endif; ?>

<?php do_action('tutor_course/single/after/topics'); ?>