(function ($) {

  $('body').on('check_dependencies', function (e, variable) {
    quadmenu.customizer.dependencies(variable);
  });

  quadmenu.customizer = quadmenu.customizer || {};

  quadmenu.customizer.required = function () {

    $.each(redux.folds, function (i, v) {

      var fieldset = $('#' + redux.args.opt_name + '-' + i);

      if (v == 'hide') {
        fieldset.addClass('hide').hide();
      }
    });
  };

  quadmenu.customizer.dependencies = function (variable) {

    if (redux.required === null) {
      return;
    }

    var current = $(variable),
            id = current.parents('.redux-field:first').data('id');

    if (!redux.required.hasOwnProperty(id)) {
      return;
    }

    var container = current.parents('.redux-field-container:first'),
            is_hidden = container.parents('.customize-control:first').hasClass('hide');

    $.each(redux.required[id], function (child, dependents) {

      var current = $(this),
              show = false,
              childFieldset = $('#' + redux.args.opt_name + '-' + child);

      if (!is_hidden) {
        show = $.redux.check_parents_dependencies(child);
      }

      if (show === true) {

        childFieldset.fadeIn(300, function () {

          $(this).removeClass('hide');

          if (redux.required.hasOwnProperty(child)) {
            quadmenu.customizer.dependencies($('#' + redux.args.opt_name + '-' + child).children().first());
          }

          $.redux.initFields();
        });

      }

      if (show === false) {

        childFieldset.fadeOut(100, function () {
          $(this).addClass('hide');
          if (redux.required.hasOwnProperty(child)) {
            //console.log('Now check, reverse: '+child);
            $.redux.required_recursive_hide(child);
          }
        });
      }

      current.find('select, radio, input[type=checkbox]').trigger('change');
    });
  };

  quadmenu.customizer.init = function () {

    $('.accordion-section.redux-section, .accordion-section.redux-panel, .accordion-section-title').click(function () {
      $.redux.initFields();
    });

    redux.args.disable_save_warn = true;

    console.log('redux_init');

    var reduxChange = redux_change;

    redux_change = function (variable) {
      variable = $(variable);
      reduxChange.apply(this, arguments);

      console.log('redux_change');

      quadmenu.customizer.save(variable)
    };

    var redux_initFields = $.redux.initFields;

    $.redux.initFiles = function () {
      redux_initFields();
    }
  };

  quadmenu.customizer.save = function ($obj) {

    var $parent = $obj.hasClass('redux-field') ? $obj : $obj.parents('.redux-field-container:first');

    quadmenu.customizer.inputSave($parent);
  };

  quadmenu.customizer.inputSave = function ($parent) {

    if (!$parent.hasClass('redux-field-container')) {
      $parent = $parent.parents('[class^="redux-field-container"]');
    }

    var $field = $parent.parent().find('.redux-customizer-input') || $parent.parents('.redux-container-repeater:first').parent().find('.redux-customizer-input'),
            $id = $field.data('id');

    var $nData = $parent.find(':input').serializeJSON();

    $.each($nData, function ($k, $v) {
      $nData = $v;
    });

    var $key = $parent.parent().find('.redux-customizer-input').data('key');

    if ($nData[$key]) {
      $nData = $nData[$key];
    }

    var $control = wp.customize.control($id);

    // Customizer hack since they didn't code it to save order...
    //if (JSON.stringify($control.setting._value) !== JSON.stringify($nData)) {
    $control.setting._value = null;
    //}

    $control.setting.set($nData);

    console.log($nData);

  }

  wp.customize.bind('ready', function () {

    quadmenu.customizer.init();

    quadmenu.customizer.required();

  });

  // Fix
  // -------------------------------------------------------------------------
  //wp.customize.bind('ready', function () {
  //var $set = $('.redux-field-container.redux-container-button_set');
  //$set.on('change', 'input[type=radio]', function (e) {
  //var id = $(this).data('id');
  //wp.customize.instance(quadmenu.global + '[' + id + ']').set('blank');
  //});
  //});

  //$('.menu-item-handle').on('click', function (e) {
  //    e.preventDefault();
  //    e.stopPropagation();
  //    alert('test');
  //});

  //wp.customize.control('display_about_text', function (control) {
  //    control.deferred.embedded.done(function () {
  //        control.container.find('label').wrapInner('<strong></strong>');
  //    });
  //});

  //wp.customize.bind('pane-contents-reflowed ', function () {
  //monitor_events('wp.customize');
  //var $control = $('.customize-control-nav_menu_item');
  //$control.hide();//append($("<span>").addClass('quadmenu_open').html('QuadMenu'));
  //});

})(jQuery);