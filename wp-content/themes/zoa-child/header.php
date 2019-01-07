<!DOCTYPE html>
<?php 
global $post,$wp;
$request = explode( '/', $wp->request );
$post_slug=$post->post_name;
$lastslug = end($request);
$user = wp_get_current_user();
if ($user->ID)
{
	
	$user_last_name = get_user_meta($user->ID, 'billing_last_name', true);
	$user_first_name = get_user_meta($user->ID, 'billing_first_name', true);
	if (!$user_last_name || !$user_first_name)
	{
		$user_last_name = get_user_meta($user->ID, 'last_name', true);
		$user_first_name = get_user_meta($user->ID, 'first_name', true);
	}
}
?>
<html <?php language_attributes(); ?>>

<head itemscope="itemscope" itemtype="https://schema.org/WebSite">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<script type="text/javascript">
		var gl_site_url = '<?php echo site_url()?>/';
		var gl_stateAllowed = [];
		var gl_alertStateNotAllowed = '';
		var gl_ajax_url = '<?php echo site_url()?>/wp-admin/admin-ajax.php';
		var gl_confirmation_text = '<?php echo __('Confirmation', 'zoa')?>';
		var gl_remove_photo_text = '<?php echo __('Are you sure to remove the image ?, If you want show it again after remove please reload the page', 'zoa')?>';
		var gl_user_name = '<?php echo isset($user_last_name) ? $user_last_name . $user_first_name : ''?>';
		var gl_cancel_appointment_alert = '<?php echo __('Are you sure to cancel this appoinment ?', 'zoa')?>';
		var gl_date_is_tempty_text = '<?php echo __('Date is empty', 'zoa')?>';
		var gl_time_is_tempty_text = '<?php echo __('Time is empty', 'zoa')?>';
		var gl_cancel_order_alert_text = '<?php echo __('Are you sure to cancel this order ?', 'zoa')?>';
	</script>
</head>

<body <?php body_class(); ?>>
<?php if ( is_home() || is_front_page() ) {?>
<canvas id="canv"></canvas>
<?php } ?>
<?php do_action('after_body_open_tag'); ?>
<!--<script>
  document.body.classList.add('fade-out');
