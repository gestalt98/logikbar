<div id="kopa_options_wrap" class="wrap">
    <div id="kopa_options_metabox" class="metabox-holder">
	    <?php 
	   		// setting notices when submit form
	    	settings_errors( 'kopa_settings_notices' );
	    ?>
	    <div id="kopa_options" class="postbox">
			<form enctype="multipart/form-data" id="kopa_options_form" action="" method="post">
				<div class="kopa_options_sidebar">
					<span id="kopa-logo"><img src="<?php echo KF()->framework_url(); ?>/assets/images/logo.png" alt=""></span>
					<span class="kopa_sidebar_menu_mobile_icon fa fa-bars"></span>					
					<?php do_action( 'kopa_sidebar_menu_settings_' . $kopa_current_tab ); ?>
				</div>

				<div class="kopa_options_content">
					<h2 class="kopa_nav_tab_wrapper">
				    	<?php
				    		$menu = Kopa_Admin_Menus::menu_settings();
							foreach ( $tabs as $name => $label ) {
								echo '<a href="' . admin_url( 'themes.php?page='.$menu['menu_slug'].'&tab=' . $name ) . '" class="kopa_nav_tab ' . ( $kopa_current_tab == $name ? 'kopa_nav_tab_active' : '' ) . '">' . $label . '</a>';
							}

							do_action( 'kopa_settings_tabs' );
						?>
				    </h2>
					
					<?php if ( $kopa_show_save_button ) { ?>
					<div id="kopa_options_submit_top">
						<input type="submit" class="button-primary kopa_save" name="update" value="<?php esc_attr_e( 'Save Options', 'kopa-framework' ); ?>" />
					</div>
					<?php } ?>

					<?php
					// action hook for generating option fields
					do_action( 'kopa_settings_' . $kopa_current_tab ); 
					?>

					<input type="hidden" name="current_tab" value="<?php echo $kopa_current_tab; ?>">
					<?php wp_nonce_field( 'kopa-settings' ); ?>

					<?php if ( $kopa_show_save_button ) { ?>
					<div id="kopa_options_submit">
						<input type="submit" class="button-primary kopa_save" name="update" value="<?php esc_attr_e( 'Save Options', 'kopa-framework' ); ?>" />
						<div class="clear"></div>
					</div>
					<?php } ?>
				</div> <!-- kopa_options_content -->
			</form> <!-- kopa_options_page -->
		</div> <!-- kopa_options -->
	</div> <!-- kopa_options_metabox -->
	<?php do_action( 'kopa_settings_after' ); ?>

	<!-- Modal -->
	<div class="kopa_modal kopa_hide" id="kopa_modal_info">
		<p class="kopa_modal_info"></p>

		<p id="kopa_modal_confirm">
			<input class="button button-primary" id="kopa_confirm_ok" type="submit"  value="<?php esc_attr_e( 'OK', 'kopa-framework' ); ?>">
			<input class="button" id="kopa_confirm_cancel" type="submit"  value="<?php esc_attr_e( 'Cancel', 'kopa-framework' ); ?>">
		</p>
	</div>
</div> <!-- / .wrap -->