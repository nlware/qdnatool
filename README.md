qDNAtool
========

[qDNAtool](https://www.qdnatool.org)


### Requirements

- PHP (5.2.8 or greater)
- MySQL (4 or greater) 

### Installation

Clone this project:

	git clone https://github.com/nlware/qdnatool.git
	
Run this in your terminal to get the latest Composer version:

	curl -sS https://getcomposer.org/installer | php

The next thing you should do is install the dependencies by executing the following command:

    php composer.phar install

Build the database schema:

	Console/cake schema create

