---
description: General coding principles for all projects
alwaysApply: true
---

## Universal

- One file = one responsibility
- Always handle errors explicitly (try/catch, fallback UI)
- No magic numbers — use named constants
- Prefer simple over clever
- Never hardcode secrets — always use .env
- Before writing code — make sure you understand the task fully
- If task is unclear — ask ONE question before coding

## PHP

- PSR-12 code style
- Always add `declare(strict_types=1)` at the top of every file
- Prefer functions over classes unless OOP is clearly justified
- Use `match` over `switch`, `??` over long ternary chains
- Always validate and sanitize external input
- No inline SQL — use PDO with prepared statements only
- Separate config / routing / templates
- No mixed PHP/HTML — logic and presentation in separate files

## HTML

- Semantic tags first: `<main>`, `<section>`, `<article>`, `<nav>`
- Accessibility required: `alt` on images, `aria-*` and `role` where needed
- No inline styles — use classes

## CSS

- Mobile-first by default
- BEM for plain CSS projects
- Use CSS custom properties instead of magic numbers
- Avoid `!important` except for utility overrides
- Group properties: positioning → box model → typography → visual → animation

## JavaScript

- ES Modules, async/await, arrow functions
- Functional style only — no class-based components
- No jQuery, no global variables
