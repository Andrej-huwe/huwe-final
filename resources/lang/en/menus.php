<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Menus Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in menu items throughout the system.
    | Regardless where it is placed, a menu item can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'backend' => [
        'access' => [
            'title' => 'Správa používateľov',

            'roles' => [
                'all' => 'All Roles',
                'create' => 'Create Role',
                'edit' => 'Edit Role',
                'management' => 'Role Management',
                'main' => 'Roles',
            ],

            'users' => [
                'all' => 'All Users',
                'active' => 'Active Users',
                'change-password' => 'Change Password',
                'create' => 'Create User',
                'deactivated' => 'Deactivated Users',
                'deleted' => 'Deleted Users',
                'edit' => 'Edit User',
                'main' => 'Users',
                'view' => 'View User',
            ],

            'permissions' => [
                'all' => 'All Permissions',
                'create' => 'Create Permission',
                'deactivated' => 'Deactivated Permission',
                'deleted' => 'Deleted Permissions',
                'edit' => 'Edit Permission',
                'main' => 'Permissions',
                'view' => 'View Permission',
                'management' => 'Permission Management',
            ],

            'pages' => [
                'all' => 'All Pages',
                'active' => 'Active Pages',
                'create' => 'Create Page',
                'deactivated' => 'Deactivated Pages',
                'deleted' => 'Deleted Pages',
                'edit' => 'Edit Page',
                'main' => 'Pages',
                'view' => 'View Page',
            ],

            'blogs' => [
                'all' => 'All Blogs',
                'active' => 'Active Blogs',
                'create' => 'Create blog',
                'deactivated' => 'Deactivated Blogs',
                'deleted' => 'Deleted Blogs',
                'edit' => 'Edit blog',
                'main' => 'Blogs',
                'view' => 'View blog',
            ],

            'blog-categories' => [
                'all' => 'All blog Categories',
                'active' => 'Active blog Categories',
                'create' => 'Create blog Category',
                'deactivated' => 'Deactivated blog Categories',
                'deleted' => 'Deleted blog Categories',
                'edit' => 'Edit blog Category',
                'main' => 'blog Categories',
                'view' => 'View blog Category',
            ],

            'blog-tags' => [
                'all' => 'All blog Tags',
                'active' => 'Active blog Tags',
                'create' => 'Create blog Tag',
                'deactivated' => 'Deactivated blog Tags',
                'deleted' => 'Deleted blog Tags',
                'edit' => 'Edit blog Tag',
                'main' => 'blog Tags',
                'view' => 'View blog Tag',
            ],

            'faqs' => [
                'all' => 'All Faqs',
                'active' => 'Active Faqs',
                'create' => 'Create Faq',
                'deactivated' => 'Deactivated Faqs',
                'deleted' => 'Deleted Faqs',
                'edit' => 'Edit Faq',
                'main' => 'blog Faqs',
                'view' => 'View Faq',
            ],

            'email-templates' => [
                'all' => 'All Email Templates',
                'active' => 'Active Email Templates',
                'create' => 'Create Email Template',
                'deactivated' => 'Deactivated Email Templates',
                'deleted' => 'Deleted Email Templates',
                'edit' => 'Edit Email Template',
                'main' => 'blog Email Templates',
                'view' => 'View Email Template',
            ],
        ],

        'log-viewer' => [
            'main' => 'Log Viewer',
            'dashboard' => 'Dashboard',
            'logs' => 'Logs',
        ],

        'sidebar' => [
            'dashboard' => 'Dashboard',
            'general' => 'General',
            'history' => 'History',
            'system' => 'Lekcie',
            'blogs' => 'Správa lekcií',
            'pages' => 'Správa slovíčok',
            'faqs' => 'Správa viet',
            'email-templates' => 'Správa score',
        ],
    ],

    'language-picker' => [
        'language' => 'Language',
        /*
         * Add the new language to this array.
         * The key should have the same language code as the folder name.
         * The string should be: 'Language-name-in-your-own-language (Language-name-in-English)'.
         * Be sure to add the new language in alphabetical order.
         */
        'langs' => [
            'ar' => 'عربى (Arabic)',
            'en' => 'English',
        ],
    ],
];
