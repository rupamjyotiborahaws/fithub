# GitHub Actions CI/CD Pipeline

This repository includes a comprehensive CI/CD pipeline using GitHub Actions. The pipeline consists of multiple workflows to ensure code quality, security, and automated deployments.

## Workflows Overview

### 1. CI/CD Pipeline (`ci-cd.yml`)
**Triggers:** Push to `main`/`develop`, Pull requests to `main`/`develop`

**Jobs:**
- **Test**: Runs PHPUnit tests, builds assets, checks code style
- **Security Check**: Scans for security vulnerabilities
- **Build**: Creates deployment artifacts (only on main branch)
- **Deploy Staging**: Deploys to staging environment (develop branch)
- **Deploy Production**: Deploys to production environment (main branch)
- **Notify**: Sends deployment notifications

**Features:**
- Multi-database testing (MySQL, SQLite)
- Asset compilation with Vite
- Code coverage reporting
- Security vulnerability scanning
- Artifact creation for deployment

### 2. Release (`release.yml`)
**Triggers:** Push tags matching `v*.*.*`

**Jobs:**
- Creates production-ready release archives
- Generates GitHub releases with release notes
- Excludes development files from release package

### 3. Code Quality (`code-quality.yml`)
**Triggers:** Push to `main`/`develop`, Pull requests to `main`/`develop`

**Jobs:**
- **PHP Code Quality**: Laravel Pint, PHPStan analysis
- **JavaScript Code Quality**: ESLint, security audits
- **Blade Template Quality**: Template syntax validation
- **Markdown Lint**: Documentation quality checks

### 4. Database (`database.yml`)
**Triggers:** 
- Pull requests affecting database files
- Manual workflow dispatch

**Jobs:**
- **Migration Test**: Tests migrations on multiple databases
- **Migration Status**: Validates migration file conventions and syntax

### 5. Dependencies (`dependencies.yml`)
**Triggers:** 
- Weekly schedule (Mondays at 2 AM UTC)
- Manual workflow dispatch

**Jobs:**
- **Update Composer**: Automated Composer dependency updates
- **Update NPM**: Automated NPM dependency updates
- Creates pull requests for dependency updates

## Setup Requirements

### 1. Environment Variables
The workflows expect certain environment variables and secrets:

- `GITHUB_TOKEN`: Automatically provided by GitHub
- Add custom secrets for deployment credentials if needed

### 2. Repository Settings
1. Enable Actions in repository settings
2. Configure branch protection rules for `main` branch
3. Set up environments for staging/production if using deployment jobs

### 3. Database Configuration
The CI pipeline is configured to work with:
- **SQLite**: For fast testing (in-memory database)
- **MySQL 8.0**: For integration testing

### 4. PHP & Node.js Versions
- **PHP**: 8.3 (as specified in composer.json)
- **Node.js**: 20 LTS

## Customization

### Deployment Jobs
The deployment jobs (`deploy-staging`, `deploy-production`) contain placeholder commands. Update these with your actual deployment scripts:

```yaml
# Example deployment commands
- name: Deploy to production server
  run: |
    # SSH to server
    # Copy files
    # Run migrations
    # Clear caches
    # Restart services
```

### Code Quality Standards
Adjust code quality tools and their configurations:
- **PHPStan Level**: Currently set to level 5, can be increased to 9
- **ESLint Rules**: Modify in the workflow or create separate config files
- **Pint Configuration**: Uses Laravel's default, customize as needed

### Testing Configuration
- **PHPUnit**: Configured for SQLite in-memory testing
- **Coverage**: Uploads to Codecov (requires CODECOV_TOKEN secret)
- **Database**: Uses MySQL service for integration tests

## Workflow Status Badges

Add these badges to your main README.md:

```markdown
[![CI/CD Pipeline](https://github.com/your-username/fithub/actions/workflows/ci-cd.yml/badge.svg)](https://github.com/your-username/fithub/actions/workflows/ci-cd.yml)
[![Code Quality](https://github.com/your-username/fithub/actions/workflows/code-quality.yml/badge.svg)](https://github.com/your-username/fithub/actions/workflows/code-quality.yml)
[![Database](https://github.com/your-username/fithub/actions/workflows/database.yml/badge.svg)](https://github.com/your-username/fithub/actions/workflows/database.yml)
```

## Best Practices

1. **Branch Protection**: Enable required status checks for all workflows
2. **Security**: Never commit secrets or environment files
3. **Testing**: Write comprehensive tests before deploying
4. **Code Review**: All pull requests should be reviewed
5. **Monitoring**: Set up notifications for failed deployments

## Troubleshooting

### Common Issues
1. **Composer Memory Limit**: Increased to 2G for PHPStan
2. **Node.js Cache**: Uses npm cache for faster builds
3. **Database Connections**: Health checks ensure database readiness
4. **Asset Building**: Separates development and production builds

### Debug Mode
To debug workflows:
1. Enable Actions debugging in repository secrets
2. Add `ACTIONS_STEP_DEBUG: true`
3. Add `ACTIONS_RUNNER_DEBUG: true`

For more detailed logs, add debug commands to workflow steps.