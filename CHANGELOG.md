# Changelog

All notable changes to this project are documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## Unreleased

### Added

- `Problem`, a mutable RFC 9457 problem data structure with JSON output.
- `ProblemException`, a logic exception for validation failures.
- `HttpProblem`, with 40 implementations covering the IANA 4xx and 5xx statuses.
- `ExceptionTransformer`, which maps throwables to problems with optional class and message extensions.
