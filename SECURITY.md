# Security policy

## Supported versions

Security fixes are provided for the latest tagged release and the current `main` branch. Earlier releases are not supported.

## Reporting a vulnerability

Do not report security vulnerabilities through public GitHub issues.

Email [chase+layup+security@crumbls.com](mailto:chase+layup+security@crumbls.com), or use GitHub's private security advisory reporting for this repository. Include:

- A description of the issue and its potential impact.
- Steps to reproduce it or a proof of concept.
- The affected Layup and Laravel versions.
- Suggested mitigation, if known.

We will acknowledge valid reports within 48 hours and provide progress updates as the issue is triaged and resolved.

## Scope and deployment guidance

Layup renders page content configured through Filament forms, including rich-text and user-supplied widget data. Applications integrating Layup are responsible for authorizing access to page editing, choosing an appropriate upload disk, and reviewing custom widgets before registering them.

Do not register widgets that render untrusted data as raw HTML unless the application sanitizes that data for its intended context. Apply Laravel and Filament security updates promptly, and keep the application's dependencies current.

## Security updates

When a report is confirmed, we will prepare a fix, publish a release when appropriate, and disclose the issue after affected users have a reasonable opportunity to update.
