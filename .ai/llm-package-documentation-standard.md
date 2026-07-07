# LLM Package Documentation Standard

Use this standard when creating or repairing documentation for any Crumbls Laravel package.

The goal is simple: a developer should be able to install the package, configure it, understand the core concepts, use the main features, and troubleshoot common issues without reading the source code first.

## Non-Negotiable Rules

- Do not use emojis.
- Do not write marketing copy in technical pages.
- Do not invent behavior. Verify commands, classes, config keys, routes, events, migrations, service providers, facades, models, policies, and published files from source.
- Do not document placeholder features as if they exist.
- Do not say "coming soon" inside package docs. If something is missing, add a clear `Documentation gaps` section.
- Prefer concrete examples over vague descriptions.
- Keep examples copy-pasteable for a normal Laravel app.
- Use fenced code blocks with language labels.
- Use stable relative links between docs pages.
- Keep one H1 per Markdown file.
- Use sentence case for headings.
- Explain defaults and failure modes.
- Include authorization, security, and production notes when relevant.

## Required Repository Files

Every package must include:

```text
README.md
CHANGELOG.md
docs/
  nav.json
  index.md
  installation.md
  configuration.md
  usage.md
  examples.md
  api-reference.md
  upgrade-guide.md
  troubleshooting.md
```

Additional pages are encouraged when the package has real depth:

```text
docs/concepts.md
docs/security.md
docs/testing.md
docs/extending.md
docs/events.md
docs/commands.md
docs/filament.md
docs/livewire.md
docs/advanced.md
```

## Required Front Matter

Every file under `docs/` must start with front matter:

```md
---
title: Installation
nav_title: Installation
order: 20
---
```

Rules:

- `title` is the page H1 unless a more specific H1 is needed.
- `nav_title` should be short enough for sidebar navigation.
- `order` controls navigation order. Use increments of 10 so new pages can fit between existing pages.

## Required Navigation

Every package must include `docs/nav.json`.

Use this shape:

```json
[
  {"title": "Introduction", "path": "index.md", "order": 10},
  {"title": "Installation", "path": "installation.md", "order": 20},
  {"title": "Configuration", "path": "configuration.md", "order": 30},
  {"title": "Usage", "path": "usage.md", "order": 40},
  {"title": "Examples", "path": "examples.md", "order": 50},
  {"title": "API reference", "path": "api-reference.md", "order": 60},
  {"title": "Upgrade guide", "path": "upgrade-guide.md", "order": 70},
  {"title": "Troubleshooting", "path": "troubleshooting.md", "order": 80}
]
```

Nested pages are allowed:

```json
[
  {"title": "Widgets", "path": "customization/widgets.md", "order": 110}
]
```

## README Requirements

The package README is the public summary. It should not replace full docs.

Required sections:

- Package name and one-sentence purpose.
- Compatibility matrix.
- Quick installation.
- Minimal usage example.
- Links to full documentation.
- Security note when applicable.
- Changelog link.
- License.

README files should stay concise. Put detailed explanations in `docs/`.

## Page Requirements

### `docs/index.md`

Must answer:

- What problem does this package solve?
- When should someone use it?
- What Laravel, PHP, and dependency versions does it support?
- What are the core concepts?
- What is the shortest working path from install to useful output?

Recommended headings:

```md
# Introduction

## What it does

## When to use it

## Requirements

## Core concepts

## Quick start

## Next steps
```

### `docs/installation.md`

Must include:

- Composer command.
- Required service provider steps if auto-discovery is not enough.
- Publish commands with exact tags.
- Migration commands if applicable.
- Asset build steps if applicable.
- Required environment variables.
- How to verify the installation worked.

Use exact commands:

```bash
composer require crumbls/package-name
php artisan vendor:publish --tag=package-config
php artisan migrate
```

Only include commands that are real for the package.

### `docs/configuration.md`

Must include:

- Published config file path.
- Every config key.
- Default value.
- Accepted types or values.
- Environment variable mapping.
- Production recommendations.

Use tables for config references:

```md
| Key | Default | Description |
| --- | --- | --- |
| `enabled` | `true` | Enables package behavior. |
```

### `docs/usage.md`

Must include:

