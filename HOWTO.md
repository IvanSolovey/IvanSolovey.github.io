# Керування сайтом локально

## Запуск

```bash
/Users/user/.rbenv/versions/3.3.7/bin/bundle exec /Users/user/.rbenv/versions/3.3.7/bin/jekyll serve
```

Сайт на http://localhost:4000 — автооновлення при зміні файлів.

## Збірка без сервера

```bash
/Users/user/.rbenv/versions/3.3.7/bin/bundle exec /Users/user/.rbenv/versions/3.3.7/bin/jekyll build
```

Результат у папці `_site/`.

---

## Новий пост

1. Створи файл `_posts/РРРР-ММ-ДД-slug.md`
2. Додай front matter:

```yaml
---
layout: post
title: Заголовок
date: 2026-06-01
type: стаття        # або: замітка
tags: [ai, процеси]
excerpt: Короткий опис для картки на головній.
standfirst: Лід-абзац під заголовком статті.
artifact:           # залиш порожнім якщо не потрібен блок "забери з собою"
  label: забери з собою
  type: промпт
  hint: Скопіюй, підстав своє, ітеруй.
  body: |
    Текст артефакту тут.
---

Текст поста у Markdown.
```

> `type: замітка` — компактна картка без опису.
> `standfirst` — сірий лід під заголовком на сторінці посту.
> Блок `artifact` — рамка "забери з собою" в кінці статті. Без нього — не рендериться.

---

## Редагування контенту

| Що змінити | Файл |
|---|---|
| "Зараз" на головній | `_data/now.yml` |
| Ім'я, біо, соцмережі | `_data/site.yml` |
| Пункти навігації | `_data/nav.yml` |
| Всі стилі | `assets/css/main.css` |
| Логіка теми / кнопка копіювати | `assets/js/theme.js` |
| Сторінка "Про автора" | `_pages/about.html` |

---

## Структура

```
_posts/        — пости (Markdown)
_pages/        — сторінки: about, archive, tags
_layouts/      — шаблони: default, home, post, page
_includes/     — фрагменти: header, footer, sidebar, post-card, artifact
_data/         — дані: site.yml, now.yml, nav.yml
assets/css/    — main.css (єдиний CSS-файл)
assets/js/     — theme.js
reference/     — оригінальні HTML-референси дизайну
_site/         — зібраний сайт (не редагувати вручну)
```

---

## Публікація на GitHub Pages

```bash
git add .
git commit -m "опис змін"
git push
```

GitHub Pages збирає сайт автоматично після пушу. Живе на `https://ivansolovey.github.io`.
