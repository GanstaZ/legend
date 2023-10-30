<?php
/**
*
* GZO Web. An extension for the phpBB Forum Software package.
*
* @copyright (c) 2022, GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\core\type;

/**
* GZO Web: interface for achievements
*/
interface achievements_interface
{
	/**
	* Set category name for achievement
	*
	* @param string $name Name of the tab
	* @return void
	*/
	public function set_name(string $name);

	/**
	* Returns the name of the achievement category
	*
	* @return string Name of the achievement category
	*/
	public function get_name();

	/**
	* Load current category
	*
	* @param string $category Achievement category
	* @param array	$data
	* @param bool	$online
	* @return void
	*/
	public function load(string $category, array $data, bool $online);
}
