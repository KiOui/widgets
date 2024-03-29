<?php
/**
 * Metabox class
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColMetabox' ) ) {
	/**
	 * WidCol Metabox class
	 *
	 * This class is able to render a custom metabox with a custom specification of fields.
	 *
	 * @class WidColMetabox
	 */
	class WidColMetabox {

		/**
		 * Name of the meta box
		 *
		 * @var string
		 */
		private string $meta_box_name;

		/**
		 * Meta fields of this meta box.
		 *
		 * @var array
		 */
		private array $meta_fields;

		/**
		 * Post type meta box should be registered on
		 *
		 * @var string
		 */
		private string $post_type;

		/**
		 * Title of the meta box
		 *
		 * @var string
		 */
		private string $meta_box_title;

		/**
		 * WidgetCollectionMetabox constructor.
		 *
		 * @param string $meta_box_name the post type to add this custom meta box to.
		 * @param array  $meta_fields array of (label: Label of meta box, desc: Description of meta box, id: ID of meta box
		 *  (used in database), type: Type of meta box (for input field)).
		 * @param string $post_type post type to register meta box on.
		 * @param string $meta_box_title title of the meta box.
		 */
		public function __construct( string $meta_box_name, array $meta_fields, string $post_type, string $meta_box_title ) {
			$this->meta_box_name = $meta_box_name;
			$this->meta_fields = $meta_fields;
			$this->post_type = $post_type;
			$this->meta_box_title = $meta_box_title;
			$this->actions_and_filters();
		}

		/**
		 * Actions and filters.
		 */
		public function actions_and_filters() {
			add_action( 'add_meta_boxes_' . $this->post_type, array( $this, 'register_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box' ) );
		}

		/**
		 * Register meta box.
		 */
		public function register_meta_box() {
			add_meta_box(
				$this->meta_box_name,
				$this->meta_box_title,
				array( $this, 'show_custom_meta_box' ),
				$this->post_type
			);
		}

		/**
		 * Get the name of the nonce corresponding to this meta box.
		 *
		 * @return string name of the nonce corresponding to this meta box
		 */
		private function get_nonce_name(): string {
			return $this->meta_box_name . '_nonce';
		}

		/**
		 * Creates HTML for the custom meta box
		 */
		public function show_custom_meta_box() {
			global $post;
			wp_nonce_field( basename( __FILE__ ), $this->get_nonce_name() ); ?>
				<table class="form-table">
					<?php foreach ( $this->meta_fields as $field ) : ?>
						<?php $meta = get_post_meta( $post->ID, $field['id'], true ); ?>
						<tr>
							<th><label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
							<td>
								<?php
								switch ( $field['type'] ) :
									case 'number':
										?>
												<input type="number"
												   <?php
													if ( array_key_exists( 'required', $field ) && $field['required'] ) :
														?>
														required
														<?php
												   endif;
													?>
												   <?php
													if ( array_key_exists( 'any', $field ) && $field['any'] ) :
														?>
														step="any"
														<?php
												   endif;
													?>
												   <?php
													if ( array_key_exists( 'min', $field ) ) :
														?>
														min=<?php echo esc_attr( $field['min'] ); ?>
														<?php
												   endif;
													?>
												   <?php
													if ( array_key_exists( 'max', $field ) ) :
														?>
														max=<?php echo esc_attr( $field['max'] ); ?>
														<?php
												   endif;
													?>
													   name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $meta ); ?>" />
												<br>
												<span class="description"><?php echo esc_html( $field['desc'] ); ?></span>
											<?php
										break;
									case 'text':
										?>
											<input type="text"
												   <?php
													if ( array_key_exists( 'required', $field ) && $field['required'] ) :
														?>
														required
														<?php
												   endif;
													?>
												   name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $meta ); ?>" />
											<br>
											<span class="description"><?php echo esc_html( $field['desc'] ); ?></span>
																	<?php
										break;
									case 'textarea':
										?>
											<?php if ( array_key_exists( 'editor', $field ) && 'tinymce' === $field['editor'] ) : ?>
												<?php
													wp_editor(
														$meta,
														$field['id'],
														array(
															'wpautop' => true,
														)
													);
												?>
											<?php else : ?>
												<textarea style="width: 100%; min-height: 200px;"
														<?php
														if ( array_key_exists( 'required', $field ) && $field['required'] ) :
															?>
															required
															<?php
														endif;
														?>
													   name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_textarea( $meta ); ?></textarea>
												<br>
												<span class="description"><?php echo esc_html( $field['desc'] ); ?></span>
											<?php endif; ?>
																	<?php
										break;
									case 'checkbox':
										?>
											<input type="checkbox"
												   <?php
													if ( array_key_exists( 'required', $field ) && $field['required'] ) :
														?>
														required
														<?php
												   endif;
													?>
												   name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>"
																	<?php
																	if ( $meta ) :
																		echo 'checked=checked';
									endif;
																	?>
			 />
											<br>
											<span class="description"><?php echo esc_html( $field['desc'] ); ?></span>
											<?php
										break;
									case 'select':
										?>
											<select name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>">
												<?php foreach ( $field['options'] as $option ) : ?>
													<option 
													<?php
													if ( $meta == $option['value'] ) :
														echo 'selected="selected"';
			endif
													?>
			 value="<?php echo esc_attr( $option['value'] ); ?>">
														<?php echo esc_html( $option['label'] ); ?>
													</option>
												<?php endforeach ?>
											</select>
											<br>
											<span class="description"><?php echo esc_html( $field['desc'] ); ?></span>
											<?php
										break;
			endswitch;
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php
		}

		/**
		 * Saves custom meta tag data
		 *
		 * @param int $post_id the post id to save the data for.
		 * @return int: post_id
		 */
		public function save_meta_box( int $post_id ): int {
			if ( ! isset( $_POST[ $this->get_nonce_name() ] ) || ! wp_verify_nonce( wp_unslash( $_POST[ $this->get_nonce_name() ] ), basename( __FILE__ ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			if ( isset( $_POST['post_type'] ) && wp_unslash( $_POST['post_type'] ) == $this->post_type && current_user_can( 'edit_post', $post_id ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				foreach ( $this->meta_fields as $field ) {
					$old = get_post_meta( $post_id, $field['id'], true );
					$new = isset( $_POST[ $field['id'] ] ) ? wp_unslash( $_POST[ $field['id'] ] ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					if ( $new && $new != $old ) {
						update_post_meta( $post_id, $field['id'], $new );
					} elseif ( ! $new && $old ) {
						delete_post_meta( $post_id, $field['id'], $old );
					}
				}
			}
			return $post_id;
		}
	}
}
