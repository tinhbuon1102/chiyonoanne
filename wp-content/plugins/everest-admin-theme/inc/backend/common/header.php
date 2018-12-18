<?php defined( 'ABSPATH' ) or die( "No script kiddies please!" ); ?>
<div id="<?php echo E_ADMIN_PLUGIN_PREFIX; ?>-admin-main-wrapper" class="<?php echo E_ADMIN_PLUGIN_PREFIX; ?>-admin-main-wrapper">
	<div class="<?php echo E_ADMIN_PLUGIN_PREFIX; ?>-header-wrapper clearfix">
        <div class="<?php echo E_ADMIN_PLUGIN_PREFIX; ?>-headerlogo">
        	<?php /* ?>
             <span  class="<?php echo E_ADMIN_PLUGIN_PREFIX; ?>-headerlogo"><?php _e('Everest Admin Theme', 'everest-admin-theme'); ?></span>
               <?php */ ?>
            <img src='<?php echo E_ADMIN_THEME_IMAGE_DIR; ?>/header-logo.png' alt='Header logo' />
        </div>
        <div class="<?php echo E_ADMIN_PLUGIN_PREFIX; ?>-header-icons">
            <?php  ?>
            <p><?php _e( 'Follow us for new updates', 'everest-admin-theme' ); ?></p>
            <div class="<?php echo E_ADMIN_PLUGIN_PREFIX; ?>-social-btns">
                <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=1411139805828592" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:50px " allowtransparency="true"></iframe>
                &nbsp;&nbsp;
                <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" src="http://platform.twitter.com/widgets/follow_button.5f46501ecfda1c3e1c05dd3e24875611.en.html#_=1421918256492&amp;dnt=true&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=apthemes&amp;show_count=false&amp;show_screen_name=true&amp;size=m" class="twitter-follow-button twitter-follow-button" title="Twitter Follow Button" data-twttr-rendered="true" style="width: 126px; height: 20px;"></iframe>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, "script", "twitter-wjs");</script>

            </div>
            <?php ?>
        </div>
    </div>
