<?php
/**
 * Class Page
 *
 * @package Packetery\Options
 */

declare( strict_types=1 );


namespace Packetery\Options;

use Packetery\Form_Factory;

/**
 * Class Page
 *
 * @package Packetery\Options
 */
class Page {

	/**
	 * Latte_engine.
	 *
	 * @var \Latte\Engine Latte engine.
	 */
	private $latte_engine;

	/**
	 * Options Provider
	 *
	 * @var Provider
	 */
	private $options_provider;

	/**
	 * Form factory.
	 *
	 * @var Form_Factory Form factory.
	 */
	private $form_factory;

	/**
	 * Plugin constructor.
	 *
	 * @param \Latte\Engine $latte_engine Latte_engine.
	 * @param Provider      $options_provider Options provider.
	 * @param Form_Factory  $form_factory Form factory.
	 */
	public function __construct( \Latte\Engine $latte_engine, Provider $options_provider, Form_Factory $form_factory ) {
		$this->latte_engine     = $latte_engine;
		$this->options_provider = $options_provider;
		$this->form_factory     = $form_factory;
	}

	/**
	 * Registers WP callbacks.
	 */
	public function register(): void {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_menu_page(
			__( 'Settings', 'packetery' ),
			__( 'Packeta', 'packetery' ),
			'manage_options',
			'packeta-options',
			array(
				$this,
				'render',
			),
			'dashicons-schedule',
			55 // todo Move item to last position in menu.
		);
	}

	/**
	 * Creates settings form.
	 *
	 * @return \Nette\Forms\Form
	 */
	private function create_form(): \Nette\Forms\Form {
		$form = $this->form_factory->create();
		$form->setAction( 'options.php' );

		$container = $form->addContainer( 'packetery' );
		$container->addText( 'api_password', __( 'API password', 'packetery' ) )
					->setRequired()
					->addRule( $form::PATTERN, __( 'API password must be 32 characters long and must contain valid characters!', 'packetery' ), '[a-z\d]{32}' );
		$container->addText( 'sender', __( 'Sender', 'packetery' ) )
					->setRequired();
		$container->addSelect(
			'packeta_label_format',
			__( 'Packeta Label Format', 'packetery' ),
			array(
				'A6 on A4'       => __( '105x148 mm (A6) label on a page of size 210x297 mm (A4)', 'packetery' ),
				'A6 on A6'       => __( '105x148 mm (A6) label on a page of the same size', 'packetery' ),
				'A7 on A7'       => __( '105x74 mm (A7) label on a page of the same size', 'packetery' ),
				'A7 on A4'       => __( '105x74 mm (A7) label on a page of size 210x297 mm (A4)', 'packetery' ),
				'105x35mm on A4' => __( '105x35 mm label on a page of size 210x297 mm (A4)', 'packetery' ),
				'A8 on A8'       => __( '50x74 mm (A8) label on a page of the same size', 'packetery' ),
			)
		)->checkDefaultValue( false );
		$container->addSelect(
			'carrier_label_format',
			__( 'Carrier Label Format', 'packetery' ),
			array(
				'A6 on A4' => __( '105x148 mm (A6) label on a page of size 210x297 mm (A4)', 'packetery' ),
				'A6 on A6' => __( '105x148 mm (A6) label on a page of the same size (offset argument is ignored for this format)', 'packetery' ),
			)
		)->checkDefaultValue( false );

		$container->addCheckbox(
			'allow_label_emailing',
			__( 'Allow Label Emailing', 'packetery' )
		);

		if ( $this->options_provider->has_any() ) {
			$container->setDefaults( $this->options_provider->data_to_array() );
		}

		return $form;
	}

	/**
	 *  Admin_init callback.
	 */
	public function admin_init(): void {
		register_setting( 'packetery', 'packetery', array( $this, 'options_validate' ) );
		add_settings_section( 'packetery_main', __( 'Main Settings', 'packetery' ), '', 'packeta-options' );
	}

	/**
	 * Validates options.
	 *
	 * @param array $options Packetery_options.
	 *
	 * @return array
	 */
	public function options_validate( $options ): array {
		$form = $this->create_form();
		$form['packetery']->setValues( $options );
		if ( $form->isValid() === false ) {
			foreach ( $form['packetery']->getControls() as $control ) {
				if ( $control->hasErrors() === false ) {
					continue;
				}

				add_settings_error( $control->getCaption(), esc_attr( $control->getName() ), $control->getError() );
				$options[ $control->getName() ] = '';
			}
		}

		$api_password = $form['packetery']['api_password'];
		if ( $api_password->hasErrors() === false ) {
			$api_pass           = $api_password->getValue();
			$options['api_key'] = substr( $api_pass, 0, 16 );
		} else {
			$options['api_key'] = '';
		}

		return $options;
	}

	/**
	 *  Renders page.
	 */
	public function render(): void {
		$this->latte_engine->render( PACKETERY_PLUGIN_DIR . '/template/options/page.latte', array( 'form' => $this->create_form() ) );
	}
}
