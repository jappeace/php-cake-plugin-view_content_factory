# View Content Factory

This is a small cakephp plugin that can handle content management for you.

It generates online forms based upon the templates it scans. you enter a name of a page, fil in the form
And the page is created.
You can customize the templates by using html/css/php just as you would using a regular view.

Which variables are filled by the script is decided by a small metascript. (see below).

## Feautures

+ Form generation based upon (metadata) in your view(s)
+ Regular content fields.
+ Structures, usage in menus for example. They will be returned as an array.
+ dynamic structures, you can grow and reduce the size of the form.
+ simple database, no tables describing tables.

## Intended features

+ Reuse content on different sheets (pages).
+ The content table used as a structure value, allows more data in structure value (now its maxed at 255).
+ Configureable filter for input.
+ Caching... Or do at least some benchmarking.

## Not suported

+ Users, this is up to your app. It only says that the views are public no more no less.
+ Plugins or modules, you can use CakePHP for this.
+ A massive template engine with 100+ features.

## Meta script

The meta script is parsed by the template model. It will parse evrything inside the View/Template
directory inside the plugin and the root app. As long as the files end with .ctp

### Open tags

The parsing starts with the 

    @# 

charsequense and ends with 

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

Contriubtions are welcome. Do a pull request and I will reply as soon as possible.

## Design philosophy

I designed this system based upon a need not a desire, keeping the overal code base pretty small.
I also used as much of CakePHP's power as possible. If you spot a way to do somthing more efficient, do a pull request
or just mail me.

