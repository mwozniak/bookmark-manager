
# Bookmark Manager

To see it in action, go to http://bm.webapps.bz

## Description

BM is a bookmark manager which helps user organize and manage bookmarks and notes  in a simple and effective way. Every bookmark can be be categorized in selected category and be tagged.
It is possible to explore bookmarks by browsing tags or categories. Every tag or category can be edited or deleted. There is also the possibility to search for bookmarks. The app is fully configurable, options can be set in a config file.
 

## Technologies

BM is based on PHP F3 Framework using MVC (Model View Controller) model. Fat Free Framework is a powerfull, lightweight, easy-to-use, and extreamly fast PHP micro-framework. F3 contains a high-performance URL routing and cache engine. Bookmarks are stored in a MySQL database. JQuery and Bootstrap 3  as a frontend framework are used also in this project.


## Requirements

* PHP 5.3
* MySQL
* F3 Framework (https://github.com/bcosca/fatfree)
* JQuery
* Bootstrap 3
* Font Awesome



## FEATURES

* every bookmark, tag or category can be created, edited&saved or deleted
* pagination of bookmarks, tags and categories
* search by any string
* responsive design
* content is retrived form https://github.com/dypsilon/frontend-dev-bookmarks 



## Routes

 
 ```
[routes]

GET /=ItemController->page
GET /c=CategoryController->index
GET /t=TagController->page
GET /c/@tok/@name=CategoryController->catpage
GET /t/@tok/@name=TagController->tagpage
GET /i/@tok/@name=ItemController->item
GET /i/page/@number=ItemController->page
GET /t/page/@number=TagController->page
GET /t/@tok/page/@number=TagController->tagpage
GET /c/@tok/page/@number=CategoryController->catpage
GET|POST /i/create=ItemController->create
GET /i/update/@tok=ItemController->update
POST /i/update=ItemController->update
GET /i/delete/@tok=ItemController->delete
GET|POST /c/create=CategoryController->create
GET /c/update/@tok=CategoryController->update
POST /c/update=CategoryController->update
GET /c/delete/@tok=CategoryController->delete
GET|POST /t/create=TagController->create
GET /t/update/@tok=TagController->update
POST /t/update=TagController->update
GET /t/delete/@tok=TagController->delete
POST /search=ItemController->search
GET /q/@query=ItemController->query

```


## Directory structure



    app/              -- application directory
      controllers/    -- controllers (main controller and controllers for items, tags and categories)
      models/         -- models
      views/          -- views, main templates (layouts, menus, breadcrumbs)
        tags/         -- templates for tags
        items/        -- templates for bookmarks
        cats/         -- templates for categories
    config/           -- configuration files
    db/               -- database structure
    inc/              -- functions and classes
    lib/              -- f3 framework directory
    tmp/              -- temp directory
    ui/               -- user interface
      css/            -- css files
      fonts/          -- font directory
      img/            -- images
      js/             -- javascript files



## Database structure

 ```sql

CREATE TABLE IF NOT EXISTS `cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tok` int(11) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `node` int(11) DEFAULT NULL,
  `dsc` text,
  `badge` int(5) DEFAULT NULL,
  `badgecolor` int(5) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tok` int(11) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `user` int(8) DEFAULT NULL,
  `note` text,
  `type` int(5) DEFAULT NULL,
  `cid` int(11) NOT NULL,
  `sticked` tinyint(1) DEFAULT NULL,
  `badge` varchar(255) NOT NULL DEFAULT '',
  `badgecolor` varchar(6) NOT NULL DEFAULT '',
  `screen` tinyint(1) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `tag2item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tok` int(11) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `dsc` text,
  `node` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

 ```



 ```sql

CREATE VIEW catgroup AS
    SELECT
        cats.name AS cat,
        cats.tok AS tok,
        cats.url AS url,
        count(*) AS itemcount
    FROM cats
    JOIN items ON
        items.cid=cats.tok
    GROUP BY
	cats.name
    ORDER BY itemcount DESC;
 

CREATE VIEW taggroup AS
    SELECT
        tags.label AS tag,
        tags.tok AS tok,
        tags.url AS url,
        count(*) AS tagcount
    FROM tags
    JOIN tag2item ON
        tag2item.tid = tags.id
    GROUP BY
	tags.label
    ORDER BY tagcount DESC;
 

CREATE VIEW itemstag AS
    SELECT
        items.title AS title,
        items.note AS note,
        items.tok AS tok,
        items.url AS url,
	tag2item.tid AS tagid,
	tags.tok AS tagtok,
	tags.label AS label
    FROM items
    JOIN tag2item ON
        items.id=tag2item.iid
    JOIN tags ON
        tags.id=tag2item.tid;


CREATE VIEW taglist AS
    SELECT
        tags.label,
	tags.tok, 
	tags.url, 
	items.tok as itok,
	items.cid as ctok
    FROM tags
    JOIN tag2item ON 
	tag2item.tid = tags.id
    JOIN items ON 
	tag2item.iid = items.id
    ORDER BY items.tok;

 ```



## Configuration

Configuration is in `config/config.ini` file


| Variable     | Value       | Description                             |
| ------------ |:-----------:| ----------------------------------------|
| db_name      | dbname      |   name of database                      |
| db_user      | user_name   |   user                                  |
| db_pass      | secret      |   password                              |
| itemlimit    | 12          |   number of bookmarks on main page      |
| onetaglimit  | 12          |   number of bookmarks on tag page       |
| alltagslimit | 30          |   number of tags page with all tags     |
| catlimit     | 12          |   number of bookmarks on category page  |
| searchlimit  | 20          |   number of bookmarks in search results |



 


##TODO

* import bookmarks (ie from browsers)
* export to html/csv format

 


 
## Licence

MIT Licence.

(c) Michal Wozniak

>The MIT Licence {{{
>
>Permission is hereby granted, free of charge, to any person obtaining a copy
>of this software and associated documentation files (the "Software"), to deal
>in the Software without restriction, including without limitation the rights
>to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
>copies of the Software, and to permit persons to whom the Software is
>furnished to do so, subject to the following conditions:
>
>The above copyright notice and this permission notice shall be included in
>all copies or substantial portions of the Software.
>
>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
>IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
>FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
>AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
>LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
>OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
>THE SOFTWARE.
>
>}}}

