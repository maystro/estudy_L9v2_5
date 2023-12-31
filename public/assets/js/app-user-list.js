"use strict";
$(function () {
    var e, a = $("#datatables-basic"), t = $(".select2"), r = "app-user-view-account.html", n = {
        1: {title: "Pending", class: "bg-label-warning"},
        2: {title: "Active", class: "bg-label-success"},
        3: {title: "Inactive", class: "bg-label-secondary"}
    };
    t.length && (t = t).wrap('<div class="position-relative"></div>').select2({
        placeholder: "Select Country",
        dropdownParent: t.parent()
    }), a.length && (e = a.DataTable({
        columns: [{data: ""}, {data: "full_name"}, {data: "role"}, {data: "current_plan"}, {data: "billing"}, {data: "status"}, {data: "action"}],
        columnDefs: [{
            className: "control",
            searchable: !1,
            orderable: !1,
            responsivePriority: 2,
            targets: 0,
            render: function (e, a, t, s) {
                return ""
            }
        }, {
            targets: 1, responsivePriority: 4, render: function (e, a, t, s) {
                var n = t.full_name, l = t.email, o = t.avatar;
                return '<div class="d-flex justify-content-start align-items-center"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">' + (o ? '<img src="' + assetsPath + "/img/avatars/" + o + '" alt="Avatar" class="rounded-circle">' : '<span class="avatar-initial rounded-circle bg-label-' + ["success", "danger", "warning", "info", "dark", "primary", "secondary"][Math.floor(6 * Math.random())] + '">' + (o = (((o = (n = t.full_name).match(/\b\w/g) || []).shift() || "") + (o.pop() || "")).toUpperCase()) + "</span>") + '</div></div><div class="d-flex flex-column"><a href="' + r + '" class="text-body text-truncate"><span class="fw-semibold">' + n + '</span></a><small class="text-muted">' + l + "</small></div></div>"
            }
        }, {
            targets: 2, render: function (e, a, t, s) {
                t = t.role;
                return "<span class='text-truncate d-flex align-items-center'>" + {
                    Subscriber: '<span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2"><i class="bx bx-user bx-xs"></i></span>',
                    Author: '<span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2"><i class="bx bx-cog bx-xs"></i></span>',
                    Maintainer: '<span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2"><i class="bx bx-pie-chart-alt bx-xs"></i></span>',
                    Editor: '<span class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2"><i class="bx bx-edit bx-xs"></i></span>',
                    Admin: '<span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2"><i class="bx bx-mobile-alt bx-xs"></i></span>'
                }[t] + t + "</span>"
            }
        }, {
            targets: 3, render: function (e, a, t, s) {
                return '<span class="fw-semibold">' + t.current_plan + "</span>"
            }
        }, {
            targets: 5, render: function (e, a, t, s) {
                t = t.status;
                return '<span class="badge ' + n[t].class + '">' + n[t].title + "</span>"
            }
        }, {
            targets: -1, title: "Actions", searchable: !1, orderable: !1, render: function (e, a, t, s) {
                return '<div class="d-inline-block"><button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button><div class="dropdown-menu dropdown-menu-end"><a href="' + r + '" class="dropdown-item">View</a><a href="javascript:;" class="dropdown-item">Suspend</a><div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></div></div></div>'
            }
        }],
        order: [[1, "desc"]],
        dom: '<"row mx-2"<"col-md-2"<"me-3"l>><"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {sLengthMenu: "_MENU_", search: "", searchPlaceholder: "Search.."},
        buttons: [{
            extend: "collection",
            className: "btn btn-label-secondary dropdown-toggle mx-3",
            text: '<i class="bx bx-upload me-2"></i>Export',
            buttons: [{
                extend: "print",
                text: '<i class="bx bx-printer me-2" ></i>Print',
                className: "dropdown-item",
                exportOptions: {columns: [2, 3, 4, 5]}
            }, {
                extend: "csv",
                text: '<i class="bx bx-file me-2" ></i>Csv',
                className: "dropdown-item",
                exportOptions: {columns: [2, 3, 4, 5]}
            }, {
                extend: "excel",
                text: "Excel",
                className: "dropdown-item",
                exportOptions: {columns: [2, 3, 4, 5]}
            }, {
                extend: "pdf",
                text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
                className: "dropdown-item",
                exportOptions: {columns: [2, 3, 4, 5]}
            }, {
                extend: "copy",
                text: '<i class="bx bx-copy me-2" ></i>Copy',
                className: "dropdown-item",
                exportOptions: {columns: [2, 3, 4, 5]}
            }]
        }, {
            text: '<i class="bx bx-plus me-0 me-sm-2"></i><span class="d-none d-lg-inline-block">مستخدم جديد</span>',
            className: "add-new btn btn-primary",
            attr: {"data-bs-toggle": "offcanvas", "data-bs-target": "#offcanvasAddUser"}
        }],
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function (e) {
                        return "Details of " + e.data().full_name
                    }
                }), type: "column", renderer: function (e, a, t) {
                    t = $.map(t, function (e, a) {
                        return "" !== e.title ? '<tr data-dt-row="' + e.rowIndex + '" data-dt-column="' + e.columnIndex + '"><td>' + e.title + ":</td> <td>" + e.data + "</td></tr>" : ""
                    }).join("");
                    return !!t && $('<table class="table"/><tbody />').append(t)
                }
            }
        },
        initComplete: function () {
            this.api().columns(2).every(function () {
                var a = this,
                    t = $('<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Role </option></select>').appendTo(".user_role").on("change", function () {
                        var e = $.fn.dataTable.util.escapeRegex($(this).val());
                        a.search(e ? "^" + e + "$" : "", !0, !1).draw()
                    });
                a.data().unique().sort().each(function (e, a) {
                    t.append('<option value="' + e + '">' + e + "</option>")
                })
            }), this.api().columns(3).every(function () {
                var a = this,
                    t = $('<select id="UserPlan" class="form-select text-capitalize"><option value=""> Select Plan </option></select>').appendTo(".user_plan").on("change", function () {
                        var e = $.fn.dataTable.util.escapeRegex($(this).val());
                        a.search(e ? "^" + e + "$" : "", !0, !1).draw()
                    });
                a.data().unique().sort().each(function (e, a) {
                    t.append('<option value="' + e + '">' + e + "</option>")
                })
            }), this.api().columns(5).every(function () {
                var a = this,
                    t = $('<select id="FilterTransaction" class="form-select text-capitalize"><option value=""> Select Status </option></select>').appendTo(".user_status").on("change", function () {
                        var e = $.fn.dataTable.util.escapeRegex($(this).val());
                        a.search(e ? "^" + e + "$" : "", !0, !1).draw()
                    });
                a.data().unique().sort().each(function (e, a) {
                    t.append('<option value="' + n[e].title + '" class="text-capitalize">' + n[e].title + "</option>")
                })
            })
        }
    })), $(".datatables-users tbody").on("click", ".delete-record", function () {
        e.row($(this).parents("tr")).remove().draw()
    }), setTimeout(() => {
        $(".dataTables_filter .form-control").removeClass("form-control-sm"), $(".dataTables_length .form-select").removeClass("form-select-sm")
    }, 300)
}), function () {
    const e = document.querySelectorAll(".phone-mask"), a = document.getElementById("addNewUserForm");
    e && e.forEach(function (e) {
        new Cleave(e, {phone: !0, phoneRegionCode: "US"})
    });
    FormValidation.formValidation(a, {
        fields: {
            userFullname: {validators: {notEmpty: {message: "Please enter fullname "}}},
            userEmail: {
                validators: {
                    notEmpty: {message: "Please enter your email"},
                    emailAddress: {message: "The value is not a valid email address"}
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger,
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: "", rowSelector: function (e, a) {
                    return ".mb-3"
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton,
            autoFocus: new FormValidation.plugins.AutoFocus
        }
    })
}();
