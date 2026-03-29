<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

use Crumbls\Layup\Forms\Components\ColorPicker;
use Crumbls\Layup\Forms\Components\SpacingPicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

abstract class BaseView extends Component
{
    protected bool $isFirst = false;

    protected bool $isLast = false;

    public function __construct(
        protected array $data = [],
        /** @var array<BaseView> */
        protected array $children = []
    ) {}

    /**
     * Set position within parent (first/last) for gutter logic.
     */
    public function setPosition(bool $first = false, bool $last = false): static
    {
        $this->isFirst = $first;
        $this->isLast = $last;

        return $this;
    }

    public function isFirst(): bool
    {
        return $this->isFirst;
    }

    public function isLast(): bool
    {
        return $this->isLast;
    }

    /**
     * Get child components for recursive rendering.
     *
     * @return array<BaseView>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Set child components.
     *
     * @param  array<BaseView>  $children
     */
    public function setChildren(array $children): static
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Add a child component.
     */
    public function addChild(BaseView $child): static
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Check if this component has children.
     */
    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * Get the data array.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Fluent constructor.
     */
    public static function make(array $data = [], array $children = []): static
    {
        return new static($data, $children);
    }

    /**
     * Full form schema with tabs.
     */
    public static function getFormSchema(): array
    {
        return static::withUploadDisk([
            Tabs::make('settings')
                ->tabs([
                    Tab::make(__('layup::widgets.shared.tab_content'))
                        ->icon('heroicon-o-document-text')
                        ->schema(static::withLiveValidation(static::getContentFormSchema())),
                    Tab::make(__('layup::widgets.shared.tab_design'))
                        ->icon('heroicon-o-paint-brush')
                        ->schema(static::withLiveValidation(static::getDesignFormSchema())),
                    Tab::make(__('layup::widgets.shared.tab_advanced'))
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema(static::withLiveValidation(static::getAdvancedFormSchema())),
                ])
                ->columnSpanFull(),
        ]);
    }

    /**
     * Apply live-on-blur validation to all form fields in a schema array.
     * This ensures validation errors appear immediately when the user
     * leaves a field, rather than only on form submission.
     *
     * @param  array<\Filament\Schemas\Components\Component>  $components
     * @return array<\Filament\Schemas\Components\Component>
     */
    protected static function withLiveValidation(array $components): array
    {
        foreach ($components as $component) {
            if ($component instanceof Field) {
                $component->live(onBlur: true);
            }
        }

        return $components;
    }

    /**
     * Recursively set the configured upload disk on all FileUpload
     * components in a schema array. This ensures files are stored on
     * a publicly accessible disk regardless of Filament's default.
     *
     * @param  array<SchemaComponent>  $components
     * @return array<SchemaComponent>
     */
    protected static function withUploadDisk(array $components): array
    {
        $disk = config('layup.uploads.disk', 'public');

        static::walkComponents($components, function (SchemaComponent $component) use ($disk): void {
            if ($component instanceof FileUpload) {
                $component->disk($disk);
            }
        });

        return $components;
    }

    /**
     * Recursively walk all components and their children.
     * Uses reflection to access the raw child array, avoiding
     * getChildComponents() which requires a mounted container.
     *
     * @param  array<SchemaComponent>  $components
     */
    public static function walkComponents(array $components, \Closure $callback): void
    {
        foreach ($components as $component) {
            $callback($component);

            try {
                $ref = new \ReflectionProperty($component, 'childComponents');
                $children = $ref->getValue($component);

                foreach ($children as $group) {
                    if (is_array($group)) {
                        static::walkComponents($group, $callback);
                    }
                }
            } catch (\ReflectionException) {
                // Component has no childComponents property -- skip.
            }
        }
    }

    /**
     * Content fields: override in subclasses for component-specific fields.
     *
     * @return array<\Filament\Schemas\Components\Component>
     */
    public static function getContentFormSchema(): array
    {
        return [];
    }

