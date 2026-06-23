<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BlurbWidget extends BaseWidget
{
    public static function getType(): string
    {
        return 'blurb';
    }

    public static function getLabel(): string
    {
        return __('layup::widgets.labels.blurb');
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-light-bulb';
    }

    public static function getCategory(): string
    {
        return 'content';
    }

    public static function getContentFormSchema(): array
    {
        return [
            TextInput::make('title')
                ->label(__('layup::widgets.blurb.title'))
                ->required(),
            RichEditor::make('content')
                ->label(__('layup::widgets.blurb.content'))
                ->columnSpanFull(),
            Select::make('media_type')
                ->label(__('layup::widgets.blurb.media_type'))
                ->options(['icon' => __('layup::widgets.blurb.icon'),
                    'image' => __('layup::widgets.blurb.image'),
                    'none' => __('layup::widgets.blurb.none'), ])
                ->default('icon')
                ->reactive(),
            Select::make('icon')
                ->label(__('layup::widgets.blurb.icon'))
                ->searchable()
                ->options([// Arrows & Navigation
                    'heroicon-o-arrow-right' => __('layup::widgets.blurb.arrow_right'),
                    'heroicon-o-arrow-left' => __('layup::widgets.blurb.arrow_left'),
                    'heroicon-o-arrow-up' => __('layup::widgets.blurb.arrow_up'),
                    'heroicon-o-arrow-down' => __('layup::widgets.blurb.arrow_down'),
                    'heroicon-o-chevron-right' => __('layup::widgets.blurb.chevron_right'),
                    'heroicon-o-arrows-pointing-out' => __('layup::widgets.blurb.expand'),
                    // Actions
                    'heroicon-o-check' => __('layup::widgets.blurb.check'),
                    'heroicon-o-check-circle' => __('layup::widgets.blurb.check_circle'),
                    'heroicon-o-x-mark' => __('layup::widgets.blurb.x_mark'),
                    'heroicon-o-plus' => __('layup::widgets.blurb.plus'),
                    'heroicon-o-minus' => __('layup::widgets.blurb.minus'),
                    'heroicon-o-pencil' => __('layup::widgets.blurb.pencil'),
                    'heroicon-o-trash' => __('layup::widgets.blurb.trash'),
                    'heroicon-o-clipboard' => __('layup::widgets.blurb.clipboard'),
                    'heroicon-o-clipboard-document-check' => __('layup::widgets.blurb.clipboard_check'),
                    // Communication
                    'heroicon-o-envelope' => __('layup::widgets.blurb.envelope'),
                    'heroicon-o-phone' => __('layup::widgets.blurb.phone'),
                    'heroicon-o-chat-bubble-left-right' => __('layup::widgets.blurb.chat'),
                    'heroicon-o-megaphone' => __('layup::widgets.blurb.megaphone'),
                    'heroicon-o-bell' => __('layup::widgets.blurb.bell'),
                    'heroicon-o-inbox' => __('layup::widgets.blurb.inbox'),
                    // Content & Media
                    'heroicon-o-document-text' => __('layup::widgets.blurb.document'),
                    'heroicon-o-photo' => __('layup::widgets.blurb.photo'),
                    'heroicon-o-camera' => __('layup::widgets.blurb.camera'),
                    'heroicon-o-video-camera' => __('layup::widgets.blurb.video'),
                    'heroicon-o-musical-note' => __('layup::widgets.blurb.music'),
                    'heroicon-o-microphone' => __('layup::widgets.blurb.microphone'),
                    'heroicon-o-film' => __('layup::widgets.blurb.film'),
                    'heroicon-o-book-open' => __('layup::widgets.blurb.book'),
                    'heroicon-o-newspaper' => __('layup::widgets.blurb.newspaper'),
                    // Business
                    'heroicon-o-briefcase' => __('layup::widgets.blurb.briefcase'),
                    'heroicon-o-building-office' => __('layup::widgets.blurb.office'),
                    'heroicon-o-chart-bar' => __('layup::widgets.blurb.chart_bar'),
                    'heroicon-o-chart-pie' => __('layup::widgets.blurb.chart_pie'),
                    'heroicon-o-presentation-chart-line' => __('layup::widgets.blurb.chart_line'),
                    'heroicon-o-currency-dollar' => __('layup::widgets.blurb.dollar'),
                    'heroicon-o-banknotes' => __('layup::widgets.blurb.banknotes'),
                    'heroicon-o-credit-card' => __('layup::widgets.blurb.credit_card'),
                    'heroicon-o-calculator' => __('layup::widgets.blurb.calculator'),
                    'heroicon-o-receipt-percent' => __('layup::widgets.blurb.receipt'),
                    // People & Social
                    'heroicon-o-user' => __('layup::widgets.blurb.user'),
                    'heroicon-o-users' => __('layup::widgets.blurb.users'),
                    'heroicon-o-user-group' => __('layup::widgets.blurb.user_group'),
                    'heroicon-o-heart' => __('layup::widgets.blurb.heart'),
                    'heroicon-o-hand-thumb-up' => __('layup::widgets.blurb.thumbs_up'),
                    'heroicon-o-face-smile' => __('layup::widgets.blurb.smile'),
                    'heroicon-o-gift' => __('layup::widgets.blurb.gift'),
                    'heroicon-o-trophy' => __('layup::widgets.blurb.trophy'),
                    'heroicon-o-academic-cap' => __('layup::widgets.blurb.academic_cap'),
                    // Objects
                    'heroicon-o-star' => __('layup::widgets.blurb.star'),
                    'heroicon-o-bolt' => __('layup::widgets.blurb.bolt'),
                    'heroicon-o-fire' => __('layup::widgets.blurb.fire'),
                    'heroicon-o-light-bulb' => __('layup::widgets.blurb.light_bulb'),
                    'heroicon-o-sparkles' => __('layup::widgets.blurb.sparkles'),
                    'heroicon-o-rocket-launch' => __('layup::widgets.blurb.rocket'),
                    'heroicon-o-puzzle-piece' => __('layup::widgets.blurb.puzzle'),
                    'heroicon-o-key' => __('layup::widgets.blurb.key'),
                    'heroicon-o-lock-closed' => __('layup::widgets.blurb.lock'),
                    'heroicon-o-lock-open' => __('layup::widgets.blurb.unlock'),
                    'heroicon-o-shield-check' => __('layup::widgets.blurb.shield'),
                    'heroicon-o-cog-6-tooth' => __('layup::widgets.blurb.gear'),
                    'heroicon-o-wrench-screwdriver' => __('layup::widgets.blurb.tools'),
                    'heroicon-o-beaker' => __('layup::widgets.blurb.beaker'),
                    'heroicon-o-flag' => __('layup::widgets.blurb.flag'),
                    'heroicon-o-tag' => __('layup::widgets.blurb.tag'),
                    // Technology
                    'heroicon-o-computer-desktop' => __('layup::widgets.blurb.desktop'),
                    'heroicon-o-device-phone-mobile' => __('layup::widgets.blurb.heroicon_o_device_phone_mobile_phone'),
                    'heroicon-o-device-tablet' => __('layup::widgets.blurb.tablet'),
                    'heroicon-o-globe-alt' => __('layup::widgets.blurb.globe'),
                    'heroicon-o-wifi' => __('layup::widgets.blurb.wifi'),
                    'heroicon-o-cloud' => __('layup::widgets.blurb.cloud'),
                    'heroicon-o-server' => __('layup::widgets.blurb.server'),
                    'heroicon-o-code-bracket' => __('layup::widgets.blurb.code'),
                    'heroicon-o-command-line' => __('layup::widgets.blurb.terminal'),
                    'heroicon-o-cpu-chip' => __('layup::widgets.blurb.cpu'),
                    // Location & Travel
                    'heroicon-o-map-pin' => __('layup::widgets.blurb.map_pin'),
                    'heroicon-o-map' => __('layup::widgets.blurb.map'),
                    'heroicon-o-home' => __('layup::widgets.blurb.home'),
                    'heroicon-o-truck' => __('layup::widgets.blurb.truck'),
                    'heroicon-o-paper-airplane' => __('layup::widgets.blurb.paper_airplane'),
                    // Time
                    'heroicon-o-clock' => __('layup::widgets.blurb.clock'),
                    'heroicon-o-calendar' => __('layup::widgets.blurb.calendar'),
                    'heroicon-o-calendar-days' => __('layup::widgets.blurb.calendar_days'),
                    // UI
                    'heroicon-o-squares-2x2' => __('layup::widgets.blurb.grid'),
                    'heroicon-o-list-bullet' => __('layup::widgets.blurb.list'),
                    'heroicon-o-bars-3' => __('layup::widgets.blurb.menu'),
                    'heroicon-o-magnifying-glass' => __('layup::widgets.blurb.search'),
                    'heroicon-o-funnel' => __('layup::widgets.blurb.filter'),
                    'heroicon-o-adjustments-horizontal' => __('layup::widgets.blurb.adjustments'),
                    'heroicon-o-eye' => __('layup::widgets.blurb.eye'),
                    'heroicon-o-link' => __('layup::widgets.blurb.link'),
                    'heroicon-o-share' => __('layup::widgets.blurb.share'),
                    'heroicon-o-bookmark' => __('layup::widgets.blurb.bookmark'),
                    // Status
                    'heroicon-o-information-circle' => __('layup::widgets.blurb.info'),
                    'heroicon-o-exclamation-triangle' => __('layup::widgets.blurb.warning'),
                    'heroicon-o-exclamation-circle' => __('layup::widgets.blurb.error'),
                    'heroicon-o-question-mark-circle' => __('layup::widgets.blurb.question'),
                    'heroicon-o-no-symbol' => __('layup::widgets.blurb.no_symbol'), ])
                ->placeholder(__('layup::widgets.blurb.choose_an_icon'))
                ->visible(fn (callable $get): bool => $get('media_type') === 'icon'),
            FileUpload::make('image')
                ->label(__('layup::widgets.blurb.image'))
                ->image()
                ->directory('layup/blurbs')
                ->visible(fn (callable $get): bool => $get('media_type') === 'image'),
            Select::make('layout')
                ->label(__('layup::widgets.blurb.layout'))
                ->options(['top' => __('layup::widgets.blurb.icon_image_top'),
                    'left' => __('layup::widgets.blurb.icon_image_left'),
                    'right' => __('layup::widgets.blurb.icon_image_right'), ])
                ->default('top'),
            TextInput::make('url')
                ->label(__('layup::widgets.blurb.link_url'))
                ->url()
                ->nullable(),
            Select::make('text_alignment')
                ->label(__('layup::widgets.blurb.text_alignment'))
                ->options(['' => __('layup::widgets.blurb.default'),
                    'left' => __('layup::widgets.blurb.left'),
                    'center' => __('layup::widgets.blurb.center'),
                    'right' => __('layup::widgets.blurb.right'), ])
                ->default('')
                ->nullable(),
        ];
    }

    public static function getDefaultData(): array
    {
        return [
            'title' => '',
            'content' => '',
            'media_type' => 'icon',
            'icon' => '',
            'image' => '',
            'layout' => 'top',
            'url' => '',
            'text_alignment' => '',
        ];
    }

    public static function getPreview(array $data): string
    {
        return $data['title'] ?? '(empty blurb)';
    }

    public static function supportsLivePreview(): bool
    {
        return true;
    }
}
