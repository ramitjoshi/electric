<?php
/**
 * PhpThumb Library Example File
 * 
 * This file contains example usage for the PHP Thumb Library
 * 
 * PHP Version 5 with GD 2.0+
 * PhpThumb : PHP Thumb Library <http://phpthumb.gxdlabs.com>
 * Copyright (c) 2009, Ian Selby/Gen X Design
 * 
 * Author(s): Ian Selby <ian@gen-x-design.com>
 * 
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @author Ian Selby <ian@gen-x-design.com>
 * @copyright Copyright (c) 2009 Gen X Design
 * @link http://phpthumb.gxdlabs.com
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 3.0
 * @package PhpThumb
 * @subpackage Examples
 * @filesource
 */

require_once 'thumb/ThumbLib.inc.php';
$w = 100;
$h = 100;
if(isset($_GET['w'])){
	$w = $_GET['w'];
}
if(isset($_GET['h'])){
	$h = $_GET['h'];
}

$thumb = PhpThumbFactory::create($_GET['src']);
$thumb->adaptiveResize($w, $h);
$thumb->show();

?>
