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
* Achievement type common
*/
class common implements achievements_interface
{
	/**
	* {@inheritdoc}
	*/
	public function get_legend_data(): array
	{
		return [
			[
				'user'   => 'user_id',
				'points' => 'user_posts',
				'type'   => 'posts',
			],
			[
				'user'   => 'user_id',
				'points' => 'topic_count',
				'type'   => 'topics',
			],
			[
				'user'   => 'user_id',
				'points' => 'user_points',
				'type'   => 'membership',
			],
			// [
			// 	'user'   => 'user_id',
			// 	'points' => 'user_points',
			// 	'type'   => 'points',
			// ],
		];
	}

	/**
	* {@inheritdoc}
	*/
	public function set($data)
	{
		return[
			'user_id' => (int) $data['user'],
			'points'  => (int) $data['points'],
			'type'    => (string) $data['type'],
		];
	}
}
