<?php
echo wp_kses_post($before_widget);
echo wp_kses_post($title);
?>

<form action="<?php echo esc_url( $form_action ); ?>" method="post" name="footer-subscribe-form" class="footer-subscribe-form">
	<input type="email" placeholder="<?php esc_attr_e( 'Enter your e-mail', 'zoa' ); ?>" name="email" required>
	<button type="submit" name="submit"><?php esc_html_e( 'Subscribe', 'zoa' ); ?></button>
</form>

<?php
    echo wp_kses_post( $after_widget );