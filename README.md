qDNAtool
========

[![Build Status](https://travis-ci.org/nlware/qdnatool.png?branch=master)](https://travis-ci.org/nlware/qdnatool) [![Coverage Status](https://coveralls.io/repos/nlware/qdnatool/badge.png?branch=master)](https://coveralls.io/r/nlware/qdnatool?branch=master)

[qDNAtool](https://www.qdnatool.org)


### Requirements

- PHP (5.2.8 or greater)
- MySQL (4 or greater)
- R (version 2.14.1)

### Installation

Clone this project:

	git clone https://github.com/nlware/qdnatool.git

Run this in your terminal to get the latest Composer version:

	curl -sS https://getcomposer.org/installer | php

The next thing you should do is install the dependencies by executing the following command:

    php composer.phar install

Build the database schema:

	Console/cake schema create