</script>-->
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="svg-symbol" style="position: absolute; width: 0; height: 0; overflow: hidden;">
	<symbol viewBox="0 0 800 288.53" id="svg-logo">
        <g class="nc-icon-wrapper" fill="#111111">
         <g id="Layer_2" data-name="Layer 2">
          <g id="Layer_1-2" data-name="Layer 1">
           <path d="M106.26 148.81c3.7-3.57 7.38-7.17 11.1-10.73 9.65-9.25 19.42-18.35 31.53-24.4 1.55-.78 3.2-1.35 5.45-2.29a46 46 0 0 1 0 4.89c-.58 4.92-1.44 9.83-1.87 14.76-.4 4.65-.49 9.34 1.1 13.85 1.06 3 3.16 4.48 6.09 3.24 3.3-1.38 6.78-3.08 9.23-5.58 4.82-4.93 9-10.5 13.4-15.85a11.41 11.41 0 0 0 1.28-2.51c1.77-3.67 3.45-7.39 5.32-11 .62-1.2 1.39-3 3.25-2s.66 2.6-.08 3.77q-3.09 4.91-6.37 9.7c-3.28 4.8-4.19 10.21-4 15.83.15 3.6 4.31 5.88 8.07 4.89 4.81-1.28 9.24-3.19 12.45-7.19a30.36 30.36 0 0 0 3-4.23c2.78-5 5.41-10.14 8.16-15.18q3.72-6.8 7.61-13.49c.47-.81 1.41-1.35 2.59-2.42v2.21c-.78 8.4-1.74 16.79-2.27 25.21a16.43 16.43 0 0 0 1.54 7.17 8.18 8.18 0 0 0 11.67 4.21 34.74 34.74 0 0 0 7.4-5A38.88 38.88 0 0 0 252 123.42c2.42-5.61 5.57-10.91 8.55-16.27a3 3 0 0 1 2.51-1.2c1.35.38 1.05 1.65.57 2.75A101.45 101.45 0 0 1 252 128a22.06 22.06 0 0 0-2.36 3.92c-7.47 16.63-15 33.22-22.27 49.95-2.77 6.41-4.76 13.16-7.1 19.77A13.27 13.27 0 0 0 220 203c2.44.38 4.73.71 7 1.11a56.32 56.32 0 0 1 17.09 5.89c11.15 6 17.62 15.2 18.74 28 1.18 13.38-2.93 24.8-13 33.74a25.66 25.66 0 0 1-35.12-1.45c-4.26-4.45-5.13-10.22-5.69-16-.89-9.1.46-18.08 2.35-26.94 1.41-6.62 3.43-13.11 5.19-19.66.27-1 .58-1.93 1-3.25-4.36 0-8.46-.29-12.49.07-6.24.57-12.45 1.5-18.64 2.43a172.14 172.14 0 0 0-33.76 8.94A254.68 254.68 0 0 0 127 227.16c-8 4-15.7 8.56-23.4 13.1-2.93 1.73-5.47 4.09-8.21 6.14s-5.92 4-8.46 6.42a121.74 121.74 0 0 0-9.39 10.39A61.28 61.28 0 0 0 72 271a53.75 53.75 0 0 0-4.61 9.35c-.56 1.54 0 3.56.38 5.31a2.24 2.24 0 0 0 1.79 1.18c2.38-.09 4.76-.42 7.13-.71a22.17 22.17 0 0 0 2.42-.52l.42.66c-.62.53-1.17 1.4-1.87 1.53a35.28 35.28 0 0 1-6.06.7c-4.72.11-7-2.66-5.73-7.19 1.93-6.85 5.68-12.77 10-18.37 9.03-11.85 21.13-20.14 33.68-27.78a233.27 233.27 0 0 1 60.63-26.33c14.94-4 30.1-6.51 45.64-5.92a2.53 2.53 0 0 0 3-2c7.33-21.82 16.6-42.84 26.37-63.65.2-.43.34-.89.6-1.56-2.49 2-4.64 3.93-7 5.55a17.59 17.59 0 0 1-5.13 2.36c-5.81 1.62-11.5-1.3-12.73-7.18a60.62 60.62 0 0 1-.79-13.88c.13-4.93.88-9.83.93-15-1 1.81-2 3.6-3 5.44-3.47 6.74-6.78 13.56-10.41 20.22a29.82 29.82 0 0 1-10.09 11.05 16 16 0 0 1-12.57 2.48c-2.64-.59-4.39-2.25-4.65-4.88a55.19 55.19 0 0 1 .17-7.6 5.17 5.17 0 0 0 0-2c-.86 1-1.65 2.16-2.61 3.11-4.17 4.16-8.3 8.38-12.67 12.33a11.68 11.68 0 0 1-5.1 2.28c-4.12.94-7.85-1.82-8.71-6-1.39-6.71-.78-13.36.21-20 .49-3.24.77-6.5 1.19-10.24a43.39 43.39 0 0 0-5.37 2.65c-5.78 3.94-11.81 7.63-17.1 12.16-7.57 6.47-14.58 13.61-21.83 20.46-.37.35-.71.84-1.15 1a21.3 21.3 0 0 1-2.78.52 7.3 7.3 0 0 1-.26-2.62c1.76-6 3.64-12 5.47-18 .13-.41.23-.82.43-1.55-2.69 2.37-5.15 4.48-7.56 6.66a75.4 75.4 0 0 1-20.32 12.71c-7.24 3.22-14.47 6.51-21.87 9.33a77 77 0 0 1-14.92 4.19c-7 1.15-14.1 2-21.26.45C12.8 159.13 1.83 150.32.42 135.38a94.22 94.22 0 0 1 0-17.21 118.68 118.68 0 0 1 6.83-30.79 171.72 171.72 0 0 1 17.81-35.5 153.89 153.89 0 0 1 33.32-36.29c5.26-4.1 10.37-8.22 16.45-11.08C80.46 1.86 86.4-.06 92.5 0c9.71.09 21 3.75 26.26 15.12a57.87 57.87 0 0 1 5.06 15.06c1.43 8.68-1.28 16.27-6 23.36a51.35 51.35 0 0 1-16.67 16.08C97 72.11 92.8 74.2 88.05 75a17.86 17.86 0 0 1-13.89-3.27c-3.19-2.3-6.6-4.26-8.84-7.69-3.46-5.3-4.6-10.58-1.71-16.68A33.07 33.07 0 0 1 71 37.49a40.5 40.5 0 0 1 16.7-9.66 28.66 28.66 0 0 1 6-.76c.37 0 .78.66 1.18 1-.38.24-.81.73-1.13.66-4.37-.86-8.13 1.18-11.57 3.1a82.75 82.75 0 0 0-12.43 9 21.58 21.58 0 0 0-4.44 5.91c-3.14 5.47-2.37 11 1.14 15.85 1.8 2.52 4.67 4.25 6.9 6.48 3.21 3.21 7.2 4.34 11.53 4.42a22.76 22.76 0 0 0 6.19-.79c8.7-2.27 15.22-7.88 20.91-14.47a60.5 60.5 0 0 0 7.55-10.66A26 26 0 0 0 121.71 28c-1.41-5.21-2.58-10.51-6-14.91-5.3-6.8-12.15-10.94-20.83-11.21-4.69-.15-9.45.12-13.95 2a75.38 75.38 0 0 0-12.08 6 125.08 125.08 0 0 0-20.56 17C44.66 30.53 41 34.16 37.51 38c-2.33 2.6-4.24 5.56-6.43 8.29-7.12 8.88-12.23 19-17.17 29.1A136.71 136.71 0 0 0 7 93.94a148.28 148.28 0 0 0-4.3 17.15 63.8 63.8 0 0 0-1 12.35 111.32 111.32 0 0 0 1.05 15.38c.87 5.76 4.22 10.4 8.42 14.26 6.06 5.58 13.51 7.6 21.57 7.33 4.93-.17 10-.12 14.72-1.27A197.67 197.67 0 0 0 68 152.5a129.48 129.48 0 0 0 23.86-11.73 88.93 88.93 0 0 0 15.61-12.55c3.64-3.55 5.23-7.64 6.66-12.17 2.11-6.72 4.24-13.47 6.82-20 6.75-17.05 12.77-34.51 22.1-50.44a59.19 59.19 0 0 1 7-9.74c1.4-1.55 3.74-2.43 5.82-3.16 1.87-.66 3.1.35 2.87 2.35a26.07 26.07 0 0 1-1.74 7.15c-4.65 10.94-9.3 21.88-14.3 32.66-6.29 13.57-13.14 26.86-21.49 39.32-1.94 2.9-4.4 5.45-6.39 8.32a21.7 21.7 0 0 0-2.5 5.23c-1.42 4.06-2.69 8.16-4 12.26-.89 2.82-1.71 5.67-2.56 8.51zM210 247.48c.32 3.69.28 7.46 1 11.07 1.33 6.36 3.87 12.08 10.25 15.17 10.95 5.29 25.13 1.78 32.2-8.1 4.14-5.78 7.11-12.09 7.69-19.26 1.17-14.47-3.47-26.38-16.2-34.17-7.18-4.4-15.26-6.45-23.65-7.21-1.59-.15-2.26.32-2.78 1.87a175.48 175.48 0 0 0-8.51 40.63zm-53.23-213a10.37 10.37 0 0 0-7.2 4.37c-3.27 5-6.85 9.84-9.38 15.19-4.13 8.75-7.73 17.77-11.18 26.81-4.63 12.13-8.9 24.39-13.32 36.6-.2.53-.32 1.08-.58 2C126.61 108 158.19 41 156.79 34.43z"/>
           <path d="M528.58 116.85l47.54-16c-.34 1.41-.59 2.89-1.07 4.3-2.08 6.07-4.42 12-6.27 18.18s-3.23 12.19-4.69 18.31a21.85 21.85 0 0 0-.05 4.51 19.53 19.53 0 0 0 2.62-2.18q5.39-6.75 10.68-13.59c3.91-5 7.76-10.15 11.68-15.2 2.58-3.33 5.22-6.62 7.88-9.87a35.15 35.15 0 0 1 2.9-2.79 16.27 16.27 0 0 1 .52 2.93c-.09 5-.43 10-.35 15a49.82 49.82 0 0 0 1.08 10.35 19.21 19.21 0 0 0 3.26 6.5c1.43 2 5 2.48 7.49 1.44a25.79 25.79 0 0 0 10.6-7.92c6.51-8.23 12.89-16.57 19.34-24.86 1.46-1.87 3-3.69 4.49-5.53l.57.25a14.84 14.84 0 0 1 0 2.83c-1.76 9-5.2 17.43-9.16 25.68-2.7 5.66-5.11 11.45-7.6 17.21a6.44 6.44 0 0 0-.44 3.13c.36-.45.74-.89 1.08-1.36 5.65-7.79 11-15.83 17-23.32 5.15-6.4 11-12.23 16.7-18.2 1.41-1.48 3.32-2.5 5.55-4.15v6.44c0 5.89-.17 11.79-.08 17.68a22.85 22.85 0 0 0 1 5.57c1.77 6.38 6.26 9.46 12.67 9.86a18.51 18.51 0 0 0 6.08-.46 41.16 41.16 0 0 0 13.54-6.24v-.54c-6.38-7-7.87-15.36-6.18-24.31s5.06-17.1 12.3-23.11c7.53-6.26 19.65-1.88 21.27 7.62.69 4.11-.32 8-1.65 11.79a53.42 53.42 0 0 1-15.39 22.52c-2.4 2.11-4.82 4.21-7.42 6.49.7.56 1.43 1.23 2.25 1.77 6 4 12.8 5.93 19.75 7.39a74.16 74.16 0 0 0 22.36 1.2c6.77-.66 13.75-1 20.27-3.72 5.24-2.21 10.71-3.86 16.09-5.73A28.51 28.51 0 0 0 798 129c1.35-1.41.66-4.26-1.32-5.7l-2.34-1.68.17-.6a5 5 0 0 1 1.95-.09c3.27 1.34 4.72 6.08 2.55 8.89a19.66 19.66 0 0 1-4.54 4c-8.48 5.75-18.13 8.6-27.92 11.11a86.46 86.46 0 0 1-28.43 2.57c-11.61-.9-22.94-2.67-32.41-10.34a2.35 2.35 0 0 0-2 0c-2.74 1.36-5.37 2.94-8.12 4.29a25.72 25.72 0 0 1-14.11 2.12c-5.25-.53-8.51-3.64-10.74-8.23-2.76-5.67-2.85-11.69-2.74-17.77.07-3.78 0-7.55 0-11.62a80.9 80.9 0 0 0-6.29 5.33c-9.66 9.91-17.93 20.94-25.67 32.39-1.51 2.24-3 4.53-4.66 6.61a3.46 3.46 0 0 1-2.84 1.2c-1.54-.4-1.33-1.89-.8-3.11 3.67-8.5 7.47-16.94 11-25.5 2.21-5.37 4-10.9 6-16.35l-.65-.35c-.34.45-.7.89-1 1.35-7 9.34-13.68 19-22.29 27a31.06 31.06 0 0 1-6.9 4.94c-5.35 2.74-10.28.93-12.89-4.44a28.23 28.23 0 0 1-2.75-12.73v-16c-1.7 2-3.39 3.71-4.86 5.64-4.82 6.33-9.49 12.76-14.33 19.06-3.78 4.92-7.73 9.69-11.63 14.51a7.54 7.54 0 0 1-1.6 1.49c-1.69 1.14-3.39.35-3.28-1.67.19-3.33.11-6.8 1-10 2.81-9.66 6-19.19 9.1-28.76.27-.84.55-1.66.79-2.5a3.68 3.68 0 0 0 0-.85c-2.23.81-4.36 1.63-6.53 2.35q-18.17 6.06-36.34 12.06c-3.15 1-3.17 1-3.24 4.11 4.22 1.94 8.44 3.54 12.33 5.74 8.18 4.62 13 11.67 14 21.13a33.09 33.09 0 0 1-2.18 16.13 121.37 121.37 0 0 1-7.21 15.4c-1.41 2.43-4.13 4.29-6.61 5.86-2.22 1.4-4.69.77-6.58-1.19-4.24-4.41-6.31-9.83-7.63-15.71-3.28-14.63-.73-29 1.5-43.44.07-.43.15-.86.2-1.3a4.59 4.59 0 0 0-.12-.8c-4-.43-8-2.25-12.18-.59a4.11 4.11 0 0 0-1.87 1.58c-4.31 7-8.32 14.23-12.88 21.07-4.24 6.35-8.91 12.44-13.67 18.43a87.54 87.54 0 0 1-13.92 13.35c-4.66 3.75-9.36 7.42-15.25 9.06a44 44 0 0 1-7 1.67 9.45 9.45 0 0 1-8.94-4.06 20.44 20.44 0 0 1-4-9.8 66.91 66.91 0 0 1 1.5-24c1.56-6.66 3.05-13.27 5.66-19.59a75.1 75.1 0 0 1 12.58-21.15c4.2-4.8 8.92-9.13 15.68-9.91a11.6 11.6 0 0 1 5.76.49 72.18 72.18 0 0 1 9 5.21c.6.38.82 1.37 1.22 2.07l-.61.43c-1.9-1.4-3.71-2.92-5.71-4.16-6-3.75-12.14-3-18 1.08-7.2 5-11.23 12.4-15.3 19.67a84.07 84.07 0 0 0-8.4 22.79c-1.33 5.93-1.58 12.11-2.28 18.18-.57 4.86.48 9.53 1.68 14.17.93 3.57 5.2 7.16 8.95 6.88a26.75 26.75 0 0 0 9.51-2.08 71.71 71.71 0 0 0 19.29-13.8 122.25 122.25 0 0 0 12.68-14.07c5.33-7.19 10.06-14.82 14.91-22.35 2-3.05 3.58-6.32 5.7-10.1-1.58.34-2.57.55-3.56.78-3.13.7-6.24 1.46-9.39 2.07-1.26.25-2.86.49-3.31-1.21s1.13-2.52 2.42-2.8c4.19-1 8.41-1.8 12.66-2.42 2-.29 3.58-.17 4.28-2.85s2.59-5 3.74-7.61c1.94-4.37 3.88-8.76 5.51-13.25 3.63-10 7.49-19.88 10.45-30 2-6.73 2.53-13.87 3.69-20.83 1.48-8.82 2.91-17.65 4.42-26.47a16.31 16.31 0 0 1 1.09-2.91h.83a9.83 9.83 0 0 1 .58 2.7c-.51 6.08-.76 12.22-1.82 18.22-1.21 6.92-3.19 13.71-4.78 20.57-.41 1.77-.65 3.58-.89 5.38-1.35 10.47-2.72 20.93-4 31.41-.85 7.2-1.61 14.31-2.49 22.05zM526.3 124c-.93 9.67-2 18.81-2.65 28a47.64 47.64 0 0 0 .34 11.26 75.27 75.27 0 0 0 3.39 12.63 25.46 25.46 0 0 0 3.79 6.55c1.78 2.4 5.18 3 7.15 1.27a31.18 31.18 0 0 0 5.94-6.59 59.81 59.81 0 0 0 4.73-10c3.65-9.38 5.16-18.81-.35-28.11-4.9-8.31-12.64-12.36-22.34-15.01zm178.23 10.5c.65-.37 1.12-.57 1.52-.86a65.25 65.25 0 0 0 19.44-22.76 29.35 29.35 0 0 0 3.63-14 9.76 9.76 0 0 0-6.12-9.42 12.17 12.17 0 0 0-13.36 1.66 28.2 28.2 0 0 0-9.24 14.3c-1.45 5-2.65 10.06-2.45 15.45.24 6.13 3.05 10.9 6.58 15.6zm-172.3-60.4l-.27.06-18.32 46.26a1.62 1.62 0 0 0 .64 0c3.72-1 7.46-1.93 11.14-3a2.5 2.5 0 0 0 1.24-1.76c.62-3.29 1.17-6.6 1.59-9.92.77-6.15 1.4-12.32 2.16-18.47.59-4.4 1.21-8.78 1.82-13.17zm-36.82 51.38l10.41-2.27c-3.28-.93-8.49.22-10.41 2.27zm30.68-6.34l-5.35 1.38v.41l4.72.77c.2-.79.36-1.46.63-2.56z"/>
           <path d="M387.13 105.67c-2.47 0-4.95.06-7.42 0-.57 0-1.11-.66-1.67-1 .39-.47.69-1.14 1.19-1.37 5.95-2.69 15.49-1.09 20.15 3.48 3.95 3.87 5.31 8.81 5.07 14.19-.45 10.35-4.82 19.03-12.38 26.03a21.84 21.84 0 0 1-5.68 3.66c-4.56 2.11-10.53 0-13.12-4.37a24.14 24.14 0 0 1-2.93-16.82c.07-.42.06-.85.11-1.71-.64.6-1 1-1.4 1.32-3.32 3.37-6.43 7-10 10a23.78 23.78 0 0 1-7.88 4.46c-6.7 2.18-11.88-1.1-12.63-8.16-.41-3.9.22-7.94.63-11.89a76.16 76.16 0 0 1 1.64-7.78c-1.86 1.79-3.42 3.14-4.81 4.65-3.05 3.3-6 6.72-9 10-4.5 4.82-9.06 9.57-13.63 14.31a46.37 46.37 0 0 1-3.87 3.55c-1.46 1.19-2.82.65-2.77-1.22a29.28 29.28 0 0 1 .91-6.17c1.75-7 5.52-13.48 5.92-20.91a8.58 8.58 0 0 0-5.83-9 17.63 17.63 0 0 0-5.14-.54 46.42 46.42 0 0 0-18.79 3.75c1.21 2.77 2.51 5.26 3.38 7.88a28.35 28.35 0 0 1 1.44 7.1c.39 7.06-.14 14-4.35 20.08a49.8 49.8 0 0 1-3.77 5 7.88 7.88 0 0 1-11.43-.16c-2.67-2.83-4.93-5.59-5-9.56a16.34 16.34 0 0 1 4.65-11.19c4.19-4.58 8.94-8.64 13.5-12.88.38-.34 1.08-.32 1.64-.47l.41.61c-.91.93-1.79 1.91-2.75 2.79-3.71 3.42-7.68 6.59-11.09 10.28s-5.61 8.43-4.31 13.91c.73 3 5.61 7.33 8.69 7.47 2.39.11 4.09-1.36 5.53-3a26.15 26.15 0 0 0 6.52-16.72 44 44 0 0 0-.65-10.77c-.67-3-2.35-5.82-3.88-8.56-.87-1.58-.85-2.37.78-3.28 4.3-2.39 9-3.25 13.77-3.66 1.67-.14 3.34-.27 5-.27 5.12 0 9.92.74 12.41 6.08a7.05 7.05 0 0 1 .82 2.86 44.19 44.19 0 0 1-3 15.2c-1.62 4.39-2.79 8.94-4.16 13.42l.56.42a10.27 10.27 0 0 0 2-1.3c4.74-5.1 9.39-10.29 14.17-15.36 5.12-5.45 10.33-10.81 15.54-16.18a14.07 14.07 0 0 1 2.22-1.53l.55.33c-.61 3.15-1.24 6.3-1.82 9.46-.85 4.64-1.92 9.25-1.05 14a8 8 0 0 0 10 6.14c4.7-1.31 8.08-4.41 11.46-7.59 1.79-1.69 3.45-3.52 5.21-5.26 4.09-4 5.86-9.49 8.62-14.33a6.91 6.91 0 0 1 1.64-2.28 7.66 7.66 0 0 1 2.61-.69c0 .85.41 1.9.05 2.51q-2.42 4-5.19 7.81a13.48 13.48 0 0 0-2.52 6.46 27 27 0 0 0 2.11 15.78 8.79 8.79 0 0 0 12.57 3.92c7.37-4.14 11.7-10.66 14.55-18.38a33.29 33.29 0 0 0 1.65-13.49 14.09 14.09 0 0 0-9.24-11.85 24.62 24.62 0 0 0-4.52-1.1c-.61-.07-1.32.65-2 1-.01.31 0 .6.01.89z"/>
           <path d="M200 92.82c1.49.39 3.25.83 5 1.32a10.41 10.41 0 0 1 6.43 4.79l-.29.59c-.48-.25-1.13-.38-1.4-.77-1.47-2.15-3.61-3-6.09-3.4 1.07 5.68-2.45 9.61-8.15 9.34-2.7-.13-4.12-2-3.39-4.55s2.59-4.14 4.78-5.42c1.04-.61 2.05-1.26 3.11-1.9zm-4.09 10.53l.3.54c1.9-1.08 4-1.9 5.61-3.32.77-.69.8-2.51.68-3.76a2.65 2.65 0 0 0-1.91-1.63c-2.53-.3-6.09 2.17-7.08 4.64-.91 2.18 0 3.54 2.38 3.53z"/>
          </g>
         </g>
        </g>
	</symbol>