    /**
     * Design tab: padding, margin, background, etc.
     *
     * @return array<\Filament\Schemas\Components\Component>
     */
    public static function getDesignFormSchema(): array
    {
        return [
            ColorPicker::make('text_color')
                ->label(__('layup::widgets.shared.text_color')),
            Select::make('text_align')
                ->label(__('layup::widgets.shared.text_alignment'))
                ->options([
                    '' => __('layup::widgets.shared.default'),
                    'left' => __('layup::widgets.shared.left_option'),
                    'center' => __('layup::widgets.shared.center'),
                    'right' => __('layup::widgets.shared.right_option'),
                    'justify' => __('layup::widgets.shared.justify'),
                ])
                ->default('')
                ->nullable(),
            Select::make('font_size')
                ->label(__('layup::widgets.shared.font_size'))
                ->options([
                    '' => __('layup::widgets.shared.default'),
                    '0.75rem' => __('layup::widgets.shared.font_xs'),
                    '0.875rem' => __('layup::widgets.shared.font_sm'),
                    '1rem' => __('layup::widgets.shared.font_base'),
                    '1.125rem' => __('layup::widgets.shared.font_lg'),
                    '1.25rem' => __('layup::widgets.shared.font_xl'),
                    '1.5rem' => __('layup::widgets.shared.font_2xl'),
                    '1.875rem' => __('layup::widgets.shared.font_3xl'),
                    '2.25rem' => __('layup::widgets.shared.font_4xl'),
                    '3rem' => __('layup::widgets.shared.font_5xl'),
                ])
                ->default('')
                ->nullable(),
            Select::make('border_radius')
                ->label(__('layup::widgets.shared.border_radius'))
                ->options([
                    '' => __('layup::widgets.shared.none'),
                    '0.25rem' => __('layup::widgets.shared.small'),
                    '0.375rem' => __('layup::widgets.shared.medium'),
                    '0.5rem' => __('layup::widgets.shared.large'),
                    '0.75rem' => 'XL',
                    '1rem' => '2XL',
                    '1.5rem' => '3XL',
                    '9999px' => __('layup::widgets.shared.full'),
                ])
                ->default('')
                ->nullable(),
            TextInput::make('border_width')
                ->label(__('layup::widgets.shared.border_width'))
                ->placeholder('e.g. 1px')
                ->nullable(),
            Select::make('border_style')
                ->label(__('layup::widgets.shared.border_style'))
                ->options([
                    '' => __('layup::widgets.shared.none'),
                    'solid' => __('layup::widgets.shared.solid'),
                    'dashed' => __('layup::widgets.shared.dashed'),
                    'dotted' => __('layup::widgets.shared.dotted'),
                    'double' => __('layup::widgets.shared.double'),
                ])
                ->default('')
                ->nullable(),
            ColorPicker::make('border_color')
                ->label(__('layup::widgets.shared.border_color')),
            Select::make('box_shadow')
                ->label(__('layup::widgets.shared.box_shadow'))
                ->options([
                    '' => __('layup::widgets.shared.none'),
                    '0 1px 2px 0 rgb(0 0 0 / 0.05)' => __('layup::widgets.shared.shadow_xs'),
                    '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)' => __('layup::widgets.shared.shadow_small'),
                    '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)' => __('layup::widgets.shared.shadow_medium'),
                    '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)' => __('layup::widgets.shared.shadow_large'),
                    '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)' => __('layup::widgets.shared.shadow_xl'),
                    '0 25px 50px -12px rgb(0 0 0 / 0.25)' => __('layup::widgets.shared.shadow_2xl'),
                ])
                ->default('')
                ->nullable(),
            Select::make('opacity')
                ->label(__('layup::widgets.shared.opacity'))
                ->options([
                    '' => __('layup::widgets.shared.opacity_default'),
                    '0.9' => '90%',
                    '0.8' => '80%',
                    '0.7' => '70%',
                    '0.6' => '60%',
                    '0.5' => '50%',
                    '0.4' => '40%',
                    '0.3' => '30%',
                    '0.2' => '20%',
                    '0.1' => '10%',
                ])
                ->default('')
                ->nullable(),
            SpacingPicker::advanced('padding', __('layup::widgets.shared.padding')),
            SpacingPicker::advanced('margin', __('layup::widgets.shared.margin')),
            ColorPicker::make('background_color')
                ->label(__('layup::widgets.shared.background_color')),
        ];
    }

    /**
     * Advanced tab: ID, CSS classes, inline styles.
     *
     * @return array<\Filament\Schemas\Components\Component>
     */
    public static function getAdvancedFormSchema(): array
    {
        return [
            TextInput::make('id')
                ->label(__('layup::widgets.shared.id'))
                ->helperText(__('layup::widgets.shared.id_helper'))
                ->nullable()
                ->unique(ignoreRecord: true),
            TextInput::make('class')
                ->label(__('layup::widgets.shared.css_classes'))
                ->helperText(__('layup::widgets.shared.css_classes_helper'))
                ->nullable(),
            Textarea::make('inline_css')
                ->label(__('layup::widgets.shared.inline_css'))
                ->rows(4)
                ->placeholder(__('layup::widgets.shared.inline_css_placeholder'))
                ->nullable(),
            CheckboxList::make('hide_on')
                ->label(__('layup::widgets.shared.hide_on'))
                ->helperText(__('layup::widgets.shared.hide_on_helper'))
                ->options([
                    'sm' => __('layup::widgets.shared.mobile'),
                    'md' => __('layup::widgets.shared.tablet'),
                    'lg' => __('layup::widgets.shared.desktop'),
                    'xl' => __('layup::widgets.shared.large_desktop'),
                ])
                ->columns(4)
                ->nullable(),
            Select::make('animation')
                ->label(__('layup::widgets.shared.entrance_animation'))
                ->options([
                    '' => __('layup::widgets.shared.none'),
                    'fade-in' => __('layup::widgets.shared.fade_in'),
                    'slide-up' => __('layup::widgets.shared.slide_up'),
                    'slide-down' => __('layup::widgets.shared.slide_down'),
                    'slide-left' => __('layup::widgets.shared.slide_left'),
                    'slide-right' => __('layup::widgets.shared.slide_right'),
                    'zoom-in' => __('layup::widgets.shared.zoom_in'),
                ])
                ->default('')
                ->nullable(),
            Select::make('animation_duration')
                ->label(__('layup::widgets.shared.animation_duration'))
                ->options([
                    '300' => __('layup::widgets.shared.fast'),
                    '500' => __('layup::widgets.shared.normal'),
                    '700' => __('layup::widgets.shared.slow'),
                    '1000' => __('layup::widgets.shared.very_slow'),
                ])
                ->default('500')
                ->visible(fn ($get): bool => ! empty($get('animation')))
                ->nullable(),
        ];
    }

