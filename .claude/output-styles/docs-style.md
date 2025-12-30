# Documentation Style Output Configuration

Use this style when writing or updating documentation in this repository.

## General Tone and Structure

- Direct, technical, and informative
- Focus on practical usage and implementation details
- Present information in a clear, structured manner without excessive prose
- Use bold for product names on first mention: **replication-manager**
- Avoid marketing language; be factual and precise

## Formatting Conventions

### Headers

- Use `##` for main sections (not `#` unless it's the page title)
- Use `###` for subsections
- Use `####` for configuration parameters and command descriptions
- Use `#####` for individual parameter definitions

### Code Blocks

- Use fenced code blocks with triple backticks
- Specify language when appropriate (bash, sql, yaml, etc.)
- For command examples, do not use prompts (no `$` or `#` prefixes)
- Show multi-line commands directly

Example:
```
replication-manager-cli switchover --cluster=test_cluster
```

### Lists

- Use checkbox-style lists `- [x]` for features, capabilities, and workflow steps
- Use regular bullet lists `- ` for general items and descriptions
- Use numbered lists for sequential procedures only when order matters

### Callouts and Warnings

Use blockquote style with bold prefix for important notes:

```
>__Important Note__: semisync SYNC status does not guarantee...
```

Use exclamation-style callouts for tips:

```
! Staying in sync
!
! When the replication can be monitored in sync...
```

### Parameter Documentation

Use consistent table format for configuration parameters:

```
##### `parameter-name`

| Item | Value |
| ---- | ----- |
| Description | Clear description of what this does |
| Type | boolean/string/integer |
| Default Value | default-value |
| Example | example-value |
```

Include version information in parameter names when applicable:
- `parameter-name` (2.0), `old-name` (0.6)

### Tables

- Use simple markdown tables with header row
- Align columns using `| ---- | ----- |` separator
- Keep table content concise

### Links

- Use inline markdown links: `[link text](url)`
- For internal references: `[see the configuration step](/installation/configuration)`
- For external references: `[GitHub Releases](https://github.com/...)`

### Images

- Reference images with markdown syntax: `![alt-text](/images/filename.png)`
- Use descriptive alt text
- Place images logically within content flow

## Writing Style

### Technical Accuracy

- Use precise technical terminology
- Specify versions when features are version-specific
- Include actual command syntax, not pseudocode
- Provide real configuration examples, not placeholders

### Conciseness

- Get to the point quickly
- Avoid unnecessary explanations of obvious concepts
- Use examples to illustrate rather than lengthy prose
- Let code and configuration speak for itself

### Workflow Documentation

When documenting workflows or processes:
- Use checkbox lists to show steps clearly
- Keep step descriptions brief
- Focus on what happens, not why it's good

Example:
```
- [x] Verify replication settings
- [x] Check replication on the slaves
- [x] Check for long running queries on master
```

### Configuration Documentation

- Group related parameters under subsections
- Use consistent table format (shown above)
- Include deprecated parameters in a "Deprecated" section at the end
- Always specify the version when a parameter was introduced

### Command Documentation

For CLI commands:
- Show the command first
- Provide brief description
- Include relevant flags and options
- Show expected output when helpful

Example:
```
#### Command line switchover

Trigger replication-manager client to perform a switchover

`replication-manager-cli switchover --cluster=test_cluster`
```

## YAML Frontmatter

Every documentation page must include:

```yaml
---
title: "Descriptive Page Title"
taxonomy:
    category: docs
---
```

## Things to Avoid

- Do not use excessive exclamation marks or enthusiasm
- Do not use emojis
- Do not say "simply" or "just" - assume reader competence
- Do not use marketing language like "powerful", "robust", "enterprise-grade"
- Do not add unnecessary warnings about obvious things
- Do not explain basic concepts the target audience would know
- Do not use overly formal or academic language

## Product Name Standards

- **replication-manager** (bold on first mention, regular after)
- Use underscores in code: `__replication-manager__`
- Component binaries: **replication-manager-cli**, **replication-manager-osc**, etc.
- Always hyphenated, never "Replication Manager" or "replicationmanager"

## Examples to Follow

Good workflow documentation:
```
- [x] Reject writes on master by calling FLUSH TABLES WITH READ LOCK
- [x] Reject writes on master by setting READ_ONLY flag
- [x] Watching for all slaves to catch up to the current GTID position
```

Good command documentation:
```
#### Command line failover

Trigger replication-manager in non-interactive to perform a failover,

`replication-manager-cli failover --cluster="test_cluster"`
```

Good configuration documentation:
```
##### `log-level`

| Item | Value |
| ---- | ----- |
| Description | Log verbosity level, log level >3 are very verbose |
| Type | int |
| Example | 1 to 7 |
```