</svg>
<?php zoa_preloader(); ?>
<?php 
$banner_limit = get_option('post_limit_banner_header', -1);
$bannerPosts = get_posts(array(
	'posts_per_page'	=> -1,
	'post_type'			=> 'post',
// 	 'meta_query' => array(
//         array(
//             'key' => 'show_for_promo_news', // name of custom field
//             'value' => '"yes"', // matches exactly "red"
//             'compare' => 'LIKE'
//         )
//     ),
	'tax_query' => array(
		array(
			'taxonomy' => 'category',
			'field' => 'slug',
			'terms' => array( 'info', 'events' ),
			'operator' => 'IN',
		)
	),
	'orderby' => 'date',
	'order' => 'DESC'
));

if( $bannerPosts ): ?>	
	<div class="row-fluid banner-ad-promo text-center">		
	<?php 
		$counter=0;
		foreach( $bannerPosts as $post ): 		
		setup_postdata( $post );		
		?>
		<div class="item" style="display: none;">
			<a data-id="<?php echo $post->ID; ?>" class="banner-ad-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>	
	<?php 
		$counter++;
		endforeach; ?>	
	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>

<div id="theme-container" class="<?php echo 'container-'.$lastslug; ?>">
			<!--snowing-->
	<!--<div class="snow_bg">
		<svg class="svg-snowscene" xmlns="http://www.w3.org/2000/svg">
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
  <circle class="snow" />
