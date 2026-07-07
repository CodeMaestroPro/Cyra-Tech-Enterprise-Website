<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cyra-Tech Platform Identity
    |--------------------------------------------------------------------------
    */

    'name' => env('CYRA_APP_NAME', 'Cyra-Tech'),
    'tagline' => env('CYRA_TAGLINE', 'Built on Vision. Driven by Intelligence.'),
    'version' => env('CYRA_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Platform Modules
    |--------------------------------------------------------------------------
    */

    'modules' => [
        ['id' => 1, 'slug' => 'project-initialization', 'name' => 'Project Initialization', 'status' => 'completed'],
        ['id' => 2, 'slug' => 'authentication-rbac', 'name' => 'Authentication & RBAC', 'status' => 'completed'],
        ['id' => 3, 'slug' => 'design-system', 'name' => 'Design System', 'status' => 'completed'],
        ['id' => 4, 'slug' => 'global-navigation', 'name' => 'Global Navigation', 'status' => 'completed'],
        ['id' => 5, 'slug' => 'homepage', 'name' => 'Homepage', 'status' => 'pending'],
        ['id' => 6, 'slug' => 'about', 'name' => 'About', 'status' => 'pending'],
        ['id' => 7, 'slug' => 'leadership', 'name' => 'Leadership', 'status' => 'pending'],
        ['id' => 8, 'slug' => 'solutions', 'name' => 'Solutions', 'status' => 'pending'],
        ['id' => 9, 'slug' => 'products', 'name' => 'Products', 'status' => 'pending'],
        ['id' => 10, 'slug' => 'industries', 'name' => 'Industries', 'status' => 'pending'],
        ['id' => 11, 'slug' => 'portfolio', 'name' => 'Portfolio', 'status' => 'pending'],
        ['id' => 12, 'slug' => 'innovation-lab', 'name' => 'Innovation Lab', 'status' => 'pending'],
        ['id' => 13, 'slug' => 'community', 'name' => 'Community', 'status' => 'pending'],
        ['id' => 14, 'slug' => 'insights', 'name' => 'Insights', 'status' => 'pending'],
        ['id' => 15, 'slug' => 'careers', 'name' => 'Careers', 'status' => 'pending'],
        ['id' => 16, 'slug' => 'contact', 'name' => 'Contact', 'status' => 'pending'],
        ['id' => 17, 'slug' => 'partner-hub', 'name' => 'Partner Hub', 'status' => 'pending'],
        ['id' => 18, 'slug' => 'client-portal', 'name' => 'Client Portal', 'status' => 'pending'],
        ['id' => 19, 'slug' => 'cms', 'name' => 'CMS', 'status' => 'pending'],
        ['id' => 20, 'slug' => 'media-library', 'name' => 'Media Library', 'status' => 'pending'],
        ['id' => 21, 'slug' => 'analytics', 'name' => 'Analytics', 'status' => 'pending'],
        ['id' => 22, 'slug' => 'dashboard', 'name' => 'Dashboard', 'status' => 'pending'],
        ['id' => 23, 'slug' => 'crm', 'name' => 'CRM', 'status' => 'pending'],
        ['id' => 24, 'slug' => 'project-management', 'name' => 'Project Management', 'status' => 'pending'],
        ['id' => 25, 'slug' => 'testing-optimization', 'name' => 'Testing & Optimization', 'status' => 'pending'],
    ],

    /*
    |--------------------------------------------------------------------------
    | RBAC Roles & Permissions
    |--------------------------------------------------------------------------
    */

    'roles' => [
        'super-admin' => [
            'name' => 'Super Administrator',
            'description' => 'Full platform access across all modules and settings.',
            'permissions' => ['*'],
        ],
        'admin' => [
            'name' => 'Administrator',
            'description' => 'Manage users, content, and operational settings.',
            'permissions' => [
                'dashboard.access',
                'users.view',
                'users.create',
                'users.update',
                'roles.view',
                'settings.view',
                'modules.view',
            ],
        ],
        'manager' => [
            'name' => 'Manager',
            'description' => 'Operational access to dashboard and team workflows.',
            'permissions' => [
                'dashboard.access',
                'users.view',
                'modules.view',
            ],
        ],
        'editor' => [
            'name' => 'Editor',
            'description' => 'Content and module visibility without user administration.',
            'permissions' => [
                'dashboard.access',
                'modules.view',
            ],
        ],
        'viewer' => [
            'name' => 'Viewer',
            'description' => 'Read-only dashboard access.',
            'permissions' => [
                'dashboard.access',
            ],
        ],
    ],

    'permissions' => [
        'dashboard.access' => ['name' => 'Access Dashboard', 'group' => 'Dashboard'],
        'users.view' => ['name' => 'View Users', 'group' => 'Users'],
        'users.create' => ['name' => 'Create Users', 'group' => 'Users'],
        'users.update' => ['name' => 'Update Users', 'group' => 'Users'],
        'users.delete' => ['name' => 'Delete Users', 'group' => 'Users'],
        'roles.view' => ['name' => 'View Roles', 'group' => 'Roles'],
        'roles.manage' => ['name' => 'Manage Roles', 'group' => 'Roles'],
        'settings.view' => ['name' => 'View Settings', 'group' => 'Settings'],
        'settings.manage' => ['name' => 'Manage Settings', 'group' => 'Settings'],
        'modules.view' => ['name' => 'View Modules', 'group' => 'Platform'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Admin Bootstrap User
    |--------------------------------------------------------------------------
    */

    'admin' => [
        'name' => env('CYRA_ADMIN_NAME', 'Collins Pever'),
        'email' => env('CYRA_ADMIN_EMAIL', 'admin@cyratech.com'),
        'password' => env('CYRA_ADMIN_PASSWORD', 'password'),
        'role' => 'super-admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Design System Tokens
    |--------------------------------------------------------------------------
    */

    'design_system' => [
        'version' => '1.0.0',
        'stack' => 'Blade + JavaScript + Tailwind CSS 4',
        'colors' => [
            'midnight' => '#050810',
            'navy' => '#0b1120',
            'surface' => '#111827',
            'border' => '#1e293b',
            'text' => '#f8fafc',
            'muted' => '#94a3b8',
            'primary' => '#2563eb',
            'primary-hover' => '#1d4ed8',
            'accent' => '#06b6d4',
            'success' => '#22c55e',
            'warning' => '#f59e0b',
            'danger' => '#ef4444',
            'purple' => '#8b5cf6',
        ],
        'typography' => [
            'font-family' => 'Inter, ui-sans-serif, system-ui, sans-serif',
            'display' => '3rem / 700',
            'heading-1' => '2.25rem / 700',
            'heading-2' => '1.875rem / 600',
            'heading-3' => '1.5rem / 600',
            'body' => '1rem / 400',
            'small' => '0.875rem / 400',
            'caption' => '0.75rem / 500',
        ],
        'radii' => [
            'sm' => '0.375rem',
            'md' => '0.5rem',
            'lg' => '0.75rem',
            'xl' => '0.75rem',
            'full' => '9999px',
        ],
        'components' => [
            'button', 'badge', 'status-badge', 'card', 'metric-card',
            'input', 'textarea', 'select', 'checkbox', 'label', 'alert',
            'spinner', 'section-heading', 'breadcrumb', 'table', 'empty-state',
            'modal', 'tabs',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Navigation
    |--------------------------------------------------------------------------
    */

    'navigation' => [
        'brand' => [
            'name' => 'Cyra-Tech',
            'accent' => 'TECH',
        ],
        'public' => [
            'header' => [
                ['label' => 'Home', 'route' => 'home', 'sort' => 1],
                ['label' => 'About', 'route' => 'about', 'sort' => 2],
                ['label' => 'Solutions', 'route' => 'solutions', 'sort' => 3],
                ['label' => 'Products', 'route' => 'products', 'sort' => 4],
                ['label' => 'Industries', 'route' => 'industries', 'sort' => 5],
                ['label' => 'Portfolio', 'route' => 'portfolio', 'sort' => 6],
                ['label' => 'Innovation Lab', 'route' => 'innovation-lab', 'sort' => 7],
                ['label' => 'Community', 'route' => 'community', 'sort' => 8],
                ['label' => 'Insights', 'route' => 'insights', 'sort' => 9],
                ['label' => 'Careers', 'route' => 'careers', 'sort' => 10],
            ],
            'actions' => [
                ['label' => 'Client Portal', 'route' => 'login', 'style' => 'link', 'sort' => 1],
                ['label' => 'Contact Us', 'route' => 'contact', 'style' => 'button', 'sort' => 2],
            ],
            'footer_columns' => [
                [
                    'title' => 'Company',
                    'links' => [
                        ['label' => 'About Us', 'route' => 'about'],
                        ['label' => 'Leadership', 'route' => 'about'],
                        ['label' => 'Careers', 'route' => 'careers'],
                        ['label' => 'Contact', 'route' => 'contact'],
                    ],
                ],
                [
                    'title' => 'Solutions',
                    'links' => [
                        ['label' => 'Services', 'route' => 'solutions'],
                        ['label' => 'Products', 'route' => 'products'],
                        ['label' => 'Industries', 'route' => 'industries'],
                        ['label' => 'Innovation Lab', 'route' => 'innovation-lab'],
                    ],
                ],
                [
                    'title' => 'Resources',
                    'links' => [
                        ['label' => 'Portfolio', 'route' => 'portfolio'],
                        ['label' => 'Insights', 'route' => 'insights'],
                        ['label' => 'Community', 'route' => 'community'],
                        ['label' => 'Partner Hub', 'route' => 'contact'],
                    ],
                ],
                [
                    'title' => 'Connect',
                    'links' => [
                        ['label' => 'Client Portal', 'route' => 'login'],
                        ['label' => 'Newsletter', 'route' => 'home'],
                        ['label' => 'Support', 'route' => 'contact'],
                        ['label' => 'Admin', 'route' => 'login'],
                    ],
                ],
            ],
            'social' => [
                ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/cyratech', 'opens_in_new_tab' => true],
                ['label' => 'X (Twitter)', 'url' => 'https://x.com/cyratech', 'opens_in_new_tab' => true],
                ['label' => 'GitHub', 'url' => 'https://github.com/CodeMaestroPro', 'opens_in_new_tab' => true],
                ['label' => 'YouTube', 'url' => 'https://www.youtube.com/@cyratech', 'opens_in_new_tab' => true],
            ],
            'legal' => [
                ['label' => 'Privacy Policy', 'route' => 'about'],
                ['label' => 'Terms of Service', 'route' => 'about'],
                ['label' => 'Cookie Policy', 'route' => 'about'],
                ['label' => 'Accessibility', 'route' => 'about'],
            ],
            'newsletter' => [
                'title' => 'Stay ahead with Cyra-Tech',
                'description' => 'Enterprise insights, product updates, and innovation stories delivered to your inbox.',
                'placeholder' => 'Enter your work email',
                'button' => 'Subscribe',
            ],
        ],
        'admin' => [
            'groups' => [
                [
                    'label' => 'Executive',
                    'items' => [
                        ['label' => 'Brief', 'route' => 'admin.dashboard', 'permission' => 'dashboard.access'],
                        ['label' => 'Company Pulse', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Business Intelligence', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Strategic Roadmap', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'AI Assistant', 'permission' => 'dashboard.access', 'available' => false],
                    ],
                ],
                [
                    'label' => 'Digital Headquarters',
                    'items' => [
                        ['label' => 'Homepage Builder', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Pages', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Navigation', 'route' => 'admin.dashboard', 'permission' => 'dashboard.access'],
                        ['label' => 'Media Library', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Component Library', 'route' => 'admin.design-system', 'permission' => 'modules.view'],
                    ],
                ],
                [
                    'label' => 'Solutions',
                    'items' => [
                        ['label' => 'Services', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Industries', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Products', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Innovation Lab', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Portfolio', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Case Studies', 'permission' => 'modules.view', 'available' => false],
                    ],
                ],
                [
                    'label' => 'Growth',
                    'items' => [
                        ['label' => 'Leads & CRM', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Partners', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Marketing', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Analytics', 'permission' => 'dashboard.access', 'available' => false],
                    ],
                ],
                [
                    'label' => 'People',
                    'items' => [
                        ['label' => 'Leadership', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Team Members', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Careers', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Applicants', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Community', 'permission' => 'modules.view', 'available' => false],
                    ],
                ],
                [
                    'label' => 'Operations',
                    'items' => [
                        ['label' => 'Projects', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Tasks', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Calendar', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Reports', 'permission' => 'dashboard.access', 'available' => false],
                    ],
                ],
                [
                    'label' => 'System',
                    'items' => [
                        ['label' => 'Users & Roles', 'permission' => 'roles.view', 'available' => false],
                        ['label' => 'Security', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Logs', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Enterprise Settings', 'permission' => 'dashboard.access', 'available' => false],
                    ],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */

    'api' => [
        'prefix' => 'api/v1',
        'rate_limit' => env('CYRA_API_RATE_LIMIT', 60),
    ],

];
