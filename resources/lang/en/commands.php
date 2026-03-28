<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Console Command Output
    |--------------------------------------------------------------------------
    */

    // Install
    'installing' => 'Installing Layup...',
    'filament_missing' => 'Filament is not installed. Install it first:',
    'config_published' => 'Config published',
    'migrations_completed' => 'Migrations completed',
    'storage_link_exists' => 'Storage symlink already exists',
    'storage_link_created' => 'Storage symlink created',
    'assets_published' => 'Filament assets published',
    'layout_exists' => 'Layout component [:layout] already exists',
    'layout_missing_scripts' => 'Your layout does not include @layupScripts. Add it before </body> for interactive widgets (accordion, tabs, countdown, etc.) to work.',
    'layout_missing_alpine' => 'Your layout may not load Alpine.js. Ensure your JS bundle imports Alpine (e.g. @vite([\'resources/js/app.js\'])) or interactive widgets will not respond to clicks.',
    'layout_created' => 'Created layout component [:layout]',
    'safelist_generated' => 'Tailwind safelist generated',
    'plugin_not_registered' => 'LayupPlugin is not registered in any Filament panel. Add LayupPlugin::make() to your panel provider\'s ->plugins([]) array.',
    'installed' => 'Layup installed successfully!',
    'next_steps' => 'Next steps:',

    // MakeWidget
    'widget_exists' => 'Widget class already exists: :path',
    'widget_created' => 'Created widget class: :path',
    'blade_created' => 'Created blade view: :path',

    // Audit
    'audit_report' => 'Layup Audit Report',
    'pages_count' => 'Pages: :total total (:published published, :drafts drafts)',
    'registered_widgets' => 'Registered widgets: :count',
    'total_widget_instances' => 'Total widget instances: :count',
    'widget_usage' => 'Widget usage:',
    'content_issues' => 'Content issues found:',
    'all_pages_valid' => 'All pages pass content validation',
    'safelist_count' => 'Safelist: :total classes (:static static + :dynamic dynamic)',
    'revisions_count' => 'Revisions: :count total',

    // Export
    'exported' => 'Exported :count pages to :path',

    // Import
    'file_not_found' => 'File not found: :path',
    'invalid_export' => 'Invalid export file -- expected { "pages": [...] }',
    'skipping_no_slug' => 'Skipping page without slug',
    'invalid_content' => "Invalid content for ':slug': :errors",
    'skipping_exists' => "Skipping ':slug' (already exists, use --overwrite)",
    'updated_page' => 'Updated: :slug',
    'created_page' => 'Created: :slug',
    'validated' => 'Validated',
    'imported' => 'Imported',
    'import_summary' => ':action: :imported | Skipped: :skipped | Errors: :errors',

    // Safelist
    'safelist_wrote' => 'Wrote :total classes to :path (:static static + :dynamic from content)',
    'safelist_tailwind_v4' => 'Add to your app.css (Tailwind v4):',
    'safelist_tailwind_v3' => 'Or add to tailwind.config.js (Tailwind v3):',
    'safelist_tip' => 'Tip: Run this command as part of your build pipeline:',
];
