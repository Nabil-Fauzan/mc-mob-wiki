# Authentication Flow

The project utilizes **Laravel Breeze** for a clean and simple authentication system.

## Setup
- **Breeze Starter Kit**: Installed with Blade templates and Tailwind CSS.
- **Middleware**: Routes are protected using the `auth` middleware.

## Flow Diagram

```mermaid
graph LR
    Public[Public Visitor] -->|View/Search| Wiki[Mob Wiki]
    Public -->|Click Add/Edit/Delete| CheckAuth{Auth?}
    CheckAuth -- No --> Login[Login Page]
    CheckAuth -- Yes --> Action[Perform CRUD]
    
    Login -->|Input Credentials| Validate[Validate User]
    Validate -- Success --> Dashboard[Dashboard/Wiki]
    Validate -- Fail --> Errors[Show Error Messages]
```

## Implementation Details
1. **Public Access**: 
   - `Route::get('/mobs', ...)`
   - `Route::get('/mobs/{mob}', ...)`
2. **Protected Access**:
   - `Route::middleware(['auth'])->group(...)`
   - Covers: `create`, `store`, `edit`, `update`, `destroy`.
3. **Session Management**: Handled by Laravel's default session driver (configured to `database` in `.env`).
4. **User Model**: Standard `App\Models\User` with typical Breeze authentication features.
