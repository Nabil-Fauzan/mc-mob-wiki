# CRUD Flow

This document explains how Create, Read, Update, and Delete operations are handled for Minecraft Mobs.

## CREATE Flow
1. User clicks "Add New Mob" (Auth required).
2. `MobController@create` retrieves all `Category` records and shows the form.
3. User fills the form and uploads an image.
4. `MobController@store` validates the input.
5. Image is stored in `storage/app/public/mobs`.
6. Final data is saved to the `mobs` table.
7. User is redirected to index with a success message.

## READ Flow
1. **Index**: `MobController@index` applies search/filter logic and paginates results.
2. **Show**: `MobController@show` displays specific mob data based on ID.

## UPDATE Flow
1. User clicks "Edit" (Auth required).
2. `MobController@edit` loads existing mob data and all categories.
3. User modifies data or uploads a new image.
4. `MobController@update` validates data.
5. If a new image is uploaded, the old image is deleted from storage.
6. Record is updated in the database.

## DELETE Flow
1. User clicks "Delete" (Auth required).
2. Confirmation dialog appears.
3. `MobController@destroy` deletes the image from storage.
4. `MobController@destroy` removes the database record.