- The main workflow.
- Common use cases.
- Code examples.
- Expected output or side effects.
- Notes for queues, caching, transactions, auth, or tenancy when applicable.

Write usage in task-oriented sections:

```md
## Create a plan

## Assign a feature

## Check access
```

### `docs/examples.md`

Must include realistic Laravel examples:

- Controller example when relevant.
- Model example when relevant.
- Filament resource/page example when relevant.
- Livewire example when relevant.
- Test example when relevant.
- CLI example when relevant.

Every example must explain where the code goes.

### `docs/api-reference.md`

Must include the public API only:

- Facades.
- Service classes intended for users.
- Contracts/interfaces.
- Events.
- Commands.
- Config keys.
- Blade components.
- Filament resources/pages/widgets.
- Published migrations.

Do not dump internals. Summarize private implementation details only when they affect extension points.

Recommended format:

```md
## `ClassName`

### `methodName($argument): ReturnType`

Description.

Parameters:

| Parameter | Type | Description |
| --- | --- | --- |

Returns:

| Type | Description |
| --- | --- |
```

### `docs/upgrade-guide.md`

Must include:

- Breaking changes by major version.
- Required migration steps.
- Renamed classes, config keys, commands, routes, or views.
- Deprecated behavior.
- Before and after examples.

If there are no breaking changes yet, say that clearly:

```md
# Upgrade guide

There are currently no documented breaking changes.
```

### `docs/troubleshooting.md`

Must include:

- Common errors.
- Probable cause.
- Fix.
- How to inspect logs/config/cache.

Use this format:

````md
## Config changes are not taking effect

Cause: Laravel has cached the configuration.

Fix:

```bash
php artisan config:clear
```
````

## Changelog Requirements

Use a normal changelog format with newest versions first.

Required shape:

```md
# Changelog

## v1.2.0 - 2026-07-07

### Added

- Added support for ...

### Changed

- Changed ...

### Fixed

- Fixed ...
```

Every release should mention documentation changes when docs are materially updated.

## Documentation Gap Section

When an LLM cannot verify behavior from source, add this section at the bottom of the relevant page:

```md
## Documentation gaps

- Confirm the publish tag for the configuration file.
- Confirm whether queued jobs are required in production.
```

Do not leave guesses in the main documentation.

## Code Example Standards

Examples must:

- Be valid PHP, Blade, Bash, JSON, YAML, or JavaScript.
- Include imports when PHP classes are referenced.
- Use realistic namespaces.
- Avoid pseudo-code unless the block is explicitly labeled.
- Avoid hidden dependencies on local app code.
- Prefer small complete examples over fragments.

PHP examples should include strict names where useful:

```php
use Crumbls\PackageName\Facades\PackageName;

$result = PackageName::handle($input);
```

## Laravel Package Checklist

Before documentation is considered complete, verify:

- Composer package name is correct.
- PHP and Laravel version constraints are correct.
- Installation command is correct.
- Service provider behavior is documented.
- Published config tags are correct.
- Migration names and commands are correct.
- Artisan commands are listed with options.
- Events and listeners are documented.
- Queue requirements are documented.
- Cache behavior is documented.
- Authorization requirements are documented.
- Testing helpers are documented.
- Upgrade steps are documented.
- Troubleshooting includes at least the top three likely failures.

## LLM Workflow

When using an LLM to generate package docs, give it this task:

```text
Read this package repository and create complete documentation following docs/llm-package-documentation-standard.md.

Requirements:
- Do not use emojis.
- Do not invent behavior.
- Inspect composer.json, config files, service providers, migrations, commands, routes, events, contracts, public classes, tests, and README.
- Create or update README.md, CHANGELOG.md, docs/nav.json, and all required docs/*.md files.
- Add a Documentation gaps section for anything that cannot be verified from source.
- Keep examples practical for a Laravel application.
```

## Acceptance Criteria

Documentation is acceptable when:

- A new Laravel developer can install and use the package from the docs alone.
- The docs site can build navigation from `docs/nav.json`.
- Every page has front matter.
- Every public feature has at least one example.
- Missing information is clearly listed as a documentation gap.
- No abandoned, experimental, or imaginary feature is presented as supported.
