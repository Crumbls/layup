<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Seitenressource
    |--------------------------------------------------------------------------
    |
    | Beschriftungen für die Filament-Ressource: Navigation, Formulare, Tabellen, Aktionen.
    |
    */

    'navigation_group' => 'Inhalt',
    'navigation_label' => 'Seiten',

    // Formularbereiche
    'template' => 'Vorlage',
    'start_from_template' => 'Von Vorlage starten',
    'blank_page' => 'Leere Seite',
    'page_details' => 'Seitendetails',
    'seo' => 'SEO',
    'meta_description' => 'Meta-Beschreibung',
    'meta_description_help' => 'Wird von Suchmaschinen und sozialen Netzwerken verwendet. Etwa 150 Zeichen sind ideal.',
    'meta_keywords' => 'Meta-Schlüsselwörter',
    'noindex' => 'Vor Suchmaschinen verbergen',
    'noindex_help' => 'Fügt ein robots noindex,nofollow-Tag hinzu, damit diese Seite von Suchergebnissen und Crawlern ausgeschlossen wird.',

    // Status
    'draft' => 'Entwurf',
    'published' => 'Veröffentlicht',

    // Aktionen
    'new_page' => 'Neue Seite',
    'duplicate' => 'Duplizieren',
    'export' => 'Exportieren',
    'import' => 'Importieren',
    'publish' => 'Veröffentlichen',
    'unpublish' => 'Nicht veröffentlicht',
    'json_file' => 'JSON-Datei',
    'copy_suffix' => '(Kopie)',

    // Feldbeschriftungen
    'title' => 'Titel',
    'slug' => 'Slug',
    'status' => 'Status',
    'parent_page' => 'Übergeordnete Seite',
    'no_parent_top_level' => 'Keine übergeordnete Seite (oberste Ebene)',
    'slug_taken_under_parent' => 'Eine Seite mit diesem Slug existiert bereits unter der ausgewählten übergeordneten Seite.',
    'path' => 'Pfad',
    'tab_all' => 'Alle',
    'tab_trash' => 'Papierkorb',
    'author' => 'Autor',
    'quick_edit' => 'Schnellbearbeitung',
    'container_preset' => 'Seitencontainer',
    'container_preset_default' => 'Globale Voreinstellung verwenden',
    'container_preset_help' => 'Umschließt Zeilen, die nicht volle Breite verwenden. Überschreibt die globale Voreinstellung pro Seite.',
    'template_preset' => 'Seitenvorlage',
    'template_preset_default' => 'Globale Voreinstellung verwenden',
    'template_preset_help' => 'Blade-Ansicht zum Rendern dieser Seite auswählen. Bei leerer Auswahl wird die Standardvorlage verwendet.',
    'scheduled' => 'Geplant',
    'status_help' => 'Wählen Sie "Geplant" mit einem zukünftigen Datum, um die Veröffentlichung zu verzögern.',
    'publish_at' => 'Veröffentlichen am',
    'publish_at_help' => 'Wann diese Seite live gehen soll. Zukünftige Daten werden automatisch veröffentlicht.',
    'featured_image' => 'Beitragsbild',
    'featured_image_help' => 'Wird für Social Shares (OG/Twitter), Schema und Theme-Vorschaubilder verwendet.',
    'permalink' => 'Permalink',
    'draft_not_published' => 'Entwurf — noch nicht veröffentlicht',
    'scheduled_for' => 'Geplant für :time',
    'empty_trash' => 'Papierkorb leeren',
    'empty_trash_confirm_heading' => 'Alle Seiten im Papierkorb dauerhaft löschen?',
    'empty_trash_confirm_description' => 'Dies löscht alle Seiten im Papierkorb endgültig. Diese Aktion kann nicht rückgängig gemacht werden.',
    'empty_trash_done' => ':count Seite(n) endgültig gelöscht.',

    // Bearbeitungsseite Kopfaktionen
    'page_settings' => 'Seiteneinstellungen',
    'page_settings_updated' => 'Seiteneinstellungen aktualisiert',
    'page_published' => 'Seite veröffentlicht',
    'page_unpublished' => 'Seite als Entwurf gespeichert',
    'revision_history' => 'Versionsgeschichte',
    'revision_history_description' => 'Frühere Versionen dieser Seite anzeigen und wiederherstellen',
    'save_as_template' => 'Als Vorlage speichern',
    'template_name' => 'Vorlagenname',
    'description' => 'Beschreibung',

    // Seitenpanel-Beschriftungen
    'row_settings' => 'Zeileneinstellungen',
    'column_settings' => 'Spalteneinstellungen',
    'edit_widget' => 'Widget bearbeiten',

    // Bestätigungsdialoge
    'delete_row' => 'Zeile löschen',
    'delete_row_description' => 'Sind Sie sicher, dass Sie diese Zeile und ihren gesamten Inhalt löschen möchten?',
    'delete_column' => 'Spalte löschen',
    'delete_column_description' => 'Sind Sie sicher, dass Sie diese Spalte und alle Widgets löschen möchten?',
    'delete_widget' => 'Widget löschen',
    'delete_widget_description' => 'Sind Sie sicher, dass Sie dieses Widget löschen möchten?',
];
