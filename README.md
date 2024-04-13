# Laravel Blade Component Template

This repository template makes it easy to create a new Laravel Blade Component als an installable Composer package, ready to use in any Laravel project.

Click the green button above "Use this template" to make a new repository.

## Structure

### Javascript

In `resources/js` you can add your source Javascript files.

Because we want to make these Components headless, we need to include every Javascript it needs inside the views or add instructions on which Javascript files needs to be added to the asset compilation step inside their existing project.

### CSS

In `resources/css` you can add your source CSS files.

### Views

In `resources/views` you can add your Blade views.

### Public

In `public` folder, you can add files that needs to be published in the Laravel app when the package is installed.
