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
        ['id' => 5, 'slug' => 'homepage', 'name' => 'Homepage', 'status' => 'completed'],
        ['id' => 6, 'slug' => 'about', 'name' => 'About', 'status' => 'completed'],
        ['id' => 7, 'slug' => 'leadership', 'name' => 'Leadership', 'status' => 'completed'],
        ['id' => 8, 'slug' => 'solutions', 'name' => 'Solutions', 'status' => 'completed'],
        ['id' => 9, 'slug' => 'products', 'name' => 'Products', 'status' => 'completed'],
        ['id' => 10, 'slug' => 'industries', 'name' => 'Industries', 'status' => 'completed'],
        ['id' => 11, 'slug' => 'portfolio', 'name' => 'Portfolio', 'status' => 'completed'],
        ['id' => 12, 'slug' => 'innovation-lab', 'name' => 'Innovation Lab', 'status' => 'completed'],
        ['id' => 13, 'slug' => 'community', 'name' => 'Community', 'status' => 'completed'],
        ['id' => 14, 'slug' => 'insights', 'name' => 'Insights', 'status' => 'completed'],
        ['id' => 15, 'slug' => 'careers', 'name' => 'Careers', 'status' => 'completed'],
        ['id' => 16, 'slug' => 'contact', 'name' => 'Contact', 'status' => 'completed'],
        ['id' => 17, 'slug' => 'partner-hub', 'name' => 'Partner Hub', 'status' => 'completed'],
        ['id' => 18, 'slug' => 'client-portal', 'name' => 'Client Portal', 'status' => 'completed'],
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
        'client' => [
            'name' => 'Client',
            'description' => 'Secure access to assigned Cyra-Tech client portal engagements.',
            'permissions' => [
                'client-portal.access',
                'client-portal.view',
            ],
        ],
    ],

    'permissions' => [
        'dashboard.access' => ['name' => 'Access Dashboard', 'group' => 'Dashboard'],
        'client-portal.access' => ['name' => 'Access Client Portal', 'group' => 'Client Portal'],
        'client-portal.view' => ['name' => 'View Client Engagements', 'group' => 'Client Portal'],
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

    'client_user' => [
        'name' => env('CYRA_CLIENT_NAME', 'Sarah Mitchell'),
        'email' => env('CYRA_CLIENT_EMAIL', 'client@novabank.com'),
        'password' => env('CYRA_CLIENT_PASSWORD', 'password'),
        'role' => 'client',
        'account' => 'novabank',
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
    | Homepage Content
    |--------------------------------------------------------------------------
    */

    'homepage' => [
        'seo' => [
            'title' => 'Cyra-Tech | Built on Vision. Driven by Intelligence.',
            'description' => 'Cyra-Tech delivers enterprise digital transformation, cloud infrastructure, AI intelligence, and secure innovation for global organizations.',
            'keywords' => [
                'enterprise technology',
                'digital transformation',
                'cloud infrastructure',
                'AI solutions',
                'Cyra-Tech',
            ],
        ],
        'sections' => [
            [
                'slug' => 'hero',
                'type' => 'hero',
                'eyebrow' => 'Enterprise Technology Partner',
                'title' => 'Built on Vision. Driven by Intelligence.',
                'description' => 'Cyra-Tech architects intelligent digital ecosystems that accelerate growth, strengthen security, and unlock enterprise innovation at scale.',
                'content' => [
                    'highlights' => [
                        'Digital Transformation',
                        'AI & Intelligence',
                        'Cloud at Scale',
                        'Secure by Design',
                    ],
                    'actions' => [
                        ['label' => 'Explore Solutions', 'route' => 'solutions', 'variant' => 'primary'],
                        ['label' => 'Talk to Our Team', 'route' => 'contact', 'variant' => 'secondary'],
                    ],
                ],
            ],
            [
                'slug' => 'stats',
                'type' => 'stats',
                'content' => [
                    'items' => [
                        ['label' => 'Enterprise Projects Delivered', 'value' => '500', 'suffix' => '+'],
                        ['label' => 'Client Satisfaction', 'value' => '98', 'suffix' => '%'],
                        ['label' => 'Industries Served', 'value' => '25', 'suffix' => '+'],
                        ['label' => 'Global Technology Experts', 'value' => '120', 'suffix' => '+'],
                    ],
                ],
            ],
            [
                'slug' => 'partners',
                'type' => 'logos',
                'eyebrow' => 'Trusted By',
                'title' => 'Organizations that scale with Cyra-Tech',
                'content' => [
                    'items' => [
                        'NovaBank', 'Helix Health', 'Astra Logistics', 'Vertex Energy', 'Quantum Retail', 'Pinnacle GovTech',
                    ],
                ],
            ],
            [
                'slug' => 'solutions',
                'type' => 'feature-grid',
                'eyebrow' => 'Solutions',
                'title' => 'End-to-end capabilities for modern enterprises',
                'description' => 'From strategy to execution, Cyra-Tech delivers modular solutions engineered for performance, resilience, and measurable outcomes.',
                'content' => [
                    'items' => [
                        [
                            'title' => 'Digital Transformation',
                            'description' => 'Modernize legacy systems, streamline operations, and build future-ready digital platforms.',
                            'route' => 'solutions',
                            'icon' => 'transform',
                        ],
                        [
                            'title' => 'Cloud & Infrastructure',
                            'description' => 'Design secure, scalable cloud architectures with observability and cost optimization built in.',
                            'route' => 'solutions',
                            'icon' => 'cloud',
                        ],
                        [
                            'title' => 'AI & Intelligence',
                            'description' => 'Deploy practical AI, automation, and data intelligence that drive decision velocity.',
                            'route' => 'innovation-lab',
                            'icon' => 'ai',
                        ],
                        [
                            'title' => 'Cybersecurity',
                            'description' => 'Protect critical assets with zero-trust frameworks, compliance readiness, and proactive monitoring.',
                            'route' => 'solutions',
                            'icon' => 'shield',
                        ],
                    ],
                    'action' => ['label' => 'View All Solutions', 'route' => 'solutions'],
                ],
            ],
            [
                'slug' => 'products',
                'type' => 'card-grid',
                'eyebrow' => 'Products',
                'title' => 'Platform products built for enterprise velocity',
                'description' => 'Composable products that integrate with your stack and scale across teams, regions, and business units.',
                'content' => [
                    'items' => [
                        [
                            'title' => 'Cyra Command',
                            'description' => 'Unified operations dashboard for executives, teams, and digital headquarters workflows.',
                            'route' => 'products',
                            'badge' => 'Flagship',
                        ],
                        [
                            'title' => 'Cyra Pulse',
                            'description' => 'Real-time business intelligence with executive briefings and automated insights.',
                            'route' => 'products',
                            'badge' => 'Analytics',
                        ],
                        [
                            'title' => 'Cyra Shield',
                            'description' => 'Enterprise security posture management with compliance automation and threat visibility.',
                            'route' => 'products',
                            'badge' => 'Security',
                        ],
                    ],
                    'action' => ['label' => 'Explore Products', 'route' => 'products'],
                ],
            ],
            [
                'slug' => 'industries',
                'type' => 'card-grid',
                'eyebrow' => 'Industries',
                'title' => 'Deep domain expertise across regulated and high-growth sectors',
                'content' => [
                    'columns' => 3,
                    'items' => [
                        ['title' => 'Financial Services', 'description' => 'Secure digital banking, fraud prevention, and regulatory-ready platforms.', 'route' => 'industries'],
                        ['title' => 'Healthcare', 'description' => 'Patient-centric systems, interoperability, and HIPAA-aligned infrastructure.', 'route' => 'industries'],
                        ['title' => 'Government', 'description' => 'Mission-critical systems with accessibility, security, and citizen experience.', 'route' => 'industries'],
                        ['title' => 'Energy & Utilities', 'description' => 'IoT-enabled operations, grid intelligence, and sustainability analytics.', 'route' => 'industries'],
                        ['title' => 'Retail & Commerce', 'description' => 'Omnichannel experiences, inventory intelligence, and personalization engines.', 'route' => 'industries'],
                        ['title' => 'Technology', 'description' => 'Product engineering, platform modernization, and SaaS acceleration.', 'route' => 'industries'],
                    ],
                    'action' => ['label' => 'View Industries', 'route' => 'industries'],
                ],
            ],
            [
                'slug' => 'innovation-lab',
                'type' => 'split-feature',
                'eyebrow' => 'Innovation Lab',
                'title' => 'Prototype the future before your market demands it',
                'description' => 'Cyra-Tech Innovation Lab combines research, rapid prototyping, and production engineering to turn bold ideas into enterprise-ready products.',
                'content' => [
                    'bullets' => [
                        'AI copilots and intelligent automation',
                        'Emerging tech proof-of-concepts',
                        'Design sprints and venture partnerships',
                    ],
                    'action' => ['label' => 'Enter Innovation Lab', 'route' => 'innovation-lab'],
                ],
            ],
            [
                'slug' => 'portfolio',
                'type' => 'card-grid',
                'eyebrow' => 'Portfolio',
                'title' => 'Proven outcomes across complex enterprise programs',
                'content' => [
                    'items' => [
                        [
                            'title' => 'NovaBank Digital Core',
                            'description' => 'Migrated 40+ legacy services to a cloud-native banking platform in 14 months.',
                            'route' => 'portfolio',
                            'metric' => '40% faster time-to-market',
                        ],
                        [
                            'title' => 'Helix Health Network',
                            'description' => 'Unified patient data platform serving 2M+ records with real-time analytics.',
                            'route' => 'portfolio',
                            'metric' => '99.95% uptime SLA',
                        ],
                        [
                            'title' => 'Astra Logistics Command',
                            'description' => 'AI-driven supply chain visibility across 18 countries and 120 distribution hubs.',
                            'route' => 'portfolio',
                            'metric' => '22% cost reduction',
                        ],
                    ],
                    'action' => ['label' => 'View Case Studies', 'route' => 'portfolio'],
                ],
            ],
            [
                'slug' => 'insights',
                'type' => 'card-grid',
                'eyebrow' => 'Insights',
                'title' => 'Executive perspectives on technology and transformation',
                'content' => [
                    'items' => [
                        [
                            'title' => 'The Executive Guide to AI Readiness',
                            'description' => 'A practical framework for adopting AI without compromising governance or security.',
                            'route' => 'insights',
                            'meta' => '8 min read',
                        ],
                        [
                            'title' => 'Cloud FinOps for Enterprise Scale',
                            'description' => 'How CIOs are aligning cloud spend with business outcomes and innovation budgets.',
                            'route' => 'insights',
                            'meta' => '6 min read',
                        ],
                        [
                            'title' => 'Zero Trust in Regulated Industries',
                            'description' => 'Implementing identity-first security models across financial and public sector environments.',
                            'route' => 'insights',
                            'meta' => '10 min read',
                        ],
                    ],
                    'action' => ['label' => 'Read Insights', 'route' => 'insights'],
                ],
            ],
            [
                'slug' => 'community',
                'type' => 'cta-band',
                'eyebrow' => 'Community',
                'title' => 'Join builders, leaders, and innovators shaping what\'s next',
                'description' => 'Connect with Cyra-Tech practitioners, partners, and enterprise leaders in our global technology community.',
                'content' => [
                    'action' => ['label' => 'Join the Community', 'route' => 'community'],
                    'variant' => 'surface',
                ],
            ],
            [
                'slug' => 'careers',
                'type' => 'cta-band',
                'eyebrow' => 'Careers',
                'title' => 'Build the future of enterprise technology with us',
                'description' => 'We\'re hiring engineers, strategists, designers, and operators who thrive on complex challenges.',
                'content' => [
                    'action' => ['label' => 'View Open Roles', 'route' => 'careers'],
                    'variant' => 'primary',
                ],
            ],
            [
                'slug' => 'contact-cta',
                'type' => 'cta-band',
                'eyebrow' => 'Let\'s Talk',
                'title' => 'Ready to transform your enterprise?',
                'description' => 'Partner with Cyra-Tech to design, build, and scale intelligent systems that deliver lasting impact.',
                'content' => [
                    'actions' => [
                        ['label' => 'Contact Us', 'route' => 'contact', 'variant' => 'primary'],
                        ['label' => 'Client Portal', 'route' => 'client-portal', 'variant' => 'secondary'],
                    ],
                    'variant' => 'gradient',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | About Pages Content
    |--------------------------------------------------------------------------
    */

    'about' => [
        'pages' => [
            [
                'slug' => 'overview',
                'route_name' => 'about',
                'nav_label' => 'Overview',
                'eyebrow' => 'About Cyra-Tech',
                'title' => 'Engineering intelligent enterprise futures',
                'description' => 'Cyra-Tech is a global technology partner helping organizations design, build, and scale secure digital platforms that deliver measurable business impact.',
                'seo' => [
                    'title' => 'About Cyra-Tech | Enterprise Technology Partner',
                    'description' => 'Learn about Cyra-Tech\'s mission, approach, and commitment to enterprise digital transformation.',
                    'keywords' => ['about Cyra-Tech', 'enterprise technology company', 'digital transformation partner'],
                ],
                'content' => [
                    ['type' => 'prose', 'title' => 'Our Story', 'paragraphs' => [
                        'Founded on a belief that technology should amplify human potential, Cyra-Tech partners with enterprises to translate vision into resilient, intelligent systems.',
                        'We combine strategy, engineering, and operational excellence to help clients modernize legacy environments, adopt cloud-native architectures, and embed AI responsibly across the organization.',
                    ]],
                    ['type' => 'stats-row', 'items' => [
                        ['label' => 'Founded', 'value' => '2010'],
                        ['label' => 'Global Offices', 'value' => '8'],
                        ['label' => 'Enterprise Clients', 'value' => '200+'],
                        ['label' => 'Countries Served', 'value' => '30+'],
                    ]],
                    ['type' => 'feature-list', 'title' => 'How we partner', 'items' => [
                        ['title' => 'Strategy first', 'description' => 'We align technology roadmaps with executive outcomes before writing a line of code.'],
                        ['title' => 'Secure by design', 'description' => 'Security, compliance, and governance are embedded from discovery through deployment.'],
                        ['title' => 'Outcome obsessed', 'description' => 'Every engagement is measured against adoption, performance, and business value.'],
                    ]],
                    ['type' => 'cta', 'title' => 'Discover what sets us apart', 'description' => 'Learn why global enterprises partner with Cyra-Tech for complex transformation programs.', 'action' => ['label' => 'Why Choose Us', 'route' => 'about.why-choose-us']],
                ],
            ],
            [
                'slug' => 'vision-mission',
                'route_name' => 'about.vision-mission',
                'nav_label' => 'Vision & Mission',
                'eyebrow' => 'Purpose',
                'title' => 'Vision and mission that drive every engagement',
                'description' => 'Our north star is simple: build technology that empowers people, strengthens organizations, and creates lasting value.',
                'seo' => [
                    'title' => 'Vision & Mission | Cyra-Tech',
                    'description' => 'Cyra-Tech\'s vision and mission for enterprise innovation, intelligence, and impact.',
                ],
                'content' => [
                    ['type' => 'quote-cards', 'items' => [
                        ['label' => 'Vision', 'quote' => 'To be the most trusted architect of intelligent enterprise ecosystems worldwide.', 'description' => 'We envision a future where every organization can harness technology confidently, ethically, and at scale.'],
                        ['label' => 'Mission', 'quote' => 'To deliver secure, intelligent, and human-centered technology solutions that accelerate transformation.', 'description' => 'We partner with clients to design, build, and operate digital systems that turn complexity into competitive advantage.'],
                    ]],
                    ['type' => 'prose', 'title' => 'Our Purpose', 'paragraphs' => [
                        'Cyra-Tech exists to bridge the gap between ambition and execution. We help leaders move from fragmented initiatives to cohesive digital programs with clarity, speed, and accountability.',
                    ]],
                ],
            ],
            [
                'slug' => 'values',
                'route_name' => 'about.values',
                'nav_label' => 'Values',
                'eyebrow' => 'Core Values',
                'title' => 'Principles that define how we work',
                'description' => 'Our values shape every client conversation, architecture decision, and line of code we deliver.',
                'seo' => [
                    'title' => 'Core Values | Cyra-Tech',
                    'description' => 'Integrity, innovation, excellence, and partnership — the values that guide Cyra-Tech.',
                ],
                'content' => [
                    ['type' => 'value-grid', 'items' => [
                        ['title' => 'Integrity', 'description' => 'We operate with transparency, accountability, and respect for client trust.'],
                        ['title' => 'Innovation', 'description' => 'We explore emerging technology pragmatically to solve real enterprise challenges.'],
                        ['title' => 'Excellence', 'description' => 'We hold ourselves to engineering and delivery standards that exceed expectations.'],
                        ['title' => 'Partnership', 'description' => 'We embed with client teams as long-term collaborators, not transactional vendors.'],
                        ['title' => 'Impact', 'description' => 'We measure success by business outcomes, not deliverables alone.'],
                        ['title' => 'Inclusion', 'description' => 'We build diverse teams and accessible solutions that serve every stakeholder.'],
                    ]],
                ],
            ],
            [
                'slug' => 'history',
                'route_name' => 'about.history',
                'nav_label' => 'History',
                'eyebrow' => 'Our Journey',
                'title' => 'Fifteen years of enterprise innovation',
                'description' => 'From a specialist engineering studio to a global technology partner, Cyra-Tech has grown alongside the enterprises we serve.',
                'seo' => [
                    'title' => 'Company History | Cyra-Tech',
                    'description' => 'Explore Cyra-Tech\'s milestones from founding to global enterprise technology leadership.',
                ],
                'content' => [
                    ['type' => 'timeline', 'items' => [
                        ['year' => '2010', 'title' => 'Cyra-Tech founded', 'description' => 'Launched as a boutique enterprise engineering firm focused on modernization programs.'],
                        ['year' => '2014', 'title' => 'Cloud practice established', 'description' => 'Expanded into cloud migration, DevOps, and platform engineering for regulated industries.'],
                        ['year' => '2018', 'title' => 'Global delivery network', 'description' => 'Opened offices across North America, Europe, and Africa to support multinational clients.'],
                        ['year' => '2021', 'title' => 'AI & Intelligence Lab', 'description' => 'Launched dedicated AI research and production engineering capabilities for enterprise clients.'],
                        ['year' => '2024', 'title' => 'Digital Headquarters platform', 'description' => 'Introduced Cyra Command — unified operations for executives and digital teams.'],
                        ['year' => '2026', 'title' => 'Enterprise platform evolution', 'description' => 'Delivering modular platform experiences across 25 capability modules for clients worldwide.'],
                    ]],
                ],
            ],
            [
                'slug' => 'why-choose-us',
                'route_name' => 'about.why-choose-us',
                'nav_label' => 'Why Choose Us',
                'eyebrow' => 'Differentiators',
                'title' => 'Why global enterprises choose Cyra-Tech',
                'description' => 'We combine deep technical expertise with executive-level partnership to deliver programs that scale.',
                'seo' => [
                    'title' => 'Why Choose Cyra-Tech | Enterprise Technology Partner',
                    'description' => 'Discover what sets Cyra-Tech apart: domain expertise, secure delivery, and measurable outcomes.',
                ],
                'content' => [
                    ['type' => 'feature-list', 'items' => [
                        ['title' => 'Enterprise-grade delivery', 'description' => 'Proven playbooks for complex, multi-year transformation programs across regulated sectors.'],
                        ['title' => 'Full-stack capabilities', 'description' => 'Strategy, design, engineering, data, AI, security, and managed operations under one partner.'],
                        ['title' => 'Executive alignment', 'description' => 'Dedicated briefings, roadmaps, and KPI dashboards that keep leadership informed and engaged.'],
                        ['title' => 'Adaptive teams', 'description' => 'Cross-functional squads that integrate with your organization and scale with program needs.'],
                    ]],
                    ['type' => 'stats-row', 'items' => [
                        ['label' => 'On-time Delivery Rate', 'value' => '96%'],
                        ['label' => 'Client Retention', 'value' => '92%'],
                        ['label' => 'Certified Experts', 'value' => '350+'],
                        ['label' => 'Security Certifications', 'value' => '12'],
                    ]],
                    ['type' => 'cta', 'title' => 'Ready to start your transformation?', 'description' => 'Connect with our team to explore how Cyra-Tech can accelerate your next initiative.', 'action' => ['label' => 'Contact Us', 'route' => 'contact']],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Leadership Content
    |--------------------------------------------------------------------------
    */

    'leadership' => [
        'seo' => [
            'title' => 'Leadership | Cyra-Tech Executive Team',
            'description' => 'Meet the Cyra-Tech executive leadership team driving enterprise innovation, client success, and responsible technology stewardship.',
            'keywords' => ['Cyra-Tech leadership', 'executive team', 'CEO', 'technology leadership'],
        ],
        'hero' => [
            'eyebrow' => 'Leadership',
            'title' => 'Stewards of vision, accountability, and enterprise impact',
            'description' => 'Cyra-Tech leaders combine deep technical expertise with executive partnership to guide clients through complex transformation programs worldwide.',
        ],
        'governance' => [
            'eyebrow' => 'Governance',
            'title' => 'Accountability built into how we operate',
            'description' => 'Our leadership model balances innovation velocity with rigorous governance, ensuring every engagement meets enterprise standards for security, compliance, and transparency.',
            'pillars' => [
                [
                    'title' => 'Executive Oversight',
                    'description' => 'Senior leaders remain actively engaged in strategic accounts, delivery quality, and client outcomes.',
                ],
                [
                    'title' => 'Ethical Technology',
                    'description' => 'We embed responsible AI, data privacy, and inclusive design principles across all practices.',
                ],
                [
                    'title' => 'Transparent Reporting',
                    'description' => 'Clients receive clear program dashboards, risk registers, and executive briefings at every stage.',
                ],
            ],
        ],
        'cta' => [
            'title' => 'Partner with proven enterprise leaders',
            'description' => 'Connect with Cyra-Tech to discuss your transformation goals with our executive team.',
            'action' => ['label' => 'Contact Us', 'route' => 'contact'],
        ],
        'profiles' => [
            [
                'slug' => 'collins-pever',
                'name' => 'Collins Pever',
                'title' => 'Chief Executive Officer',
                'tier' => 'executive',
                'bio' => 'Collins Pever founded Cyra-Tech with a mission to bridge enterprise ambition and execution. He leads global strategy, major client partnerships, and the company\'s long-term platform vision. Under his leadership, Cyra-Tech has delivered transformation programs across financial services, healthcare, and public sector organizations.',
                'focus_areas' => ['Enterprise Strategy', 'Client Partnerships', 'Platform Vision'],
                'linkedin_url' => 'https://www.linkedin.com/in/collinspever',
                'email' => 'collins.pever@cyratech.com',
                'is_featured' => true,
            ],
            [
                'slug' => 'amara-okonkwo',
                'name' => 'Dr. Amara Okonkwo',
                'title' => 'Chief Technology Officer',
                'tier' => 'executive',
                'bio' => 'Dr. Okonkwo oversees Cyra-Tech\'s engineering standards, cloud architecture practice, and innovation roadmap. She brings two decades of experience building secure, scalable platforms for regulated industries and leads the company\'s technical excellence programs.',
                'focus_areas' => ['Cloud Architecture', 'Engineering Excellence', 'Platform Security'],
                'linkedin_url' => 'https://www.linkedin.com/in/amaraokonkwo',
                'is_featured' => true,
            ],
            [
                'slug' => 'james-whitfield',
                'name' => 'James Whitfield',
                'title' => 'Chief Operating Officer',
                'tier' => 'executive',
                'bio' => 'James Whitfield manages global delivery operations, client success, and program governance. He ensures Cyra-Tech engagements run with predictable outcomes, strong communication, and measurable business value for enterprise stakeholders.',
                'focus_areas' => ['Global Delivery', 'Client Success', 'Program Governance'],
                'linkedin_url' => 'https://www.linkedin.com/in/jameswhitfield',
                'is_featured' => true,
            ],
            [
                'slug' => 'elena-vasquez',
                'name' => 'Elena Vasquez',
                'title' => 'Chief Innovation Officer',
                'tier' => 'executive',
                'bio' => 'Elena Vasquez leads Cyra-Tech\'s Innovation Lab, AI practice, and emerging technology research. She helps clients evaluate, prototype, and productionize intelligent systems that create durable competitive advantage.',
                'focus_areas' => ['AI & Intelligence', 'Innovation Lab', 'Emerging Technology'],
                'linkedin_url' => 'https://www.linkedin.com/in/elenavasquez',
                'is_featured' => true,
            ],
            [
                'slug' => 'priya-sharma',
                'name' => 'Priya Sharma',
                'title' => 'Chief Financial Officer',
                'tier' => 'extended',
                'bio' => 'Priya Sharma directs Cyra-Tech\'s financial strategy, enterprise risk management, and commercial operations. She partners with clients on long-term investment models that align technology spend with business outcomes.',
                'focus_areas' => ['Financial Strategy', 'Risk Management', 'Commercial Operations'],
                'is_featured' => false,
            ],
            [
                'slug' => 'marcus-chen',
                'name' => 'Marcus Chen',
                'title' => 'Chief People Officer',
                'tier' => 'extended',
                'bio' => 'Marcus Chen leads talent strategy, culture, and organizational development across Cyra-Tech\'s global teams. He champions inclusive leadership and the growth of high-performance engineering and consulting teams.',
                'focus_areas' => ['Talent Strategy', 'Culture', 'Organizational Development'],
                'is_featured' => false,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Solutions Content
    |--------------------------------------------------------------------------
    */

    'solutions' => [
        'seo' => [
            'title' => 'Enterprise Solutions | Cyra-Tech',
            'description' => 'Explore Cyra-Tech solutions for digital transformation, cloud infrastructure, AI intelligence, cybersecurity, data analytics, and enterprise integration.',
            'keywords' => ['enterprise solutions', 'digital transformation', 'cloud services', 'AI solutions', 'cybersecurity'],
        ],
        'hero' => [
            'eyebrow' => 'Solutions',
            'title' => 'End-to-end capabilities for modern enterprises',
            'description' => 'Cyra-Tech delivers modular solution offerings engineered for performance, resilience, and measurable business outcomes across complex environments.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Solutions'],
            ['slug' => 'transformation', 'label' => 'Transformation'],
            ['slug' => 'platform', 'label' => 'Platform'],
            ['slug' => 'intelligence', 'label' => 'Intelligence'],
            ['slug' => 'security', 'label' => 'Security'],
            ['slug' => 'operations', 'label' => 'Operations'],
        ],
        'offerings' => [
            [
                'slug' => 'digital-transformation',
                'category' => 'transformation',
                'title' => 'Digital Transformation',
                'summary' => 'Modernize legacy systems and build future-ready digital platforms.',
                'description' => 'Cyra-Tech helps enterprises reimagine operating models, modernize core systems, and deliver customer experiences that scale. We combine discovery, architecture, and agile delivery to reduce transformation risk.',
                'capabilities' => ['Legacy modernization', 'Platform re-architecture', 'Process automation', 'Change management'],
                'outcomes' => ['40% faster release cycles', 'Reduced operational friction', 'Improved customer experience'],
                'icon' => 'transform',
                'is_featured' => true,
            ],
            [
                'slug' => 'cloud-infrastructure',
                'category' => 'platform',
                'title' => 'Cloud & Infrastructure',
                'summary' => 'Design secure, scalable cloud foundations with observability built in.',
                'description' => 'From landing zones to multi-cloud strategy, we architect resilient infrastructure with cost governance, automated operations, and enterprise-grade reliability targets.',
                'capabilities' => ['Cloud migration', 'Kubernetes platforms', 'FinOps & cost optimization', 'Site reliability engineering'],
                'outcomes' => ['99.9%+ availability targets', 'Optimized cloud spend', 'Faster deployment pipelines'],
                'icon' => 'cloud',
                'is_featured' => true,
            ],
            [
                'slug' => 'ai-intelligence',
                'category' => 'intelligence',
                'title' => 'AI & Intelligence',
                'summary' => 'Deploy practical AI, automation, and decision intelligence at scale.',
                'description' => 'Our AI practice focuses on production-ready copilots, intelligent workflows, and data products with governance, monitoring, and human oversight from day one.',
                'capabilities' => ['AI strategy & roadmaps', 'Copilot development', 'MLOps & model governance', 'Intelligent automation'],
                'outcomes' => ['Faster decision cycles', 'Higher team productivity', 'Responsible AI adoption'],
                'icon' => 'ai',
                'is_featured' => true,
            ],
            [
                'slug' => 'cybersecurity',
                'category' => 'security',
                'title' => 'Cybersecurity',
                'summary' => 'Protect critical assets with zero-trust and proactive monitoring.',
                'description' => 'Cyra-Tech embeds security into every layer of the stack — identity, infrastructure, applications, and data — with compliance frameworks tailored to regulated industries.',
                'capabilities' => ['Zero-trust architecture', 'Security operations', 'Compliance readiness', 'Penetration testing'],
                'outcomes' => ['Reduced breach exposure', 'Audit-ready controls', 'Improved incident response'],
                'icon' => 'shield',
                'is_featured' => true,
            ],
            [
                'slug' => 'data-analytics',
                'category' => 'intelligence',
                'title' => 'Data & Analytics',
                'summary' => 'Turn enterprise data into actionable intelligence and executive insight.',
                'description' => 'We design modern data platforms, analytics pipelines, and executive dashboards that unify fragmented data sources and enable confident decision-making.',
                'capabilities' => ['Data platform engineering', 'BI & executive dashboards', 'Real-time analytics', 'Master data management'],
                'outcomes' => ['Unified data visibility', 'Self-service analytics', 'Trusted executive reporting'],
                'icon' => 'data',
                'is_featured' => false,
            ],
            [
                'slug' => 'enterprise-integration',
                'category' => 'platform',
                'title' => 'Enterprise Integration',
                'summary' => 'Connect systems, APIs, and partners with reliable integration fabric.',
                'description' => 'Cyra-Tech builds API ecosystems, event-driven architectures, and integration hubs that keep mission-critical systems synchronized across the enterprise.',
                'capabilities' => ['API management', 'Event-driven architecture', 'B2B integrations', 'Legacy connectivity'],
                'outcomes' => ['Reduced integration debt', 'Faster partner onboarding', 'Improved data consistency'],
                'icon' => 'integration',
                'is_featured' => false,
            ],
            [
                'slug' => 'managed-services',
                'category' => 'operations',
                'title' => 'Managed Services',
                'summary' => 'Operate and optimize platforms with dedicated Cyra-Tech engineering teams.',
                'description' => 'Our managed services extend your capabilities with 24/7 monitoring, incident response, platform enhancements, and continuous improvement aligned to SLAs.',
                'capabilities' => ['24/7 platform monitoring', 'Incident & problem management', 'Continuous improvement', 'SLA-backed support'],
                'outcomes' => ['Predictable operations', 'Lower total cost of ownership', 'Continuous platform evolution'],
                'icon' => 'managed',
                'is_featured' => false,
            ],
        ],
        'process' => [
            'eyebrow' => 'Delivery Model',
            'title' => 'How Cyra-Tech delivers enterprise solutions',
            'description' => 'A proven engagement model that balances discovery, delivery velocity, and executive visibility.',
            'steps' => [
                ['title' => 'Discover', 'description' => 'Assess current state, define outcomes, and align stakeholders on a transformation roadmap.'],
                ['title' => 'Design', 'description' => 'Architect secure, scalable solutions with clear milestones, risks, and success metrics.'],
                ['title' => 'Deliver', 'description' => 'Execute in agile increments with transparent reporting and continuous client collaboration.'],
                ['title' => 'Optimize', 'description' => 'Measure impact, harden operations, and evolve the platform for long-term value.'],
            ],
        ],
        'cta' => [
            'title' => 'Need a tailored solution architecture?',
            'description' => 'Our solution architects partner with your team to design programs aligned to your industry, compliance, and growth goals.',
            'action' => ['label' => 'Talk to an Architect', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Products Content
    |--------------------------------------------------------------------------
    */

    'products' => [
        'seo' => [
            'title' => 'Platform Products | Cyra-Tech',
            'description' => 'Discover Cyra-Tech platform products — Command, Pulse, Shield, Connect, Flow, and Studio — built for enterprise operations, analytics, and security.',
            'keywords' => ['Cyra Command', 'enterprise products', 'platform software', 'Cyra-Tech products'],
        ],
        'hero' => [
            'eyebrow' => 'Products',
            'title' => 'Platform products built for enterprise velocity',
            'description' => 'Composable Cyra-Tech products integrate with your stack and scale across teams, regions, and business units with unified governance.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Products'],
            ['slug' => 'operations', 'label' => 'Operations'],
            ['slug' => 'analytics', 'label' => 'Analytics'],
            ['slug' => 'security', 'label' => 'Security'],
            ['slug' => 'integration', 'label' => 'Integration'],
            ['slug' => 'automation', 'label' => 'Automation'],
        ],
        'items' => [
            [
                'slug' => 'cyra-command',
                'category' => 'operations',
                'title' => 'Cyra Command',
                'tagline' => 'Your digital headquarters for enterprise operations.',
                'summary' => 'Unified operations dashboard for executives, teams, and digital headquarters workflows.',
                'description' => 'Cyra Command centralizes executive briefings, program dashboards, team workflows, and platform administration in one secure command center designed for enterprise leaders and operators.',
                'badge' => 'Flagship',
                'features' => ['Executive briefings', 'Program dashboards', 'Team workspaces', 'Role-based access control'],
                'use_cases' => ['Executive command centers', 'Digital HQ operations', 'Transformation program offices'],
                'icon' => 'command',
                'is_featured' => true,
            ],
            [
                'slug' => 'cyra-pulse',
                'category' => 'analytics',
                'title' => 'Cyra Pulse',
                'tagline' => 'Real-time intelligence for decisive leadership.',
                'summary' => 'Real-time business intelligence with executive briefings and automated insights.',
                'description' => 'Cyra Pulse aggregates enterprise data into live dashboards, automated insights, and executive-ready narratives so leaders can monitor performance and act with confidence.',
                'badge' => 'Analytics',
                'features' => ['Live KPI dashboards', 'Automated insight alerts', 'Executive narrative reports', 'Data source connectors'],
                'use_cases' => ['C-suite reporting', 'Operational analytics', 'Performance management'],
                'icon' => 'pulse',
                'is_featured' => true,
            ],
            [
                'slug' => 'cyra-shield',
                'category' => 'security',
                'title' => 'Cyra Shield',
                'tagline' => 'Enterprise security posture, continuously validated.',
                'summary' => 'Enterprise security posture management with compliance automation and threat visibility.',
                'description' => 'Cyra Shield provides continuous control monitoring, compliance automation, and threat visibility across cloud and on-prem environments with audit-ready reporting.',
                'badge' => 'Security',
                'features' => ['Posture dashboards', 'Compliance automation', 'Threat intelligence feeds', 'Audit reporting'],
                'use_cases' => ['Regulated industry compliance', 'Zero-trust monitoring', 'Security operations'],
                'icon' => 'shield',
                'is_featured' => true,
            ],
            [
                'slug' => 'cyra-connect',
                'category' => 'integration',
                'title' => 'Cyra Connect',
                'tagline' => 'Integration fabric for the modern enterprise.',
                'summary' => 'API management and event-driven integration hub for connected ecosystems.',
                'description' => 'Cyra Connect enables secure API publishing, partner integrations, and event-driven workflows with observability and lifecycle governance built in.',
                'badge' => 'Integration',
                'features' => ['API gateway & management', 'Event streaming', 'Partner onboarding', 'Integration observability'],
                'use_cases' => ['B2B partner ecosystems', 'Legacy modernization', 'Microservices connectivity'],
                'icon' => 'integration',
                'is_featured' => false,
            ],
            [
                'slug' => 'cyra-flow',
                'category' => 'automation',
                'title' => 'Cyra Flow',
                'tagline' => 'Orchestrate intelligent workflows at scale.',
                'summary' => 'Workflow automation platform for cross-functional enterprise processes.',
                'description' => 'Cyra Flow automates approvals, notifications, and multi-system workflows with human-in-the-loop controls, audit trails, and low-code configuration.',
                'badge' => 'Automation',
                'features' => ['Visual workflow builder', 'Human-in-the-loop steps', 'System connectors', 'Audit trails'],
                'use_cases' => ['Approval workflows', 'Customer onboarding', 'Internal service requests'],
                'icon' => 'flow',
                'is_featured' => false,
            ],
            [
                'slug' => 'cyra-studio',
                'category' => 'operations',
                'title' => 'Cyra Studio',
                'tagline' => 'Content and experience management for digital teams.',
                'summary' => 'Composable content management for marketing sites, portals, and internal experiences.',
                'description' => 'Cyra Studio empowers digital teams to manage pages, media, navigation, and reusable components with enterprise governance and preview workflows.',
                'badge' => 'Content',
                'features' => ['Page builder', 'Media library integration', 'Component governance', 'Preview & publish workflows'],
                'use_cases' => ['Corporate websites', 'Client portals', 'Internal knowledge hubs'],
                'icon' => 'studio',
                'is_featured' => false,
            ],
        ],
        'ecosystem' => [
            'eyebrow' => 'Product Ecosystem',
            'title' => 'Built to work better together',
            'description' => 'Cyra-Tech products share identity, permissions, design language, and data fabric — so your teams operate on one coherent platform.',
            'points' => [
                'Unified authentication and RBAC across all products',
                'Shared design system and component library',
                'Cross-product analytics and executive reporting',
                'Modular licensing aligned to enterprise growth',
            ],
        ],
        'cta' => [
            'title' => 'Ready to explore the Cyra platform?',
            'description' => 'Schedule a product walkthrough with our team to see how Cyra products fit your enterprise architecture.',
            'action' => ['label' => 'Book a Demo', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Industries Content
    |--------------------------------------------------------------------------
    */

    'industries' => [
        'seo' => [
            'title' => 'Industries | Cyra-Tech',
            'description' => 'Cyra-Tech delivers domain expertise for financial services, healthcare, government, energy, retail, and technology organizations with compliance-ready engineering.',
            'keywords' => ['industry expertise', 'regulated industries', 'enterprise consulting', 'sector solutions'],
        ],
        'hero' => [
            'eyebrow' => 'Industries',
            'title' => 'Deep domain expertise across regulated and high-growth sectors',
            'description' => 'We partner with leaders in complex industries to modernize platforms, strengthen compliance posture, and deliver measurable outcomes with sector-specific playbooks.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Industries'],
            ['slug' => 'regulated', 'label' => 'Regulated'],
            ['slug' => 'public-sector', 'label' => 'Public Sector'],
            ['slug' => 'commercial', 'label' => 'Commercial'],
            ['slug' => 'industrial', 'label' => 'Industrial'],
        ],
        'verticals' => [
            [
                'slug' => 'financial-services',
                'category' => 'regulated',
                'title' => 'Financial Services',
                'tagline' => 'Secure, compliant platforms for modern banking and capital markets.',
                'summary' => 'Secure digital banking, fraud prevention, and regulatory-ready platforms.',
                'description' => 'Cyra-Tech helps banks, insurers, and fintech organizations modernize core systems while maintaining audit-ready controls, fraud resilience, and customer trust across digital channels.',
                'challenges' => ['Legacy core modernization', 'Real-time fraud detection', 'Regulatory reporting burden', 'Omnichannel customer expectations'],
                'capabilities' => ['Digital banking platforms', 'Fraud & risk analytics', 'Core system integration', 'Compliance automation'],
                'compliance' => ['PCI DSS', 'SOX', 'Basel III', 'GDPR'],
                'icon' => 'finance',
                'is_featured' => true,
            ],
            [
                'slug' => 'healthcare',
                'category' => 'regulated',
                'title' => 'Healthcare',
                'tagline' => 'Patient-centric systems built for interoperability and trust.',
                'summary' => 'Patient-centric systems, interoperability, and HIPAA-aligned infrastructure.',
                'description' => 'We design healthcare platforms that connect clinical workflows, patient experiences, and operational data with interoperability standards, privacy controls, and reliable uptime.',
                'challenges' => ['EHR interoperability', 'Patient data privacy', 'Clinical workflow friction', 'Operational cost pressure'],
                'capabilities' => ['Patient portal experiences', 'FHIR integration hubs', 'Clinical workflow automation', 'HIPAA-aligned cloud platforms'],
                'compliance' => ['HIPAA', 'HITECH', 'HL7 FHIR', 'SOC 2'],
                'icon' => 'health',
                'is_featured' => true,
            ],
            [
                'slug' => 'government',
                'category' => 'public-sector',
                'title' => 'Government',
                'tagline' => 'Mission-critical systems with accessibility and citizen experience.',
                'summary' => 'Mission-critical systems with accessibility, security, and citizen experience.',
                'description' => 'Cyra-Tech supports public-sector agencies with secure, accessible digital services, legacy modernization, and resilient infrastructure aligned to citizen expectations and procurement standards.',
                'challenges' => ['Legacy system debt', 'Citizen digital expectations', 'Security & accessibility mandates', 'Budget and procurement cycles'],
                'capabilities' => ['Citizen service portals', 'Legacy modernization', 'Secure cloud landing zones', 'Accessibility-first UX'],
                'compliance' => ['Section 508', 'FedRAMP readiness', 'NIST CSF', 'WCAG 2.1'],
                'icon' => 'government',
                'is_featured' => true,
            ],
            [
                'slug' => 'energy-utilities',
                'category' => 'industrial',
                'title' => 'Energy & Utilities',
                'tagline' => 'Intelligent operations for grids, assets, and sustainability programs.',
                'summary' => 'IoT-enabled operations, grid intelligence, and sustainability analytics.',
                'description' => 'We help energy and utility organizations connect field operations, grid intelligence, and sustainability reporting with IoT platforms, real-time analytics, and resilient OT/IT integration.',
                'challenges' => ['Grid modernization', 'Asset performance visibility', 'OT/IT convergence', 'Sustainability reporting'],
                'capabilities' => ['IoT operations platforms', 'Grid analytics dashboards', 'Asset lifecycle management', 'ESG data pipelines'],
                'compliance' => ['NERC CIP', 'ISO 27001', 'IEC 62443', 'GHG Protocol'],
                'icon' => 'energy',
                'is_featured' => false,
            ],
            [
                'slug' => 'retail-commerce',
                'category' => 'commercial',
                'title' => 'Retail & Commerce',
                'tagline' => 'Omnichannel experiences powered by inventory and customer intelligence.',
                'summary' => 'Omnichannel experiences, inventory intelligence, and personalization engines.',
                'description' => 'Cyra-Tech enables retailers to unify storefront, marketplace, and fulfillment experiences with real-time inventory intelligence, personalization, and scalable commerce platforms.',
                'challenges' => ['Omnichannel consistency', 'Inventory accuracy', 'Personalization at scale', 'Peak traffic resilience'],
                'capabilities' => ['Headless commerce platforms', 'Inventory intelligence', 'Customer data platforms', 'Order management modernization'],
                'compliance' => ['PCI DSS', 'CCPA', 'GDPR', 'SOC 2'],
                'icon' => 'retail',
                'is_featured' => false,
            ],
            [
                'slug' => 'technology',
                'category' => 'commercial',
                'title' => 'Technology',
                'tagline' => 'Product engineering and platform acceleration for high-growth tech leaders.',
                'summary' => 'Product engineering, platform modernization, and SaaS acceleration.',
                'description' => 'We partner with technology companies to accelerate product delivery, modernize SaaS platforms, and scale engineering organizations with cloud-native architecture and DevOps excellence.',
                'challenges' => ['Platform scalability', 'Release velocity', 'Multi-tenant architecture', 'Global expansion'],
                'capabilities' => ['SaaS platform engineering', 'Product modernization', 'DevOps & SRE programs', 'API ecosystem design'],
                'compliance' => ['SOC 2', 'ISO 27001', 'GDPR', 'CSA STAR'],
                'icon' => 'transform',
                'is_featured' => false,
            ],
        ],
        'expertise' => [
            'eyebrow' => 'Sector Playbooks',
            'title' => 'Industry programs engineered for outcomes',
            'description' => 'Every Cyra-Tech industry engagement combines domain advisors, compliance-aware engineering, and measurable delivery frameworks.',
            'points' => [
                'Sector-specific architecture and compliance patterns',
                'Executive governance with transparent milestone reporting',
                'Cross-functional teams spanning strategy, engineering, and operations',
                'Continuous optimization aligned to industry KPIs',
            ],
        ],
        'cta' => [
            'title' => 'Need an industry-specific transformation roadmap?',
            'description' => 'Connect with Cyra-Tech advisors to assess your sector challenges and design a program aligned to your compliance and growth goals.',
            'action' => ['label' => 'Talk to an Advisor', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Portfolio Content
    |--------------------------------------------------------------------------
    */

    'portfolio' => [
        'seo' => [
            'title' => 'Portfolio & Case Studies | Cyra-Tech',
            'description' => 'Explore Cyra-Tech case studies showcasing measurable outcomes across banking, healthcare, logistics, government, energy, and retail programs.',
            'keywords' => ['case studies', 'enterprise portfolio', 'digital transformation outcomes', 'Cyra-Tech projects'],
        ],
        'hero' => [
            'eyebrow' => 'Portfolio',
            'title' => 'Proven outcomes across complex enterprise programs',
            'description' => 'Cyra-Tech partners with global organizations to deliver transformation programs with transparent milestones, executive governance, and measurable business impact.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Projects'],
            ['slug' => 'financial', 'label' => 'Financial Services'],
            ['slug' => 'healthcare', 'label' => 'Healthcare'],
            ['slug' => 'public-sector', 'label' => 'Public Sector'],
            ['slug' => 'commercial', 'label' => 'Commercial'],
            ['slug' => 'industrial', 'label' => 'Industrial'],
        ],
        'projects' => [
            [
                'slug' => 'novabank-digital-core',
                'category' => 'financial',
                'title' => 'NovaBank Digital Core',
                'client_name' => 'NovaBank',
                'tagline' => 'Cloud-native banking platform modernization at enterprise scale.',
                'summary' => 'Migrated 40+ legacy services to a cloud-native banking platform in 14 months.',
                'description' => 'NovaBank engaged Cyra-Tech to modernize its core banking stack, replacing fragmented legacy services with a secure, API-first platform. The program delivered phased migration, zero-downtime cutovers, and executive dashboards for program governance.',
                'services' => ['Digital Transformation', 'Cloud & Infrastructure', 'Enterprise Integration', 'Managed Services'],
                'outcomes' => ['40% faster time-to-market for new products', 'Reduced integration debt across 40+ services', 'Improved audit readiness for regulatory reviews'],
                'metrics' => [
                    ['label' => 'Time-to-market', 'value' => '40% faster'],
                    ['label' => 'Services migrated', 'value' => '40+'],
                    ['label' => 'Program duration', 'value' => '14 months'],
                ],
                'duration' => '14 months',
                'icon' => 'finance',
                'is_featured' => true,
            ],
            [
                'slug' => 'helix-health-network',
                'category' => 'healthcare',
                'title' => 'Helix Health Network',
                'client_name' => 'Helix Health',
                'tagline' => 'Unified patient data platform with real-time clinical intelligence.',
                'summary' => 'Unified patient data platform serving 2M+ records with real-time analytics.',
                'description' => 'Helix Health partnered with Cyra-Tech to consolidate patient records, clinical workflows, and operational analytics into a HIPAA-aligned data platform with FHIR interoperability and executive reporting.',
                'services' => ['Data & Analytics', 'Enterprise Integration', 'Cybersecurity', 'Managed Services'],
                'outcomes' => ['99.95% uptime SLA across critical workloads', 'Unified visibility across 2M+ patient records', 'Reduced manual reporting for clinical operations teams'],
                'metrics' => [
                    ['label' => 'Platform uptime', 'value' => '99.95%'],
                    ['label' => 'Records unified', 'value' => '2M+'],
                    ['label' => 'Reporting cycle', 'value' => '60% faster'],
                ],
                'duration' => '18 months',
                'icon' => 'health',
                'is_featured' => true,
            ],
            [
                'slug' => 'astra-logistics-command',
                'category' => 'commercial',
                'title' => 'Astra Logistics Command',
                'client_name' => 'Astra Logistics',
                'tagline' => 'AI-driven supply chain visibility across a global distribution network.',
                'summary' => 'AI-driven supply chain visibility across 18 countries and 120 distribution hubs.',
                'description' => 'Astra Logistics deployed Cyra-Tech\'s intelligence platform to connect warehouse, fleet, and partner systems with predictive analytics, exception management, and executive command dashboards.',
                'services' => ['AI & Intelligence', 'Data & Analytics', 'Enterprise Integration', 'Cloud & Infrastructure'],
                'outcomes' => ['22% reduction in logistics operating costs', 'Real-time visibility across 120 distribution hubs', 'Predictive alerts reduced fulfillment delays by 35%'],
                'metrics' => [
                    ['label' => 'Cost reduction', 'value' => '22%'],
                    ['label' => 'Countries connected', 'value' => '18'],
                    ['label' => 'Distribution hubs', 'value' => '120'],
                ],
                'duration' => '12 months',
                'icon' => 'integration',
                'is_featured' => true,
            ],
            [
                'slug' => 'civic-one-portal',
                'category' => 'public-sector',
                'title' => 'CivicOne Digital Services',
                'client_name' => 'CivicOne Agency',
                'tagline' => 'Accessible citizen services portal with secure identity and workflow automation.',
                'summary' => 'Modernized citizen-facing services with accessibility-first design and secure cloud infrastructure.',
                'description' => 'Cyra-Tech helped CivicOne replace legacy citizen portals with an accessible digital services hub, integrated identity verification, and automated case routing aligned to public-sector security standards.',
                'services' => ['Digital Transformation', 'Cybersecurity', 'Cloud & Infrastructure', 'Managed Services'],
                'outcomes' => ['50% reduction in service request processing time', 'WCAG 2.1 AA accessibility compliance achieved', 'Unified case management across 12 service lines'],
                'metrics' => [
                    ['label' => 'Processing time', 'value' => '50% faster'],
                    ['label' => 'Service lines', 'value' => '12'],
                    ['label' => 'Citizen satisfaction', 'value' => '+28 NPS'],
                ],
                'duration' => '16 months',
                'icon' => 'government',
                'is_featured' => false,
            ],
            [
                'slug' => 'gridwise-energy-platform',
                'category' => 'industrial',
                'title' => 'GridWise Operations Platform',
                'client_name' => 'GridWise Utilities',
                'tagline' => 'IoT-enabled grid intelligence with sustainability reporting.',
                'summary' => 'IoT operations platform connecting field assets, grid analytics, and ESG reporting.',
                'description' => 'GridWise Utilities engaged Cyra-Tech to build an IoT-enabled operations platform integrating SCADA feeds, field asset telemetry, and sustainability dashboards for executive and regulatory reporting.',
                'services' => ['Cloud & Infrastructure', 'Data & Analytics', 'Enterprise Integration', 'Managed Services'],
                'outcomes' => ['Real-time asset performance visibility across 8 regions', 'Automated ESG reporting reduced manual effort by 70%', 'Improved outage response with predictive maintenance alerts'],
                'metrics' => [
                    ['label' => 'Regions connected', 'value' => '8'],
                    ['label' => 'ESG reporting effort', 'value' => '70% less'],
                    ['label' => 'Outage response', 'value' => '35% faster'],
                ],
                'duration' => '20 months',
                'icon' => 'energy',
                'is_featured' => false,
            ],
            [
                'slug' => 'velocity-retail-commerce',
                'category' => 'commercial',
                'title' => 'Velocity Retail Commerce',
                'client_name' => 'Velocity Retail Group',
                'tagline' => 'Headless commerce platform powering omnichannel growth.',
                'summary' => 'Headless commerce modernization with inventory intelligence and personalization at scale.',
                'description' => 'Velocity Retail Group partnered with Cyra-Tech to launch a composable commerce platform connecting storefront, marketplace, and fulfillment systems with real-time inventory and personalization engines.',
                'services' => ['Digital Transformation', 'Enterprise Integration', 'Data & Analytics', 'AI & Intelligence'],
                'outcomes' => ['31% increase in digital conversion rates', 'Unified inventory accuracy above 98% across channels', 'Faster campaign launches with modular content workflows'],
                'metrics' => [
                    ['label' => 'Conversion lift', 'value' => '31%'],
                    ['label' => 'Inventory accuracy', 'value' => '98%+'],
                    ['label' => 'Campaign launch time', 'value' => '45% faster'],
                ],
                'duration' => '11 months',
                'icon' => 'retail',
                'is_featured' => false,
            ],
        ],
        'impact' => [
            'eyebrow' => 'Delivery Excellence',
            'title' => 'Programs built for measurable enterprise impact',
            'description' => 'Every Cyra-Tech engagement is structured around executive alignment, transparent reporting, and outcome metrics tied to business value.',
            'points' => [
                'Executive steering with milestone-based governance',
                'Cross-functional delivery teams with domain expertise',
                'Outcome metrics defined upfront and tracked continuously',
                'Knowledge transfer and operational handoff built into every program',
            ],
        ],
        'cta' => [
            'title' => 'Ready to discuss your transformation program?',
            'description' => 'Share your goals with Cyra-Tech strategists and explore a case-study-aligned approach for your organization.',
            'action' => ['label' => 'Start a Conversation', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Innovation Lab Content
    |--------------------------------------------------------------------------
    */

    'innovation_lab' => [
        'seo' => [
            'title' => 'Innovation Lab | Cyra-Tech',
            'description' => 'Cyra-Tech Innovation Lab combines research, rapid prototyping, and production engineering to turn bold ideas into enterprise-ready products.',
            'keywords' => ['innovation lab', 'AI copilots', 'proof of concept', 'design sprints', 'emerging technology'],
        ],
        'hero' => [
            'eyebrow' => 'Innovation Lab',
            'title' => 'Prototype the future before your market demands it',
            'description' => 'Cyra-Tech Innovation Lab combines research, rapid prototyping, and production engineering to turn bold ideas into enterprise-ready products with governance built in.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Programs'],
            ['slug' => 'ai-automation', 'label' => 'AI & Automation'],
            ['slug' => 'emerging-tech', 'label' => 'Emerging Tech'],
            ['slug' => 'venture-design', 'label' => 'Venture & Design'],
        ],
        'initiatives' => [
            [
                'slug' => 'ai-copilot-studio',
                'category' => 'ai-automation',
                'title' => 'AI Copilot Studio',
                'tagline' => 'Design, prototype, and productionize enterprise AI copilots.',
                'summary' => 'Rapid copilot development with governance, evaluation, and human-in-the-loop controls.',
                'description' => 'The AI Copilot Studio helps enterprises move from copilot concepts to production pilots with model evaluation frameworks, prompt governance, and integration patterns for existing systems of record.',
                'focus_areas' => ['Executive copilots', 'Domain-specific assistants', 'Knowledge retrieval', 'Responsible AI guardrails'],
                'deliverables' => ['Copilot architecture blueprint', 'Working prototype', 'Evaluation scorecard', 'Production readiness roadmap'],
                'timeline' => '6–10 weeks',
                'badge' => 'Flagship',
                'icon' => 'ai',
                'is_featured' => true,
            ],
            [
                'slug' => 'intelligent-automation-lab',
                'category' => 'ai-automation',
                'title' => 'Intelligent Automation Lab',
                'tagline' => 'Automate complex workflows with AI-assisted orchestration.',
                'summary' => 'Intelligent automation proof-of-concepts for cross-system enterprise workflows.',
                'description' => 'Cyra-Tech\'s Intelligent Automation Lab prototypes decision-aware workflows that combine rules, AI classification, and human approvals with full audit trails for regulated environments.',
                'focus_areas' => ['Process mining', 'Decision automation', 'Exception handling', 'Workflow observability'],
                'deliverables' => ['Workflow prototype', 'Automation ROI model', 'Integration map', 'Operational runbook'],
                'timeline' => '8–12 weeks',
                'badge' => 'Automation',
                'icon' => 'flow',
                'is_featured' => true,
            ],
            [
                'slug' => 'emerging-tech-poc',
                'category' => 'emerging-tech',
                'title' => 'Emerging Tech PoC Lab',
                'tagline' => 'Evaluate next-generation technologies with structured proof-of-concepts.',
                'summary' => 'Structured proof-of-concepts for quantum readiness, edge AI, and advanced analytics.',
                'description' => 'The Emerging Tech PoC Lab provides a disciplined framework to assess novel technologies against enterprise use cases, security requirements, and total cost of ownership before full investment.',
                'focus_areas' => ['Technology scouting', 'Feasibility assessment', 'Security review', 'TCO modeling'],
                'deliverables' => ['PoC implementation', 'Technical assessment report', 'Risk & compliance review', 'Scale recommendation'],
                'timeline' => '4–8 weeks',
                'badge' => 'Research',
                'icon' => 'transform',
                'is_featured' => true,
            ],
            [
                'slug' => 'spatial-edge-lab',
                'category' => 'emerging-tech',
                'title' => 'Spatial & Edge Lab',
                'tagline' => 'Prototype spatial computing and edge intelligence experiences.',
                'summary' => 'Spatial computing and edge AI experiments for field operations and immersive training.',
                'description' => 'Cyra-Tech explores spatial interfaces, edge inference, and mixed-reality workflows for industries where field teams need real-time guidance, remote expert support, and contextual data overlays.',
                'focus_areas' => ['Edge inference', 'Mixed-reality workflows', 'Field operations UX', 'Device integration'],
                'deliverables' => ['Interactive prototype', 'Hardware compatibility matrix', 'Latency benchmarks', 'Deployment playbook'],
                'timeline' => '6–10 weeks',
                'badge' => 'Experimental',
                'icon' => 'pulse',
                'is_featured' => false,
            ],
            [
                'slug' => 'design-sprint-studio',
                'category' => 'venture-design',
                'title' => 'Design Sprint Studio',
                'tagline' => 'Compress months of discovery into focused design sprints.',
                'summary' => 'Facilitated design sprints to validate product concepts with users and executives.',
                'description' => 'Design Sprint Studio brings cross-functional teams together to define problems, prototype solutions, and test with stakeholders in accelerated cycles aligned to enterprise procurement and governance.',
                'focus_areas' => ['Problem framing', 'Rapid prototyping', 'User validation', 'Executive alignment'],
                'deliverables' => ['Sprint outcomes report', 'Validated prototypes', 'Roadmap recommendations', 'Stakeholder decision brief'],
                'timeline' => '2–4 weeks',
                'badge' => 'Sprint',
                'icon' => 'studio',
                'is_featured' => false,
            ],
            [
                'slug' => 'venture-partnership-hub',
                'category' => 'venture-design',
                'title' => 'Venture Partnership Hub',
                'tagline' => 'Co-innovate with startups and research partners at enterprise scale.',
                'summary' => 'Venture partnerships connecting enterprise challenges with startup innovation pipelines.',
                'description' => 'The Venture Partnership Hub helps Cyra-Tech clients identify, evaluate, and integrate startup solutions with enterprise architecture standards, security reviews, and joint go-to-market models.',
                'focus_areas' => ['Partner scouting', 'Due diligence', 'Pilot integration', 'Joint innovation models'],
                'deliverables' => ['Partner shortlist', 'Pilot integration plan', 'Commercial framework', 'Innovation portfolio map'],
                'timeline' => '8–16 weeks',
                'badge' => 'Partnership',
                'icon' => 'integration',
                'is_featured' => false,
            ],
        ],
        'methodology' => [
            'eyebrow' => 'Lab Methodology',
            'title' => 'From hypothesis to production-ready innovation',
            'description' => 'Every Innovation Lab engagement follows a disciplined path that balances speed with enterprise governance.',
            'points' => [
                'Discover — align on use cases, success metrics, and constraints',
                'Prototype — build working experiments with measurable evaluation criteria',
                'Validate — test with users, security, and compliance stakeholders',
                'Productionize — hand off to engineering with architecture and operational readiness',
            ],
        ],
        'cta' => [
            'title' => 'Ready to prototype your next breakthrough?',
            'description' => 'Partner with Cyra-Tech Innovation Lab to explore AI, emerging tech, and venture-led programs with enterprise-grade delivery.',
            'action' => ['label' => 'Book a Lab Session', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Community Content
    |--------------------------------------------------------------------------
    */

    'community' => [
        'seo' => [
            'title' => 'Community | Cyra-Tech',
            'description' => 'Join builders, leaders, and innovators in the Cyra-Tech global community — forums, roundtables, partner networks, and learning programs.',
            'keywords' => ['technology community', 'enterprise networking', 'Cyra-Tech events', 'partner community'],
        ],
        'hero' => [
            'eyebrow' => 'Community',
            'title' => 'Join builders, leaders, and innovators shaping what\'s next',
            'description' => 'Connect with Cyra-Tech practitioners, partners, and enterprise leaders through forums, roundtables, learning programs, and global events.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Programs'],
            ['slug' => 'forums', 'label' => 'Forums'],
            ['slug' => 'events', 'label' => 'Events'],
            ['slug' => 'learning', 'label' => 'Learning'],
            ['slug' => 'partners', 'label' => 'Partners'],
        ],
        'programs' => [
            [
                'slug' => 'cyra-connect-forum',
                'category' => 'forums',
                'title' => 'Cyra Connect Forum',
                'tagline' => 'Monthly virtual forum for enterprise technology leaders.',
                'summary' => 'Peer discussions on transformation, AI adoption, and platform strategy with Cyra-Tech experts.',
                'description' => 'Cyra Connect Forum brings CIOs, CTOs, and digital leaders together for candid conversations on enterprise technology trends, implementation lessons, and strategic priorities in a moderated virtual setting.',
                'benefits' => ['Executive peer networking', 'Curated discussion topics', 'Access to Cyra-Tech advisors', 'Session recordings and summaries'],
                'activities' => ['Monthly virtual sessions', 'Panel discussions', 'Q&A with practitioners', 'Community resource library'],
                'membership' => 'Open to clients and invited partners',
                'schedule' => 'Monthly — third Thursday',
                'badge' => 'Flagship',
                'icon' => 'integration',
                'is_featured' => true,
            ],
            [
                'slug' => 'executive-roundtable',
                'category' => 'forums',
                'title' => 'Executive Roundtable',
                'tagline' => 'Intimate roundtables for C-suite technology decision-makers.',
                'summary' => 'Small-group executive sessions focused on governance, investment, and innovation strategy.',
                'description' => 'Executive Roundtable offers confidential, invitation-only gatherings where senior leaders share perspectives on board-level technology priorities, risk management, and long-term digital investment models.',
                'benefits' => ['Confidential peer exchange', 'Board-ready insights', 'Cross-industry perspectives', 'Executive briefing materials'],
                'activities' => ['Quarterly roundtables', 'Facilitated discussions', 'Industry benchmarking', 'Executive whitepapers'],
                'membership' => 'By invitation — C-suite and board advisors',
                'schedule' => 'Quarterly regional sessions',
                'badge' => 'Executive',
                'icon' => 'command',
                'is_featured' => true,
            ],
            [
                'slug' => 'innovation-summit',
                'category' => 'events',
                'title' => 'Cyra Innovation Summit',
                'tagline' => 'Annual flagship event for enterprise innovation and technology leadership.',
                'summary' => 'Two-day summit featuring keynotes, labs, portfolio showcases, and partner showcases.',
                'description' => 'The Cyra Innovation Summit convenes global clients, partners, and industry leaders for keynotes, hands-on lab sessions, case study showcases, and networking designed to accelerate enterprise innovation programs.',
                'benefits' => ['Keynote sessions', 'Innovation Lab demos', 'Portfolio case studies', 'Partner ecosystem expo'],
                'activities' => ['Keynotes & panels', 'Hands-on lab sessions', 'Networking receptions', 'Awards & recognition'],
                'membership' => 'Open registration — clients, partners, and alumni',
                'schedule' => 'Annual — October',
                'badge' => 'Summit',
                'icon' => 'transform',
                'is_featured' => true,
            ],
            [
                'slug' => 'developer-guild',
                'category' => 'learning',
                'title' => 'Developer Guild',
                'tagline' => 'Technical community for engineers building on the Cyra platform.',
                'summary' => 'Workshops, office hours, and certification paths for platform engineers and architects.',
                'description' => 'Developer Guild supports engineers and architects with technical workshops, API deep-dives, architecture office hours, and certification pathways aligned to Cyra-Tech platform products and integration patterns.',
                'benefits' => ['Technical workshops', 'Architecture office hours', 'Certification pathways', 'Open-source contributions'],
                'activities' => ['Bi-weekly tech talks', 'Hands-on labs', 'Code review sessions', 'Certification exams'],
                'membership' => 'Open to client engineering teams',
                'schedule' => 'Bi-weekly virtual sessions',
                'badge' => 'Technical',
                'icon' => 'studio',
                'is_featured' => false,
            ],
            [
                'slug' => 'practitioner-guild',
                'category' => 'learning',
                'title' => 'Practitioner Guild',
                'tagline' => 'Study groups and certifications for delivery and operations practitioners.',
                'summary' => 'Structured learning cohorts for project managers, analysts, and operations leaders.',
                'description' => 'Practitioner Guild provides cohort-based learning for delivery managers, business analysts, and operations leaders with study groups, best-practice playbooks, and Cyra-Tech methodology certifications.',
                'benefits' => ['Cohort-based learning', 'Methodology playbooks', 'Peer study groups', 'Practitioner certifications'],
                'activities' => ['Monthly study cohorts', 'Playbook workshops', 'Peer mentoring', 'Certification assessments'],
                'membership' => 'Open to client practitioners',
                'schedule' => 'Monthly cohort cycles',
                'badge' => 'Learning',
                'icon' => 'pulse',
                'is_featured' => false,
            ],
            [
                'slug' => 'partner-network',
                'category' => 'partners',
                'title' => 'Partner Network',
                'tagline' => 'Ecosystem community for Cyra-Tech technology and services partners.',
                'summary' => 'Partner onboarding, co-selling resources, and joint go-to-market programs.',
                'description' => 'The Partner Network connects technology vendors, system integrators, and consulting partners with Cyra-Tech co-selling resources, joint solution playbooks, and partner enablement programs.',
                'benefits' => ['Partner enablement', 'Co-selling resources', 'Joint solution playbooks', 'Partner portal access'],
                'activities' => ['Partner onboarding', 'Quarterly business reviews', 'Joint marketing programs', 'Solution certification'],
                'membership' => 'Approved Cyra-Tech partners',
                'schedule' => 'Ongoing — quarterly reviews',
                'badge' => 'Partnership',
                'icon' => 'managed',
                'is_featured' => false,
            ],
        ],
        'values' => [
            'eyebrow' => 'Community Values',
            'title' => 'A community built on collaboration and excellence',
            'description' => 'Cyra-Tech Community is designed for meaningful connection, knowledge sharing, and responsible innovation across the enterprise technology ecosystem.',
            'points' => [
                'Open exchange of ideas with enterprise-grade professionalism',
                'Inclusive participation across roles, industries, and regions',
                'Practical knowledge grounded in real delivery experience',
                'Responsible innovation aligned to security and governance standards',
            ],
        ],
        'cta' => [
            'title' => 'Ready to join the Cyra-Tech community?',
            'description' => 'Register your interest and our community team will connect you with programs aligned to your role and goals.',
            'action' => ['label' => 'Join the Community', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Insights Content
    |--------------------------------------------------------------------------
    */

    'insights' => [
        'seo' => [
            'title' => 'Insights & Thought Leadership | Cyra-Tech',
            'description' => 'Executive perspectives on AI readiness, cloud FinOps, zero trust, data platforms, and enterprise transformation from Cyra-Tech advisors.',
            'keywords' => ['thought leadership', 'enterprise insights', 'AI readiness', 'cloud FinOps', 'zero trust'],
        ],
        'hero' => [
            'eyebrow' => 'Insights',
            'title' => 'Executive perspectives on technology and transformation',
            'description' => 'Research-backed articles, frameworks, and briefings from Cyra-Tech practitioners helping leaders navigate complex technology decisions.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Insights'],
            ['slug' => 'ai', 'label' => 'AI & Intelligence'],
            ['slug' => 'cloud', 'label' => 'Cloud & Platform'],
            ['slug' => 'security', 'label' => 'Security'],
            ['slug' => 'transformation', 'label' => 'Transformation'],
            ['slug' => 'data', 'label' => 'Data & Analytics'],
        ],
        'articles' => [
            [
                'slug' => 'executive-guide-ai-readiness',
                'category' => 'ai',
                'title' => 'The Executive Guide to AI Readiness',
                'tagline' => 'A practical framework for adopting AI without compromising governance.',
                'summary' => 'A practical framework for adopting AI without compromising governance or security.',
                'description' => 'Enterprise AI adoption requires more than model selection — it demands data readiness, governance frameworks, human oversight, and clear ROI metrics. This guide outlines a phased approach for executives to evaluate AI opportunities, establish responsible guardrails, and scale pilots into production programs.',
                'author' => 'Elena Vasquez, Chief Innovation Officer',
                'read_time' => '8 min read',
                'topics' => ['AI strategy', 'Governance frameworks', 'Pilot-to-production', 'ROI measurement'],
                'takeaways' => ['Start with high-value, low-risk use cases', 'Establish AI governance before scaling', 'Invest in data quality and observability', 'Measure business outcomes, not model accuracy alone'],
                'published_label' => 'March 2026',
                'badge' => 'Featured',
                'icon' => 'ai',
                'is_featured' => true,
            ],
            [
                'slug' => 'cloud-finops-enterprise-scale',
                'category' => 'cloud',
                'title' => 'Cloud FinOps for Enterprise Scale',
                'tagline' => 'Aligning cloud spend with business outcomes and innovation budgets.',
                'summary' => 'How CIOs are aligning cloud spend with business outcomes and innovation budgets.',
                'description' => 'Cloud cost overruns remain a top CIO concern as workloads scale. This article explores FinOps operating models, tagging discipline, unit economics, and executive dashboards that connect cloud investment to product velocity and revenue outcomes.',
                'author' => 'Dr. Amara Okonkwo, Chief Technology Officer',
                'read_time' => '6 min read',
                'topics' => ['FinOps', 'Cost optimization', 'Cloud governance', 'Executive reporting'],
                'takeaways' => ['Implement tagging and allocation from day one', 'Connect cloud spend to business units and products', 'Use showback/chargeback to drive accountability', 'Balance cost control with innovation investment'],
                'published_label' => 'February 2026',
                'badge' => 'Guide',
                'icon' => 'cloud',
                'is_featured' => true,
            ],
            [
                'slug' => 'zero-trust-regulated-industries',
                'category' => 'security',
                'title' => 'Zero Trust in Regulated Industries',
                'tagline' => 'Identity-first security for financial and public sector environments.',
                'summary' => 'Implementing identity-first security models across financial and public sector environments.',
                'description' => 'Zero trust is no longer optional for regulated enterprises facing evolving threat landscapes and audit requirements. Cyra-Tech advisors share patterns for identity-centric access, micro-segmentation, continuous validation, and compliance-ready monitoring.',
                'author' => 'James Whitfield, Chief Executive Officer',
                'read_time' => '10 min read',
                'topics' => ['Zero trust architecture', 'Identity management', 'Compliance', 'Threat monitoring'],
                'takeaways' => ['Treat identity as the primary security perimeter', 'Implement continuous verification, not one-time authentication', 'Align controls to regulatory frameworks early', 'Automate evidence collection for audit readiness'],
                'published_label' => 'January 2026',
                'badge' => 'Report',
                'icon' => 'shield',
                'is_featured' => true,
            ],
            [
                'slug' => 'digital-transformation-playbook',
                'category' => 'transformation',
                'title' => 'The Digital Transformation Playbook',
                'tagline' => 'Reducing risk while accelerating enterprise modernization programs.',
                'summary' => 'A structured playbook for executives leading multi-year transformation initiatives.',
                'description' => 'Successful transformation programs balance ambition with discipline. This playbook covers portfolio prioritization, agile delivery at scale, change management, and executive governance models that keep complex programs on track.',
                'author' => 'Collins Pever, Founder & CEO',
                'read_time' => '12 min read',
                'topics' => ['Program governance', 'Agile at scale', 'Change management', 'Portfolio prioritization'],
                'takeaways' => ['Define outcome metrics before selecting technology', 'Sequence quick wins alongside foundational investments', 'Invest in change leadership as much as engineering', 'Report transparently to build executive trust'],
                'published_label' => 'December 2025',
                'badge' => 'Playbook',
                'icon' => 'transform',
                'is_featured' => false,
            ],
            [
                'slug' => 'modern-data-platform-guide',
                'category' => 'data',
                'title' => 'Modern Data Platform Guide',
                'tagline' => 'Building trusted data foundations for analytics and AI.',
                'summary' => 'Architecture patterns for unified data platforms that power analytics and AI workloads.',
                'description' => 'Fragmented data estates limit analytics and AI initiatives. This guide outlines lakehouse patterns, data mesh considerations, master data management, and executive reporting layers that create a trusted foundation for enterprise intelligence.',
                'author' => 'Dr. Amara Okonkwo, Chief Technology Officer',
                'read_time' => '9 min read',
                'topics' => ['Data platform engineering', 'Lakehouse architecture', 'Master data', 'Executive analytics'],
                'takeaways' => ['Prioritize data quality and lineage from the start', 'Design for self-service with governance guardrails', 'Unify batch and streaming for real-time decisions', 'Connect data investments to executive KPIs'],
                'published_label' => 'November 2025',
                'badge' => 'Guide',
                'icon' => 'data',
                'is_featured' => false,
            ],
            [
                'slug' => 'responsible-ai-governance',
                'category' => 'ai',
                'title' => 'Responsible AI Governance',
                'tagline' => 'Operationalizing ethics, monitoring, and compliance for enterprise AI.',
                'summary' => 'Frameworks for responsible AI governance across model lifecycle and deployment.',
                'description' => 'As AI systems influence critical business decisions, governance must extend beyond policy documents. Learn how Cyra-Tech clients implement model monitoring, bias testing, human-in-the-loop controls, and audit trails for production AI systems.',
                'author' => 'Elena Vasquez, Chief Innovation Officer',
                'read_time' => '7 min read',
                'topics' => ['Model governance', 'Bias testing', 'Human oversight', 'Audit trails'],
                'takeaways' => ['Document model purpose and limitations clearly', 'Monitor drift and performance in production', 'Maintain human review for high-impact decisions', 'Build audit trails for regulatory inquiries'],
                'published_label' => 'October 2025',
                'badge' => 'Framework',
                'icon' => 'pulse',
                'is_featured' => false,
            ],
        ],
        'editorial' => [
            'eyebrow' => 'Cyra-Tech Research',
            'title' => 'Insights grounded in delivery experience',
            'description' => 'Our articles combine research, client program learnings, and practitioner expertise to help executives make confident technology decisions.',
            'points' => [
                'Written by Cyra-Tech executives and senior practitioners',
                'Grounded in real enterprise program outcomes',
                'Actionable frameworks, not abstract theory',
                'Updated regularly as technology and regulations evolve',
            ],
        ],
        'cta' => [
            'title' => 'Stay ahead with Cyra-Tech insights',
            'description' => 'Subscribe to receive executive briefings, research updates, and event invitations from the Cyra-Tech team.',
            'action' => ['label' => 'Subscribe to Insights', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Careers Content
    |--------------------------------------------------------------------------
    */

    'careers' => [
        'seo' => [
            'title' => 'Careers | Cyra-Tech',
            'description' => 'Join Cyra-Tech and build the future of enterprise technology. Explore open roles in engineering, consulting, design, and operations.',
            'keywords' => ['Cyra-Tech careers', 'enterprise technology jobs', 'cloud engineering roles', 'consulting careers'],
        ],
        'hero' => [
            'eyebrow' => 'Careers',
            'title' => 'Build the future of enterprise technology with us',
            'description' => 'We\'re hiring engineers, strategists, designers, and operators who thrive on complex challenges and want to deliver meaningful impact for global organizations.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Roles'],
            ['slug' => 'engineering', 'label' => 'Engineering'],
            ['slug' => 'consulting', 'label' => 'Consulting'],
            ['slug' => 'design', 'label' => 'Design'],
            ['slug' => 'operations', 'label' => 'Operations'],
        ],
        'openings' => [
            [
                'slug' => 'senior-cloud-architect',
                'category' => 'engineering',
                'title' => 'Senior Cloud Architect',
                'department' => 'Engineering',
                'location' => 'Remote — Global',
                'work_type' => 'Remote',
                'tagline' => 'Design secure, scalable cloud foundations for enterprise clients.',
                'summary' => 'Lead cloud architecture engagements across multi-cloud and hybrid environments.',
                'description' => 'As a Senior Cloud Architect, you will partner with enterprise clients to design landing zones, migration strategies, and platform architectures that meet security, compliance, and performance requirements at scale.',
                'responsibilities' => ['Design cloud landing zones and reference architectures', 'Lead technical discovery and architecture workshops', 'Guide migration and modernization programs', 'Mentor engineering teams on cloud best practices'],
                'requirements' => ['8+ years in cloud architecture or platform engineering', 'Deep experience with AWS, Azure, or GCP', 'Strong understanding of security and compliance frameworks', 'Excellent client communication and workshop facilitation'],
                'experience_level' => 'Senior',
                'badge' => 'New',
                'icon' => 'cloud',
                'is_featured' => true,
            ],
            [
                'slug' => 'lead-full-stack-engineer',
                'category' => 'engineering',
                'title' => 'Lead Full-Stack Engineer',
                'department' => 'Engineering',
                'location' => 'Lagos, Nigeria',
                'work_type' => 'Hybrid',
                'tagline' => 'Build enterprise web platforms with Laravel and modern JavaScript.',
                'summary' => 'Lead development of Cyra-Tech client platforms and internal product modules.',
                'description' => 'Join Cyra-Tech\'s product engineering team to deliver secure, scalable enterprise applications using Laravel, Blade, JavaScript, and Tailwind CSS with a focus on clean architecture and test-driven delivery.',
                'responsibilities' => ['Lead full-stack feature development for client platforms', 'Establish coding standards and review practices', 'Collaborate with designers on component implementation', 'Drive automated testing and CI/CD pipelines'],
                'requirements' => ['6+ years full-stack development experience', 'Expert-level Laravel and PHP skills', 'Strong JavaScript and modern CSS proficiency', 'Experience with enterprise security and RBAC patterns'],
                'experience_level' => 'Lead',
                'badge' => 'Featured',
                'icon' => 'studio',
                'is_featured' => true,
            ],
            [
                'slug' => 'ai-ml-engineer',
                'category' => 'engineering',
                'title' => 'AI / ML Engineer',
                'department' => 'Innovation Lab',
                'location' => 'Remote — Global',
                'work_type' => 'Remote',
                'tagline' => 'Productionize AI copilots and intelligent automation for enterprise clients.',
                'summary' => 'Build and deploy AI systems with governance, monitoring, and human oversight.',
                'description' => 'Work within Cyra-Tech Innovation Lab to design, prototype, and productionize AI copilots, intelligent workflows, and analytics models with responsible AI guardrails and enterprise integration patterns.',
                'responsibilities' => ['Develop AI copilot prototypes and production pilots', 'Implement model evaluation and monitoring frameworks', 'Integrate AI systems with enterprise data platforms', 'Document governance and operational runbooks'],
                'requirements' => ['4+ years in ML engineering or applied AI', 'Experience with LLM integration and RAG patterns', 'Strong Python and API development skills', 'Understanding of AI governance and responsible AI practices'],
                'experience_level' => 'Mid-Senior',
                'badge' => 'Innovation',
                'icon' => 'ai',
                'is_featured' => true,
            ],
            [
                'slug' => 'enterprise-solutions-consultant',
                'category' => 'consulting',
                'title' => 'Enterprise Solutions Consultant',
                'department' => 'Consulting',
                'location' => 'London, UK',
                'work_type' => 'Hybrid',
                'tagline' => 'Guide clients through digital transformation and solution design.',
                'summary' => 'Partner with enterprise clients on transformation strategy and solution architecture.',
                'description' => 'Enterprise Solutions Consultants at Cyra-Tech lead client discovery, design transformation roadmaps, and align technology investments with business outcomes across regulated and high-growth industries.',
                'responsibilities' => ['Lead client discovery and requirements workshops', 'Design solution architectures and program roadmaps', 'Facilitate executive steering and milestone reviews', 'Collaborate with delivery teams on program execution'],
                'requirements' => ['7+ years in enterprise consulting or solution architecture', 'Strong presentation and stakeholder management skills', 'Experience with digital transformation programs', 'Industry knowledge in financial services, healthcare, or public sector preferred'],
                'experience_level' => 'Senior',
                'badge' => 'Consulting',
                'icon' => 'transform',
                'is_featured' => false,
            ],
            [
                'slug' => 'ux-product-designer',
                'category' => 'design',
                'title' => 'UX / Product Designer',
                'department' => 'Design',
                'location' => 'Remote — Global',
                'work_type' => 'Remote',
                'tagline' => 'Craft enterprise experiences aligned to the Cyra-Tech design system.',
                'summary' => 'Design intuitive interfaces for complex enterprise workflows and dashboards.',
                'description' => 'Cyra-Tech UX/Product Designers create accessible, polished experiences for enterprise dashboards, client portals, and marketing platforms using our design system and collaborative delivery workflows.',
                'responsibilities' => ['Design enterprise UX flows and interface components', 'Maintain alignment with Cyra-Tech design system standards', 'Conduct user research and usability testing', 'Partner with engineers on component implementation'],
                'requirements' => ['5+ years in UX or product design', 'Portfolio demonstrating enterprise or B2B design', 'Proficiency with Figma and design systems', 'Understanding of accessibility standards (WCAG)'],
                'experience_level' => 'Mid-Senior',
                'badge' => 'Design',
                'icon' => 'pulse',
                'is_featured' => false,
            ],
            [
                'slug' => 'program-delivery-manager',
                'category' => 'operations',
                'title' => 'Program Delivery Manager',
                'department' => 'Operations',
                'location' => 'Washington, DC',
                'work_type' => 'Hybrid',
                'tagline' => 'Lead complex delivery programs with transparent client governance.',
                'summary' => 'Manage multi-workstream programs with milestone reporting and risk management.',
                'description' => 'Program Delivery Managers orchestrate Cyra-Tech client engagements, ensuring on-time delivery, transparent reporting, and strong collaboration between consulting, engineering, and client stakeholders.',
                'responsibilities' => ['Manage program plans, milestones, and dependencies', 'Lead weekly client status and steering sessions', 'Track risks, issues, and change requests', 'Ensure quality gates and knowledge transfer'],
                'requirements' => ['6+ years in program or project management', 'PMP, PRINCE2, or equivalent certification preferred', 'Experience with agile and hybrid delivery models', 'Strong client communication in regulated environments'],
                'experience_level' => 'Senior',
                'badge' => 'Operations',
                'icon' => 'command',
                'is_featured' => false,
            ],
        ],
        'culture' => [
            'eyebrow' => 'Life at Cyra-Tech',
            'title' => 'A culture of excellence, impact, and growth',
            'description' => 'Cyra-Tech offers a collaborative environment where talented people solve complex problems for world-class clients while investing in continuous learning and career development.',
            'points' => [
                'Work on meaningful enterprise programs with global impact',
                'Continuous learning through Innovation Lab and certifications',
                'Flexible remote and hybrid work across global offices',
                'Inclusive teams that value diverse perspectives and collaboration',
            ],
        ],
        'cta' => [
            'title' => 'Don\'t see the right role?',
            'description' => 'Send us your profile and tell us how you\'d like to contribute. We\'re always looking for exceptional talent.',
            'action' => ['label' => 'Contact Talent Team', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact Content
    |--------------------------------------------------------------------------
    */

    'contact' => [
        'seo' => [
            'title' => 'Contact Us | Cyra-Tech',
            'description' => 'Contact Cyra-Tech for sales inquiries, support, partnerships, careers, and general questions. Our team responds within one business day.',
            'keywords' => ['contact Cyra-Tech', 'enterprise sales', 'support', 'partnerships'],
        ],
        'hero' => [
            'eyebrow' => 'Contact',
            'title' => 'Ready to transform your enterprise?',
            'description' => 'Partner with Cyra-Tech to design, build, and scale intelligent systems that deliver lasting impact. Tell us about your goals and our team will respond promptly.',
        ],
        'inquiry_types' => [
            ['slug' => 'sales', 'label' => 'Sales & Solutions'],
            ['slug' => 'support', 'label' => 'Client Support'],
            ['slug' => 'partnership', 'label' => 'Partnerships'],
            ['slug' => 'careers', 'label' => 'Careers & Talent'],
            ['slug' => 'media', 'label' => 'Media & Press'],
            ['slug' => 'general', 'label' => 'General Inquiry'],
        ],
        'offices' => [
            [
                'city' => 'Lagos, Nigeria',
                'address' => 'Victoria Island Business District',
                'hours' => 'Mon–Fri, 8:00 AM – 6:00 PM WAT',
            ],
            [
                'city' => 'London, United Kingdom',
                'address' => 'Canary Wharf Enterprise Quarter',
                'hours' => 'Mon–Fri, 9:00 AM – 5:30 PM GMT',
            ],
            [
                'city' => 'Washington, DC',
                'address' => 'Capitol Riverfront Innovation Center',
                'hours' => 'Mon–Fri, 9:00 AM – 5:00 PM EST',
            ],
        ],
        'channels' => [
            ['label' => 'Sales', 'email' => 'sales@cyratech.com'],
            ['label' => 'Support', 'email' => 'support@cyratech.com'],
            ['label' => 'Careers', 'email' => 'careers@cyratech.com'],
            ['label' => 'Partnerships', 'email' => 'partners@cyratech.com'],
        ],
        'form' => [
            'eyebrow' => 'Get in Touch',
            'title' => 'Send us a message',
            'description' => 'Complete the form and a Cyra-Tech advisor will follow up within one business day.',
            'submit_label' => 'Send Message',
            'success_message' => 'Thank you for contacting Cyra-Tech. Our team will respond within one business day.',
        ],
        'support' => [
            'title' => 'Existing clients',
            'description' => 'For active engagements, reach your delivery team through the Client Portal or email support@cyratech.com for priority assistance.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Partner Hub Content
    |--------------------------------------------------------------------------
    */

    'partner_hub' => [
        'seo' => [
            'title' => 'Partner Hub | Cyra-Tech',
            'description' => 'Explore Cyra-Tech partner programs for cloud alliances, technology integrations, consulting co-sell, channel enablement, and global delivery partnerships.',
            'keywords' => ['Cyra-Tech partners', 'cloud alliance', 'system integrator', 'technology partner program', 'channel partners'],
        ],
        'hero' => [
            'eyebrow' => 'Partner Hub',
            'title' => 'Grow with Cyra-Tech through strategic partnerships',
            'description' => 'Join our ecosystem of cloud providers, technology vendors, system integrators, and consulting firms co-delivering enterprise transformation at global scale.',
        ],
        'categories' => [
            ['slug' => 'all', 'label' => 'All Programs'],
            ['slug' => 'cloud', 'label' => 'Cloud'],
            ['slug' => 'technology', 'label' => 'Technology'],
            ['slug' => 'services', 'label' => 'Services'],
            ['slug' => 'channel', 'label' => 'Channel'],
            ['slug' => 'global', 'label' => 'Global'],
        ],
        'programs' => [
            [
                'slug' => 'cloud-alliance-program',
                'category' => 'cloud',
                'title' => 'Cloud Alliance Program',
                'partner_type' => 'Cloud Provider Alliance',
                'region' => 'Global',
                'engagement_model' => 'Co-sell & Co-deliver',
                'tagline' => 'Co-deliver secure landing zones and migrations on AWS, Azure, and GCP.',
                'summary' => 'Joint cloud architecture, migration, and managed services with hyperscaler alignment.',
                'description' => 'The Cloud Alliance Program enables certified partners to co-design landing zones, lead migration programs, and deliver managed cloud operations with Cyra-Tech architecture standards, security controls, and client governance models.',
                'benefits' => ['Joint go-to-market motions with Cyra-Tech sales', 'Architecture review board access', 'Migration factory playbooks and tooling', 'Co-branded client workshops and assessments'],
                'requirements' => ['Cloud partner tier credentials (AWS, Azure, or GCP)', 'Proven enterprise migration or platform delivery experience', 'Dedicated cloud architecture and engineering bench', 'Commitment to Cyra-Tech security and compliance standards'],
                'enablement' => ['Cloud alliance onboarding', 'Reference architecture library', 'Migration assessment templates', 'Quarterly partner business reviews'],
                'badge' => 'Alliance',
                'icon' => 'cloud',
                'is_featured' => true,
            ],
            [
                'slug' => 'technology-isv-partners',
                'category' => 'technology',
                'title' => 'Technology ISV Partners',
                'partner_type' => 'Independent Software Vendor',
                'region' => 'Global',
                'engagement_model' => 'Integrate & Co-innovate',
                'tagline' => 'Integrate complementary platforms with Cyra Connect and enterprise delivery playbooks.',
                'summary' => 'Embed your product into Cyra-Tech solution stacks with governed integration patterns.',
                'description' => 'Technology ISV Partners work with Cyra-Tech to validate integrations, build joint solution accelerators, and bring complementary capabilities into client programs through Cyra Connect, API standards, and enterprise security reviews.',
                'benefits' => ['Solution integration validation with Cyra-Tech architects', 'Joint solution briefs and marketplace listings', 'Access to Cyra Connect integration framework', 'Co-marketing at Innovation Summit and client events'],
                'requirements' => ['Enterprise-ready product with documented APIs', 'Security and compliance documentation (SOC 2, ISO, or equivalent)', 'Dedicated partner engineering liaison', 'Willingness to support joint client pilots'],
                'enablement' => ['Integration design workshops', 'API certification pathway', 'Joint demo environment setup', 'Partner solution catalog submission'],
                'badge' => 'ISV',
                'icon' => 'integration',
                'is_featured' => true,
            ],
            [
                'slug' => 'system-integrator-network',
                'category' => 'services',
                'title' => 'System Integrator Network',
                'partner_type' => 'System Integrator',
                'region' => 'Americas & EMEA',
                'engagement_model' => 'Co-delivery',
                'tagline' => 'Scale complex transformation programs with certified SI co-delivery models.',
                'summary' => 'Extend delivery capacity for multi-workstream enterprise programs with shared governance.',
                'description' => 'System Integrator partners join Cyra-Tech delivery teams on large-scale transformation programs, contributing specialized squads, regional presence, and domain expertise while operating under unified program governance and quality standards.',
                'benefits' => ['Participation in Cyra-Tech enterprise pursuits', 'Shared delivery methodology and tooling', 'Program governance and quality gate alignment', 'Bench augmentation for peak program demand'],
                'requirements' => ['Established SI practice with enterprise references', 'Delivery teams aligned to Cyra-Tech tech stack capabilities', 'Program management and quality assurance processes', 'Regional coverage in target Cyra-Tech markets'],
                'enablement' => ['SI onboarding and certification', 'Delivery playbook training', 'Shared program management templates', 'Partner delivery community forums'],
                'badge' => 'SI Network',
                'icon' => 'command',
                'is_featured' => true,
            ],
            [
                'slug' => 'consulting-co-sell-program',
                'category' => 'services',
                'title' => 'Consulting Co-Sell Program',
                'partner_type' => 'Consulting Firm',
                'region' => 'Global',
                'engagement_model' => 'Co-sell & Advisory',
                'tagline' => 'Joint pursuit and advisory engagements aligned to executive transformation roadmaps.',
                'summary' => 'Partner with Cyra-Tech on strategy, discovery, and executive advisory programs.',
                'description' => 'Consulting Co-Sell partners collaborate on executive discovery, transformation roadmaps, and advisory engagements that connect strategic priorities to Cyra-Tech solution and delivery capabilities across regulated industries.',
                'benefits' => ['Joint executive briefing and discovery sessions', 'Shared pursuit support and proposal development', 'Advisory content co-development', 'Referral and revenue share frameworks'],
                'requirements' => ['Management or technology consulting practice', 'Executive stakeholder engagement experience', 'Industry expertise in Cyra-Tech focus verticals', 'Aligned commercial and ethics standards'],
                'enablement' => ['Co-sell playbooks and pursuit kits', 'Executive workshop facilitation guides', 'Industry solution mapping resources', 'Quarterly partner pipeline reviews'],
                'badge' => 'Co-Sell',
                'icon' => 'transform',
                'is_featured' => false,
            ],
            [
                'slug' => 'channel-reseller-program',
                'category' => 'channel',
                'title' => 'Channel & Reseller Program',
                'partner_type' => 'Channel Partner',
                'region' => 'North America & EMEA',
                'engagement_model' => 'Resell & Enable',
                'tagline' => 'Bring Cyra-Tech solutions to mid-market clients through structured enablement and deal registration.',
                'summary' => 'Enablement, deal registration, and support for partners serving growth-market clients.',
                'description' => 'Channel and reseller partners introduce Cyra-Tech products and packaged services to mid-market organizations with deal registration protection, sales enablement, and delivery support from Cyra-Tech partner success teams.',
                'benefits' => ['Deal registration and margin protection', 'Sales and technical enablement sessions', 'Co-branded marketing assets and campaigns', 'Partner success manager support'],
                'requirements' => ['Active client base in target mid-market segments', 'Sales team trained on enterprise technology solutions', 'Ability to lead discovery and qualify opportunities', 'Agreement to partner code of conduct and pricing policies'],
                'enablement' => ['Product and solution certification', 'Sales playbook and objection handling guides', 'Demo and proof-of-value kits', 'Partner portal access (coming soon)'],
                'badge' => 'Channel',
                'icon' => 'pulse',
                'is_featured' => false,
            ],
            [
                'slug' => 'global-delivery-partners',
                'category' => 'global',
                'title' => 'Global Delivery Partners',
                'partner_type' => 'Delivery Partner',
                'region' => 'APAC, Africa & LATAM',
                'engagement_model' => 'Follow-the-sun Delivery',
                'tagline' => 'Extend Cyra-Tech capacity across regions with governed delivery and quality standards.',
                'summary' => 'Regional delivery partners supporting follow-the-sun engineering and operations.',
                'description' => 'Global Delivery Partners provide regional engineering squads, language coverage, and local compliance expertise to Cyra-Tech programs, operating under shared delivery standards, security policies, and client reporting frameworks.',
                'benefits' => ['Access to Cyra-Tech global client programs', 'Follow-the-sun delivery model participation', 'Shared engineering standards and tooling', 'Regional market expansion support'],
                'requirements' => ['Proven nearshore or offshore delivery capability', 'Engineering teams skilled in Cyra-Tech core technologies', 'ISO or equivalent quality management practices', 'Data protection and security compliance alignment'],
                'enablement' => ['Delivery standards onboarding', 'Engineering toolchain alignment', 'Quality gate and reporting training', 'Regional partner leadership council'],
                'badge' => 'Global',
                'icon' => 'studio',
                'is_featured' => false,
            ],
        ],
        'ecosystem' => [
            'eyebrow' => 'Partner Ecosystem',
            'title' => 'Built for mutual growth and client impact',
            'description' => 'Cyra-Tech partner programs are designed to help organizations expand reach, accelerate delivery, and deliver consistent enterprise outcomes alongside our team.',
            'points' => [
                'Structured onboarding with architecture and delivery alignment',
                'Joint go-to-market support across sales and marketing motions',
                'Shared playbooks, templates, and quality governance frameworks',
                'Access to Cyra-Tech community events, labs, and executive forums',
            ],
        ],
        'cta' => [
            'title' => 'Ready to partner with Cyra-Tech?',
            'description' => 'Tell us about your organization and partnership goals. Our partner team will respond within one business day.',
            'action' => ['label' => 'Become a Partner', 'route' => 'contact'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Client Portal Content
    |--------------------------------------------------------------------------
    */

    'client_portal' => [
        'seo' => [
            'title' => 'Client Portal | Cyra-Tech',
            'description' => 'Secure Cyra-Tech client portal for engagement tracking, milestone visibility, deliverables, and priority support.',
            'keywords' => ['Cyra-Tech client portal', 'engagement tracking', 'enterprise client dashboard', 'delivery status'],
        ],
        'hero' => [
            'eyebrow' => 'Client Portal',
            'title' => 'Your engagement command center',
            'description' => 'Track program progress, review milestones, access deliverables, and connect with your Cyra-Tech delivery team in one secure workspace.',
        ],
        'features' => [
            'eyebrow' => 'Portal Capabilities',
            'title' => 'Everything you need to stay aligned with delivery',
            'description' => 'The Cyra-Tech Client Portal gives stakeholders transparent visibility into active programs without compromising security or governance.',
            'points' => [
                'Real-time engagement status, phase, and progress indicators',
                'Milestone tracking with upcoming and completed checkpoints',
                'Deliverable library and delivery team contact directory',
                'Priority support routing to your assigned account team',
            ],
        ],
        'security' => [
            'title' => 'Enterprise-grade security',
            'description' => 'Portal access is role-based, encrypted in transit, and scoped to your organization\'s assigned engagements only.',
            'points' => [
                'Session-based authentication with RBAC enforcement',
                'Engagement data isolated per client account',
                'Audit-ready access controls and activity logging (coming soon)',
            ],
        ],
        'dashboard_support' => [
            'title' => 'Need assistance?',
            'description' => 'Contact your account manager or email support@cyratech.com for priority response on active engagements.',
        ],
        'cta' => [
            'title' => 'Sign in to your workspace',
            'description' => 'Use your Cyra-Tech client credentials to access assigned engagements and delivery updates.',
            'action' => ['label' => 'Sign In', 'route' => 'login'],
        ],
        'accounts' => [
            [
                'slug' => 'novabank',
                'name' => 'NovaBank',
                'industry' => 'Financial Services',
                'region' => 'North America',
                'account_manager' => 'James Whitfield',
                'support_email' => 'support@cyratech.com',
                'engagements' => [
                    [
                        'slug' => 'digital-core-modernization',
                        'portfolio_slug' => 'novabank-digital-core',
                        'title' => 'Digital Core Modernization',
                        'status' => 'active',
                        'phase' => 'Delivery',
                        'progress' => 68,
                        'tagline' => 'Cloud-native banking core migration and API modernization.',
                        'summary' => 'Multi-phase program modernizing NovaBank\'s legacy core with secure APIs and real-time payment rails.',
                        'description' => 'Cyra-Tech is leading NovaBank through a regulated digital core transformation, including landing zone design, core service decomposition, API gateway rollout, and progressive cutover of high-volume payment workloads.',
                        'milestones' => ['Landing zone approved', 'API gateway production pilot', 'Payment rail migration wave 1', 'Operational hypercare'],
                        'deliverables' => ['Architecture decision records', 'Migration runbooks', 'Security assessment reports', 'Executive steering decks'],
                        'team' => ['James Whitfield — Delivery Director', 'Senior Cloud Architect — Lead Engineer', 'Enterprise Solutions Consultant — Client Partner'],
                    ],
                    [
                        'slug' => 'fraud-analytics-copilot',
                        'portfolio_slug' => null,
                        'title' => 'Fraud Analytics Copilot',
                        'status' => 'active',
                        'phase' => 'Design',
                        'progress' => 34,
                        'tagline' => 'AI-assisted fraud detection with human oversight and compliance guardrails.',
                        'summary' => 'Innovation Lab pilot embedding intelligent fraud workflows into NovaBank operations.',
                        'description' => 'This engagement prototypes and validates an AI copilot for fraud analysts, integrating model monitoring, explainability, and escalation workflows aligned to NovaBank risk policies.',
                        'milestones' => ['Use case discovery complete', 'Prototype evaluation criteria', 'Pilot integration design', 'Production readiness review'],
                        'deliverables' => ['Use case backlog', 'Model evaluation framework', 'Pilot architecture brief', 'Governance checklist'],
                        'team' => ['Elena Vasquez — Innovation Lead', 'AI / ML Engineer — Technical Lead', 'Program Delivery Manager — Governance'],
                    ],
                    [
                        'slug' => 'regulatory-reporting-automation',
                        'portfolio_slug' => null,
                        'title' => 'Regulatory Reporting Automation',
                        'status' => 'on-track',
                        'phase' => 'Hypercare',
                        'progress' => 92,
                        'tagline' => 'Automated compliance reporting with auditable data pipelines.',
                        'summary' => 'Final hypercare phase for automated regulatory reporting and audit trail modernization.',
                        'description' => 'NovaBank\'s regulatory reporting automation program is completing hypercare with Cyra-Tech support, ensuring reporting pipelines, audit evidence, and operational runbooks are production-stable.',
                        'milestones' => ['Reporting pipelines live', 'Audit evidence validated', 'Operations handover', 'Program closeout'],
                        'deliverables' => ['Runbook library', 'Audit trail documentation', 'Operations training materials', 'Closeout report'],
                        'team' => ['Program Delivery Manager — Lead', 'Lead Full-Stack Engineer — Platform', 'Enterprise Solutions Consultant — Advisor'],
                    ],
                ],
            ],
            [
                'slug' => 'helix-health',
                'name' => 'Helix Health Network',
                'industry' => 'Healthcare',
                'region' => 'North America',
                'account_manager' => 'Priya Sharma',
                'support_email' => 'support@cyratech.com',
                'engagements' => [
                    [
                        'slug' => 'connected-care-platform',
                        'portfolio_slug' => 'helix-health-network',
                        'title' => 'Connected Care Platform',
                        'status' => 'active',
                        'phase' => 'Delivery',
                        'progress' => 54,
                        'tagline' => 'FHIR-enabled care coordination and patient engagement platform.',
                        'summary' => 'Enterprise platform connecting clinicians, patients, and operational systems across Helix Health.',
                        'description' => 'Cyra-Tech is delivering Helix Health\'s connected care platform with FHIR integrations, patient portal experiences, and HIPAA-aligned cloud infrastructure for multi-site deployment.',
                        'milestones' => ['FHIR integration hub live', 'Patient portal beta', 'Clinical workflow automation wave 1', 'Enterprise rollout planning'],
                        'deliverables' => ['Integration specifications', 'Portal UX prototypes', 'Security compliance pack', 'Rollout roadmap'],
                        'team' => ['Priya Sharma — Executive Sponsor', 'UX / Product Designer — Experience Lead', 'Senior Cloud Architect — Platform Lead'],
                    ],
                    [
                        'slug' => 'clinical-data-governance',
                        'portfolio_slug' => null,
                        'title' => 'Clinical Data Governance',
                        'status' => 'active',
                        'phase' => 'Discovery',
                        'progress' => 22,
                        'tagline' => 'Enterprise data governance for clinical and operational datasets.',
                        'summary' => 'Foundational data governance program for Helix Health analytics and AI readiness.',
                        'description' => 'This engagement establishes data ownership models, quality standards, and governance councils to prepare Helix Health for advanced analytics and responsible AI adoption.',
                        'milestones' => ['Data domain inventory', 'Stewardship model design', 'Quality rules pilot', 'Council operating model'],
                        'deliverables' => ['Data domain catalog', 'Governance charter', 'Quality framework', 'Roadmap brief'],
                        'team' => ['Enterprise Solutions Consultant — Lead', 'Program Delivery Manager — Governance', 'AI / ML Engineer — Advisor'],
                    ],
                    [
                        'slug' => 'telehealth-scale-program',
                        'portfolio_slug' => null,
                        'title' => 'Telehealth Scale Program',
                        'status' => 'planning',
                        'phase' => 'Discovery',
                        'progress' => 12,
                        'tagline' => 'Scaling virtual care capacity across Helix Health regions.',
                        'summary' => 'Early-stage program planning for telehealth platform scale and regional expansion.',
                        'description' => 'Cyra-Tech is partnering with Helix Health leadership to define telehealth scaling priorities, platform requirements, and phased rollout plans across regional care networks.',
                        'milestones' => ['Stakeholder alignment workshops', 'Capacity model draft', 'Platform requirements baseline', 'Program charter approval'],
                        'deliverables' => ['Workshop outcomes', 'Capacity model', 'Requirements baseline', 'Program charter'],
                        'team' => ['James Whitfield — Delivery Director', 'Enterprise Solutions Consultant — Strategy Lead', 'Program Delivery Manager — Planning'],
                    ],
                ],
            ],
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
                ['label' => 'Client Portal', 'route' => 'client-portal', 'style' => 'link', 'sort' => 1],
                ['label' => 'Contact Us', 'route' => 'contact', 'style' => 'button', 'sort' => 2],
            ],
            'footer_columns' => [
                [
                    'title' => 'Company',
                    'links' => [
                        ['label' => 'About Us', 'route' => 'about'],
                        ['label' => 'Leadership', 'route' => 'leadership'],
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
                        ['label' => 'Partner Hub', 'route' => 'partner-hub'],
                    ],
                ],
                [
                    'title' => 'Connect',
                    'links' => [
                        ['label' => 'Client Portal', 'route' => 'client-portal'],
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
                        ['label' => 'Client Portal', 'route' => 'client-portal', 'permission' => 'modules.view'],
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
                        ['label' => 'Services', 'route' => 'solutions', 'permission' => 'modules.view'],
                        ['label' => 'Industries', 'route' => 'industries', 'permission' => 'modules.view'],
                        ['label' => 'Products', 'route' => 'products', 'permission' => 'modules.view'],
                        ['label' => 'Innovation Lab', 'route' => 'innovation-lab', 'permission' => 'modules.view'],
                        ['label' => 'Portfolio', 'route' => 'portfolio', 'permission' => 'modules.view'],
                        ['label' => 'Case Studies', 'route' => 'portfolio', 'permission' => 'modules.view'],
                    ],
                ],
                [
                    'label' => 'Growth',
                    'items' => [
                        ['label' => 'Contact', 'route' => 'contact', 'permission' => 'modules.view'],
                        ['label' => 'Leads & CRM', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Partners', 'route' => 'partner-hub', 'permission' => 'modules.view'],
                        ['label' => 'Marketing', 'permission' => 'dashboard.access', 'available' => false],
                        ['label' => 'Insights', 'route' => 'insights', 'permission' => 'modules.view'],
                        ['label' => 'Analytics', 'permission' => 'dashboard.access', 'available' => false],
                    ],
                ],
                [
                    'label' => 'People',
                    'items' => [
                        ['label' => 'Leadership', 'route' => 'leadership', 'permission' => 'modules.view'],
                        ['label' => 'Team Members', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Careers', 'route' => 'careers', 'permission' => 'modules.view'],
                        ['label' => 'Applicants', 'permission' => 'modules.view', 'available' => false],
                        ['label' => 'Community', 'route' => 'community', 'permission' => 'modules.view'],
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
