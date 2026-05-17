# Jekyll Migration Plan — ivansolovey.github.io

## Goal
Migrate static HTML blog "Соловей" to Jekyll for GitHub Pages hosting.
- URL: ivansolovey.github.io (+ custom domain later via CNAME)
- Features: RSS feed, Pagination, SEO meta tags, Sitemap, Giscus comments
- CSS: single external file `assets/css/main.css`

---

## Target Directory Structure

```
ivansolovey.github.io/
├── _config.yml              # Jekyll site config (URL, plugins, pagination)
├── Gemfile                  # gem dependencies
├── .gitignore
├── CNAME                    # empty placeholder for custom domain
├── PLAN.md                  # this file
├── CLAUDE.md
├── design-brief.md
│
├── _layouts/
│   ├── default.html         # base: <html>, head, header, footer, CSS/JS links
│   ├── home.html            # extends default: 2-col grid + sidebar
│   ├── post.html            # extends default: single article + giscus
│   └── page.html            # extends default: simple page (about, archive, tags)
│
├── _includes/
│   ├── head.html            # <head> meta, fonts, jekyll-seo-tag
│   ├── header.html          # masthead + nav + theme toggle
│   ├── footer.html          # footer
│   ├── sidebar.html         # about block + tag cloud + subscribe form
│   ├── post-card.html       # reusable post card component
│   ├── artifact.html        # artifact block (conditionally used in post layout)
│   └── giscus.html          # giscus comments embed
│
├── _posts/                  # ALL blog content — one .md file per post
│   ├── 2026-01-15-ai-intern-amnesia.md
│   ├── 2026-02-03-prompts-in-files.md
│   ├── 2026-02-20-process-beats-talent.md
│   ├── 2026-03-10-first-result-always-bad.md
│   └── 2026-04-05-small-team-ai.md
│
├── _data/
│   ├── site.yml             # author name, handle, bio, social links, tag list
│   ├── nav.yml              # navigation menu items
│   └── now.yml              # "Зараз" section content (editable without touching layouts)
│
├── _pages/                  # static pages
│   ├── about.md             # → /about/
│   ├── archive.md           # → /archive/ (loops posts by year via Liquid)
│   └── tags.md              # → /tags/ (single page with JS tag switcher)
│
└── assets/
    ├── css/
    │   └── main.css         # ALL CSS extracted from inline styles
    └── js/
        └── theme.js         # theme toggle + clipboard copy (extracted from inline scripts)
```

---

## Implementation Checklist

### Phase 1: Project Bootstrap
- [x] Create `Gemfile` with jekyll + plugins (feed, seo-tag, sitemap, paginate)
- [x] Create `_config.yml` (title, url, author, plugins, pagination, permalink)
- [x] Create `.gitignore`
- [x] Create empty `CNAME`

### Phase 2: Extract CSS and JS
- [x] Extract and merge all inline CSS → `assets/css/main.css`
- [x] Extract theme toggle JS → `assets/js/theme.js`
- [x] Add clipboard copy JS to `assets/js/theme.js`

### Phase 3: Create Layouts
- [x] `_layouts/default.html` — base layout (head, header, footer, asset links)
- [x] `_layouts/home.html` — 2-col grid + sidebar + "Зараз" section
- [x] `_layouts/post.html` — single article + artifact block + giscus
- [x] `_layouts/page.html` — simple single-column page

### Phase 4: Create Includes
- [x] `_includes/head.html` — meta, fonts, `{% seo %}`
- [x] `_includes/header.html` — masthead, nav from `_data/nav.yml`, theme toggle
- [x] `_includes/footer.html` — footer
- [x] `_includes/sidebar.html` — author bio, tag cloud, subscribe form
- [x] `_includes/post-card.html` — reusable post card
- [x] `_includes/artifact.html` — artifact block
- [x] `_includes/giscus.html` — giscus embed

### Phase 5: Create Data Files
- [x] `_data/site.yml` — author info, social links, tag list
- [x] `_data/nav.yml` — navigation items
- [x] `_data/now.yml` — "Зараз" section content

### Phase 6: Convert Posts to Markdown
- [x] `_posts/2026-05-15-ai-intern-amnesia.md`
- [x] `_posts/2026-04-20-prompts-in-files.md`
- [x] `_posts/2026-03-15-process-beats-talent.md`
- [x] `_posts/2026-02-28-first-result-always-bad.md`
- [x] `_posts/2026-02-01-small-team-ai.md`

### Phase 7: Create Static Pages
- [x] `_pages/about.html` — content from `about.html`
- [x] `_pages/archive.html` — posts grouped by year (Liquid loop)
- [x] `_pages/tags.html` — all posts with JS tag filter

### Phase 8: Homepage
- [x] Convert `index.html` to Jekyll front matter page (layout: home)

### Phase 9: Cleanup
- [x] Delete old HTML files: `post.html`, `about.html`, `tag.html`, `archive.html`

---

## Post Front Matter Schema

```yaml
---
layout: post
title: "Title here"
date: YYYY-MM-DD
type: стаття        # or: замітка
tags: [tag1, tag2]
excerpt: "One-sentence excerpt shown in lists"
artifact:           # OPTIONAL — omit entirely if post has no artifact
  type: промпт      # or: шаблон, тощо
  content: |
    Artifact content here (monospace block)
  hint: "Hint text shown under artifact"
---

Post body in Markdown...
```

## Maintenance Guide

| Task | Action |
|------|--------|
| New post | Create `_posts/YYYY-MM-DD-slug.md` with front matter + Markdown |
| Update "Зараз" | Edit `_data/now.yml` |
| Add/rename tag | Add to `_data/site.yml` tags list |
| Edit author bio | Edit `_data/site.yml` |
| Edit nav links | Edit `_data/nav.yml` |
| Set custom domain | Add domain to `CNAME` file |
| Enable Giscus | Configure repo in `_includes/giscus.html` |

## Plugins (GitHub Pages whitelist only)
- `jekyll-feed` → `/feed.xml`
- `jekyll-seo-tag` → meta tags (OG, Twitter Card, canonical)
- `jekyll-sitemap` → `/sitemap.xml`
- `jekyll-paginate` → paginated homepage

## Tag Pages Note
`jekyll-archives` is NOT on GitHub Pages whitelist.
Tags handled via single `/tags/` page + JS filter (same UX as current `tag.html`).

---

## Verification Checklist
- [ ] `bundle exec jekyll serve` runs locally at localhost:4000
- [ ] Homepage shows paginated posts + sidebar
- [ ] `/archive/` lists posts grouped by year
- [ ] `/tags/` tag switcher filters correctly
- [ ] Individual post: artifact block renders, giscus loads
- [ ] `/feed.xml` exists
- [ ] `/sitemap.xml` exists
- [ ] View source: og:title, og:description, canonical present
- [ ] Theme toggle persists across pages (localStorage)
- [ ] Mobile layout works (≤860px breakpoint)
