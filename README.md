# ImageTable
Convert images to HTML tables using GD, great for email templates.

<a href="https://codepen.io/jakebown/pen/eYzZyaN">![Jake](https://i.ibb.co/YZ3gKQD/sample2.jpg "See it in action")</a>
<p><small>Click the image above to <a href="https://codepen.io/jakebown/pen/eYzZyaN">view the demo</a>.</small></p>

This is an experimental project created to try and overcome the issue of loading images into email templates by replacing them using a table. This project works great with small icons and graphics, it's not recommended for larger images.

## Requirements
- PHP 5+
- GD Library

## How to Use
This simple class can turn any PNG, JPG or JPEG into a valid HTML table. Heres an example of how to use ImageTable.

```php
<?php 

	require './ImageTable.php';

	$image_file = 'image.png';

	$it = new ImageTable($image_file);

?>
```