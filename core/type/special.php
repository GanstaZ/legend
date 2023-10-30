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
* Achievement type special
*/
class special implements achievements_interface
{
	/**
	* {@inheritdoc}
	*/
	public function get_legend_data(): array
	{
		return [
			[
				'user'   => 'user_id',
				'points' => 'mixed',
				'type'   => 'special',
			]
		];
	}

	/**
	* {@inheritdoc}
	*/
	public function set($data)
	{
		return [];
	}
}
