# Laravel Test Kit


### Version Compatibility

| Laravel Version | This Package Version               | Branch         |
| ---------------:| ----------------------------------:|---------------:|
| v9              | 2.0                                | master         |  
| v8              | 1.0                                | version/v1.0   |  

See [CHANGE LOG](CHANGELOG.md) for change history.

## Installation

1. Add the repository to your `composer.json`

```
"repositories": [
    {
        "type":"vcs",
        "url":"git@bitbucket.org:elegantmedia/laravel-test-kit.git"
    }
],
```

2. Require the package through the command line

```
composer require emedia/laravel-test-kit --dev
```


## Faker Trait

``` php
use Faker;

// usage
$email = $this->faker()->email;        // defaults to Australia
$email = $this->faker('en_US')->email;
```

## InteractsWithUsers Trait

``` php
use InteractsWithUsers;

// find a user by email
$user = $this->findUserByEmail('someone@somewhere.com');

// find a user with a given role (assumes you have the 'roles' relationship set)
$user = $this->findUserWithoutRole('admin');

// find a user without given (all) roles (assumes you have the 'roles' relationship set)
$user = $this->findUserWithoutRoles('admin', 'super-admin');
$user = $this->findUserWithoutRoles(['admin', 'super-admin']);

// change the default user model
$this->setUserClass('App\Models\User');

// get the resolved user model
$model = $this->getUserModel();
```


## Testing Emails

In your `phpunit.xml` add the mail driver.
```
<env name="MAIL_DRIVER" value="log" />
```

``` php
use MailTracking;

public function testAdminCanLogin(): void
{
    // do something that triggers an email

    $this->seeEmailWasSent()
         ->seeEmailSubject('Subject Line')
         ->seeEmailTo('john@example.com')
         ->seeEmailContains('Test Message');
}	
```



## How to Test on a Separate Database

If you want to run testing on a separate database, create a separate one in `database.php`

``` php
    'mysql_testing' => [
        'driver'    => 'mysql',
        'host'      => env('DB_TESTING_HOST', config('database.connections.mysql.host')),
        'database'  => env('DB_TESTING_DATABASE', config('database.connections.mysql.database')),
        'username'  => env('DB_TESTING_USERNAME', 'forge'),
        'password'  => env('DB_TESTING_PASSWORD', ''),
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
        'strict'    => false,
    ],
```

Add the new vars in `.env`
```
DB_TESTING_DATABASE=mydb_test
DB_TESTING_USERNAME=secret
DB_TESTING_PASSWORD=secret
```

In your `phpunit.xml` add the new DB connection.
```
<env name="DB_CONNECTION" value="mysql_testing" />
```


## Copyright

Copyright (c) Elegant Media.
