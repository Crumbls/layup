<?php

declare(strict_types=1);

use Crumbls\Layup\Forms\Components\LayupBuilder;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Livewire;

trait BootsBuilderFormProviders
{
    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            \BladeUI\Icons\BladeIconsServiceProvider::class,
            \BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
            \Filament\Support\SupportServiceProvider::class,
            \Filament\Schemas\SchemasServiceProvider::class,
            \Filament\Forms\FormsServiceProvider::class,
            \Filament\Actions\ActionsServiceProvider::class,
        ];
    }
}

uses(BootsBuilderFormProviders::class);

class BuilderFormFixture extends \Livewire\Component implements \Filament\Actions\Contracts\HasActions, HasSchemas
{
    use \Filament\Actions\Concerns\InteractsWithActions;
    use InteractsWithSchemas;

    public array $data = [];

    public function mount(array $content = []): void
    {
        $this->form->fill(['content' => $content]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([LayupBuilder::make('content')])->statePath('data');
    }

    public function render(): string
    {
        return '<div>{{ $this->form }}</div>';
    }
}

beforeEach(function (): void {
    $this->app->register(\Livewire\LivewireServiceProvider::class, true);
});

it('hydrates missing section row column and widget identities for editing', function (): void {
    $widget = ['type' => 'text', 'data' => ['content' => 'Section content']];
    $row = ['columns' => [['widgets' => [$widget]]]];
    $section = ['settings' => ['padding' => ['unit' => 'rem', 'top' => 2]], 'rows' => [$row]];
    $component = Livewire::test(BuilderFormFixture::class, ['content' => ['sections' => [$section]]]);
    $content = $component->get('data.content');
    expect($content)->not->toHaveKey('rows');
    expect($content['sections'][0]['id'])->toStartWith('section_');
    expect($content['sections'][0]['rows'][0]['id'])->toStartWith('row_');
    expect($content['sections'][0]['rows'][0]['columns'][0]['widgets'][0]['id'])->toStartWith('widget_');
    $component->assertSee('Section settings');
});

it('runs exposed section and row operations through the mounted Filament field', function (): void {
    $component = Livewire::test(BuilderFormFixture::class, ['content' => ['rows' => []]]);
    $key = $component->instance()->form->getComponents()[0]->getKey();
    $component->call('callSchemaComponentMethod', $key, 'sectionAdd', []);
    $sectionId = $component->get('data.content.sections.0.id');
    $component->call('callSchemaComponentMethod', $key, 'rowAdd', ['columns' => [6, 6], 'position' => 0, 'sectionId' => $sectionId]);
    $content = $component->get('data.content');
    expect($content['sections'][0]['rows'])->toHaveCount(1);
    expect($content['sections'][0]['rows'][0]['columns'])->toHaveCount(2);
    $component->call('callSchemaComponentMethod', $key, 'sectionDuplicate', ['id' => $sectionId]);
    expect($component->get('data.content.sections'))->toHaveCount(2);
    $component->call('callSchemaComponentMethod', $key, 'restoreContent', ['state' => $content]);
    expect($component->get('data.content'))->toBe($content);
});

it('saves shared and responsive section settings through its action form', function (): void {
    $component = Livewire::test(BuilderFormFixture::class, ['content' => ['sections' => [['id' => 's1', 'settings' => [], 'rows' => []]]]]);
    $key = $component->instance()->form->getComponents()[0]->getKey();
    $component->call('mountAction', 'sectionEdit', ['id' => 's1'], ['schemaComponent' => $key])
        ->set('mountedActions.0.data.padding.top', 24)
        ->set('mountedActions.0.data.responsive.md.padding.top', 0)
        ->set('mountedActions.0.data.id', 'hero')
        ->set('mountedActions.0.data.opacity', '1')
        ->set('mountedActions.0.data.responsive.md.opacity', '0')
        ->call('callMountedAction')
        ->assertHasNoErrors();
    expect($component->get('data.content.sections.0.settings.padding.top'))->toEqual(24);
    expect($component->get('data.content.sections.0.settings.responsive.md.padding.top'))->toEqual(0);
});
