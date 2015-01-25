<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Include custom libraries and initialization code here

require_once(dirname(__FILE__).'/moodle_bridge.php');

MoodleBridge::Get(); //Executes the moodle things, to avoid collisions
