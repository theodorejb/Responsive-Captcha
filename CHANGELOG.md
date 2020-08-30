# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 2017-04-16
### Changed
- No longer depends on PHP sessions - you can now save answers any way you'd like
  (e.g. in single-use tokens which can be used with an API)
- PHP 7+ is now required
- Simplified API (see readme for details)
- Greatly expanded test coverage

## [2.0.2] - 2016-02-26
### Fixed
- Random numbers are now properly shuffled

## [2.0.1] - 2015-01-22
### Fixed
- Bug which prevented the textual version of a number from being accepted in an answer.

## [2.0.0] - 2014-09-21
### Added
- `getAnswer()` method

### Changed
- Moved to `theodorejb` namespace
- Install using Composer
- `checkAnswer()` now returns `true` or `false`, rather than throwing an exception

## [1.0.1] - 2013-08-01
- Initial semver release

[Unreleased]: https://github.com/theodorejb/Responsive-Captcha/compare/v3.0.0...HEAD
[3.0.0]: https://github.com/theodorejb/Responsive-Captcha/compare/v2.0.2...v3.0.0
[2.0.2]: https://github.com/theodorejb/Responsive-Captcha/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/theodorejb/Responsive-Captcha/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/theodorejb/Responsive-Captcha/compare/v1.0.1...v2.0.0
[1.0.1]: https://github.com/theodorejb/Responsive-Captcha/tree/v1.0.1
