<?php

namespace IDX_Broker_Featured_Properties\Widget;


use IDX_Broker_Featured_Properties\Properties\Request;

class Featured_Properties extends \WP_Widget {


	protected static $ver = '0.1';
	protected $ioc;

	/**
	 * Initialization method
	 */
	public function init() {
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

	}


	public function register_widget() {
		register_widget( "\\IDX_Broker_Featured_Properties\\Widget\\Featured_Properties" );
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {


		parent::__construct(
			'ibfp-widget', // Base ID
			'IBFP IDX Widget', // Name
			array( 'description' => __( 'IDX Broker Featured Property Wiget.', 'ibfp' ), ) // Args
		);
	}


	/**
	 * Front-end display of widget.
	 *
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$template_file      = plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . 'views/widget.php';
		$template_file      = apply_filters( 'ibfp_widget_template', $template_file );
		$all_properties     = Request::get_properties();
		$properties_to_pass = [];
		$use_all            = false;
		if ( count( $instance['featured_properties'] ) === 0 ) {
			$use_all = true;
		}
		foreach ( $instance['featured_properties'] as $property_id ) {
			if ( ! $use_all && ! isset( $all_properties[ $property_id ] ) ) {
				continue;
			}
			$properties_to_pass[] = $all_properties[ $property_id ];
		}

		?>
		<?php extract( $args ); ?>
		<?php if ( isset( $before_widget ) ) : ?>
			<?php echo $before_widget; ?>
		<?php endif; ?>
		<?php include( $template_file ); ?>
		<?php if ( isset( $after_widget ) ) : ?>
			<?php echo $after_widget; ?>
		<?php endif; ?>
		<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                        = array();
		$instance['featured_properties'] = (array) $new_instance['featured_properties'];

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$defaults = array(
			'featured_properties' => [],

		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$properties = Request::get_properties();

		$selected_properties_keys   = array_values( $instance['featured_properties'] );
		$property_keys              = array_keys( $properties );
		$unselected_properties_keys = array_diff( (array) $property_keys, (array) $selected_properties_keys );

		?>
		<div class="widget-form">
			<div class="ibfp-sortable">
				<?php foreach ( $selected_properties_keys as $property_key ): ?>

					<?php
					$checked = in_array( $property_key, $instance['featured_properties'] );
					$this->generate_checkbox( $property_key, $properties[ $property_key ], $checked ); ?>
				<?php endforeach; ?>

				<?php foreach ( $unselected_properties_keys as $property_key ): ?>

					<?php
					$checked = in_array( $property_key, $instance['featured_properties'] );
					$this->generate_checkbox( $property_key, $properties[ $property_key ], $checked ); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	public function generate_checkbox( $property_key, $property, $checked = false ) {
		?>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'featured_properties' ); ?>"
				   name="<?php echo $this->get_field_name( 'featured_properties' ); ?>[]"
				   value="<?php esc_attr_e( $property_key ); ?>"
				<?php checked( true, $checked ); ?>
			> <?php esc_html_e( $property['address'] ); ?>


			</input>
		</p>
		<?php
	}
}

