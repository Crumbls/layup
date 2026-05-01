<?php

declare(strict_types=1);

namespace Crumbls\Layup\View;

/**
 * Backwards-compatible alias for BaseBladeWidget.
 *
 * Originally the only widget base. Kept as an empty subclass so that all
 * existing widgets (`extends BaseWidget`), stubs, docs, and downstream
 * packages continue to work without modification. New widgets may extend
 * either BaseBladeWidget directly (clearer intent) or BaseLivewireWidget
 * (Livewire-rendered output).
 *
 * Note for runtime checks: prefer `instanceof Widget` (the interface) over
 * `instanceof BaseWidget` so that BaseLivewireWidget instances are also
 * recognised. The Widget interface is the contract — BaseWidget is just
 * one base implementation.
 */
abstract class BaseWidget extends BaseBladeWidget {}
