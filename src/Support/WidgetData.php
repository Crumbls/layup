<?php

declare(strict_types=1);

namespace Crumbls\Layup\Support;

class WidgetData implements \ArrayAccess
{
    public function __construct(protected array $data) {}

    public static function from(array $data): static
    {
        return new static($data);
    }

    public function string(string $key, string $default = ''): string
    {
        return (string) ($this->data[$key] ?? $default);
    }

    public function bool(string $key, bool $default = false): bool
    {
        return (bool) ($this->data[$key] ?? $default);
    }

    public function int(string $key, int $default = 0): int
    {
        return (int) ($this->data[$key] ?? $default);
    }

    public function float(string $key, float $default = 0.0): float
    {
        return (float) ($this->data[$key] ?? $default);
    }

    public function array(string $key, array $default = []): array
    {
        return (array) ($this->data[$key] ?? $default);
    }

    public function has(string $key): bool
    {
        return ! empty($this->data[$key]);
    }

    public function storageUrl(string $key): string
    {
        $path = $this->data[$key] ?? '';

        return $path !== '' ? asset('storage/' . $path) : '';
    }

    public function url(string $key): string
    {
        return filter_var($this->data[$key] ?? '', FILTER_VALIDATE_URL) ?: '';
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }
}
