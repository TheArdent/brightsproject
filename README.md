BrightsProject

Installation

1.Edit DB config at classes/DB.php

2.Create schema `webstat` run sql script or use dump.sql

``CREATE TABLE `webstat` (
    `id` int(11) NOT NULL,
    `url` varchar(256) NOT NULL,
    `code` int(11) DEFAULT NULL,
    `title` varchar(256) DEFAULT NULL,
    `time` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
``

3.If use nginx server rewrite all request to index.php
