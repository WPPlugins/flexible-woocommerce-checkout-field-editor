"use strict";
(function ($, editorsDescription) {
    function initTextBox(textBoxDescription) {
        if (textBoxDescription.defaultValue)
            $("#" + textBoxDescription.name).val(textBoxDescription.defaultValue);
    }

    function initTextArea(textAreaDescription) {
        if (textAreaDescription.defaultValue)
            $("#" + textAreaDescription.name).val(textAreaDescription.defaultValue);
    }

    function initPassword(passwordDescription) {
        if (passwordDescription.defaultValue)
            $("#" + passwordDescription.name).val(passwordDescription.defaultValue);
    }

    function initCheckBox(checkBoxDescription) {
        if (checkBoxDescription.defaultValue)
            $("#" + checkBoxDescription.name).prop('checked', checkBoxDescription.defaultValue);
    }

    function initSelect(selectDescription) {
        $("#" + selectDescription.name).select2();
        $("#" + selectDescription.name).select2().select2("val", null);

        if (selectDescription.defaultValue)
            $("#" + selectDescription.name).select2().select2("val", selectDescription.defaultValue);
        else
            $("#" + selectDescription.name + "_field").find(".select2-choice .select2-chosen").html('&nbsp;');
    }

    function initMultiSelect(multiselectDescription) {
        $("#" + multiselectDescription.name).attr("multiple", "multiple");
        $("#" + multiselectDescription.name).select2();
        $("#" + multiselectDescription.name).select2().select2("val", null);

        if (multiselectDescription.defaultValue) {
            var arrayDefaultValues = JSON.parse(multiselectDescription.defaultValue);
            $("#" + multiselectDescription.name).select2().select2("val", arrayDefaultValues);
        }

        var newId = multiselectDescription.name + "[]";
        $("#" + multiselectDescription.name).attr("name", newId);
    }

    function initDatePicker(datePickerDescription) {
        $("#" + datePickerDescription.name).datepicker({
            showWeek: datePickerDescription.showWeek,
            firstDay: datePickerDescription.firstDay,
            dateFormat: datePickerDescription.dateFormat,
            numberOfMonths: datePickerDescription.numberOfMonths,
            changeYear: datePickerDescription.changeYear,
            changeMonth: datePickerDescription.changeMonth
        });

        if (datePickerDescription.maxDate)
            $("#" + datePickerDescription.name).datepicker("option", "maxDate", datePickerDescription.maxDate);

        if (datePickerDescription.minDate)
            $("#" + datePickerDescription.name).datepicker("option", "minDate", datePickerDescription.minDate);

        if (datePickerDescription.defaultSelectedDate)
            $("#" + datePickerDescription.name).datepicker("setDate", datePickerDescription.defaultSelectedDate);
    }

    function prepareEditors(fieldDescriptions) {
        for (var indexField = 0; indexField < fieldDescriptions.length; indexField++) {
            var currentField = fieldDescriptions[indexField];
            if (currentField.standartField === true)
                continue;
            switch (currentField.typeField) {
                case 0:
                {
                    initTextBox(currentField);
                    break;
                }
                case 1:
                {
                    initTextArea(currentField);
                    break;
                }
                case 2:
                {
                    initPassword(currentField);
                    break;
                }
                case 3:
                {
                    initCheckBox(currentField);
                    break;
                }
                case 4:
                {
                    initSelect(currentField);
                    break;
                }
                case 5:
                {
                    initMultiSelect(currentField);
                    break;
                }
                case 6:
                {
                    initDatePicker(currentField);
                    break;
                }
            }

        }
    }


    $().ready(function () {
        if (window.checkoutFieldsEditor.billingEditorsDescription) {
            var billingFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.billingEditorsDescription);
            if (billingFieldDescriptions) {
                prepareEditors(billingFieldDescriptions);
            }
        }

        if (window.checkoutFieldsEditor.shippingEditorsDescription) {
            var shippingFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.shippingEditorsDescription);
            if (shippingFieldDescriptions) {
                prepareEditors(shippingFieldDescriptions);
            }
        }

        if (window.checkoutFieldsEditor.accountEditorsDescription) {
            var accountFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.accountEditorsDescription);
            if (accountFieldDescriptions) {
                prepareEditors(accountFieldDescriptions);
                setDependsOfVisibility(accountFieldDescriptions);
            }
        }
        if (window.checkoutFieldsEditor.orderEditorsDescription) {
            var orderFieldDescriptions = JSON.parse(window.checkoutFieldsEditor.orderEditorsDescription);
            if (orderFieldDescriptions) {
                prepareEditors(orderFieldDescriptions);
                setDependsOfVisibility(orderFieldDescriptions);
            }
        }

    });


})(jQuery, checkoutFieldsEditor.editorsDescription);
