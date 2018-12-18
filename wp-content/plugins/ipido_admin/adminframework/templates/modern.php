<header class="csf-header <?php echo $sticky_header; ?>">
	<?php if ( ! empty( $title ) ) : ?>
		<h1><?php echo $title; ?><small><?php echo $subtitle; ?></small></h1>
	<?php endif; ?>

	<div class="csf-header-buttonbar">
		<?php
		if ( 'yes' === $ajax ) {
			echo '<span id="csf-save-ajax">' . esc_html__( 'Settings Saved', 'csf-framework' ) . '</span>';
		}
		echo $class->get_settings_buttons();
		?>
	</div>
	<?php
	if ( true === $class->is( 'has_nav' ) ) {
		if ($class->_option( 'show_all_options_link' )){
			echo '<a href="#" class="csf-expand-all"><i class="fa fa-eye-slash"></i> ' . __( 'Show All Options','csf-framework' ) . '</a>';
		}
	}
	echo '<div class="clear"></div>';
	?>

</header>

<div class="csf-body <?php echo $has_nav; ?>">
	<div class="csf-nav">
		<div class="csf-nav-buttons"><div class="csf-nav-button csf-nav-prev" data-type="prev"></div><div class="csf-nav-button csf-nav-next" data-type="next"></div></div>
		<div class="csf-nav-wrapper">
			<ul> <?php csf_modern_navs( $class->navs(), $class ); ?> </ul>
		</div>
	</div>


	<div class="csf-content">
		<div class="csf-sections">
			<?php
			foreach ( $class->options as $option ) {
				if ( 'no' === $single_page && $option['name'] !== $class->active() ) {
					continue;
				}

				$pg_active = ( $option['name'] === $class->active() ) ? true : false;

				if ( isset( $option['sections'] ) ) {
					foreach ( $option['sections'] as $section ) {
						if ( 'no' === $single_page && $section['name'] !== $class->active( false ) ) {
							continue;
						}

						$sc_active = ( true === $pg_active && $section['name'] === $class->active( false ) ) ? true : false;
						$fields    = $class->render_fields( $section );

						echo '<div ' . $class->is( 'page_active', $sc_active ) . ' 
                        id="csf-tab-' . $option['name'] . '-' . $section['name'] . '" 
                        class="csf-section">' . $class->get_title( $section ) . $fields . '</div>';
					}
				} elseif ( isset( $option['fields'] ) || isset( $option['callback_hook'] ) ) {
					$fields = $class->render_fields( $option );
					echo '<div ' . $class->is( 'page_active', $pg_active ) . ' 
                        id="csf-tab-' . $option['name'] . '" 
                        class="csf-section">' . $class->get_title( $option ) . $fields . '</div>';
				}
			}
			?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="csf-nav-background"></div>
</div>

<footer class="csf-footer">
	<div class="csf-block-left"><?php _e( 'Powered by CastorStudio Settings Framework' ); ?></div>
	<div class="csf-block-right">
		<?php
			echo __( 'Version', 'csf-framework' );
			echo ' ' . CSF_VERSION;
		?>
	</div>
	<div class="clear"></div>
</footer>