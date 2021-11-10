<?php

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Widgets Collection Dashboard', 'widgets-collection' ); ?></h1>
	<hr class="wp-header-end">
	<p><?php esc_html_e( 'Widgets Collection settings', 'widgets-collection' ); ?></p>
	<form action='options.php' method='post'>
		<?php
			settings_fields( 'widgets_collection_settings' );
			do_settings_sections( 'widgets_collection_settings' );
			submit_button();
		?>
	</form>
</div>
