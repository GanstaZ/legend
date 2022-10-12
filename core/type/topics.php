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
* GZO Web: Achievement type topics
*/
class topics extends base
{
	/**
	* {@inheritdoc}
	*/
	public function load($category, $member, $online)
	{
		return parent::load(
			$category,
			[
				'user_id' => (int) $member['user_id'],
				'points'  => (int) $member['topic_count'],
				'a_type'  => 'topics',
			],
			$online
		);
	}
}
