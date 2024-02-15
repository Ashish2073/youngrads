<?php

use App\Http\Controllers\LanguageController;
//use Illuminate\Routing\Route; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminNotification;
use App\Http\Controllers\Admin\CampusProgramController;
use App\Http\Controllers\CourseFinderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ApplicationMessageController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ChangeEmailController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\UserShortlistProgramController;
use App\Http\Controllers\ApplicationTimelineController;
use App\Http\Controllers\OtherDocumentController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\IntakeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\ModifiresController;








/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['middleware' => 'prevent-back-history'], function () {
    Auth::routes(['verify' => true]);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');
});


Route::name('auth.')->namespace('Auth')->group(function () {
    Route::get('change-password', [ChangePasswordController::class, 'showLinkRequestForm'])->name('password.change');
    Route::post('change-password', [ChangePasswordController::class, 'changePassword'])->name('password.update_password');

    Route::get('change-email', [ChangeEmailController::class, 'showLinkRequestForm'])->name('email.change');
    Route::post('change-email', [ChangeEmailController::class, 'changeEmail'])->name('email.update');
    Route::post('resend-email', [ChangeEmailController::class . 'resend'])->name('email.resend');
    Route::get('social/{provider}', [LoginController::class, 'socialLogin'])->name('social.login');

    Route::get('callback/{provider}', [LoginController::class, 'providerCallback'])->name('callback');
});




