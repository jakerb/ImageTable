<?php


class ImageTable {

  /*
   * imageSrc 	Local image file with MIME type PNG, JPEG or JPG.
   */
  public $imageSrc;

  /*
   * imageMime 	MIME type of image used to specify to GD.
   */
  public $imageMIME;


  public function __construct($imageSrc = String) {

    if (!extension_loaded('gd')) {
      throw new Exception("ImageTable requires GD library to work.", 1);
    }

    if (!file_exists($imageSrc)) {
      throw new Exception("Could not find Image file.", 1);
    }

    $this->imageMIME = strtolower(pathinfo($imageSrc, PATHINFO_EXTENSION));

    if (!in_array(strtolower($this->imageMIME), ['png', 'jpg', 'jpeg'])) {
      throw new Exception("Filetype is not supported.", 1);
    }

    $this->imageSrc = $imageSrc;

    return $this;
  }

  public function renderTable($outputFileName = FALSE) {

    $image = FALSE;
    $file = FALSE;

    switch ($this->imageMIME) {
      case 'jpeg':
      case 'jpg':
        $image = imagecreatefromjpeg($this->imageSrc);
        break;

      case 'png':
        $image = imagecreatefrompng($this->imageSrc);
        break;
    }

    if (!$image) {
      throw new Exception("MIME type is not supported.", 1);
    }

    if ($outputFileName) {
      if (file_exists($outputFileName)) {
        throw new Exception("Cannot render table to specified file as it already exists.", 1);
      }

      $file = fopen($outputFileName, "w");
    }

    $width = imagesx($image);
    $height = imagesy($image);

    $style = '<style>table.imagetable tr td { padding: 0; width: 1px; height: 1px; }</style>';
    $table_open = '<table class="imagetable" style="border:0;border-collapse:collapse;">';
    $table_close = '</table>';
    $table_row_open = '<tr>';
    $table_row_close = '</tr>';

    if ($file) {
      fwrite($file, $style);
      fwrite($file, $table_open);
    }
    else {
      echo $table_open;
    }

    for ($x = 0; $x < $width; $x++) {

      if ($file) {
        fwrite($file, $table_row_open);
      }
      else {
        echo $table_row_open;
      }

      for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($image, $y, $x);

        if (!$rgb) {
          continue;
        }

        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        $table_column = sprintf("<td bgcolor=\"#%02x%02x%02x\"></td>", $r, $g, $b);

        if ($file) {
          fwrite($file, $table_column);
        }
        else {
          echo $table_column;
        }

      }

      if ($file) {
        fwrite($file, $table_row_close);
      }
      else {
        echo $table_row_close;
      }

    }

    if ($file) {
      fwrite($file, $table_close);
      fclose($file);
    }
    else {
      echo $table_close;
    }

    return $this;
  }

}
