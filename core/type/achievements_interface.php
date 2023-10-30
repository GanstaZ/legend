<?php
/**
*
* An extension for the phpBB Forum Software package.
*
* @copyright (c) GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\core\type;

/**
* Interface for achievements
*/
interface achievements_interface
{
	/**
	* Get legend data
	*	['user' => '','points' => '', a_type => '',]
	*
	* @return array
	*/
	public function get_legend_data();

	/**
	* Set data
	*
	* @param mixed $data
	* @return void
	*/
	public function set(array $data);
}
