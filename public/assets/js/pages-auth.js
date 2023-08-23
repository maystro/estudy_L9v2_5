"use strict";
const formAuthentication = document.querySelector("#formAuthentication");
document.addEventListener("DOMContentLoaded", function (e) {
    {
        formAuthentication && FormValidation.formValidation(formAuthentication, {
            fields: {
                username: {
                    validators: {
                        notEmpty: {message: "من فضلك أدخل اسم المستخدم"},
                        stringLength: {min: 6, message: "اسم المستخدم لا يقل عن ٦ أحرف"}
                    }
                },
                email: {
                    validators: {
                        notEmpty: {message: "من فضلك أدخل الإيميل"},
                        emailAddress: {message: "البريد غير صحيح"}
                    }
                },
                "email-username": {
                    validators: {
                        notEmpty: {message: "من فضلك أدخل اسم المستخدم"},
                        stringLength: {min: 6, message: "الإسم لا يقل عن ٦ أحرف"}
                    }
                },
                password: {
                    validators: {
                        notEmpty: {message: "من فضلك أدخل كلمة المرور"},
                        stringLength: {min: 6, message: "كلمة المرور لا تقل عن ٦ أحرف"}
                    }
                },
                "confirm-password": {
                    validators: {
                        notEmpty: {message: "من فضلك قم بتأكيد كلمة المرور"},
                        identical: {
                            compare: function () {
                                return formAuthentication.querySelector('[name="password"]').value
                            }, message: "كلمة المرور غير مؤكدة"
                        },
                        stringLength: {min: 6, message: "كلمة المرور لا تقل عن ٦ أحرف"}
                    }
                },
                "password_confirmation": {
                    validators: {
                        notEmpty: {message: "من فضلك قم بتأكيد كلمة المرور"},
                        identical: {
                            compare: function () {
                                return formAuthentication.querySelector('[name="password"]').value
                            }, message: "كلمة المرور غير مؤكدة"
                        },
                        stringLength: {min: 6, message: "كلمة المرور لا تقل عن ٦ أحرف"}
                    }
                },
                terms: {validators: {notEmpty: {message: "لابد من إثبات الهوية"}}}
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger,
                bootstrap5: new FormValidation.plugins.Bootstrap5({eleValidClass: "", rowSelector: ".mb-3"}),
                submitButton: new FormValidation.plugins.SubmitButton,
                defaultSubmit: new FormValidation.plugins.DefaultSubmit,
                autoFocus: new FormValidation.plugins.AutoFocus
            },
            init: e => {
                e.on("plugins.message.placed", function (e) {
                    e.element.parentElement.classList.contains("input-group") && e.element.parentElement.insertAdjacentElement("afterend", e.messageElement)
                })
            }
        });
        const t = document.querySelectorAll(".numeral-mask");
        return void (t.length && t.forEach(e => {
            new Cleave(e, {numeral: !0})
        }))
    }
});
