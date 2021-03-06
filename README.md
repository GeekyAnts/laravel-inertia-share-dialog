# **Sharedo**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/geekyants/sharedo.svg?style=flat-square)](https://packagist.org/packages/geekyants/sharedo)
[![License](https://img.shields.io/packagist/l/geekyants/sharedo)](https://github.com/GeekyAnts/laravel-inertia-sharedo/blob/master/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/geekyants/sharedo.svg?style=flat-square)](https://packagist.org/packages/geekyants/sharedo)

Share Popup for Laravel Apps

![Sharedo](./public/images/sharedo.gif)

## 1) Introduction

**Sharedo** provides you an interface to manage roles and permissions for Laravel apps. You can assign read or write permissions to a user and remove the permissions as required. It is powered by Inertia.js and Laravel Bouncer.

## 2) Motivation

We realised that it is very time consuming to add a share functionality to Laravel projects and this can hinder the development process.  
This package aims to solve this problem by enabling you to share your project's entities with other users easily.

## 3) Dependencies

If you have [Tailwind](https://tailwindcss.com/) and [Bouncer](https://github.com/JosephSilber/bouncer) pre-installed, you can move on to the [Installation](#4-installation) section.

### **Tailwind Installation**

Install Tailwind as shown below:

```jsx
npm install -D tailwindcss@latest postcss@latest autoprefixer@latest
```

Next, generate your tailwind.config.js file:

```jsx
npx tailwindcss init
```

In your **tailwind.config.js** file, configure the **purge** option with the paths to all of your Blade templates and JavaScript components so Tailwind can tree-shake unused styles in production builds:

```jsx
module.exports = {
    purge: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [],
};
```

In your webpack.mix.js, add **tailwindcss** as a PostCSS plugin:

```jsx
mix.js("resources/js/app.js", "public/js").postCss(
    "resources/css/app.css",
    "public/css",
    [require("tailwindcss")]
);
```

Open the `./resources/css/app.css` file and add the following code, replacing the original file contents:

```jsx
/* ./resources/css/app.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

```

Next, import your stylesheet in your main Blade layout (commonly `resources/views/layouts/app.blade.php` or similar) and add the responsive viewport meta tag if it's not already present:

```jsx
 <!doctype html>
  <head>
    <!-- ... --->
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <!-- ... --->
```

### **Bouncer Installation**

Install Bouncer with composer:

```jsx
composer require silber/bouncer v1.0.0-rc.10
```

Add Bouncer's trait to your user model:

```jsx
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Model
{
    use HasRolesAndAbilities;
}
```

Publish Bouncer's migrations to your app's migrations directory by running the following command:

```jsx
php artisan vendor:publish --tag="bouncer.migrations"
```

## 4) Installation

1. Install the Sharedo package using composer as shown below:

    ```jsx
    composer require  geekyants/sharedo
    ```

1. After installation, move the package's config file to your project's config folder:

    ```jsx
    php artisan vendor:publish  --tag="config"
    ```

1. Moving forward, scaffold the view components present in the sharedo package as follows:

    ```jsx
    php artisan ui sharedo
    ```

    A **Sharedo** folder containing Vue.js components will be created in your resources directory. You can now easily customise your Sharedo's Vuejs components 🚀

1. Now, run the migrations. After executing this command, Bouncer migrations and the **new_users_sharedo** table will be migrated:

    ```jsx
    php artisan migrate
    ```

5) To compile and minify the CSS and JavaScript files generated by sharedo, add this to your webpack.mix.js file:

    ```jsx
    .js("resources/js/sharedo.js", "public/js")
    .vue()
    ```

    > Note: If your css is not compiled in your app.css file, you can change it in the sharedo.blade.php file.

6) Install the dependencies:

    ```jsx
    composer install
    npm install
    ```

7) Finally, build your assets as shown below:

    ```jsx
    npm run dev
    ```

## 5) Usage

You must define a relation **user** on the entity model that you want to share. The relation should return the user of that entity.

To share your entity with other users, visit:

```jsx
{APP_URL}/sharedo/{entity_name}/{entity_id}
```

For example, if you want to open Sharedo for a project model with id 123, then visit:

```
{APP_URL}/sharedo/projects/123
```

> Note: **The entity_name should have the same name as that of the database migration corresponding to the model that you want to share.**

> Note: **Sharedo sends error messages back to your application in the error props.**

If you invite a user who is not present in your database, Sharedo automatically creates it in your users table. Also, a new entry is inserted into the **new_users_sharedo table** referencing the user's id as a foreign key and **has_ever_logged_in** property is set to false.  
This can help you differentiate between the users created by Sharedo and users created by the usual sign-up flow.

