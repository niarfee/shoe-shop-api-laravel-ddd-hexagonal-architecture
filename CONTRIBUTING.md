# Contributing Guidelines

Thank you for considering contributing to this project! To keep our repository
organized and maintainable, please follow these guidelines for branch names and
commit messages.

## Branch Naming Conventions

We use the following prefixes for branches to clearly indicate the purpose of
the changes:

| Branch Type | Description                                                                                                     |
| ----------- | --------------------------------------------------------------------------------------------------------------- |
| `feature/`  | For new features or enhancements.                                                                               |
| `fix/`      | For bug fixes.                                                                                                  |
| `chore/`    | For routine tasks, maintenance, or non-functional changes (e.g., updates to dependencies, configuration files). |
| `docs/`     | For documentation updates or additions.                                                                         |
| `refactor/` | For code restructuring without changing behavior.                                                               |
| `test/`     | For adding or fixing tests.                                                                                     |
| `perf/`     | For performance improvements.                                                                                   |
| `ci/`       | For continuous integration and delivery pipeline changes.                                                       |
| `release/`  | For release branches.                                                                                           |
| `hotfix/`   | For hotfixes.                                                                                                   |

### Branch Name Format

```
<prefix>/<short-description>
```

Examples:

- `feature/add-user-authentication`
- `fix/login-error-handling`
- `chore/update-dependencies`
- `docs/update-contributing-guide`
- `refactor/clean-up-api`
- `test/add-product-unit-tests`
- `perf/optimize-query-speed`
- `ci/update-github-actions`
- `release/v1.2.0`
- `hotfix/fix-critical-bug`

## Commit Message Conventions

We follow the [Conventional Commits](https://www.conventionalcommits.org/)
specification to keep commit history clear and meaningful.

### Commit Message Format

```
<type>(<scope>): <short summary>
```

- **type**: The category of the commit. Common types:

  | Type       | Description                                                   |
  | ---------- | ------------------------------------------------------------- |
  | `feat`     | A new feature                                                 |
  | `fix`      | A bug fix                                                     |
  | `chore`    | Changes to the build process or auxiliary tools and libraries |
  | `docs`     | Documentation only changes                                    |
  | `refactor` | Code change that neither fixes a bug nor adds a feature       |
  | `test`     | Adding or correcting tests                                    |
  | `perf`     | Code change that improves performance                         |
  | `ci`       | Changes to the CI/CD pipelines, workflows, or configuration   |
  | `style`    | Formatting, missing semi colons, etc; no code change          |

- **scope**: A noun describing a section of the codebase (optional).

- **short summary**: A concise description of the change.

### Examples

- `feat(auth): add JWT token support`
- `fix(api): handle null pointer exception`
- `chore(docker): update docker-compose.yml`
- `docs: add contributing guidelines`
- `refactor(api): remove unused code`
- `test(product): add unit tests`
- `perf(order): optimize performance`
- `ci: update CI/CD pipeline`
- `style(api): format code`

### Additional Notes

- Use the imperative mood in the subject line (e.g., “fix”, not “fixed” or
  “fixes”).
- Limit the subject line to 50 characters.
- Separate the subject from the body with a blank line.
- Wrap the body at 72 characters.
- Use the body to explain what and why vs. how.

## Branch Protection

Branches like `main`, `develop`, and any `release/*` branches are protected.\
Please create a pull request for all changes and ensure tests pass before
merging.

### Git Flow Branching Model

This project follows the Git Flow workflow:

- All branch types except `hotfix` branch off from `develop` and merge back into
  `develop`.
- `hotfix` branches branch off from `main` and merge back into both `main` and
  `develop`.
- Release branches are created from `develop` to prepare for production
  deployment.

## Pull Request Guidelines

- Reference related issues in the PR description (e.g., "Closes #123").
- Provide a clear summary of changes and rationale.
- Include relevant tests for new or changed functionality.
- Ensure the CI pipeline passes before requesting review.
- Use descriptive titles and assign reviewers as needed.

## Testing Requirements

Every pull request should include tests that cover new or changed
functionality.\
This ensures code quality and prevents regressions.

If the change is only documentation or minor styling, tests may not be
necessary.

---

Thank you for helping us keep the project clean and organized!
