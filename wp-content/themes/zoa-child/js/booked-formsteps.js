jQuery(function ($) {

    function getStepsData(formId) {
        var form = $("#" + formId),
                steps = form.find(".step"),
                stepNr,
                stepTitle,
                stepsData = [];

        $.each(steps, function (index, step) {
            var stepObj = {};

            stepObj.number = index + 1;
            stepObj.title = $(step).find("legend").text();

            stepsData.push(stepObj);
        });

        return stepsData;
    }


    function buildSteps(formId, data) {
        var form = $("#" + formId),
                wrapper = $("<ul class='form__steps'>"),
                //customwrapper = ("<div class='wrapper_steps'>") ,
                step = "<li class='form__step'>",
                container;

        $.each(data, function (index, item) {
            container = $("<div class='form__step-container'>");
            container.append("<span class='form__step-nr'>" + item.number + "</span>");
            container.append("<span class='form__step-title'>" + item.title + "</span>");

            //added
            //wrapper.wrap(customwrapper);

            wrapper.append(step);

            if (index === 0) {
                wrapper.children(".form__step").addClass("is-active");
            }

            wrapper.children(".form__step:last-child").append(container);

            container = $("<div class='form__step-container'>");
        });

        form.before(wrapper);
    }


    function foldForm(formId) {
        var form = $("#" + formId),
                steps = form.find(".step");

        $.each(steps, function (index, step) {
            if (index === 0) {
                $(step).addClass("is-active");
            } else {
                $(step).hide();
            }
        });
    }


    function setVisibilityButtons(formId) {
        var form = $("#" + formId),
                steps = form.find(".step"),
                firstStep = $(steps[0]),
                lastStep = $(steps[steps.length - 1]),
                submitBtn = $(form.find(".btn_submit")),
                prevBtn = $(form.find(".js-prev")),
                nextBtn = $(form.find(".js-next"));

        if (firstStep.hasClass("is-active")) {
            submitBtn.hide();
            prevBtn.hide();
            nextBtn.show();
        } else if (lastStep.hasClass("is-active")) {
            submitBtn.show();
            prevBtn.show();
            nextBtn.hide();
        } else {
            submitBtn.hide();
            prevBtn.show();
            nextBtn.show();
        }
    }


    function nextStep(formId) {
        var form = $("#" + formId),
                currentStep = $(form.find(".step.is-active")),
                nextStep = currentStep.next(".step"),
                currentStepTop = $(form.prev().find(".form__step.is-active")),
                nextStepTop = currentStepTop.next(".form__step");

        currentStep.removeClass("is-active").hide();
        nextStep.addClass("is-active").show();
        currentStepTop.removeClass("is-active");
        nextStepTop.addClass("is-active");
    }


    function prevStep(formId) {
        var form = $("#" + formId),
                currentStep = $(form.find(".step.is-active")),
                prevStep = currentStep.prev(".step"),
                currentStepTop = $(form.prev().find(".form__step.is-active")),
                prevStepTop = currentStepTop.prev(".form__step");

        currentStep.removeClass("is-active").hide();
        prevStep.addClass("is-active").show();
        currentStepTop.removeClass("is-active");
        prevStepTop.addClass("is-active");
    }


    function initForms() {
        var steppedFormSelector = ".form--stepped",
                formId,
                stepsData;
        $(steppedFormSelector).each(function () {
            formId = $(this).attr("id");

            // Get the staps data -- step numbers and titles
            stepsData = getStepsData(formId);

            // Build the steps navigation
            buildSteps(formId, stepsData);

            // Hide all but the first steps
            foldForm(formId);

            // Show / hide relevant buttons
            setVisibilityButtons(formId);

        });
        $(steppedFormSelector).css('opacity', 1);
    }
    ;


    // Initialize form validation
    /*$(".validate").validate({
     errorPlacement: function(error, element) {         
     error.insertBefore(element);
     },
     success: function(element) {
     if (element.parent().hasClass("checkboxes") || element.parent().hasClass("radiobuttons") ) {
     $(element).remove();
     }
     }
     });*/


    // Initialize all stepped forms
    initForms();

    // Next Step
    $("body").on("click", ".js-next:not(.disabled)", function (event) {
        event.preventDefault();
        var errors = [];
        var formId = 'bookedForm';// $(this).closest("form").attr("id");

        /*if ($("form#birs_appointment_form").length){
         $('#birs_appointment_date_error').hide();
         $('#birs_appointment_time_error').hide();  
         
         if ($('#step-1').hasClass('is-active'))
         {
         if (!$('#birs_appointment_date').val())
         {
         errors.push({element: '#birs_appointment_date', message: gl_date_is_tempty_text, scrollto: '#birs_appointment_datepicker'});
         }
         else if (!$('#birs_appointment_time').val())
         {
         errors.push({element: '#birs_appointment_time', message: gl_time_is_tempty_text, scrollto: '.birs_appointment_time'});
         }
         }
         
         var validateForm = $("form#birs_appointment_form");
         validateForm.validationEngine({
         promptPosition : 'inline',
         addFailureCssClassToField : "inputError",
         bindMethod : "live"
         });
         var isValid = validateForm.validationEngine('validate');
         if (!isValid) {
         return '';
         }
         }*/

        /*,
         isValid = $("#" + formId).valid();
         
         
         if ( isValid ) {
         nextStep(formId);
         setVisibilityButtons(formId);         
         }*/
        /*$(".validate").validation ({
         onValidationComplete: function(form, status){ 
         alert(status);
         if (status === true) {
         nextStep(formId);
         setVisibilityButtons(formId); 
         }
         }}
         );*/
        /*if (errors.length == 0)
         {
         nextStep(formId);
         setVisibilityButtons(formId); 
         
         scrollToFormTop(formId);
         
         }
         else {
         // Animate and show error
         $.each(errors, function(index, error){
         $("html, body").animate({ scrollTop: $(error.scrollto).offset().top - $('.sticky_header').outerHeight() }, 1000);
         $(error.element + '_error').html(error.message);
         $(error.element + '_error').show();
         })
         }*/
        if ($('#step2').hasClass('is-active')) {
            var showRequiredError = false;
            $('.booked-form').find('input,textarea,select').each(function (i, field) {

                var required = $(this).attr('required');

                if (required && $(field).attr('type') == 'hidden') {
                    var fieldParts = $(field).attr('name');
                    fieldParts = fieldParts.split('---');
                    fieldName = fieldParts[0];
                    fieldNumber = fieldParts[1].split('___');
                    fieldNumber = fieldNumber[0];

                    if (fieldName == 'radio-buttons-label') {
                        var radioValue = false;
                        $('input:radio[name="single-radio-button---' + fieldNumber + '[]"]:checked').each(function () {
                            if ($(this).val()) {
                                radioValue = $(this).val();
                            }
                        });
                        if (!radioValue) {
                            showRequiredError = true;
                        }
                    } else if (fieldName == 'checkboxes-label') {
                        var checkboxValue = false;
                        $('input:checkbox[name="single-checkbox---' + fieldNumber + '[]"]:checked').each(function () {
                            if ($(this).val()) {
                                checkboxValue = $(this).val();
                            }
                        });
                        if (!checkboxValue) {
                            showRequiredError = true;
                        }
                    }

                } else if (required && $(field).attr('type') != 'hidden' && $(field).val() == '') {
                    showRequiredError = true;
                }

            });

            if (showRequiredError) {
                $('form#newAppointmentForm p.status').show().html('<i class="booked-icon booked-icon-alert" style="color:#E35656"></i>&nbsp;&nbsp;&nbsp;' + booked_js_vars.i18n_fill_out_required_fields);
                return false;
            }
            $("input[name='action']").val('booked_fill_form_steps');
            var form_data = $("#newAppointmentForm").serialize(); //Encode form elements for submission
            $.ajax({
                action: 'booked_fill_form_steps',
                url: booked_js_vars.ajax_url,
                type: 'post',
                data: form_data
            }).done(function (response) { //
                $("input[name='action']").val('booked_add_appt');
                $('.ch-aq').html(response);
                $("#bookedForm .btn--2").css("display", "inline-block");
                $("#bookedForm .status").css("display", "none");
            });
        }
        nextStep(formId);
        setVisibilityButtons(formId);

        scrollToFormTop(formId);

    });

//submit form
    $("body").on("click", ".btn--2", function (event) {
        $("#submit-request-appointment").click();
    });

    // Prev Step
    $("body").on("click", ".js-prev", function (event) {

        event.preventDefault();
        $("#bookedForm .btn--2").css("display", "none");
        var formId = 'bookedForm';// $(this).closest("form").attr("id");

        prevStep(formId);
        setVisibilityButtons(formId);

        scrollToFormTop(formId);
    });

    function scrollToFormTop(formId)
    {
        $("html, body").animate({scrollTop: $('#' + formId).offset().top - 52}, 1000);
    }
    //wrap ul steps
    $('ul.form__steps').wrap('<div class="form__steps_wrap"></div>');
});