    /**
     * Build visibility classes from hide_on array.
     * Returns Tailwind classes like "hidden md:block" or "md:hidden lg:block".
     */
    public static function visibilityClasses(array $hideOn): string
    {
        if ($hideOn === []) {
            return '';
        }

        $breakpoints = ['sm', 'md', 'lg', 'xl'];
        $classes = [];

        // If hiding on mobile (sm), start with hidden, then show at first non-hidden breakpoint
        // For each breakpoint, determine if it should be hidden or shown
        foreach ($breakpoints as $i => $bp) {
            $hidden = in_array($bp, $hideOn);
            $prevHidden = $i === 0 ? false : in_array($breakpoints[$i - 1], $hideOn);

            if ($i === 0 && $hidden) {
                $classes[] = 'hidden';
            } elseif ($i === 0 && ! $hidden) {
                // default visible, no class needed
            } elseif ($hidden && ! $prevHidden) {
                $classes[] = "{$bp}:hidden";
            } elseif (! $hidden && $prevHidden) {
                $classes[] = "{$bp}:block";
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Build inline style string from common data fields (text_color, text_align, background_color, inline_css).
     */
    public static function buildInlineStyles(array $data): string
    {
        $styles = [];

        if (! empty($data['text_color'])) {
            $styles[] = "color: {$data['text_color']};";
        }
        if (! empty($data['text_align'])) {
            $styles[] = "text-align: {$data['text_align']};";
        }
        if (! empty($data['font_size'])) {
            $styles[] = "font-size: {$data['font_size']};";
        }
        if (! empty($data['border_radius'])) {
            $styles[] = "border-radius: {$data['border_radius']};";
        }
        if (! empty($data['border_width']) && ! empty($data['border_style'])) {
            $color = $data['border_color'] ?? '#e5e7eb';
            $styles[] = "border: {$data['border_width']} {$data['border_style']} {$color};";
        }
        if (! empty($data['box_shadow'])) {
            $styles[] = "box-shadow: {$data['box_shadow']};";
        }
        if (! empty($data['opacity'])) {
            $styles[] = "opacity: {$data['opacity']};";
        }
        if (! empty($data['background_color'])) {
            $styles[] = "background-color: {$data['background_color']};";
        }
        if (! empty($data['inline_css'])) {
            $styles[] = $data['inline_css'];
        }

        return implode(' ', $styles);
    }

    /**
     * Build Alpine.js animation attributes for entrance animations.
     * Returns a string of Alpine directives to add to the element.
     */
    public static function animationAttributes(array $data): string
    {
        $animation = $data['animation'] ?? '';
        if (empty($animation)) {
            return '';
        }

        $duration = $data['animation_duration'] ?? '500';

        $initial = match ($animation) {
            'fade-in' => 'opacity: 0',
            'slide-up' => 'opacity: 0; transform: translateY(2rem)',
            'slide-down' => 'opacity: 0; transform: translateY(-2rem)',
            'slide-left' => 'opacity: 0; transform: translateX(2rem)',
            'slide-right' => 'opacity: 0; transform: translateX(-2rem)',
            'zoom-in' => 'opacity: 0; transform: scale(0.9)',
            default => '',
        };

        $final = match ($animation) {
            'fade-in' => 'opacity: 1',
            'slide-up', 'slide-down' => 'opacity: 1; transform: translateY(0)',
            'slide-left', 'slide-right' => 'opacity: 1; transform: translateX(0)',
            'zoom-in' => 'opacity: 1; transform: scale(1)',
            default => '',
        };

        if ($initial === '' || $initial === '0') {
            return '';
        }

        return sprintf(
            'x-data="{ shown: false }" x-intersect.once="shown = true" '
            . ':style="shown ? \'%s; transition: all %sms ease-out\' : \'%s; transition: all %sms ease-out\'"',
            $final,
            $duration,
            $initial,
            $duration,
        );
    }

    /**
     * Get the view / contents that represent the component.
     */
    abstract public function render(): View;
}
