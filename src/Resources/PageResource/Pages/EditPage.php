<?php

declare(strict_types=1);

namespace Crumbls\Layup\Resources\PageResource\Pages;

use Crumbls\Layup\Models\Page;
use Crumbls\Layup\Resources\PageResource;
use Crumbls\Layup\Support\PageTemplate;
use Crumbls\Layup\View\Row;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    //    protected string $view = 'layup::livewire.page-builder';

    protected Width|string|null $maxContentWidth = 'full';

    /** @var array Excluded from Filament's form hydration via */
    public array $pageContent = [];

    public ?string $editingRowId = null;

    public ?string $editingColumnId = null;

    public ?string $editingWidgetId = null;

    public ?string $editingWidgetType = null;

    public array $rowSettings = [];

    public array $columnSettings = [];

    public array $widgetData = [];

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->pageContent = $this->record->content ?? ['rows' => []];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('pageSettings')
                ->label(__('layup::resource.page_settings'))
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->modalWidth('md')
                ->fillForm(fn (): array => [
                    'title' => $this->record->title,
                    'slug' => $this->record->slug,
                    'status' => $this->record->status,
                ])
                ->schema([
                    TextInput::make('title')
                        ->label(__('layup::resource.title'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->label(__('layup::resource.slug'))
                        ->required()
                        ->maxLength(255)
                        ->unique(
                            config('layup.pages.model', Page::class),
                            'slug',
                            ignoreRecord: true,
                        ),
                    Select::make('status')
                        ->label(__('layup::resource.status'))
                        ->options([
                            'draft' => __('layup::resource.draft'),
                            'published' => __('layup::resource.published'),
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update($data);

                    Notification::make()
                        ->success()
                        ->title(__('layup::resource.page_settings_updated'))
                        ->send();
                }),
            Action::make('revisions')
                ->label(__('layup::resource.revision_history'))
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->slideOver()
                ->modalWidth('2xl')
                ->modalHeading(__('layup::resource.revision_history'))
                ->modalDescription(__('layup::resource.revision_history_description'))
                ->modalContent(fn (): \Illuminate\Contracts\View\View => $this->getRevisionHistoryView())
                ->modalFooterActions([])
                ->action(fn (): null => null),
            Action::make('saveAsTemplate')
                ->label(__('layup::resource.save_as_template'))
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->schema([
                    TextInput::make('template_name')
                        ->label(__('layup::resource.template_name'))
                        ->required()
                        ->default(fn (): string => $this->record->title . ' Template'),
                    TextInput::make('template_description')
                        ->label(__('layup::resource.description'))
                        ->nullable(),
                ])
                ->action(function (array $data): void {
                    PageTemplate::saveFromPage(
                        $data['template_name'],
                        $this->record->content ?? ['rows' => []],
                        $data['template_description'] ?? null,
                    );
                    Notification::make()->success()->title(__('layup::notifications.template_saved'))->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRevisionHistoryView(): \Illuminate\Contracts\View\View
    {
        $revisions = $this->record->revisions()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('layup::revision-history', [
            'revisions' => $revisions,
            'currentPageId' => $this->record->id,
        ]);
    }

    public function restoreRevision(int $revisionId): void
    {
        $revision = $this->record->revisions()->findOrFail($revisionId);

        $this->record->update(['content' => $revision->content]);
        $this->pageContent = $revision->content;
        $this->syncContent();

        Notification::make()
            ->title(__('layup::notifications.revision_restored'))
            ->body(__('layup::notifications.revision_restored_body', ['time' => $revision->created_at->diffForHumans()]))
            ->success()
            ->send();

        $this->dispatch('close-modal', id: 'revisions');
    }

    // ─── Row Operations ──────────────────────────────────────

    // ─── Properties for Alpine ───────────────────────────────

}
