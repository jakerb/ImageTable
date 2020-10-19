<?php
namespace jakerb;

class ImageTable {

  /**
   * @var string[]
   * A list of allowed MIME Types.
   */
  protected $allowedMimeTypes = [
    'image/png',
    'image/jpeg',
  ];

  /**
   * @var string
   * The path to the image being processed.
   */
  protected $imageSrc;

  /**
   * @var string $imageMime
   * The MIME type of image used to specify to GD.
   */
  protected $imageMIME;

  /**
   * ImageTable constructor.
   *
   * @param string $imageSrc Path to the image.
   *
   * @return \jakerb\ImageTable
   * @throws \Exception
   */
  public function __construct($imageSrc) {

    if (!extension_loaded('gd')) {
      throw new \Exception("ImageTable requires GD library to work.", 1);
    }

    if (!file_exists($imageSrc)) {
      throw new \Exception("The file you provided could not be found.", 1);
    }

    $this->imageMIME = mime_content_type($imageSrc);

    if (!in_array(strtolower($this->imageMIME), $this->allowedMimeTypes)) {
      throw new \Exception("The file you provided is of an unsupported or unrecognised type.", 1);
    }

    $this->imageSrc = $imageSrc;

    return $this;
  }

  /**
   * @param bool|string $outputFileName Path of output file, or false to skip
   * file generation.
   *
   * @return \jakerb\ImageTable
   */
  public function renderTable($outputFileName = FALSE) {
    $write_to_file = $outputFileName !== false;
    $image = null;
    $file = null;

    switch ($this->imageMIME) {
      case 'image/jpeg':
        $image = imagecreatefromjpeg($this->imageSrc);
        break;
      case 'image/png':
        $image = imagecreatefrompng($this->imageSrc);
        break;
    }

    if (!isset($image)) {
      throw new \Exception("Failed to create image from supplied file.", 1);
    }

    if ($write_to_file) {
      if (file_exists($outputFileName)) {
        throw new \Exception("Cannot render table to specified file as it already exists.", 1);
      }

      $file = fopen($outputFileName, "w");
      ob_start();
    }

    $width = imagesx($image);
    $height = imagesy($image);

    $style = '<style>table.imagetable tr td { padding: 0; width: 1px; height: 1px; }</style>';
    $table_open = '<table class="imagetable" style="border:0;border-collapse:collapse;">';
    $table_close = '</table>';
    $table_row_open = '<tr>';
    $table_row_close = '</tr>';

    echo $style;
    echo $table_open;

    for ($y = 0; $y < $height; $y++) {
      echo $table_row_open;

      for ($x = 0; $x < $width; $x++) {
        if($rgb = imagecolorat($image, $x, $y)) {
          $r = ($rgb >> 16) & 0xFF;
          $g = ($rgb >> 8) & 0xFF;
          $b = $rgb & 0xFF;

          $table_column = sprintf("<td bgcolor=\"#%02x%02x%02x\"></td>", $r, $g, $b);

          echo $table_column;
        }
      }

      echo $table_row_close;
    }

    echo $table_close;

    if($write_to_file){
      $file_contents = ob_get_contents();
      ob_end_clean();
      fwrite($file, $file_contents);
      fclose($file);
    }

    return $this;
  }

}
