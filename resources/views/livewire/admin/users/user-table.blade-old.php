<div>
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">المسجلين بالموقع</h5>
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <!-- Dropdown with Icon -->
                <div class="btn-group me-3">
                    <button class="btn btn-label-primary dropdown-toggle" type="button" id="dropdownMenuButtonIcon"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span><i class="bx bx-export me-0 me-sm-0"></i>
                            <span class="d-none d-lg-inline-block">تصدير</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonIcon">
                        <li><a class="dropdown-item d-flex align-items-center"
                               wire:click="export_print"
                               href="javascript:void(0);">
                                <i class="bx bx-printer me-2 scaleX-n1-rtl"></i> طباعة</a></li>

                        <li><a class="dropdown-item d-flex align-items-center"
                               wire:click="export_pdf"
                               href="javascript:void(0);">
                                <i class="bx bxs-file-pdf me-2 scaleX-n1-rtl"></i> ملف PDF</a></li>

                        <li><a class="dropdown-item d-flex align-items-center"
                               wire:click="export_excel"
                               href="javascript:void(0);"><i
                                        class="bx bxs-file me-2 scaleX-n1-rtl"></i> ملف اكسل</a></li>
                    </ul>
                </div>
                <!-- Icon Dropdown -->
                <button type="button" class="btn btn-primary me-2">
                    <span><i class="bx bx-plus me-0 me-sm-0"></i>
                        <span class="d-none d-lg-inline-block">إضافة مستخدم</span>
                    </span>
                </button>
            </div>
        </div>

        <div class="card-body">


            <div class="d-flex justify-content-between align-items-center row py-2 gap-2 gap-md-0">

                <div class="col-md-4">
                    <input class="form-control" wire:model="searchStr" type="text" value="" id="html5-text-input"
                           placeholder="بحث عن ..."/>
                </div>

                <div class="col-md-4 user_role">
                    <select id="UserRole" wire:model="role" class="form-select text-capitalize">
                        <option value="">اختر نوع المستخدم</option>
                        @foreach(\App\Enums\Roles::asArray() as $role)
                            <option value="{{$role}}">{{$role}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 user_status">
                    <select id="FilterTransaction" wire:model="status" class="form-select text-capitalize">
                        <option value="">حالة التفعيل</option>
                        <option value="pending" class="text-capitalize">Pending</option>
                        <option value="active" class="text-capitalize">Active</option>
                        <option value="inactive" class="text-capitalize">Inactive</option>
                    </select>
                </div>

            </div>


            <hr class="hr my-5 divide-red-300">

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th><input wire:model="selectAll" type="checkbox" class="dt-checkboxes form-check-input"></th>
                        <th>الإسم</th>
                        <th>الإيميل</th>
                        <th>الاشتراك</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($users as $user)

                        <tr>
                            <td>
                                <input type="checkbox"
                                       wire:model="selectedItems.{{$user->id}}"
                                       value="{{$user->id}}"
                                       class="dt-checkboxes form-check-input">
                            </td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-2">
                                            <img src="{{$user->getProfilePhoto()}}" alt="Avatar" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="emp_name text-truncate">{{$user->name}}</span>
                                        <small class="emp_post text-truncate text-muted">{{$user->details->job}}</small>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <span class="emp_name text-truncate">{{$user->email}}</span>
                            </td>

                            <td>
                                @switch($user->roles[0]->title)
                                    @case (\App\Enums\Roles::Admin)
                                    <span class="text-truncate d-flex align-items-center">
                                    <span
                                            class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2">
                                        <i class="bx bx-mobile-alt bx-xs"></i>
                                    </span>Admin</span>
                                    @break;
                                    @case (\App\Enums\Roles::Author)
                                    <span class="text-truncate d-flex align-items-center">
                                    <span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2">
                                        <i class="bx bx-cog bx-xs"></i>
                                    </span>Author</span>
                                    @break;
                                    @case (\App\Enums\Roles::Editor)
                                    <span class="text-truncate d-flex align-items-center">
                                    <span class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2">
                                        <i class="bx bx-edit bx-xs"></i>
                                    </span>Editor</span>
                                    @break;
                                    @case (\App\Enums\Roles::Maintainer)
                                    <span class="text-truncate d-flex align-items-center">
                                    <span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2">
                                        <i class="bx bx-pie-chart-alt bx-xs"></i>
                                    </span>Maintainer</span>
                                    @break;
                                    @case (\App\Enums\Roles::Teacher)
                                    <span class="text-truncate d-flex align-items-center">
                                    <span
                                            class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2">
                                        <i class="bx bx-speaker bx-xs"></i>
                                    </span>Teacher</span>
                                    @break;
                                    @case (\App\Enums\Roles::Student)
                                    <span class="text-truncate d-flex align-items-center">
                                    <span class="badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2">
                                        <i class="bx bx-male bx-xs"></i>
                                    </span>Student</span>
                                    @break;
                                    @case (\App\Enums\Roles::Subscriber)
                                    <span class="text-truncate d-flex align-items-center">
                                    <span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2">
                                        <i class="bx bx-user bx-xs"></i>
                                    </span>Subscriber</span>
                                    @break;
                                @endswitch
                                <small class="emp_post text-truncate text-muted">{{$user->permissions->implode('title',',')}}</small>
                            </td>

                            <td>
                                @switch($user->status)
                                    @case('pending')
                                    <span class="badge bg-label-primary me-1">{{$user->status}}</span>
                                    @break
                                    @case('inactive')
                                    <span class="badge bg-label-warning me-1">{{$user->status}}</span>
                                    @break
                                    @case('active')
                                    <span class="badge bg-label-success me-1">{{$user->status}}</span>
                                    @break
                                @endswitch
                            </td>


                            <td>
                                <button type="button" class="btn btn-icon me-2 btn-primary">
                                    <span class="tf-icons bx bx-pencil"></span>
                                </button>
                                @can('delete')
                                    <button type="button" wire:click="confirm_deleteUser({{$user->id}})"
                                            class="btn btn-icon me-2 btn-danger">
                                        <span class="tf-icons bx bx-x"></span>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <hr class="my-5">

            <div class="row mx-2">
                {{$users->links('livewire-pagination-links')}}
            </div>
            <!--/ Basic Bootstrap Table -->

        </div>

        @include('v2.admin.partials.modal-dialogs')
    </div>
</div>