/** 
 * Application Routes
 * Admin Routes
 */
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function () {
    //All the admin routes will be defined here...
    Route::namespace('Auth')->group(function () {

        //Login Routes
        Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
        Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

        //Forgot Password Routes
        Route::get('/password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        //Reset Password Routes
        Route::get('/password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
        Route::post('/password/update', [App\Http\Controllers\Admin\Auth\ChangePasswordController::class, 'changePassword'])->name('changepassword')->middleware('auth:admin');
        Route::post('/email/update', [App\Http\Controllers\Admin\Auth\ChangeEmailController::class, 'changeEmail'])->name('changeemail')->middleware('auth:admin');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth:admin');
    Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth:admin');

    // Activities
    Route::resource('/activity', '\App\Http\Controllers\Admin\ActivityController', [
        'names' => [
            'index' => 'activities',
            'show' => 'activity.show'
        ]
    ]);

    // Import Routes
    Route::get('import', [App\Http\Controllers\Admin\ImportController::class, 'index'])->name('import.index');
    Route::post('import/univ-campus', [App\Http\Controllers\Admin\ImportController::class, 'importUnivCampus'])->name('import.univ_campus');
    Route::post('import/programs', [App\Http\Controllers\Admin\ImportController::class, 'importPrograms'])->name('import.programs');
  
    // Profile
    Route::resource('profile', '\App\Http\Controllers\Admin\ProfileController', [
        'names' => [
            'edit' => 'profile',
            'update' => 'profile.update'
        ]
    ]);

    Route::post('/profile', [AdminController::class, 'chageProfilePic'])->name('profilePic');

    // User Management
    Route::resource('users', '\App\Http\Controllers\Admin\AdminController', [
        'names' => [
            'index' => 'users',
            'create' => 'user.create',
            'edit' => 'user.edit', 
            'update' => 'user.update',
            'store' => 'user.store',
            'destroy' => 'user.destroy'
        ]
    ]);
  //Moderator Mangment
  Route::resource('modifires', '\App\Http\Controllers\Admin\ModifiresController', [
    'names' => [
        'index' => 'modifires', 
        'create' => 'modifires.create',
        'edit' => 'modifires.edit',
        'update' => 'modifires.update',
        'store' => 'modifires.store',
        'destroy' => 'modifires.destroy'
    ]
]);


  //////



    Route::resource('roles', '\App\Http\Controllers\Admin\RoleController', [
        'names' => [
            'index' => 'roles',
            'create' => 'role.create',
            'edit' => 'role.edit',
            'update' => 'role.update',
            'store' => 'role.store',
            'destroy' => 'role.destroy'
        ]
    ]);

    // Logs
    Route::get('/log', 'LogController@index');

    // Student Management
    Route::resource('students', '\App\Http\Controllers\Admin\StudentController', [
        'names' => [
            'index' => 'students',
            'create' => 'student.create',
            'edit' => 'student.edit',
            'update' => 'student.update',
            'store' => 'student.store',
            'destroy' => 'student.destroy'
        ]
    ]);

    Route::post('students-data-filter',[\App\Http\Controllers\Admin\StudentController::class,'filterstudentdata'])->name('students-data-filter');

    Route::get('students-data-export', [App\Http\Controllers\Admin\StudentController::class, 'get_student_data'])->name('students-data-export');

    //shortlist
    Route::get('/shortlist-courses/{id}', [StudentController::class, 'shortlistApplication'])->name('shortlist-courses');

    // Unviersites Management
    Route::resource('universities', '\App\Http\Controllers\Admin\UniversityController', [
        'names' => [
            'index' => 'universities',
            'create' => 'university.create',
            'edit' => 'university.edit',
            'update' => 'university.update',
            'store' => 'university.store',
            'destroy' => 'university.destroy'
        ]
    ]);

    // Campus Management
    Route::resource('campus', '\App\Http\Controllers\Admin\CampusController', [
        'names' => [
            'index' => 'campuses',
            'create' => 'campus.create',
            'edit' => 'campus.edit',
            'update' => 'campus.update',
            'store' => 'campus.store',
            'destroy' => 'campus.destroy'
        ]
    ]);
    Route::get('/campus-details/{id}', [CampusController::class, 'addDetails'])->name('campus-details');
    Route::post('/campus-details', [CampusController::class, 'saveDetails'])->name('save-details');
    Route::get('/getexcel', [CampusController::class, 'excelImport']);


    //Pages Management
    Route::resource('pages', '\App\Http\Controllers\Admin\PageController', [
        'names' => [
            'index' => 'pages',
            'create' => 'page.create',
            'edit' => 'page.edit',
            'update' => 'page.update',
            'store' => 'page.store',
            'destroy' => 'page.destroy'
        ]
    ]);

    Route::get('page/{url}', [PageController::class, 'page'])->name('test');

    //Program Level

    Route::resource('programlevel', '\App\Http\Controllers\Admin\ProgramLevelController', [
        'names' => [
            'index' => 'programlevels',
            'create' => 'programlevel.create',
            'edit' => 'programlevel.edit',
            'update' => 'programlevel.update',
            'store' => 'programlevel.store',
            'destroy' => 'programlevel.destroy'
        ]
    ]);

    //Study Area



    Route::resource('study', '\App\Http\Controllers\Admin\StudyController', [
        'names' => [
            'index' => 'studies',
            'create' => 'study.create',
            'edit' => 'study.edit',
            'update' => 'study.update',
            'store' => 'study.store',
            'destroy' => 'study.destroy' 
        ]
    ]);

    Route::post('study-to-substudy',[\App\Http\Controllers\Admin\StudyController::class,'studytosubstudy'])->name('study-to-substudy');
    //Fee_type
    Route::post('reset-study-area-filter',[\App\Http\Controllers\Admin\StudyController::class,'resetstudyarea'])->name('reset-study-area-filter');

    Route::resource('feetype', '\App\Http\Controllers\Admin\FeeTypeController', [
        'names' => [
            'index' => 'feetypes',
            'create' => 'feetype.create',
            'edit' => 'feetype.edit',
            'update' => 'feetype.update',
            'store' => 'feetype.store',
            'destroy' => 'feetype.destroy'
        ]
    ]);

    //test

    Route::resource('test', '\App\Http\Controllers\Admin\TestController', [
        'names' => [
            'index' => 'tests',
            'create' => 'test.create',
            'edit' => 'test.edit',
            'update' => 'test.update',
            'store' => 'test.store',
            'destroy' => 'test.destroy'
        ]
    ]);


    //intake

    Route::resource('intake', '\App\Http\Controllers\Admin\IntakeController', [
        'names' => [
            'index' => 'intakes',
            'create' => 'intake.create',
            'edit' => 'intake.edit',
            'update' => 'intake.update',
            'store' => 'intake.store',
            'destroy' => 'intake.destroy'
        ]
    ]);

    //curruncy

    Route::resource('currency', '\App\Http\Controllers\Admin\CurrencyController', [
        'names' => [
            'index' => 'currencies',
            'create' => 'currency.create',
            'edit' => 'currency.edit',
            'update' => 'currency.update',
            'store' => 'currency.store',
            'destroy' => 'currency.destroy'
        ]
    ]);

    //program
    Route::resource('program', '\App\Http\Controllers\Admin\ProgramController', [
        'names' => [
            'index' => 'programs',
            'create' => 'program.create',
            'edit' => 'program.edit',
            'update' => 'program.update',
            'store' => 'program.store',
            'destroy' => 'program.destroy'
        ]
    ]);

    //campus program
    Route::resource('campus-program', '\App\Http\Controllers\Admin\CampusProgramController', [
        'names' => [
            'index' => 'campus-programs',
            'create' => 'campus-program.create',
            'edit' => 'campus-program.edit',
            'update' => 'campus-program.update',
            'store' => 'campus-program.store',
            'destroy' => 'campus-program.destroy'
        ]
    ]);

    Route::post('university-campus-relation',[\App\Http\Controllers\Admin\CampusProgramController::class,'universitycampus'])->name('university-to-campus');

    Route::post('reset-filter',[\App\Http\Controllers\Admin\CampusProgramController::class,'resetData'])->name('reset-filter');
    //document type
    Route::resource('document-type', '\App\Http\Controllers\Admin\DocumentTypeController', [
        'names' => [
            'index' => 'document-types',
            'create' => 'document-type.create',
            'edit' => 'document-type.edit',
            'update' => 'document-type.update',
            'store' => 'document-type.store',
            'destroy' => 'document-type.destroy'
        ]
    ]);

    //country
    Route::resource('country', '\App\Http\Controllers\Admin\CountryController', [
        'names' => [
            'index' => 'countries',
            'create' => 'country.create',
            'edit' => 'country.edit',
            'update' => 'country.update',
            'store' => 'country.store',
            'destroy' => 'country.destroy'
        ]
    ]);

    //state
    Route::resource('state', '\App\Http\Controllers\Admin\StateController', [
        'names' => [
            'index' => 'states',
            'create' => 'state.create',
            'edit' => 'state.edit',
            'update' => 'state.update',
            'store' => 'state.store',
            'destroy' => 'state.destroy'
        ]
    ]);

    //state
    Route::resource('city', '\App\Http\Controllers\Admin\CityController', [
        'names' => [
            'index' => 'cities',
            'create' => 'city.create',
            'edit' => 'city.edit',
            'update' => 'city.update',
            'store' => 'city.store',
            'destroy' => 'city.destroy'
        ]
    ]);

    //ApplicationDocuments
    Route::resource('application-document', '\App\Http\Controllers\Admin\ApplicationDocumentController', [
        'names' => [
            'index' => 'application-documents',
            'create' => 'application-document.create',
            'edit' => 'application-document.edit',
            'update' => 'application-document.update',
            'store' => 'application-document.store',
            'destroy' => 'application-document.destroy'
        ]
    ]);



    Route::get('/get-campus/{id}', [CampusProgramController::class, 'getCampus'])->name('get-campus');

    //excel importing
    Route::get('/excelimport', [HomeController::class, 'excelForm'])->name('exceform');
    Route::post('/excelimport', [HomeController::class, 'importExcel'])->name('storeform');

    //address route
    Route::get('/state/address/{id}', [\App\Http\Controllers\Admin\AddressController::class, 'selectStates'])->name('state');
    Route::get('/city/address/{id}', [\App\Http\Controllers\Admin\AddressController::class, 'selectCity'])->name('city');

    //application route
    Route::get('applications-all', [ApplicationController::class, 'index'])->name('applications-all');
    
     Route::post('number-application-allow', [ApplicationController::class, 'applicationallow'])->name('number-application-allow');
    
     Route::get('students-application-export', [ApplicationController::class, 'get_student_application_data'])->name('students-application-export');
    
    
    Route::get('applications-all/favourite', [ApplicationController::class, 'index'])->name('favorite-applicatons');
    Route::get('applications-all/inactive-application', [ApplicationController::class, 'index'])->name('inactive-applicatons');
    Route::get('application/{id}/message', [ApplicationController::class, 'applicationMessage'])->name('applicaton-message-admin');
    Route::get('application-status/{id}', [ApplicationController::class, 'status'])->name('application-status');
    Route::post('application-status', [ApplicationController::class, 'updateStatus'])->name('update-status');
    //favorite
    Route::post('application-favorite', [ApplicationController::class, 'setFavorite'])->name('application-favorite');
    //priority
    Route::post('student-priority', [ApplicationController::class, 'setPriority'])->name('student-priority');
    //delete
    Route::delete('application-delete/{id}', [ApplicationController::class, 'destroy'])->name('application-delete');
    Route::get('application-toggle-status/{id}', [ApplicationController::class, 'toggleAdminStatus'])->name('application-toggle-status');

    //admin notificaton
    Route::get('/notifications', [AdminNotification::class, 'index'])->name('notifications');
    Route::post('/notifications', [AdminNotification::class, 'markRead'])->name('mark-read');

    Route::get('/testchat', function () {

        $pageConfigs = [
            'pageHeader' => false,
            'contentLayout' => "content-left-sidebar",
            'bodyClass' => 'chat-application',
        ];

        return view('chat_test', compact('pageConfig'));
    });

    Route::get('/test-campus', function () {
        return view('dashboard.campus.campus_details');
    });
});

// Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Contact US
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact_store');

// Contact Entries
Route::get('admin/contacts', [ContactController::class, 'messageListing'])->name('contact-entries');
Route::get('admin/contact/{id}', [ContactController::class, 'show'])->name('contact-show');

// Search
Route::get('/course-finder', [CourseFinderController::class, 'index'])->name('course-finder');
Route::get('/search', [CourseFinderController::class, 'guestUserResult'])->name('course-finder-guest');
Route::post('/get-programs', [CourseFinderController::class, 'getPrograms'])->name('get-programs');
Route::get('/get-program', [CourseFinderController::class, 'getCampusProgram'])->name('getprogram');
Route::get('/test-program', [CourseFinderController::class, 'test']);
Route::get('/program-details/{id}', [CourseFinderController::class, 'programDetails'])->name('program-details');
Route::get('/campus-search/{id}', [CourseFinderController::class, 'campusePage'])->name('campus-search');
Route::get('autocompletecourse', [CourseFinderController::class, 'autocompleteCourse'])->name('autocompletecourse');
Route::get('autocompletecountry', [CourseFinderController::class, 'autocompleteCountries'])->name('autocompletecountry');
/**
 * Students Route 
 */
Route::get('edit-profile', [App\Http\Controllers\StudentController::class, 'editProfile'])->name('student.edit-profile');
Route::get('edit-profile/{step}', [App\Http\Controllers\StudentController::class, 'stepView'])->name('student.stepView');
Route::post('edit-profile/{step}', [App\Http\Controllers\StudentController::class, 'stepView'])->name('student.profile-step');
Route::get('profile/progress-detail', [App\Http\Controllers\StudentController::class, 'profileCompleteDetail'])->name('student.profile.progress');
Route::post('profile/document/upload', [App\Http\Controllers\StudentController::class, 'uploadDocument'])->name('student.document.upload');
Route::get('profile/document/delete/{id}', [App\Http\Controllers\StudentController::class, 'deleteDocument'])->name('student.document.delete');

Route::get('my-account', [App\Http\Controllers\StudentController::class, 'index'])->name('my-account');
Route::get('my-profile/{id}', [App\Http\Controllers\StudentController::class, 'edit'])->name('edit-profile');
Route::put('update-profile/{id}', [App\Http\Controllers\StudentController::class, 'update'])->name('update-profile');
Route::post('update-profilepic', [App\Http\Controllers\StudentController::class, 'changeProfilePic'])->name('update-profilepic');
Route::post('email-change/{id}', [App\Http\Controllers\StudentController::class, 'emailChange'])->name('email-change');
Route::get('email-update/{token}/{id}', [App\Http\Controllers\StudentController::class, 'emailUpdate'])->name('email-update');
route::get('complete-profile', [App\Http\Controllers\StudentController::class, 'completeProfile'])->name('complete-profile');
// Route::get('general-information', '[App\Http\Controllers\StudentController::class,generalInforamtion')->name('general-information');
// Route::post('general-information', '[App\Http\Controllers\StudentController::class,generalInforamtion')->name('general-information-store');
Route::get('education-history', [App\Http\Controllers\StudentController::class, 'educationHistory'])->name('education-history');
Route::post('education-history', [App\Http\Controllers\StudentController::class, 'educationHistory'])->name('education-history-store');
Route::post('upload-document', [App\Http\Controllers\StudentController::class, 'uploadDocument'])->name('upload-document');
Route::post('background', [App\Http\Controllers\StudentController::class, 'backgroundInformation'])->name('background');
Route::get('education-add', [App\Http\Controllers\StudentController::class, 'addEducation'])->name('education-add');
Route::get('education-listing', [App\Http\Controllers\StudentController::class, 'educationListing'])->name('education-listing');
Route::post('education-delete', [App\Http\Controllers\StudentController::class, 'educationDelete'])->name('education-delete');
Route::post('education-edit', [App\Http\Controllers\StudentController::class, 'editEducation'])->name('education-edit');
Route::put('education-update/{id}', [App\Http\Controllers\StudentController::class, 'updateEducation'])->name('education-update');
Route::post('highest-education', [App\Http\Controllers\StudentController::class, 'highestEducation'])->name('highest-education');

/* student work experience */
Route::get('work-experence', [App\Http\Controllers\StudentController::class, 'workExperience'])->name('work-experence');
Route::post('work-experence', [App\Http\Controllers\StudentController::class, 'workExperienceStore'])->name('work-experence-store');
Route::get('experence-listing', [App\Http\Controllers\StudentController::class, 'workExperienceList'])->name('experence-listing');
Route::post('experence-delete', [App\Http\Controllers\StudentController::class, 'deleteExperience'])->name('experence-delete');
Route::post('experence-edit', [App\Http\Controllers\StudentController::class, 'editExperience'])->name('experence-edit');
Route::put('experence-update/{id}', [App\Http\Controllers\StudentController::class, 'updateExperience'])->name('experence-update');
/* special test   */
Route::post('test-score-add', [App\Http\Controllers\StudentController::class, 'testScoreAdd'])->name('test-score-add');
Route::post('test-score-store', [App\Http\Controllers\StudentController::class, 'testScoreStore'])->name('test-score-store');
Route::get('test-score-list', [App\Http\Controllers\StudentController::class, 'testScoreList'])->name('test-score-list');
Route::post('test-score-delete', [App\Http\Controllers\StudentController::class, 'testScoreDelete'])->name('test-score-delete');
Route::post('test-score-edit', [App\Http\Controllers\StudentController::class, 'testScoreEdit'])->name('test-score-edit');
Route::put('test-score-update/{id}', [App\Http\Controllers\StudentController::class, 'testScoreUpdate'])->name('test-score-update');
/** user document */
Route::get('user-document', [App\Http\Controllers\StudentController::class, 'userDocument'])->name('user-document');
Route::post('user-document-store', [App\Http\Controllers\StudentController::class, 'userDocumentStore'])->name('user-document-store');
Route::get('user-document-listing', [App\Http\Controllers\StudentController::class, 'DocumentListing'])->name('user-document-listing');
Route::post('user-document-delete', [App\Http\Controllers\StudentController::class, 'documentDelete'])->name('user-document-delete');
Route::get('user-document-edit', [App\Http\Controllers\StudentController::class, 'documentEdit'])->name('user-document-edit');
Route::put('user-document-update/{id}', [App\Http\Controllers\StudentController::class, 'documentUpdate'])->name('user-document-update');
//deleting file in document section
Route::get('delete-file', [App\Http\Controllers\StudentController::class, 'deleteFile'])->name('delete-file');

// Other Document section
Route::resource('other-document', '\App\Http\Controllers\OtherDocumentController', [
    'names' => [
        'index' => 'other_documents',
        'create' => 'other_document.create',
        'edit' => 'other_document.edit',
        'update' => 'other_document.update',
        'store' => 'other_document.store',
        'destroy' => 'other_document.destroy'
    ]
]);


//shortilisting routes
Route::get('shortlist-programs', [UserShortlistProgramController::class, 'index'])->name('shortlist-programs');
Route::post('shortlist-programs-add', [UserShortlistProgramController::class, 'addProgram'])->name('shortlist-programs-add');
Route::post('shortlist-programs-remove', [UserShortlistProgramController::class, 'removeProgram'])->name('shortlist-programs-remove');
//auto complete ajax
Route::get('/country-auto', [CourseFinderController::class, 'countryAutoComplete'])->name('country-auto');
Route::get('/program-auto', [CourseFinderController::class, 'programAutoComplete'])->name('program-auto');

//student profile
Route::get('student/{id}/viewprofile', [App\Http\Controllers\Admin\StudentController::class, 'profileResume'])->name('student-profile');
//apply route
Route::get('apply-application/{id}', [UserApplicationController::class, 'index'])->name('apply-application');
Route::post('applications', [UserApplicationController::class, 'store'])->name('applications-store');
Route::get('applications', [UserApplicationController::class, 'allApplication'])->name('applications');
Route::post('application-remove', [UserApplicationController::class, 'removeApplication'])->name('application-remove');
Route::get('application/{id}/message', [ApplicationMessageController::class, 'index'])->name('applicaton-message-user');
Route::post('application/{id}/message', [ApplicationMessageController::class, 'store'])->name('applicaton-store');
Route::get('application/message/all/{id}', [ApplicationMessageController::class, 'showMessages'])->name('messages-all');
Route::get('application/{id}/documents', [UserApplicationController::class, 'documentView'])->name('application.document.view');
//set important
Route::post('/message-important', [ApplicationMessageController::class, 'setImportant'])->name('message-important');
Route::get('application/{id}/archive', [UserApplicationController::class, 'archive'])->name('archive-application');
Route::get('application/{id}/recover', [UserApplicationController::class, 'recover'])->name('recover-application');
Route::get('application/{id}', [UserApplicationController::class, 'show'])->name('application');
Route::put('submit-application', [UserApplicationController::class, 'submitApplication'])->name('submit_to_ygrad');

// Application Timelines
Route::resource('application/{id}/timeline', '\App\Http\Controllers\ApplicationTimelineController', [
    'names' => [
        'index' => 'application.timeline',
        'create' => 'application.timeline.create',
        'edit' => 'application.timeline.edit',
        'update' => 'application.timeline.update',
        'store' => 'application.timeline.store',
        'destroy' => 'application.timeline.destroy'
    ]
]);


//beta home
Route::get('home-beta', function () {
    return view('home_beta');
});


Route::get('/gng', function () {
    $str = '{"Engineering and Technology":["Aero Space, Aviation and Pilot Technology ","Agriculture ","Architecture","Biomedical Engineering","Chemical Engineering","Civil Engineering, Construction","Electrical Engineering","Electronic","Environmental Engineering","Game Design, Game Animation, Game Creation","Industrial","Material Engineering","Mechanical, Manufacturing, Robotic Engineering","Radiography","Technology, Software, Computer, IT"],"Sciences":["Astronomy","Biochemistry","Biology","Chemistry","Computer Science ","Dental","Environmental, Earth Sciences","Food, Nutrition, Exercise","General","Geology","Humanitarian Sciences","Mathematics","Optometry","Pharmacy","Physics","Political","Psychology, Philosophy, Therapy","Veterinarian"],"Arts":["Animation","Anthropology","Communication ","English Literature","Fashion, Esthetics","Fine Arts","Food and Culinary ","Gender Studies","General","Geography","Global Studies","Graphic Design, Interior Design","History","Journalism","Languages","Liberal Arts","Media, Photography, Film, Theatre, Performance ","Music, Audio","Planning (Urban)","Religion","Sociology"],"Business, Management and Economics":["Accounting","Entrepreneurship","Finance, Economics","Hospitality and Tourism, Recreation","Human Resources","International Business","Management, Administration, General","Marketing, Analyst, Advertising","Public Relation","Supply Chain"],"Law, Politics, Social, Community Service and Teaching":["Community, Social Service","Law, Politics, Police, Security","Teaching, Early Development, Child Care"]}';
    $str = '{"Agriculture, Forestry and Fishery":["Agribusiness","Agricultural Science","Agronomy","Fisheries ","Forestry","Horticulture","Mariculture","Poultry, Dairy"],"Architecture and Building":["Architecture","Construction","Construction Management","Heating","Interior Design","Surveying","Urban Planning","Ventilation "],"Arts":["Animation","Applied Arts - Printing, Studio Art","Arts","Bakery and Pastry Arts","Car Interior Design ","Carpentry, Woodworking, Blacksmithing, Goldsmithing, Silversmithing, Ceramics","Creative Writing","Dance","Design - Fashion, Textile, Graphics, Product","English","Exhibition/Event","Film, Cinematography, Interactive Media, Multimedia","Fine Arts-Drawing, Painting, Sculpture","General Studies","Interdisciplinary Studies","Liberal Arts and Studies","Library","Music","Photography","Publishing","Theatre,Drama","Visual Arts"],"Commerce, Business and Administration":["Banking, Insurance, Risk Management, Taxation","Business Analytics, Management Consulting","Business Management, Business Administration, Entrepreneurship, Innovation, Operations, Project Management, Event Management, Organizational Management, Health Management","Commerce","Conflict Analysis and Management","Finance, Accounts, Commerce, Economics","Financial Management, International Management, Marketing Management, Technical Management","Hospitality and Tourism Management","Human Resource Management, Human Resource Development","Information Systems, Information Technology Management","International Business","Logistics, Supply Chain Management","Office Administration","Real Estate, Property Administeration","Sales, Marketing, Public Relations, Public Services, E-business, Digital Business, Digital Marketing"],"Computer Science and Information Technology":["Artificial Intelligence","Business Analysis, IT Business Analytics","Computer Information Systems","Computer Science, Software Development","Computer Technology","Computer/ Game Programming","Computing","Customer Intelligence and Analytics","Cyber Security, Information Security","Cytotechnology","Data Science and Analytics","Game Design","Information Technology","Mobile Application","Mobile Communication","Multimedia","Networking","Programmer","Software Testing","Web Design, Web Applications"],"Education":["Adult, Organisational Learning and Leadership","Early Childhood Education, Primary Education ,Secondary Education","Education Assistance ","Education Counseling","Educational Training","Elementary Education","Gender Studies","Physical Education","Reading","Teacher Education Program"],"Engineering and Engineering Trades":["Aeronautical, Aerospace Engineering, Aviation Technology","Applied Engineering","Architectural Engineering, Structural Engineering","Automotive Engineering","Aviation Technology","Biomedical Engineering ","Chemical Engineering","Civil Engineering, Geotechnical Engineering","Computer Engineering, Software Engineering","Electrical and Electronic Engineering","Electrical Engineering","Electromechanical Engineering","Electronics and Telecommunication Engineering","Electronics Engineering","Energy Systems Technology","Engineering Management","Environmental Engineering","Industrial Design","Industrial Engineering, Process Engineering","Instrumentation","Interdisciplinary Engineering","Liquid Crystal Engineering","Machine Learning","Manufacturing Engineering","Marine Engineering","Mechanical and Mechatronics Engineering","Mechanical Engineering","Mechatronics","Metallurgical Engineering, Material Engineering","Micro electro-mechanical systems (MEMS)","Mining Engineering","Nuclear Engineering","Petroleum Engineering","Plastics Engineering","Power Engineering ","Product Design Engineering","Robotics","Spatial Engineering","System Engineering","Technician","Telecommunications Engineering","Textile Engineering"],"Environmental Science/Protection":["Environmental Science and Technology","Renewable Energy and Materials","Water Resources","Wild Life Ecosystem, Conservation"],"Health":["Acupunture","Addiction and Mental Health","Audiology - Speech, Speech pathology, Speech Therapy","Autism","Biomechanical devices","Clinical Lab ","Communicative Disorder","Dentistry, Dental Hygiene","Dietetics, Nutrition","Drug Development","Fitness, Physical Activity","Health Psychology","Health Science, Healthcare, Health Management","Kinesiology ","Medical Radiation Technology","Medical Science","Medical Technology ","Midwifery","Nursing","Occupational Therapy","Paradmeic Studies ","Pharmaceutical Sciences, Pharmacy","Physical Therapy, Physiotherapy","Psychiatric Nursing","Psychology","Public Heath","Radiologic Science","Recreation","Rehabilitation Assistant","Respiratory Care","Therapeutic Recreation","Therapist Assistant"],"Humanities":["Geography","History","Humanities","Indigenous Studies","Language and Literature","Museum and Gallery Studies","Philosophy, Aesthetics","Religious Studies","Theology"],"Journalism and Information":["Advertising","Journalism, Broadcasting Journalism","Media, Mass communication, Technical comunication"],"Law":["Criminology","Forensic Science","Justice and Emergency Services","Law, LLB, LLM","Legal Assistant, Court Support"],"Life Sciences":["Biochemistry","Bioinformatics","Biology, Biological Science","Biomaterials","Biomedical Sciences","Biotechnology","Clinical Sciences","Food Science, Food Science and Nutrition, Food Science and Technology","Hydrobiology","Immunology","Life Science","Marine Biology","Microbiology","Neuroscience","Physiology","Plant Taxanomy , Plant Science","Zoology"],"Manufacturing and Processing":["Material Science, Material Science and Engineering","Mining","Paper and Bioprocess","Petroleum, Oil and Gas, Diesel Technology","Supply Chain Management","Textiles ","Welding and Fabrication","Wine Making, Brewery, Winery"],"Mathematics and Statistics":["Actuarial Science","Mathematics","Statistics"],"Personal Services":["Aviation","Culinary Skills ","Financial Services","Hair Styling, Makeup, Cosmetics, Esthetic Services","Hospitality, Culinary Arts, Travel and Tourism","Massage Therapy","Sports Science, Sports, Sports Management, Exercise"],"Physical Sciences, Sciences":["Astronomy, Astrophysics","Atmospheric Sciences","Chemistry","Earth Sciences","Geography","Geology","Geology","Geophysics","Geospatial Science","Lab Technician","Meteorology","Nanoscience, Nanotechnology","Physics","Science"],"Security Services":["Criminal Science, International Criminology","Fire Science, firefighting, Fire and Safety","Forensic Science","Military","Safety, Police and Public Safety "],"Social and Behavioural Science":["Anthropology","Archaeology","Behavioral Sciences","Cultural studies, Inter-cultural communication","Economics","Ethnic Studies","Gerontology","Human Ecology, Ecology","Human Service","International Relations","Political Science","Psychology","Sociology, Social Science"],"Social Services":["Child Care, Child and Youth Worker, Child Care Development and Studies","Community Development","Developmental Service Worker","Personal Support Work ","Social Work and NGO Related Fields"],"Transport Services":["Transportation, Transportation Management"],"Veterinary":["Animal Care ","Animal Conservation ","Animal Science","Veterinary Science"]}';

    $arr = json_decode($str, true);

    // echo '<pre>'; print_r($arr); echo '</pre>';
    foreach ($arr as $cat => $subcats) {
        $sa = new App\Models\Study;
        $sa->name = $cat;
        $sa->parent_id = 0;
        $sa->slug = \Str::slug($cat, "-");
        if ($sa->save()) {
            foreach ($subcats as $subcat) {

                $suba = new App\Models\Study;
                $suba->name = $subcat;
                $suba->parent_id = $sa->id;
                $suba->slug = \Str::slug($subcat, "-");
                $suba->save();
            }
        }
    }
    // $file = fopen("Area.csv","w");

    // foreach($arr as $cat => $subcats) {
    //   foreach($subcats as $subcat) {
    //     $line = [];
    //     $line[] = $cat;
    //     $line[] = $subcat;
    //     fputcsv($file, $line);

    //   }
    // }

    // fclose($file);

});


Route::get('sub-study-areas/{id}', [App\Http\Controllers\Admin\StudyController::class, 'getSubStudyAreas'])->name('sub_study_areas');
Route::post('get-sub-areas', function () {
    if (!is_array(request()->get('ids'))) {
        return [];
    }
    return App\Models\Study::whereIn('parent_id', request()->ids)->get();
})->name('get-sub-areas');

Route::get('finder', [CourseFinderController::class, 'finder'])->name('finder');

Route::get('privacy-policy', function () {
    return view('privacy_policy');
})->name('privacy_policy');
Route::get('terms-of-use', function () {
    return view('terms');
})->name('terms');
Route::get('{url}', [App\Http\Controllers\Admin\PageController::class, 'show']);
