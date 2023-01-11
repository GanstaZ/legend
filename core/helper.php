<?php
/**
*
* GZO Web. An extension for the phpBB Forum Software package.
*
* @copyright (c) 2022, GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\core;

use phpbb\db\driver\driver_interface;
use phpbb\template\template;

/**
* GZO Web: Legend helper class
*/
class helper
{
	/** @var driver_interface */
	protected $db;

	/** @var template */
	protected $template;

	/** @var table prefix */
	protected $table;

	/**
	* Constructor
	*
	* @param driver_interface $db		Database object
	* @param template		  $template Template object
	* @param string			  $table	Table prefix
	*/
	public function __construct(driver_interface $db, template $template, $table)
	{
		$this->db		= $db;
		$this->template = $template;
		$this->table	= $table;
	}

	/**
	* Update user topic count
	*
	* @param integer $user_id
	* @return void
	*/
	public function update_user_topic_count(int $user_id): void
	{
		$sql_update = $this->db->cast_expr_to_string($this->db->cast_expr_to_bigint('topic_count') . ' + 1');

		$this->db->sql_query('UPDATE ' . $this->table . 'users SET topic_count = ' . $sql_update . ' WHERE user_id = ' . $user_id);
	}

	/**
	* Get user achievements
	*
	* @param ?string $category
	* @param integer $user_id
	* @return array
	*/
	public function get_user_achievements(?string $category, int $user_id): array
	{
		$gzo_achievement_types = 'gzo_achievement_types';

		$sql = 'SELECT l.category, a.aid
				FROM ' . $this->table . 'gzo_achievements l, ' . $this->table . $gzo_achievement_types . ' a
				WHERE l.id = a.cat_id
					AND active = 1
				ORDER BY l.id';
		$result = $this->db->sql_query($sql, 86400);

		$categories = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$categories[(int) $row['aid']] = (string) $row['category'];
		}
		$this->db->sql_freeresult($result);

		$sql = 'SELECT a.aid, a.achievement
				FROM ' . $this->table . $gzo_achievement_types . ' a, ' . $this->table . 'gzo_achievements_user au
				WHERE a.aid = au.aid
					AND au.user_id = ' . $user_id . '
				ORDER BY a.aid';
		$result = $this->db->sql_query($sql, 300);

		$achievements = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$aid = (int) $row['aid'];
			$achievements[$categories[$aid]][$aid] = (string) $row['achievement'];
		}
		$this->db->sql_freeresult($result);
		unset($categories);

		return $achievements[$category] ?? $achievements;
	}

	/**
	* Set template data
	*
	* @param  array $template_data
	* @return void
	*/
	public function set_template_data(array $template_data): void
	{
		foreach ($template_data as $category => $data)
		{
			// Set categories
			$this->template->assign_block_vars('achievements', [
				'category' => $category,
				'count'    => count($data),
			]);

			// Add data to given category
			foreach ($data as $item)
			{
				$this->template->assign_block_vars('achievements.item', [
					'name' => $item
				]);
			}
		}
	}
}
