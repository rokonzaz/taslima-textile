<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DutySlotRulesController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\CompanyListController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DutySlotsController;
use App\Http\Controllers\TestCntroller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SelectizeController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\WeekendController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\MyRequestsController;
use App\Http\Controllers\RequestApprovalsController;
Route::get('/test', [\App\Http\Controllers\TestCntroller::class, 'index']);
Route::get('/leave-request/email-preview/{id}', [LeaveController::class, 'emailPreview'])->name('leaveRequest.email-preview');
Route::post('/leave-request/email-preview/{id}', [LeaveController::class, 'emailPreviewSubmit'])->name('leaveRequest.email-preview');

Route::get('/employee-request/email-preview/{id}', [RequestApprovalsController::class, 'emailPreview'])->name('employeeRequest.email-preview');
Route::post('/employee-request/email-preview/{id}', [RequestApprovalsController::class, 'emailPreviewSubmit'])->name('employeeRequest.email-preview');


Route::prefix('test')->group(function () {
        Route::get('/', [\App\Http\Controllers\TestCntroller::class, 'index']);
        Route::get('/import', [TestCntroller::class, 'import'])->name('test.import-component');
        Route::get('/tabs', [TestCntroller::class, 'tabs'])->name('test.tablist');
        Route::get('/layout', [TestCntroller::class, 'layout'])->name('test.layout');
    });
