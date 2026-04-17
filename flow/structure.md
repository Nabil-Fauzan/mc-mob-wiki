# Folder Structure

This project follows the standard Laravel directory structure with some custom additions for the Minecraft Mob Wiki features.

## Key Directories

### 1. `app/`
Contains the core application logic.
- `app/Http/Controllers/`: Contains `MobController.php` (CRUD logic).
- `app/Models/`: Contains `Mob.php` and `Category.php`.

### 2. `database/`
Contains database related files.
- `database/migrations/`: Definitions for `categories` and `mobs` tables.
- `database/seeders/`: `CategorySeeder.php` for initial data.

### 3. `resources/`
Contains frontend assets and templates.
- `resources/views/mobs/`: All Blade templates for the wiki.
  - `index.blade.php`: Listing page.
  - `show.blade.php`: Detail page.
  - `create.blade.php`: Form for adding mobs.
  - `edit.blade.php`: Form for editing mobs.
- `resources/css/app.css`: Tailwind CSS entry point.

### 4. `routes/`
Contains route definitions.
- `routes/web.php`: Defines all mob resource and public routes.

### 5. `storage/`
Contains application storage.
- `storage/app/public/mobs/`: Folder for uploaded mob images.
- `public/storage/`: Symlink to `storage/app/public/` for web access.

### 6. `flow/` (Documentation)
Contains explanation files for the project.
- `database.md`: Database design explanation.
- `app-flow.md`: Application journey and lifecycle.
- `crud-flow.md`: Description of CRUD operations.
- `auth-flow.md`: Authentication process details.
- `structure.md`: This file.
