(function ($) {

  var save = false;

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

  $(document).on('quadmenu_compiler_end', function (e, notice) {
    $('nav#quadmenu').addClass('js').removeClass('customize-partial-refreshing');
  });

  $(document).on('quadmenu_customize', function (e, customized, action) {

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
        action: 'quadmenu_customize',
        customized: customized,
        change: (action === 'change'),
        nonce: quadmenu.nonce,
      },
      success: function (response) {

        console.log('Customized [' + customized + ']');

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

  wp.customize.bind('preview-ready', function () {
    //monitor_events('wp.customize.preview');

    if (typeof (quadmenu) == 'undefined')
      return;

    if (!quadmenu.global)
      return;

    wp.customize.preview.bind('setting', _.debounce(function (to) {

      if ($.inArray(to[0], quadmenu.options_refresh) === -1) {

        if (to[0].match(RegExp(quadmenu.global + '[(?<=\\[).+?(?=\\])]', 'g'))) {
          $(document).trigger('quadmenu_customize', [to, 'change']);
        }

      }

    }, 500));

    wp.customize.preview.bind('saved', function (to) {
      if (save) {
        $(document).trigger('quadmenu_customize', [to, 'save']);
      }
    });

    wp.customize.selectiveRefresh.bind('partial-content-rendered', function (placement) {

      $('nav#quadmenu').quadmenu();

      console.log('QuadMenu rendered...');
    });

    wp.customize.preview.bind('loading-initiated', function (to) {

      $.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
          quadmenu_customize: true,
        },
      });

      console.log('QuadMenu settings loaded...');

    });

    //wp.customize.preview.bind('active', function (to) {
    //});

  });


})(jQuery);