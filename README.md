# M_C

## Prerequisites/Requirements

- PHP 7.2 or greater



## Installation

Installation is possible using Composer

```
git clone git@github.com:am2umbrele/M_C.git
```


## Usage

Change the App\Config\DB parameters

```php
private $host = '';
private $user = '';
private $pass = '';
private $name = '';
```

Add the tables to your database

```
CREATE TABLE `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1

CREATE TABLE `patient_metrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `unique_id` varchar(50) NOT NULL DEFAULT left(md5(current_timestamp()),6),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id` (`unique_id`,`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1
```

## Request body handling

Raw json requests are handled for POST and PATCH methods

**Ex: POST /patients**

```
{
 "name": "Derek Malek"
}
```

## Run tests

```
./vendor/bin/phpunit tests
```


