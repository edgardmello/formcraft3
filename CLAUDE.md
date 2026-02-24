# CLAUDE.md

The role of this file is to describe common mistakes and confusion points that agents might encounter as they work in this project. If you ever encounter something in the project that surprises you, please alert the developer working with you and indicate that this is the case in the AgentMD file to help prevent future agents from having the same issue.

### Database Tables

The plugin creates 5 custom tables:

- `wp_formcraft_3_forms` - Form definitions (builder, meta, HTML)
- `wp_formcraft_3_submissions` - Form submissions
- `wp_formcraft_3_views` - Analytics data (views, submissions, payments per day)
- `wp_formcraft_3_files` - Uploaded file metadata
- `wp_formcraft_3_progress` - Multi-step form progress tracking

### Frontend vs Backend Structure

**Backend (Admin):**

- Uses Angular.js 1.5 for the form builder interface
- Builder view: `views/builder.php`
- Admin JS sources: `src/js/` (compiled to `dist/` minified)
- Key admin controllers: `formcraft-builder.js`, `formcraft-dashboard.js`, `formcraft-entries.js`, `formcraft-insights.js`

**Frontend (Forms):**

- Forms render via shortcode: `[formcraft id=X]`
- Form rendering JS: `dist/form.min.js`
- Source: `assets/js/src/form.js`
- Styles: `dist/form.css` (source: `src/less/form.less`)

### Key Dependencies

**Third-party libraries (in `lib/`):**

- `eos/` - Expression evaluator for form calculations
- `html2text/` - HTML to text conversion
- `moment/` - Date/time handling with 100+ locales
- `parsedown.php` - Markdown parser

**Frontend vendor libraries (in `assets/js/vendor/`):**

- Angular.js 1.5 (admin only)
- jQuery UI components (datepicker, slider, widget)
- Chart.js (analytics visualization)
- Selectize (dropdown enhancement)

### Actions

- `formcraft_addon_init` - Initialize addons
- `formcraft_form_scripts` - Enqueue form scripts
- `formcraft_form_content` - Modify form HTML output
- `formcraft_new_form` - After form creation
- `formcraft_after_form_add` - After form is added to database
- `formcraft_after_form_delete` - After form deletion
- `formcraft_before_save` - Before form configuration is saved
- `formcraft_after_save` - After form configuration is saved
- `formcraft_after_fields` - Add custom field types to builder

### Filters

- `formcraft_filter_entry_meta` - Filter submission meta before processing
- `formcraft_filter_raw` - Filter raw POST data during submission
- `formcraft_filter_entry_content` - Filter entry content before saving
- `formcraft_filter_email_template` - Filter email template content

## File Structure

```
formcraft3/
├── formcraft-main.php      # Main plugin file (all PHP logic)
├── views/
│   └── builder.php          # Admin form builder UI
├── src/
│   ├── js/                  # Unminified admin JS sources
│   └── less/                # LESS stylesheets (compiled to dist/)
├── dist/                    # Minified JS/CSS for production
├── assets/
│   ├── js/
│   │   ├── vendor/          # Third-party libraries
│   │   ├── src/             # Frontend JS sources
│   │   └── datepicker-lang/ # jQuery UI datepicker locales
│   ├── fonts/               # Material Design icons
│   └── images/              # Builder backgrounds
├── lib/                     # PHP libraries
├── templates/               # Form templates (.txt files)
└── languages/               # Translation files
```

## Form Data Structure

Forms are stored as JSON in the `meta_builder` column:

```php
{
  "config": {
    "Custom_CSS": "...",
    "CustomJS": "...",
    "Messages": { ... },
    ...
  },
  "fields": [
    {
      "type": "oneLineText",
      "identifier": "name_123",
      "elementDefaults": {
        "main_label": "Name",
        ...
      }
    },
    ...
  ]
}
```

## Common Development Tasks

Use the skill formcraft-developer to perform common tasks like:

- Creating a feature;
- Fixing a bug;
- Adding a new field type;
- Modifying form styles;
- Implementing a new action/filter hook;

---

## CSS/LESS Development Notes

### Build Process

- **LESS sources:** `src/less/*.less`
- **Compiled CSS:** `dist/*.css` (minified)
- **Build command:** `npm run build:css`
- The build script (`build-css.js`) compiles LESS files and minifies them using CleanCSS

### Builder Styles Structure

The form builder styles in `formcraft-builder.less` have a complex nested structure:

- `#formcraft-builder-cover` (line 154) - Main wrapper
  - `#form-cover-html` (line 1687) - Form preview area
    - `.form-element` (line 1790, then nested via `&` around line 1925) - Individual form fields
      - `.form-options` (line 1973) - Field options modal panel

**Important:** The `.form-options` modal positioning is controlled by CSS within `#formcraft-builder-cover #form-cover-html .form-element .form-options`. When modifying positioning properties:

- `left: 100%` positions modal to the RIGHT of the field
- `right: 100%` positions modal to the LEFT of the field
- The `:before` pseudo-element creates the arrow pointer and needs matching transform:
  - For left-side modal: `left: 100%; margin-left: -10px; transform: rotate(45deg)`
  - For right-side modal: `right: 100%; margin-right: -10px; transform: rotate(-45deg)`

### LESS Nesting Gotchas

The LESS file has some unusual nesting patterns due to historical code structure. The `.form-options` block around line 1973 appears to close at line 1924 (which is actually closing a GLOBAL `.form-options` block from line 1881), then continues with `&`-prefixed selectors that are still within `#form-cover-html .form-element`. Be careful when adding new rules - verify the compiled CSS output to ensure selectors match the intended scope.
