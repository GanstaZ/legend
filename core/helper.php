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

/**
* GZO Web: Legend helper class
*/
class helper
{
	/** @var driver_interface */
	protected $db;

	/** @var achievements table */
	protected $legend;

	/** @var achievement types table */
	protected $achievements;

	/** @var achievements user table */
	protected $a_user;

	protected $table;

	/**
	* Constructor
	*
	* @param driver_interface $db Database object
	*/
	public function __construct(driver_interface $db, $table)
	{
		$this->db	 = $db;
		$this->table = $table;
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
	* @param string	 $category
	* @param integer $user_id
	* @return array
	*/
	public function get_user_achievements(string $category = null, int $user_id): array
	{
		$sql = 'SELECT l.category, a.aid
				FROM ' . $this->table . 'gzo_achievements l, ' . $this->table . 'gzo_achievement_types a
				WHERE l.id = a.cat_id
					AND active = 1
				ORDER BY l.id';
		$result = $this->db->sql_query($sql);

		$categories = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$categories[(int) $row['aid']] = (string) $row['category'];
		}
		$this->db->sql_freeresult($result);

		$sql = 'SELECT a.aid, a.achievement
				FROM ' . $this->table . 'gzo_achievement_types a, ' . $this->table . 'gzo_achievements_user au
				WHERE a.aid = au.aid
					AND au.user_id = ' . $user_id . '
				ORDER BY a.aid';
		$result = $this->db->sql_query($sql);

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
}
