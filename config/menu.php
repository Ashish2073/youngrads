<?php
return [
    [
        "url" => "",
        "navheader" => "Dashboard",
        "icon" => ""
    ],
    [
        'menu_name' => 'Dashboard',
        'route' => 'admin.home',
        'icon_class' => 'fa fa-dashboard',
        'permission' => 'dashboard_view'
       
    ], 
    [
        'menu_name' => 'Import Data',
        'route' => 'admin.import.index',
        'icon_class' => 'fa fa-file-text',
        'permission' => 'import_data_view'
    ],
    [
        "url" => "",
        "navheader" => "Users",
        "icon" => ""
    ],
    [
        'menu_name' => 'User Management',
        'route' => 'admin.users',
        'icon_class' => 'fas fa-user-shield',
        'sub_menu' => [ 
            // [
            //     'menu_name' => 'Admin Users',
            //     'route' => 'admin.users',
            //     'icon_class' => 'fas fa-user-shield',
            //     'permission' => 'admin_users_view'
            // ],
            [
                'menu_name' => 'Modifiers Users',
                'route' => 'admin.modifiers',
                'icon_class' => 'fas fa-user-cog',
                'permission' => 'modifiers_view'
            ],
            [
                'menu_name' => 'Roles & Permission',
                'route' => 'admin.roles',
                'icon_class' => 'fa-solid fa-shield-blank',
                'permission' => 'roles_and_permissions_view'
            ],
          
        ], 
    ],
    [ 
        'menu_name' => 'Students',
        'route' => 'admin.students',
        'icon_class' => 'fa fa-users',
        'permission' => 'students_view'
    ], 
    
    [    
        'menu_name' => 'Moderators',
        'route' => 'admin.moderators',
        'icon_class' => 'fa-solid fa-people-arrows',
        'permission' => 'moderators_view'
    ], 
    [
        'menu_name' => 'Applications', 
       
        'icon_class' => 'fa fa-file-o',
        'route' => 'admin.applications-all',
        'permission' => 'applications_view'

        // 'sub_menu' => [
        //     [
        //         'menu_name' => 'Applications',
        //         'route' => 'admin.applications-all',
        //         'icon_class' => 'fa fa-bullseye',
        //     ]
        // ]
    ],

    [
        "url" => "",
        "navheader" => "Manage",
        "icon" => ""
    ],
    [
        'menu_name' => 'Universities',
        'route' => 'admin.universities',
        'icon_class' => 'fa fa-building-o',
        'permission' => 'universities_view'
    ],
    [
        'menu_name' => 'Campus',
        'route' => 'admin.campuses',
        'icon_class' => 'fa fa-building',
        'permission' => 'campus_view'
    ],

    [
        'menu_name' => 'Programs',
        'route' => 'admin.programs',
        'icon_class' => 'fa fa-certificate',
        'permission' => 'program_view'
    ],
    [
        'menu_name' => 'Campus Program',
        'route' => 'admin.campus-programs',
        'icon_class' => 'fa fa-columns',
        'permission' => 'campus_program_view'
    ],

    // [
    //     'menu_name' => 'Importing Data',
    //     'route' => 'admin.exceform',
    //     'icon_class' => 'fa fa-file-excel-o',
    //     'permission' => 'Program_view'
    // ],



    [
        'menu_name' => 'Types',
        'icon_class' => 'fa fa-book',
        // 'route' => 'admin.studies',
        'sub_menu' => [
            [
                'menu_name' => 'Program Levels',
                'route' => 'admin.programlevels',
                'icon_class' => 'fa fa-tasks',
                'permission' => 'program_level_view'
            ],
            [
                'menu_name' => 'Study Areas',
                'route' => 'admin.studies',
                'icon_class' => 'fa fa-book',
                'permission' => 'study_view'
            ],
            // [
            //     'menu_name' => 'Specialized Tests',
            //     'route' => 'admin.tests',
            //     'icon_class' => 'fa fa-file-text',
            //     'permission' => 'test_view'
            // ],
            [
                'menu_name' => 'Mandatory Document',
                'route' => 'admin.document-types',
                'icon_class' => 'fa fa-file',
                'permission' => 'mandatory_document_view'
            ],
            [
                'menu_name' => 'Application Document',
                'route' => 'admin.application-documents',
                'icon_class' => 'fa fa-file-o',
                'permission' => 'campus_program_view'
            ],
            // [
            //     'menu_name' => 'Fee Types',
            //     'route' => 'admin.feetypes',
            //     'icon_class' => 'fa fa-money',
            //     'permission' => 'fee_view'
            // ],

            // [
            //     'menu_name' => 'Intakes',
            //     'route' => 'admin.intakes',
            //     'icon_class' => 'fa fa-clock-o',
            //     'permission' => 'intake_view'
            // ],
            // [
            //     'menu_name' => 'Currency',
            //     'route' => 'admin.currencies',
            //     'icon_class' => '',
            //     'permission' => 'currency_view'
            // ]

        ]
    ],

    [
        "url" => "",
        "navheader" => "Activities",
        "icon" => "",
        'permission' => 'countries_view'
    ],
    [
        'menu_name' => 'User Activities',
        'route' => 'admin.activities',
        'icon_class' => 'feather icon-activity',
        'permission' => 'user_activity_view'
    ],

    [
        "url" => "",
        "navheader" => "Address",
        "icon" => ""
    ],
    [
        'menu_name' => 'Countries',
        'route' => 'admin.countries',
        'icon_class' => 'fa fa-globe',
        'permission' => 'countries_view'
    ],
    [
        'menu_name' => 'States',
        'route' => 'admin.states',
        'icon_class' => 'fa fa-list-alt',
        'permission' => 'states_view'
    ],
    [
        'menu_name' => 'City',
        'route' => 'admin.cities',
        'icon_class' => 'fa fa-list-alt',
        'permission' => 'cities_view'
    ],
    [
        "url" => "",
        "navheader" => "",
        "icon" => ""
    ],

    // [
    //     'menu_name' => 'Contact Entries',
    //     'route' => 'contact-entries',
    //     'icon_class' => 'fa fa-comments-o',
    //     'permission' => 'messages_view'
    // ],
    // [ 
    //     'menu_name' => 'Pages',
    //     'route' => 'admin.pages',
    //     'icon_class' => 'fa fa-pagelines',
    //     'permission' => 'pages_view'
    // ],



];
