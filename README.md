# ThreadedComments

[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)


A polymorphic threaded comment Laravel Service provider with visibility control.

An answer to capturing and summarizing arbitrarily nested comments. Rather than nested set or SQL recursion, all comments contain a root reference(e.g. original Post) along with the standard morphMany relation so it is very efficient to load or summarise an entire reply tree.  e.g.

Comment::topRepliesFor([Issue::class, Post::class], strtotime('-1 week'), 'count', 5)

answers an array of up to five objects representing Issue/Post with the greatest number of comments. Objects contain the id and model of all Issues & Posts along with a count of all comments on each and the time of the latest comment within one week.  Visibility e.g. this user does not have access to Post 13321 may be added.  The result of one simple SQL query (and another per model to obtain titles of the Issues & Posts) could be displayed in a hot topics widget. This performs well with ten thousand post/issues and one hundred thousand comments(< one sec), a daunting challenge for alternatives.

alternatives:
[SQL recursion](https://learnsql.com/blog/articles-about-sql-recursive-queries)
[Nested Set](http://en.wikipedia.org/wiki/Nested_set_model)

# Table of Contents

* [Requirements](#requirements)
* [Getting Started](#getting-started)
* [Usage](#usage)
* [Change Logs](#change-logs)
* [Contribution Guidelines](#contribution-guidelines)

# <a name="requirements"></a>Requirements

* Laravel 7 or later.

# <a name="getting-started"></a>Getting Started

1. Require the package with [Composer](https://getcomposer.org).
    ```shell
    $ composer require bpmason/threadedComments
    ```

2. Add the package to your application service providers in `config/app.php`.
    ```php
    'providers' => [

        Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        ...
        BMason\threadedComments\ServiceProvider::class,

    ],
    ```

3. Publish the package\'s migrations to your application and migrate.
    ```shell
    $ php artisan vendor:publish --provider="bpmason/threadedComments" --tag="migrations"
    $ php artisan migrate
    ```

# <a name="usage"></a>Usage

```php
use BMason\ThreadedComments\Traits\ThreadedComments
```
in a model to define instance methods for comments and threadedComments - see the trait for more information

```php
BMason\ThreadedComments\Models\Comment::topRepliesFor($root_type='all',
   $since=null, $orderBy='count', $limit=10, $exclude=null)
```
for a summary - see the Comment model for more information  

say the logged in user does not have access to Post 5 nor 1117
```php
BMason\ThreadedComments\Models\Comment::topRepliesFor('all', null, 'count', 10,
    [['App\Models\Post', [5,1117]]])
```
will provide a summary ignoring the two posts

# <a name="change-logs"></a>Change Logs

first release

# <a name="contribution-guidelines"></a>Contribution Guidelines

Support follows PSR-12 PHP coding standards, and semantic versioning.

Please report any issue you find in the issues page.
Pull requests are welcome.
