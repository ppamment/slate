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
 
class Pimcore_Image_HtmlToImage {

    /**
     * @return bool
     */
    public static function isSupported() {
        return (bool) self::getWkhtmltoimageBinary();
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function getWkhtmltoimageBinary () {

        if(Pimcore_Config::getSystemConfig()->documents->wkhtmltoimage) {
            if(@is_executable(Pimcore_Config::getSystemConfig()->documents->wkhtmltoimage)) {
                return (string) Pimcore_Config::getSystemConfig()->documents->wkhtmltoimage;
            } else {
                Logger::critical("wkhtmltoimage binary: " . Pimcore_Config::getSystemConfig()->documents->wkhtmltoimage . " is not executable");
            }
        }

        $paths = array("/usr/bin/wkhtmltoimage-amd64","/usr/local/bin/wkhtmltoimage-amd64","/bin/wkhtmltoimage-amd64",
        "/usr/bin/wkhtmltoimage", "/usr/local/bin/wkhtmltoimage", "/bin/wkhtmltoimage");

        foreach ($paths as $path) {
            if(@is_executable($path)) {
                return $path;
            }
        }

        return false;
    }

    /**
     * @param $url
     * @param $outputFile
     * @param int $screenWidth
     * @param string $format
     * @return bool
     */
    public static function convert($url, $outputFile, $screenWidth = 1200, $format = "png") {
        Pimcore_Tool_Console::exec(self::getWkhtmltoimageBinary() . " --width " . $screenWidth . " --format " . $format . " \"" . $url . "\" " . $outputFile, PIMCORE_LOG_DIRECTORY . "/wkhtmltoimage.log");
        if(filesize($outputFile) > 1000) {
            return true;
        }
        return false;
    }
}
