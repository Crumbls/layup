<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Konsolenbefehl-Ausgaben
    |--------------------------------------------------------------------------
    */

    // Installation
    'installing' => 'Layup wird installiert...',
    'config_published' => 'Konfiguration veröffentlicht',
    'migrations_completed' => 'Migrationen abgeschlossen',
    'assets_published' => 'Filament-Assets veröffentlicht',
    'layout_exists' => 'Layout-Komponente [:layout] existiert bereits',
    'layout_created' => 'Layout-Komponente [:layout] erstellt',
    'safelist_generated' => 'Tailwind-Safelist generiert',
    'installed' => 'Layup wurde erfolgreich installiert!',
    'next_steps' => 'Nächste Schritte:',

    // MakeWidget
    'widget_exists' => 'Widget-Klasse existiert bereits: :path',
    'widget_created' => 'Widget-Klasse erstellt: :path',
    'blade_created' => 'Blade-View erstellt: :path',

    // Audit
    'audit_report' => 'Layup-Auditbericht',
    'pages_count' => 'Seiten: :total gesamt (:published veröffentlicht, :drafts Entwürfe)',
    'registered_widgets' => 'Registrierte Widgets: :count',
    'total_widget_instances' => 'Widget-Instanzen gesamt: :count',
    'widget_usage' => 'Widget-Nutzung:',
    'content_issues' => 'Inhaltsprobleme gefunden:',
    'all_pages_valid' => 'Alle Seiten bestehen die Inhaltsvalidierung',
    'safelist_count' => 'Safelist: :total Klassen (:static statisch + :dynamic dynamisch)',
    'revisions_count' => 'Versionen: :count gesamt',

    // Export
    'exported' => ':count Seiten nach :path exportiert',

    // Import
    'file_not_found' => 'Datei nicht gefunden: :path',
    'invalid_export' => 'Ungültige Exportdatei -- erwartet { "pages": [...] }',
    'skipping_no_slug' => 'Überspringe Seite ohne Slug',
    'invalid_content' => "Ungültiger Inhalt für ':slug': :errors",
    'skipping_exists' => "Überspringe ':slug' (existiert bereits, verwenden Sie --overwrite)",
    'updated_page' => 'Aktualisiert: :slug',
    'created_page' => 'Erstellt: :slug',
    'validated' => 'Validiert',
    'imported' => 'Importiert',
    'import_summary' => ':action: :imported | Übersprungen: :skipped | Fehler: :errors',

    // Safelist
    'safelist_wrote' => ':total Klassen nach :path geschrieben (:static statisch + :dynamic aus Inhalt)',
    'safelist_tailwind_v4' => 'Fügen Sie dies zu Ihrer app.css hinzu (Tailwind v4):',
    'safelist_tailwind_v3' => 'Oder fügen Sie dies zu tailwind.config.js hinzu (Tailwind v3):',
    'safelist_tip' => 'Tipp: Führen Sie diesen Befehl als Teil Ihrer Build-Pipeline aus:',
];
