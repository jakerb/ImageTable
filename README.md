![ImageTable in Mac Mail](https://i.ibb.co/C0cvB8v/Screenshot-2020-10-16-at-22-27-51.png "ImageTable in Mac Mail")

# ImageTable
Convert images to HTML tables using GD, great for email templates.

<a href="https://codepen.io/jakebown/pen/eYzZyaN">![Jake](https://i.ibb.co/YZ3gKQD/sample2.jpg "See it in action")</a>
<p><small>Click the image above to <a href="https://codepen.io/jakebown/pen/eYzZyaN">view the demo</a>.</small></p>

This is an experimental project created to try and overcome the issue of loading images into email templates by replacing them using a table. This project works great with small icons and graphics, it's not recommended for larger images.

## Requirements
- PHP 5+
- GD Library

## Filesize
Using the thumbnail of ~~Brad Pitt~~ me above which is 6KB as a JPEG image is turned into a 271KB HTML file once rendered. The size difference is substantial and ImageTable has been optimized to output the smallest filesize based on tests. If you can contribute a more efficient output then please PR!

## How to Use
This simple class can turn any PNG, JPG or JPEG into a valid HTML table. Heres an example of how to use ImageTable.

```php
<?php 

require './ImageTable.php';

$image_file = 'image.png';

$it = new jakerb\ImageTable($image_file);

?>
```

### Output HTML to browser

```php

$it->renderTable();

```

### Output HTML to new file

```php

$it->renderTable('image-table.html');

```

That's it! Star this project if you've found it useful!
