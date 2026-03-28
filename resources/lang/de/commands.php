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
    'filament_missing' => 'Filament ist nicht installiert. Installieren Sie es zuerst:',
    'config_published' => 'Konfiguration veröffentlicht',
    'migrations_completed' => 'Migrationen abgeschlossen',
    'storage_link_exists' => 'Storage-Symlink existiert bereits',
    'storage_link_created' => 'Storage-Symlink erstellt',
    'assets_published' => 'Filament-Assets veröffentlicht',
    'layout_exists' => 'Layout-Komponente [:layout] existiert bereits',
    'layout_missing_scripts' => 'Ihr Layout enthaelt kein @layupScripts. Fuegen Sie es vor </body> ein, damit interaktive Widgets (Akkordeon, Tabs, Countdown usw.) funktionieren.',
    'layout_missing_alpine' => 'Ihr Layout laedt moeglicherweise kein Alpine.js. Stellen Sie sicher, dass Ihr JS-Bundle Alpine importiert (z.B. @vite([\'resources/js/app.js\'])), sonst reagieren interaktive Widgets nicht auf Klicks.',
    'layout_created' => 'Layout-Komponente [:layout] erstellt',
    'safelist_generated' => 'Tailwind-Safelist generiert',
    'plugin_not_registered' => 'LayupPlugin ist in keinem Filament-Panel registriert. Fuegen Sie LayupPlugin::make() zum ->plugins([])-Array Ihres Panel-Providers hinzu.',
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
