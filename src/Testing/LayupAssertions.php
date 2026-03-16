<?php

declare(strict_types=1);

namespace Crumbls\Layup\Testing;

use Crumbls\Layup\Support\ContentWalker;
use Crumbls\Layup\Support\WidgetRegistry;
use Illuminate\Database\Eloquent\Model;

trait LayupAssertions
{
    /**
     * Assert that a page (or any model with a content column) contains a specific widget type.
     */
    public function assertPageContainsWidget(Model $page, string $type, ?int $expectedCount = null): void
    {
        $content = $page->content ?? [];
        $types = ContentWalker::collectWidgetTypes($content);

        $this->assertArrayHasKey(
            $type,
            $types,
            "Failed asserting that the page contains a '{$type}' widget."
        );

        if ($expectedCount !== null) {
            $this->assertSame(
                $expectedCount,
                $types[$type],
                "Failed asserting that the page contains exactly {$expectedCount} '{$type}' widget(s). Found {$types[$type]}."
            );
        }
    }

    /**
     * Assert that a page does not contain a specific widget type.
     */
    public function assertPageDoesNotContainWidget(Model $page, string $type): void
    {
        $content = $page->content ?? [];
        $types = ContentWalker::collectWidgetTypes($content);

        $this->assertArrayNotHasKey(
            $type,
            $types,
            "Failed asserting that the page does not contain a '{$type}' widget."
        );
    }

    /**
     * Assert that a widget type renders without errors.
     */
    public function assertWidgetRenders(string $type, array $data = []): void
    {
        $registry = app(WidgetRegistry::class);
        $class = $registry->get($type);

        $this->assertNotNull(
            $class,
            "Failed asserting that widget type '{$type}' is registered."
        );

        $widget = $class::make($data ?: $class::getDefaultData());
        $html = $widget->render()->render();

        $this->assertIsString($html, "Failed asserting that widget '{$type}' renders a string.");
        $this->assertNotEmpty($html, "Failed asserting that widget '{$type}' renders non-empty HTML.");
    }

    /**
     * Assert that a page renders without errors.
     */
    public function assertPageRenders(Model $page): void
    {
        $html = $page->toHtml();

        $this->assertIsString($html, 'Failed asserting that the page renders a string.');
        $this->assertNotEmpty($html, 'Failed asserting that the page renders non-empty HTML.');
    }
}
