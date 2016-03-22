# Drupal 8 Base Module

This module contains assorted functionality for Drupal 8. These snippets have been collected from various sources and projects, and are useful as a base for common implementations.

No guarantees can be made of the quality / reliability of any code.

## Installation

Add the module via composer:

```composer require jessicadigital/drupalbase```

Then, visit the Modules page in your Drupal admin panel, and enable the drupalbase module.

## Features

### 1. OlderNewerBlock

This custom block adds a pair of "Older" / "Newer" buttons to the bottom of every node. Content types can be restricted within the admin panel.

To use a custom template, you can create a file at ```/themes/yourtheme/templates/block/block--oldernewerblock.html.twig``` using the ```content.older``` and ```content.newer``` variables to access page URLs, e.g.

``` twig
{% if content.older is not empty %}
    <a class="btn btn-primary" href="{{ content.older }}">Older article</a>
{% endif %}
{% if content.newer is not empty %}
    <a class="btn btn-primary pull-right" href="{{ content.newer }}">Newer article</a>
{% endif %}
```

Note that the block will need to be assigned to a region - normally the bottom of the Content region.

### 2. Query debugging

Drupal 8 does not come with a built in method for debugging EntityFieldQueries. By adding the tag ```debugthis``` to your query, the raw SQL will be dumped into the ```/tmp/drupal_debug.txt``` file.

### 3. Sitemap

Automatically generates a sitemap of live Pages and Articles at the URL: ```/sitemap```.