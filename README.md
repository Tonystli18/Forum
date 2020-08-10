# About This Project

This is a demo project that created by following Jeffry Way's laracasts lessons [Let's Build A Forum with Laravel and TDD](https://laracasts.com/series/lets-build-a-forum-with-laravel). It is built up on Laravel framework, Vue.js and using TDD methedology.    
However, if you go through all his 102 episodes, you'll stuck at many places because his solution was based on Laravel 5.5 and some old version 3rd party modules.   
This project was built on new Laravel 7 and latest version of 3rd party modules like Vue.js, Algolia, Trix etc. Please check *composer.json* and *package.json* files under root directory for more information about the dependencies of this project. 

## Development Environment/Tools
- **OS**: Windows 10
- **Web server**: Apache v2.4.41
- **Database**: MySQL v8.0.18
- **Editor**: Visual Studio Code v1.42.0
- **Programming language**: [PHP v7.4.0](https://www.php.net/downloads.php)
- **[Wampserver64 v3.2.0](http://www.wampserver.com/en/download-wampserver-64bits/)**. I'm using a HP laptop running Windows 10(much cheaper than MacBook, isn't it?), so I choose Wampserver which can easily install Apache, MySQL, setup/manage the local development environment. To make this project run, you might need to create a virtual host for *c:/wamp64/www/forum/public*. No worry, it is easy to use Wampserver.
- **[Laravel Framework v7.15.0](https://laravel.com/docs/6.x)**. 
To install Laravel, [Composer](https://getcomposer.org/download/)(v1.9.3) should be installed first. Then run below command:
```
    > composer global require laravel/installer
```
- **Javascript package manager**: [npm v6.13.4](https://www.npmjs.com/get-npm). It is used to install dependencies (node_modules) required by laravel framework, Vue and CSS. npm is distributed with Node.js - which means if you download Node.js, you automatically get npm installed on your computer.
- **JavaScript framework**: [Vue.js 2.5.17](https://vuejs.org/v2/guide/installation.html#NPM)
- **Utility-first CSS framework**: [tailwindcss 1.2.0](https://tailwindcss.com/docs/installation).

## Before reproduce this project on your computer
1. Since git won't add *node_modules* and *vendors* folders into repository, after you clone this project, you need to run **npm install** to install *node_modules* and **composer install** to install *vendor* dependencies. 
2. The *.env* file is also not included in this repository. You can change *.env.example* to *.env* and make necessary changes according to the environment on your own computer.  

Have fun!