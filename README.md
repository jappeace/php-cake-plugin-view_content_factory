# UPDATE

I no longer maintain this project in favour of other frameworks (ruby on rails, for easy programming or angular js because its cheaper in resources).
This means that this project is Dead. So only use this to get snippsets of code or if you want to take the burden of maintenance on your shoulders. But you'l have to fork it.
When I started this project I thought cakephp was the way to go in terms of web. Well it is if you are an weekend programmer. But for somoene
who likes to think of himself as a professional its just wrong.

## Things I would like have seen implemented but are not

* Cross refrencing of exising data.
* change the meta scriptinglanguage to JSON

# View Content Factory

This is a small cakephp plugin that can handle content management for you.

It generates online forms based upon the templates it scans. you enter a name of a page, fill in the form
And the page is created.
You can customize the templates by using html/css/php just as you would using a regular view.

Which variables are filled by the script is decided by a small metascript. (see below).

## Install

### Source

Clone, submodule add or unzip into Plugin/ViewContentFactory.

### Database

Make sure you have access to an database and have the connection configured. This plugin relies
on a default db configuration. If it should use a different one, configure it in the plugin appModel.

#### The dummy source

Put this in your databaseconfig

    public $dummy = array(
	'datasource' => 'ViewContentFactory.DummySource'
    );

#### The other tables

execute Plugin/ViewContentFactory/Config/Schema/structure.sql onto your database.

### plugin loading

Add this to your bootsrap.php

    CakePlugin::loadAll(array(
	'ViewContentFactory' => array('bootstrap' => true, 'routes' => true)
    ));

### Authentication

Choose either the safe way or the workaround, but not both. (or your own authentication scheme).

#### The safe way

Configure you auth component in your appcontroller:

    public $components = array(
        'Auth' => array(
            'loginAction' => array(
		'plugin' => false,
                'controller' => 'people',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email'),
                    'userModel' => 'Person'
                )
            ),
            'authorize' => 'Controller'
        )
    );

#### The workaround

Or delete the following lines from Plugin/ViewContentFactory/Controller/SheetsController.php

    public function beforeFilter() {

	/**
	 * Only allows view actions open for public use. Don't forget to define your own authcomponent
	 * in the your appcontroller class
	 */
        parent::beforeFilter();
        $this->Auth->allow('view');
    }

### dynamic pages support

To be able to use growing forms you need to include jquery. Otherwise the buttons simply won't work.
There is a lot of documentation about this on cakephp.

### Create your pages

Goto www.youresite.com/view_content_factory/sheets/ where the generated index will show you your
options.

### View pages

To view a page goto www.yoursite.com/view/pagename

## Features

+ Form generation based upon (metadata) in your view(s)
+ Regular content fields.
+ Structures, usage in menus for example. They will be returned as an array.
+ dynamic structures, you can grow and reduce the size of the form.
+ simple database, no tables describing tables.

## Intended features

+ Reuse content on different sheets (pages).
+ Use content as a structure value, allows more data in structure value (now its maxed at 255 chars).
+ Configurable filter for input.
+ Caching... Or do at least some benchmarking.

## Not supported

+ Users, this is up to your app. It only says that the views are public no more no less.
+ Plugins or modules, you can use CakePHP for this.
+ A massive template engine with 100+ features.

## Meta script

The meta script is parsed by the template model. It will parse everything inside the View/Template
directory inside the plugin and the root app. As long as the files end with .ctp

### Open tags

The parsing starts with the 

    @# 

char sequence and ends with 

    #@ 

inside the views.

### defining a content variable

To define a content simply open parsing insert the var label and close it with the content marker.
For example the following code will create a article variable in your view filled with the content:

    @#article !content!#@

Contents are quite easy.

### defining a structure or array

This is more complex and best shown as an example:

      @#nav(
        title,
        url 
     )  !struct! #@

this will create a variable $nav with a title and url key. ie $nav['title'] and $nav['url]. Note you
can only fill in one title and one url here. That was not good enough to make a dynamic menu so
I added another feature:

### defining a anonymous structure

this example can grow:

    @#nav[](
	  title,
	  url 
       )  !struct! #@

so in php you will read it as: 

    $nav[0]['title'] $nav[0]['url']
    $nav[1]['title'] $nav[1]['url']

etc etc.

### misc
In structures you can also add options trough:

    @#nav[](
	  title:awesome|lame|themiddle,
	  url 
       )  !struct! #@

But I will might change this syntax later on.

recursive structures are also allowed:

    @#nav[](
	  name(
		prefix:awesome|lame|themiddle,
		title 
	  ),
	  url 
       )  !struct! #@

so is the anonymous form:

    @#nav[](
	  name[](
		prefix:awesome|lame|themiddle,
		title 
	  ),
	  url 
       )  !struct! #@

you can go as deep as you want. Thanks to the tree behavior for that.

## A note on speed.

I've noticed in my testing that the saving and editing of pages takes by far the most time. Rendering
is quite fast, since the tree behavior then does not has to 'think' a lot. Which in my opinion is good
for a cms.

## Contributions

Contributions are welcome. Do a pull request and I will reply as soon as possible.

## Design philosophy

I designed this system based upon a need not a desire, keeping the overall code base pretty small.
I also used as much of CakePHP's power as possible. If you spot a way to do something more efficient, do a pull request
or just mail me.