Route::middleware(['auth', 'active-user'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::get('/assign-role-form/{empId}', [EmployeeController::class, 'assignRoleForm']);
        Route::get('/assign-role/{empId}', [EmployeeController::class, 'assignRole']);
        Route::get('/change-status-form/{empId}', [EmployeeController::class, 'changeActiveStatusForm']);
        Route::get('/change-status/{empId}', [EmployeeController::class, 'changeActiveStatus']);
        Route::get('/delete/{id}', [EmployeeController::class, 'delete'])->name('employees.delete');
        Route::get('/delete-education/{id}', [EmployeeController::class, 'deleteEducation'])->name('employee.delete.education');
        Route::get('/delete-document/{id}', [EmployeeController::class, 'deleteDocument'])->name('employee.delete.document');
        Route::delete('/force-delete/{id}', [EmployeeController::class, 'forceDelete'])->name('employees.force-delete');
        Route::get('/view/{id}', [EmployeeController::class, 'view'])->name('employees.view');
        Route::get('/validate-single-data', [EmployeeController::class, 'validateSingleData']);
        Route::get('/import-employees', [EmployeeController::class, 'importEmployees'])->name('employees.import');
        Route::post('/import-employees/submit', [EmployeeController::class, 'importEmployeesSubmit'])->name('employees.import.submit');
        Route::get('/get-employees-notice-list', [EmployeeController::class, 'getEmployeeByNotice'])->name('employees.data');
    });

    Route::prefix('my-requests')->group(function () {
        Route::get('/', [MyRequestsController::class, 'index'])->name('my-requests.index');
        Route::post('/store', [MyRequestsController::class, 'store'])->name('my-requests.store');
        Route::get('/delete/{id}', [MyRequestsController::class, 'delete'])->name('my-requests.delete');
    });
    Route::prefix('request-approvals')->group(function () {
        Route::get('/', [RequestApprovalsController::class, 'index'])->name('request-approvals.index');
        Route::get('/approval/{id}', [RequestApprovalsController::class, 'requestApproval'])->name('request-approvals.approval');
        Route::post('/approval/{id}', [RequestApprovalsController::class, 'submitApproval'])->name('request-approvals.submitApproval');
        // TODO: Need to implement this method
        // Route::get('/delete/{id}', [RequestApprovalsController::class, 'delete'])->name('request-approvals.delete');
    });

    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/sync', [AttendanceController::class, 'syncAttendanceData'])->name('attendance.sync');
        Route::get('/bulk-tag', [AttendanceController::class, 'bulkTag'])->name('attendance.bulk-tag');
        Route::post('/bulk-tag', [AttendanceController::class, 'bulkTagSubmit'])->name('attendance.bulk-tag-submit');
        Route::get('/bulk-tag-machine-id', [AttendanceController::class, 'bulkTagMachineId'])->name('attendance.bulk-tag-machine-id');
        Route::post('/bulk-tag-machine-id', [AttendanceController::class, 'bulkTagMachineIdSubmit'])->name('attendance.bulk-tag-machine-id-submit');
        Route::get('/add-manual-attendance/{id}/{date}', [AttendanceController::class, 'addManualAttendance']);
        Route::get('/edit-attendance-details/{id}/{date}', [AttendanceController::class, 'editAttendanceDetails']);
        Route::post('/update-attendance-details/{id}/{date}', [AttendanceController::class, 'updateAttendanceDetails']);
        Route::get('/reporting', [AttendanceController::class, 'attendanceReporting']);

    });
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('/get-reports', [ReportsController::class, 'getReports'])->name('reports.reg-reports');
    });
    Route::prefix('duty-slots')->group(function () {
        Route::get('/', [DutySlotsController::class, 'index'])->name('dutySlots.index');
        Route::get('/validate-single-data', [DutySlotsController::class, 'validateSingleData']);
        Route::post('/store', [DutySlotsController::class, 'store'])->name('dutySlots.store');
        Route::get('/edit/{id}', [DutySlotsController::class, 'edit'])->name('dutySlots.edit');
        Route::post('/update/{id}', [DutySlotsController::class, 'update'])->name('dutySlots.update');
        Route::get('/delete/{id}', [DutySlotsController::class, 'delete'])->name('dutySlots.delete');
    });

    Route::prefix('leave')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('leave.index');
        Route::post('/store', [LeaveController::class, 'store'])->name('leaveRequest.store');
        Route::get('/view/{id}', [LeaveController::class, 'view'])->name('leaveRequest.view');
        Route::get('/edit/{id}', [LeaveController::class, 'edit'])->name('leaveRequest.edit');
        Route::post('/update/{id}', [LeaveController::class, 'update'])->name('leaveRequest.update');
        Route::post('/delete/{id}', [LeaveController::class, 'delete'])->name('leaveRequest.delete');

        Route::prefix('leave-type')->group(function () {
            Route::get('/', [LeaveController::class, 'leaveType'])->name('leave-type.index');
            Route::post('/update/{id}', [LeaveController::class, 'leaveTypeUpdate'])->name('leave-type.update');
        });
        Route::prefix('holiday')->group(function () {
            Route::get('/', [HolidayController::class, 'index'])->name('holiday.index');
            Route::get('/create', [HolidayController::class, 'create'])->name('holiday.create');
            Route::post('/store', [HolidayController::class, 'store'])->name('holiday.store');
            Route::get('/edit/{id}', [HolidayController::class, 'edit'])->name('holiday.edit');
            Route::post('/update/{id}', [HolidayController::class, 'update'])->name('holiday.update');
            Route::get('/delete/{id}', [HolidayController::class, 'delete'])->name('holiday.delete');
        });
        Route::prefix('weekend')->group(function () {
            Route::get('/', [WeekendController::class, 'index'])->name('weekend.index');
            Route::get('/create', [WeekendController::class, 'index'])->name('weekend.create');
            Route::post('/store', [WeekendController::class, 'store'])->name('weekend.store');
            Route::get('/edit/{id}', [WeekendController::class, 'edit'])->name('weekend.edit');
            Route::post('/update', [WeekendController::class, 'store'])->name('weekend.update');
        });
    });

    Route::prefix('notice')->group(function () {
        Route::get('/', [NoticeController::class, 'index'])->name('notice.index');
        Route::get('/ajaxNotice', [NoticeController::class, 'ajaxNotice'])->name('notice.ajaxNotice');
        Route::get('/create', [NoticeController::class, 'create'])->name('notice.create');
        Route::get('/notice-list', [NoticeController::class, 'noticeList'])->name('notice.notice-list');
        Route::post('/store', [NoticeController::class, 'store'])->name('notice.store');
        Route::get('/edit/{id}', [NoticeController::class, 'edit'])->name('notice.edit');
        Route::post('/update/{id}', [NoticeController::class, 'update'])->name('notice.update');
        Route::get('/delete/{id}', [NoticeController::class, 'delete'])->name('notice.delete');
        Route::get('/send/{id}', [NoticeController::class, 'sendNotice'])->name('notice.send');
        Route::post('/send-mail/{id}', [NoticeController::class, 'sendNoticeList'])->name('notice.list');
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users-list', [UserController::class, 'usersList'])->name('users.users-list');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
        Route::get('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    });

    Route::prefix('team')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('team.index');
        Route::get('/team-members/{id}', [TeamController::class, 'teamMembers'])->name('team.team-members');
        Route::post('/store', [TeamController::class, 'store'])->name('team.store');
        Route::get('/edit/{id}', [TeamController::class, 'edit'])->name('team.edit');
        Route::post('/update/{id}', [TeamController::class, 'update'])->name('team.update');
        Route::get('/delete/{id}', [TeamController::class, 'delete'])->name('team.delete');
        Route::get('/remove-employee/{id}', [TeamController::class, 'removeEmployee'])->name('team.remove-employee');
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::prefix('fingerprint')->group(function () {
            Route::get('/', [SettingController::class, 'fingerprintMachine'])->name('settings.fingerprintMachine.index');
            Route::post('/update', [SettingController::class, 'fingerprintMachineUpdate'])->name('settings.fingerprintMachine.update');
        });
        Route::prefix('attendance-reporting')->group(function () {
            Route::get('/', [SettingController::class, 'fingerprintMachine']);
            Route::post('/update', [SettingController::class, 'fingerprintMachineUpdate']);
        });
        Route::prefix('duty-slot-rules')->group(function () {
            Route::get('/', [DutySlotRulesController::class, 'index'])->name('dutySlotRules.index');
            Route::post('/store', [DutySlotRulesController::class, 'store'])->name('dutySlotRules.store');
            Route::get('/edit/{id}', [DutySlotRulesController::class, 'edit'])->name('dutySlotRules.edit');
            Route::post('/update/{id}', [DutySlotRulesController::class, 'update'])->name('dutySlotRules.update');
            Route::get('/delete/{id}', [DutySlotRulesController::class, 'delete'])->name('dutySlotRules.delete');
        });

    });



    Route::get('/selectize/{a}', [SelectizeController::class, 'index']);
    Route::get('/live-validate-single-data', [ValidationController::class, 'liveValidateSingleData'])->name('liveValidateSingleData');


    Route::prefix('my-profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('my-profile.index');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/edit-permission', [RoleController::class, 'editRolePermission'])->name('permission.edit');
        Route::post('/update-permission', [RoleController::class, 'updateRolePermission'])->name('permission.update');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::get('/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
    });



    // Route start for settings menu

    // TODO:
   /*  Route::prefix('organization-list')->group(function () {
        Route::get('/', [CompanyListController::class, 'index'])->name('organization-list.index');
        Route::get('/create', [CompanyListController::class, 'create'])->name('company-list.create');
        Route::post('/store', [CompanyListController::class, 'store'])->name('company-list.store');
        Route::get('/edit/{id}', [CompanyListController::class, 'edit'])->name('company-list.edit');
        Route::post('/update/{id}', [CompanyListController::class, 'update'])->name('company-list.update');
        Route::get('/delete/{id}', [CompanyListController::class, 'delete'])->name('company-list.delete');
    }); */


    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('department.index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('department.create');
        Route::post('/store', [DepartmentController::class, 'store'])->name('department.store');
        Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
        Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
        Route::get('/delete/{id}', [DepartmentController::class, 'delete'])->name('department.delete');
    });

    Route::prefix('designations')->group(function () {
        Route::get('/', [DesignationController::class, 'index'])->name('designation.index');
        Route::get('/create', [DesignationController::class, 'create'])->name('designation.create');
        Route::post('/store', [DesignationController::class, 'store'])->name('designation.store');
        Route::get('/edit/{id}', [DesignationController::class, 'edit'])->name('designation.edit');
        Route::post('/update/{id}', [DesignationController::class, 'update'])->name('designation.update');
        Route::get('/delete/{id}', [DesignationController::class, 'delete'])->name('designation.delete');
    });

    // TODO:
    /*Route::prefix('document-type')->group(function () {
        Route::get('/', [DocumentTypeController::class, 'index'])->name('document-type.index');
        Route::get('/create', [DocumentTypeController::class, 'create'])->name('document-type.create');
        Route::post('/store', [DocumentTypeController::class, 'store'])->name('document-type.store');
        Route::get('/edit/{id}', [DocumentTypeController::class, 'edit'])->name('document-type.edit');
        Route::post('/update/{id}', [DocumentTypeController::class, 'update'])->name('document-type.update');
        Route::get('/delete/{id}', [DocumentTypeController::class, 'delete'])->name('document-type.delete');
    });*/
});
require __DIR__ . '/auth.php';
