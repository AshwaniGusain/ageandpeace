# AGE & PEACE

- staging.ageandpeace.com
- ageandpeace.com

## Deployments
- Deployed through GitLab using phploy

## Hosting
- SNAP admin is found at `/snap`
- Has Let's Encrypt setup for https
- Hosted on AWS

### AWS
- 2 EC2 Instances using AWS Linux
- RDB instances
- Has Elastic IP
- Uses AWS SES for Email

## JS & CSS
- Asset files are located under resources/assets folder (normal Laravel setup)
- Asset compilation is done using Laravel mix (npm run watch and npm run dev)

## Cache
- The header is cached due to the number of queries used in it. ANY CHANGES TO THE HEADER MUST CLEAR THE CACHE!!!
`
php artisan cache:clear
`


# SNAP

SNAP provides tools for creating admin interfaces. The SNAP code packages live under `snap` directory. 
Additional application specific code is placed in the app/Admin folder. 
Configuration files can be found in the `config/snap` folder.

1. Run via command line `composer install`
2. Run via command line `npm install`
3. Create your database and add credentials to the `.env` file
4. Run via command line `php artisan migrate:fresh --seed`
5. Run via command line `php artisan storage:link`
6. Make storage folder writable by PHP
7. Setup a your web server to point to the `public` folder
8. Browse to http://mydomain.local/admin (where mydomain.local is the domain you used to setup):
```
uid: admin@example.com
pwd: qwe123
``` 

Additional documentation may be found in a packages `docs` folder found under `snap/{package}/docs`. 
The `docs` module can be enabled in the `config/snap/admin.php` file under th `modules` section by adding the 
class `\Snap\Docs\Modules\DocsModule::class`.

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell):

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[British Software Development](https://www.britishsoftware.co)**
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Pulse Storm](http://www.pulsestorm.net/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
