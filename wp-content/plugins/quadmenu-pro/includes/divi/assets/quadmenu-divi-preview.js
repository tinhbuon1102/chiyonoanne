(function ($) {

  var save = false;

  $(document).on('quadmenu_compiler_end', function (e, notice) {
    $('nav#quadmenu').addClass('js');
  });

  $(document).on('quadmenu_divi_customizer', function (e, customized, action) {

    if (typeof (quadmenu) == 'undefined')
      return;

    if (!quadmenu.files)
      return;

    if (!action)
      return;

    if (!customized)
      return;

    if (action === 'change') {
      save = true;
      $('nav#quadmenu').removeClass('js');
    }

    $.ajax({
      type: 'post',
      dataType: 'json',
      url: ajaxurl,
      data: {
        action: 'quadmenu_divi_customized',
        customized: customized,
        nonce: quadmenu.nonce,
      },
      success: function (response) {

        console.log('Customized [' + JSON.stringify(response.data) + ']');

        $(document).trigger('quadmenu_compiler_files', [quadmenu.files, response.data, action]);

      },
      error: function (response) {
        console.log(response.responseText);

        $(document).trigger('quadmenu_compiler_end');
      },
      complete: function (xhr, status, error) {
      }
    });

  });

  function monitor_events(object_path) {
    var p = eval(object_path);
    if (p) {
      var k = _.keys(p.topics);
      console.log(object_path + " has events ", k);
      _.each(k, function (a) {
        p.bind(a, function () {
          console.log(object_path + ' event ' + a, arguments);
        });
      });
    } else {
      console.log(object_path + ' does not exist');
    }
  }

  wp.customize.bind('preview-ready', function () {
    //monitor_events('wp.customize.preview');

    wp.customize('et_divi[menu_height]', function (value) {
      value.bind(function (to) {

        save = true;

        $('style#quadmenu_customizer_menu_height', 'head').remove();

        $('head').append('<style id="quadmenu_customizer_menu_height">#top-menu-nav #quadmenu.quadmenu-is-horizontal .quadmenu-navbar-nav > li > a {  padding-bottom:' + (to / 2) + 'px;}</style>');

      });
    });

    wp.customize('et_divi[minimized_menu_height]', function (value) {
      value.bind(function (to) {

        save = true;

        $('style#quadmenu_customizer_minimized_menu_height', 'head').remove();

        $('head').append('<style id="quadmenu_customizer_minimized_menu_height">.et-fixed-header #top-menu-nav #quadmenu.quadmenu-is-horizontal .quadmenu-navbar-nav > li > a {  padding-bottom:' + (to / 2) + 'px;}</style>');

      });
    });

    wp.customize('et_divi[fixed_menu_link]', function (value) {
      value.bind(function (to) {
        save = true;

        $('style#quadmenu_customizer_fixed_menu_link', 'head').remove();

        $('head').append('<style id="quadmenu_customizer_fixed_menu_link">.et-fixed-header #top-menu-nav #quadmenu .quadmenu-navbar-nav > li > a > .quadmenu-item-content { color: ' + to + '; }</style>');

      });
    });
    wp.customize('et_divi[fixed_menu_link_active]', function (value) {
      value.bind(function (to) {

        console.log(to);

        save = true;

        $('style#quadmenu_customizer_fixed_menu_link_active', 'head').remove();

        $('head').append('<style id="quadmenu_customizer_fixed_menu_link_active">.et-fixed-header #top-menu-nav #quadmenu .quadmenu-navbar-nav > li > a:hover > .quadmenu-item-content { color: ' + to + '; }</style>');

      });
    });

    _.each(quadmenu.customizer_settings, function (id) {
      wp.customize(id, function (value) {
        value.bind(_.debounce(function (to) {

          $(document).trigger('quadmenu_divi_customizer', [to, 'change']);

        }, 500));
      });
    });

    wp.customize.preview.bind('saved', function (to) {
      if (save) {
        $(document).trigger('quadmenu_divi_customizer', [to, 'save']);
      }
    });

    wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {
      $('nav#quadmenu').addClass('js')
    });
  });


})(jQuery);