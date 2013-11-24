<?php 
/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */
 
class Pimcore_Image_Adapter_InkscapeImagick extends Pimcore_Image_Adapter_Imagick {

    protected $isOriginal = true;

    /**
     * @return string
     */
    protected static function getBinary() {
        return "/usr/bin/inkscape";
    }

    /**
     * @return bool
     */
    protected function isSvg() {
        return (bool) preg_match("/\.svgz?$/", $this->imagePath);
    }

    /**
     * @param $width
     * @return Pimcore_Image_Adapter|void
     */
    public function scaleByWidth ($width) {

        if(!$this->isOriginal || !$this->isSvg()) {
            return parent::scaleByWidth($width);
        }

        $width  = (int)$width;

        $tmpFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/" . uniqid() . "_pimcore_image_svg_width_tmp_file.png";
        $this->tmpFiles[] = $tmpFile;

        Pimcore_Tool_Console::exec(self::getBinary() . " -w " . $width . " -D -f " . $this->imagePath . " -e " . $tmpFile);
        $this->initImagick($tmpFile);

        return $this;
    }

    /**
     * @param $height
     * @return Pimcore_Image_Adapter|void
     */
    public function scaleByHeight ($height) {

        if(!$this->isOriginal || !$this->isSvg()) {
            return parent::scaleByHeight($height);
        }

        $height = (int)$height;

        $tmpFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/" . uniqid() . "_pimcore_image_svg_height_tmp_file.png";
        $this->tmpFiles[] = $tmpFile;

        Pimcore_Tool_Console::exec(self::getBinary() . " -h " . $height . " -D -f " . $this->imagePath . " -e " . $tmpFile);
        $this->initImagick($tmpFile);


        return $this;
    }

    /**
     * @param  $width
     * @param  $height
     * @return Pimcore_Image_Adapter
     */
    public function resize ($width, $height) {

        if(!$this->isOriginal || !$this->isSvg()) {
            return parent::resize($width, $height);
        }

        $width  = (int)$width;
        $height = (int)$height;

        $tmpFile = PIMCORE_SYSTEM_TEMP_DIRECTORY . "/" . uniqid() . "_pimcore_image_svg_resize_tmp_file.png";
        $this->tmpFiles[] = $tmpFile;

        Pimcore_Tool_Console::exec(self::getBinary() . " -w " . $width . " -h " . $height . " -D -f " . $this->imagePath . " -e " . $tmpFile);
        $this->initImagick($tmpFile);

        return $this;
    }

    /**
     * @param $tmpFile
     */
    protected function initImagick($tmpFile) {
        $this->isOriginal = false;

        $this->destroy();
        $this->load($tmpFile);
    }

    /**
     *
     */
    protected function reinitializeImage() {
        $this->isOriginal = false;
        parent::reinitializeImage();
    }

}
