SubmittableAPIImageGallery
==========================

This allows images from your Submittable.com account to display in a thumbnail gallery.
Code for this project is PHP & HTML.

Requirements:

You must have PHP with JSON and GD Library support enabled for this code to run.

The output is designed to be generic-looking so as to fit within most website designs.

There are two files: images.php and thumbnail.php.

Both must be in the same directory.

Embed the images.php file into the page you want to display the gallery on with a simple PHP include statement:
<?php include('images.php'); ?>

The page the include statement is on must also have a .php extension, unless your webserver is set up to execute PHP
code from non-PHP pages.