</svg>
	</div>-->
	
	<?php //zoa_before_content(); ?>

	<?php
	if ( function_exists( 'hfe_render_header' ) ) :

		//hfe_render_header();

	else :

	/*! SET LOGO IMAGE
    ------------------------------------------------->*/
    if( ! function_exists( 'zoa_logo_image' ) ):
        function zoa_logo_image(){
            $pid         = get_queried_object_id();
            $p_lg        = function_exists( 'fw_get_db_post_option' ) ? fw_get_db_post_option( $pid, 'p_lg' ) : '';
            $menu_layout = zoa_menu_slug();

            /*general logo*/
            $logo        = get_theme_mod( 'custom_logo' );

            /*logo src*/
            $logo_src    = ! empty( $logo ) ? wp_get_attachment_image_src( $logo, 'full' )[0] : get_template_directory_uri() . '/images/logo.svg';

            // retina logo
            $retina_logo     = get_theme_mod( 'retina_logo' );
            $retina_logo_src = ! empty( $retina_logo ) ? $retina_logo : get_template_directory_uri() . '/images/logo@2x.svg';

            $tag         = 'figure';
            $child_tag   = 'figcaption';

            if( is_front_page() ) {
                $tag       = 'h1';
                $child_tag = 'span';
            }

            ?>
                <<?php echo esc_attr( $tag ) . ' '; ?> class="theme-logo" itemscope itemtype="http://schema.org/Organization">
                    <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
						<span class="svg-wrapper"><svg class="svg" width="320" height="116" viewBox="0 0 320 116"><use href="#svg-logo" xlink:href="#svg-logo"/></svg></span>
                    </a>
                    <<?php echo esc_attr( $child_tag ); ?> class="screen-reader-text"><?php echo esc_attr( bloginfo( 'name' ) ); ?></<?php echo esc_attr( $child_tag ); ?>>
                </<?php echo esc_attr( $tag ); ?>>
            <?php
        }
    endif;
		?>
		
		<div id="theme-menu-layout">
		
			<?php if ( is_singular( 'product' ) ) {?>
			<div class="menu-layout menu-layout-custom menu-layout--classic">
				<header class="header-box static_header">					
					<div class="max-width--site container">
						<div class="header-scroll-logo">
							<div class="header-logo">
								<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
									<span class="svg-wrapper"><svg class="svg" width="320" height="116" viewBox="0 0 320 116"><use href="#svg-logo" xlink:href="#svg-logo"/></svg></span>
								</a>
						     </div><!-- .header-logo -->
						</div>
						 <div class="header-container">
							 <div class="menu-toggle col-lg-3">
								 <?php language_selector_flags(); ?>
								 <button id="theme-search-btn" class="zoa-icon-search js-search-button" style="display: none;"></button>
								 <button id="menu-toggle-btn"><span></span></button>
							 </div>
							 <div class="display--mid-only header-content col-lg-6">
								 <div class="header-logo">
									 <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
										 <span class="svg-wrapper"><svg class="svg" width="320" height="116" viewBox="0 0 320 116"><use href="#svg-logo" xlink:href="#svg-logo"/></svg></span>
									 </a>
								 </div><!-- .header-logo -->
							 </div>
							 <div class="header-action col-lg-3">
                                        <?php
                                        if ( class_exists( 'woocommerce' ) ) {
                                            zoa_wc_header_action();
                                        }
                                        ?>
                             </div><!-- .header-action -->
						</div>
						<div class="nav-container">
							<div class="header__secondary__content">
							<div class="theme-menu-box header-menu">
                                <?php
                                    if( has_nav_menu( 'primary' ) ):
                                        quadmenu(array("theme_location" => "primary", "menu_class" => "theme-primary-menu", "theme" => "custom_theme_1"));
                                    else:
                                ?>
                                    <a class="add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add Menu', 'zoa' ); ?></a>
                                <?php endif; ?>
							</div>
							<div class="header__user display--mid-only">
								<?php
                                        if ( class_exists( 'woocommerce' ) ) {
                                            zoa_wc_header_action_mobile();
                                        }
                                 ?>
							</div>
							</div><!--/header__secondary__content-->
						</div>
					</div>
				</header>
			</div>
			<?php }else{ ?>
			<div class="menu-layout menu-layout-custom menu-layout--classic">
				<header class="header-box sticky_header">
					<div class="max-width--site container">
						<div class="header-scroll-logo" style="display: none;">
							<div class="header-logo">
								<h1 class="theme-logo" itemscope itemtype="http://schema.org/Organization">
								<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
									<span class="svg-wrapper"><svg class="svg" width="320" height="116" viewBox="0 0 320 116"><use href="#svg-logo" xlink:href="#svg-logo"/></svg></span>
								</a>
								</h1>
						     </div><!-- .header-logo -->
						</div>
						 <div class="header-container">
							 <div class="menu-toggle col-lg-3">
								 <?php language_selector_flags(); ?>
								 <button id="theme-search-btn" class="zoa-icon-search js-search-button"></button>
								 <button id="menu-toggle-btn"><span></span></button>
							 </div>
							 <div class="header-content col-lg-6">
								 <div class="header-logo">
									 <h1 class="theme-logo" itemscope itemtype="http://schema.org/Organization">
									 <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
										 <span class="svg-wrapper"><svg class="svg" width="320" height="116" viewBox="0 0 320 116"><use href="#svg-logo" xlink:href="#svg-logo"/></svg></span>
										 </a>
									 </h1>
								 </div><!-- .header-logo -->
							 </div>
							 <div class="header-action col-lg-3">
                                        <?php
                                        if ( class_exists( 'woocommerce' ) ) {
                                            zoa_wc_header_action();
                                        }
                                        ?>
                             </div><!-- .header-action -->
						</div>
						<div class="nav-container">
							<div class="header__secondary__content">
							<div class="theme-menu-box header-menu">
                                <?php
                                    if( has_nav_menu( 'primary' ) ):
                                        quadmenu(array("theme_location" => "primary", "menu_class" => "theme-primary-menu", "theme" => "custom_theme_1"));
                                    else:
                                ?>
                                    <a class="add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add Menu', 'zoa' ); ?></a>
                                <?php endif; ?>
							</div>
							<div class="header__user display--mid-only">
								<?php
                                        if ( class_exists( 'woocommerce' ) ) {
                                            zoa_wc_header_action_mobile();
                                        }
                                 ?>
							</div>
							</div><!--/header__secondary__content-->
						</div>
					</div>
				</header>
			</div>
			<?php } ?>
			<span id="menu-overlay"></span>
		</div>

	<?php endif; ?>
	

	<div id="theme-page-header" class="">
	
		<?php zoa_page_header(); ?>
	</div>
    <script>
	
	
	</script>