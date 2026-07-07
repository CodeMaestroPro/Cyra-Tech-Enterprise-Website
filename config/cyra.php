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
    |
    | Tracks the 25-module enterprise build roadmap. Module 01 is marked
    | complete once initialization finishes; later modules update status.
    |
    */

    'modules' => [
        ['id' => 1, 'slug' => 'project-initialization', 'name' => 'Project Initialization', 'status' => 'completed'],
        ['id' => 2, 'slug' => 'authentication-rbac', 'name' => 'Authentication & RBAC', 'status' => 'pending'],
        ['id' => 3, 'slug' => 'design-system', 'name' => 'Design System', 'status' => 'pending'],
        ['id' => 4, 'slug' => 'global-navigation', 'name' => 'Global Navigation', 'status' => 'pending'],
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
    | API Configuration
    |--------------------------------------------------------------------------
    */

    'api' => [
        'prefix' => 'api/v1',
        'rate_limit' => env('CYRA_API_RATE_LIMIT', 60),
    ],

];