To restrict other users from accessing your entities, you have to explicitly use the Bouncer methods [here](https://github.com/JosephSilber/bouncer#cheat-sheet).

> Note: **Uses who are given write access to the entity can also share that entity with other users.**

## 6) Customisation

You can customise the functionality of Sharedo easily by making changes in the sharedo.php file present in your config folder.

You can customise the functionality of Sharedo easily by making changes to the **sharedo.php** file present in your config folder.

1. If your files are not present in the `App\Models\\`, then change the modelPath to that location instead of `App\Models\\`.

    ```jsx
    "modelPath" => "App\Models\\"
    ```

2. To add your own custom middleware to Sharedo, append it to the middleware array.  
   For example, if you want to add the **"admin"** middleware, then your middleware array will look like this:

    ```jsx
    "middleware" => ['web', 'auth','admin']
    ```

3. If you want only certain entities to be shareable, you can add them to the  **restrict-entities** array.  
   For instance, if you want only the files entity to be shareable, your array will look like this:

    ```jsx
    'restrict-entities' => ['files'],
    ```

4. You can also send email notifications to the users when they are given access to an entity.  
   Sharedo fires an **UserAbilityChanged** event when a user's access is changed and attaches the **SendUserAbilityChangedNotification** listener to it. If you want to send an email notification, make the following changes in EventServiceProvider:

    ```php
    use Geekyants\Sharedo\Events\UserAbilityChanged;
    use Geekyants\Sharedo\Listeners\SendUserAbilityChangedNotification;
    protected $listen = [
     ...
          UserAbilityChanged::class => [
            SendUserAbilityChangedNotification::class,
        ]

    ];
    ```

    You can also modify the email template by publishing the Sharedo mail resources:

    ```php
    php artisan vendor:publish  --tag="mail"
    ```

    > After running this command, the mail notification template will be located in the `resources/vendor/sharedo/mail` directory:

    You can attach your own listeners to the event. For example, if you want to attach a **SendSlackNotification** listener to the **UserAbilityChanged** event, execute the following:

    ```php
    use Geekyants\Sharedo\Events\UserAbilityChanged;

    ...

    protected $listen = [
            UserAbilityChanged::class => [
                SendSlackNotification::class,
            ]
        ];
    ```

5. To provide an option that allows searching for users, you can create a class that implements **UserContactsInterface**.php from the package and define the **getUserContacts** function.

    The return type of the **getUserContacts** function should be a string containing a JSON representation of an array of objects. Each object denoting a user must have an **"email"** attribute as shown below:

    ```php
    "[
      { email: '', ... },
      { email: '', ... },
      { email: '', ... }
    ]"
    ```

    For example, you can create a class called **SendUserContacts** which implements **UserContactsInterface** and performs an operation to get users in the **getUserContacts** function:

    ```php
    <?php

    namespace App\Repository;

    use App\Models\User;
    use Geekyants\Sharedo\Interfaces\UserContactsInerface;

    class SendUserContacts implements UserContactsInerface
    {

        public function getUserContacts($query)
        {
            $users = Some operation to get users
            $users = json_encode($users); //convert users array to json string
            return $users;
        }
    }
    ```

    In sharedo.config file set the "**typehead"** key to the **SendUserContacts** class path:

    ```php
    "typehead" => "App\Repository\SendUserContacts"
    ```

## 7) Tech Stack

**Laravel**, **Tailwind**, **Bouncer**, **Vue.js**, **Inertia.js**

## 8) Contributors

-   Cyrus Passi ([@Cyrus2505](https://twitter.com/Cyrus2505?s=20))
-   Ila Sahu ([@ilasahu94](https://twitter.com/ilasahu94?lang=en))
-   Sanket Sahu ([@sanketsahu](https://twitter.com/sanketsahu))
-   Gaurav Guha ([@greedy_reader](https://twitter.com/greedy_reader?lang=en))
-   Aditya Jamuar ([@GeekJamuar](https://twitter.com/geekjamuar?lang=en))
-   Madhav Bansal ([@madhavb23](https://twitter.com/madhavb23?lang=en))

## 9) How to Contribute

Thank you for your interest in contributing to Sharedo! We are lucky to have you 🙂 Head over to [Contribution Guidelines](https://github.com/GeekyAnts/laravel-inertia-sharedo/blob/master/CONTRIBUTING.md) and learn how you can be a part of a wonderful, growing community.

For major changes, please open an issue first to discuss changes and update tests as appropriate.

## 10) License

Licensed under the MIT License. Please see the [License File](https://github.com/GeekyAnts/laravel-inertia-sharedo/blob/master/LICENSE.md) for more information.
