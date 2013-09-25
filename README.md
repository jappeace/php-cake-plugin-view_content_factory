View Content Factory
====================================

A light plugin for cakephp that allows contentmanagement by parsing existing view files and creating a content insertion forms for the parsed views.
The content is returned in variables. 

It can also can contain structures. IE big multidimensional arrays.
A directory for scanning can be specified.

The form for content insertion is based upon the views scanned. In the views some meta data has to be added on how the structures should look like and in which variable the data should be stored.
I chose this setup because its faster then the database (filesystem always wins), and because I found out that regular users don't do complex things like making their own page structures. 
