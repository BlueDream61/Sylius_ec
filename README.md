<p align="center">
    <a href="http://sylius.org" target="_blank">
        <img src="http://demo.sylius.org/assets/shop/img/logo.png" />
    </a>
</p>
<p align="center">
    <a href="https://packagist.org/packages/sylius/sylius">
        <img src="https://img.shields.io/packagist/l/Sylius/Sylius.svg" alt="License" />
    </a>
    <a href="https://packagist.org/packages/sylius/sylius">
        <img src="https://img.shields.io/packagist/v/Sylius/Sylius.svg" alt="Version" />
    </a>
    <a href="http://travis-ci.org/Sylius/Sylius">
        <img src="https://img.shields.io/travis/Sylius/Sylius/master.svg" alt="Build status" />
    </a>
    <a href="https://scrutinizer-ci.com/g/Sylius/Sylius/">
        <img src="https://img.shields.io/scrutinizer/g/Sylius/Sylius.svg" alt="Scrutinizer" />
    </a>
    <a href="https://packagist.org/packages/sylius/sylius">
        <img src="https://poser.pugx.org/sylius/sylius/downloads" alt="Total Downloads" />
    </a>
</p>

Sylius is the first eCommerce framework for tailored solution based on [**Symfony**](http://symfony.com) and [**Doctrine**](http://doctrine-project.org). 

The highest quality of code, strong testing culture, built-in Agile (BDD) workflow and exceptional flexibility make it the best solution for application tailored to your business requirements. 
Powerful REST API allows for easy integrations and creating unique customer experience on any device.

We're using full-stack Behavior-Driven-Development, with [phpspec](http://phpspec.net) and [Behat](http://behat.org).

Enjoy being an eCommerce Developer again!

Documentation
-------------

Documentation is available at [docs.sylius.org](http://docs.sylius.org).

Installation
------------

You need [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) to install PHP packages and [yarn](https://yarnpkg.com/lang/en/docs/install/) to install JS packages.

```bash
$ composer create-project sylius/sylius-standard my-sylius-shop && cd my-sylius-shop
$ php bin/console sylius:install
$ yarn install && yarn run gulp
$ php bin/console server:start
```

Then open `http://localhost:8000/` in your web browser to enjoy your Sylius shop in a development environment.

Alternatively, you can use [Vagrant](http://docs.sylius.org/en/latest/book/installation/vagrant_installation.html) for your initial setup.

**Production**

When you're ready to go live, setup your production database:

```bash
php bin/console sylius:install --env prod
```

And choose _N_ when it comes to the _Loading sample data_ step.

Then please refer to [Symfony's documentation](https://symfony.com/doc/current/setup/web_server_configuration.html) to properly setup your Apache/Nginx web server.


Troubleshooting
---------------

If something goes wrong, errors & exceptions are logged at the application level:

```bash
$ tail -f var/logs/prod.log
$ tail -f var/logs/dev.log
```

If you are using the supplied Vagrant development environment, please see the related [Troubleshooting guide](http://github.com/Sylius/Vagrant/README.md#Troubleshooting) for more information.

Contributing
------------

Would like to help us and build the most developer-friendly eCommerce platform? Start from reading our [Contributing Guide](http://docs.sylius.org/en/latest/contributing/index.html)!

Stay Updated
------------

If you want to keep up with the updates, [follow the official Sylius account on Twitter](http://twitter.com/Sylius) and [like us on Facebook](https://www.facebook.com/SyliusEcommerce/).

Bug Tracking
------------

If you want to report a bug or suggest an idea, please use [GitHub issues](https://github.com/Sylius/Sylius/issues).

Community Support
-----------------

Have a question? Join our [Slack](http://sylius.org/slack) or post it on [StackOverflow](http://stackoverflow.com) tagged with ["sylius"](https://stackoverflow.com/questions/tagged/sylius). You can also join our [group on Facebook](https://www.facebook.com/groups/sylius/)!

MIT License
-----------

Sylius is completely free and released under the [MIT License](https://github.com/Sylius/Sylius/blob/master/LICENSE).

Authors
-------

Sylius was originally created by [Paweł Jędrzejewski](http://pjedrzejewski.com).
See the list of [contributors from our awesome community](https://github.com/Sylius/Sylius/contributors).
