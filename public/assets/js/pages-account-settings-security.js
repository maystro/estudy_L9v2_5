"use strict";
document.addEventListener("DOMContentLoaded", function (e) {
    {
        const t = document.querySelector("#formAccountSettings"),
            n = document.querySelector("#formAccountSettingsApiKey");
        return t && FormValidation.formValidation(t, {
            fields: {
                currentPassword: {
                    validators: {
                        notEmpty: {message: "كلمة المرور الحالية"},
                        stringLength: {min: 8, message: "كلمة المرور لا تقل عن ٨ حروف"}
                    }
                },
                newPassword: {
                    validators: {
                        notEmpty: {message: "من فضلك ادخل كلمة المرور الجديدة"},
                        stringLength: {min: 8, message: "كلمة المرور لا تقل عن ٨ حروف"}
                    }
                },
                confirmPassword: {
                    validators: {
                        notEmpty: {message: "من فضلك قم بتأكيد كلمة المرور"},
                        identical: {
                            compare: function () {
                                return t.querySelector('[name="newPassword"]').value
                            }, message: "لابد من تطابق كلمة المرور الجديدة مع التأكيد"
                        },
                        stringLength: {min: 8, message: "كلمة المرور لا تقل عن ٨ حروف"}
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger,
                bootstrap5: new FormValidation.plugins.Bootstrap5({eleValidClass: "", rowSelector: ".col-md-6"}),
                submitButton: new FormValidation.plugins.SubmitButton,
                autoFocus: new FormValidation.plugins.AutoFocus
            },
            init: e => {
                e.on("plugins.message.placed", function (e) {
                    e.element.parentElement.classList.contains("input-group") && e.element.parentElement.insertAdjacentElement("afterend", e.messageElement)
                })
            }
        }), void (n && FormValidation.formValidation(n, {
            fields: {apiKey: {validators: {notEmpty: {message: "Please enter API key name"}}}},
            plugins: {
                trigger: new FormValidation.plugins.Trigger,
                bootstrap5: new FormValidation.plugins.Bootstrap5({eleValidClass: ""}),
                submitButton: new FormValidation.plugins.SubmitButton,
                autoFocus: new FormValidation.plugins.AutoFocus
            },
            init: e => {
                e.on("plugins.message.placed", function (e) {
                    e.element.parentElement.classList.contains("input-group") && e.element.parentElement.insertAdjacentElement("afterend", e.messageElement)
                })
            }
        }))
    }
}), $(function () {
    var e = $(".select2");
    e.length && e.each(function () {
        var e = $(this);
        e.wrap('<div class="position-relative"></div>'), e.select2({dropdownParent: e.parent()})
    })
});
