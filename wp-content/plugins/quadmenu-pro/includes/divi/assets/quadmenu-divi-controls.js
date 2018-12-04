(function ($) {
  'use strict';

  wp.customize('et_divi[primary-menu_integration]', function (setting) {
    var isHeaderTextDisplayed, linkSettingValueToControlActiveState;

    isHeaderTextDisplayed = function () {
      return setting.get();
    };
    linkSettingValueToControlActiveState = function (control) {
      var setActiveState = function () {
        control.active.set(isHeaderTextDisplayed());
      };
      control.active.validate = isHeaderTextDisplayed;
      setActiveState();
      setting.bind(setActiveState);
    };

    $.each(quadmenu.custom_settings, function (key, id) {
      wp.customize.control(id, linkSettingValueToControlActiveState);
    });
  });

})(jQuery);

//https://make.xwp.co/2016/07/24/dependently-contextual-customizer-controls/
