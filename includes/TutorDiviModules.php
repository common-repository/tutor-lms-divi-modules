<?php
/**
 * TutorDiviModules class
 */

defined( 'ABSPATH' ) || exit;

class TutorDiviModules extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'tutor-lms-divi-modules';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'tutor-lms-divi-modules';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = DTLMS_VERSION;

	/**
	 * TUDM_TutorDiviModules constructor.
	 * load dependecny
	 * load scripts
	 * load text-domain
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'tutor-lms-divi-modules', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );

		$this->load_dependencies();

		// add_action('wp_enqueue_scripts', [$this, 'enqueue_divi_styles'], 99);
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_divi_scripts' ), 99 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
	}

	public function load_dependencies() {
		require_once $this->plugin_dir . 'functions.php';
		require_once $this->plugin_dir . 'classes/Helper.php';
		require_once $this->plugin_dir . 'classes/Template.php';
		require_once $this->plugin_dir . 'classes/Requirements.php';
	}

	public function enqueue_divi_styles() {
		$css_file = DTLMS_ENV == 'DEV' ? 'css/tutor-divi-style.css' : 'css/tutor-divi-style.min.css';
		$version  = DTLMS_ENV == 'DEV' ? time() : $this->version;
		wp_enqueue_style(
			'tutor-divi-styles',
			DTLMS_ASSETS . $css_file,
			array(),
			$version
		);
		wp_enqueue_style(
			'tutor-divi-slick-css',
			DTLMS_ASSETS . 'slick/slick.min.css',
			null,
			$this->version
		);

		wp_enqueue_style(
			'tutor-divi-slick-theme-css',
			DTLMS_ASSETS . 'slick/slick-theme.css',
			null,
			$this->version
		);
	}

	public function enqueue_divi_scripts() {

		$this->enqueue_divi_styles();

		$version      = $this->version;
		$scripts_file = 'js/scripts.js';
		wp_enqueue_script(
			'tutor-divi-scripts',
			DTLMS_ASSETS . $scripts_file,
			array( 'jquery' ),
			$version,
			true
		);
		wp_enqueue_script(
			'tutor-divi-slick',
			DTLMS_ASSETS . 'slick/slick.min.js',
			array( 'jquery' ),
			$this->version,
			true
		);
		$inline_data = array(
			'is_divi_builder' => isset( $_GET['et_fb'] ) ? $_GET['et_fb'] : false,
		);
		wp_add_inline_script( 'tutor-divi-scripts', 'const dtlmsData = ' . json_encode( $inline_data ), 'before' );
	}

	public function admin_enqueue_scripts() {
		$css_file = DTLMS_ENV == 'DEV' ? 'css/tutor-divi-style.css' : 'css/tutor-divi-style.min.css';
		$version  = DTLMS_ENV == 'DEV' ? time() : $this->version;
		wp_enqueue_style(
			'tutor-divi-styles',
			DTLMS_ASSETS . $css_file,
			array(),
			$version
		);
	}

}

new TutorDiviModules();

