# About This Project

This is a demo project that create a Forum with Laravel, Vue.js and TDD methedology. Originally, it was created by Jeffry Way in his laracasts lessons [Let's Build A Forum with Laravel and TDD](https://laracasts.com/series/lets-build-a-forum-with-laravel).   
But if you follow his instructions through the lesson videos, many pieces won't work if you use the latest version of laravel(7.x) or some modules.   
This project was created base on Jeffry's work, but did all necessary changes to make it work with new version of laravel and related modules.

## My Development Environment/Tools
- **OS**: Windows 10
- **Web server**: Apache v2.4.41
- **Database**: MySQL v8.0.18
- **Editor**: Visual Studio Code v1.42.0
- **Programming language**: [PHP v7.4.0](https://www.php.net/downloads.php)
- **[Wampserver64 v3.2.0](http://www.wampserver.com/en/download-wampserver-64bits/)**. My computer is a HP laptop running Windows 10, so I choose Wampserver which can easily install Apache, MySQL, and start/stop them. To make this project run, you need to create a virtual host for *c:/wamp64/www/forum/public*
- **[Laravel Framework v6.15.1](https://laravel.com/docs/6.x)**. 
To install Laravel, you need to install [Composer](https://getcomposer.org/download/)(v1.9.3) first. Then run below command:
```
    > composer global require laravel/installer
```
- **Javascript package manager**: [npm v6.13.4](https://www.npmjs.com/get-npm). It is used to install dependencies (node_modules) required by laravel, Vue and CSS. npm is distributed with Node.js - which means that when you download Node.js, you automatically get npm installed on your computer.
- **JavaScript framework**: [Vue.js 2.5.17](https://vuejs.org/v2/guide/installation.html#NPM)
- **Utility-first CSS framework**: [tailwindcss 1.2.0](https://tailwindcss.com/docs/installation).

## Before reproduce this project on your computer
1. Since git won't add *node_modules* and *vendors* folders into repository, after you clone this project, you need to run **npm install** to install *node_modules* and **composer install** to install *vendor* dependencies. 
2. The *.env* file is also not included in this repository. You can change *.env.example* to *.env* and make necessary changes according to the environment in your computer.  

GOOD LUCK!