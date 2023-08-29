<?php
/**
 * Print admin dashboard
 *
 * @package widgets
 */

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Widgets Collection Dashboard', 'widgets-collection' ); ?></h1>
	<hr class="wp-header-end">
	<p><?php esc_html_e( 'Widgets Collection settings', 'widgets-collection' ); ?></p>
	<form action='/wp-admin/admin.php?page=widcol_admin_menu' method='post'>
		<?php
			settings_fields( 'widcol_settings' );
			do_settings_sections( 'widcol_admin_menu' );
			submit_button();
		?>
	</form>
</div>
