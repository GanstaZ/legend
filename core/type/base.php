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

use phpbb\cache\service as cache;
use phpbb\db\driver\driver_interface;

/**
* GZO Web: base class for tabs
*/
class base implements achievements_interface
{
	/** @var cache */
	protected $cache;

	/** @var driver_interface */
	protected $db;

	/** @var achievements table */
	protected $legend;

	/** @var achievement types table */
	protected $achievements;

	/** @var achievements user table */
	protected $a_user;

	/** @var string name */
	protected $name;

	/** @var array Contains all achievement data */
	protected static $data = false;

	/**
	* Constructor
	*
	* @param cache			  $cache		Cache object
	* @param driver_interface $db			Database object
	* @param string			  $legend		Achievements table
	* @param string			  $achievements Achievement types table
	* @param string			  $a_user		Achievements user table
	*/
	public function __construct
	(
		cache $cache,
		driver_interface $db,
		$legend,
		$achievements,
		$a_user
	)
	{
		$this->cache		= $cache;
		$this->db			= $db;
		$this->legend		= $legend;
		$this->achievements = $achievements;
		$this->a_user		= $a_user;
	}

	/**
	* {@inheritdoc}
	*/
	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	/**
	* {@inheritdoc}
	*/
	public function get_name(): string
	{
		return $this->name;
	}

	/**
	* {@inheritdoc}
	*/
	public function load($category, $data, $online)
	{
		$user_id = $data['user_id'];

		if ($online)
		{
			$this->check($category, $user_id, $data['points'], $data['a_type']);
		}

		return $this->get_user_achievements($category, $user_id);
	}

	/**
	* Get achievements data
	*
	* @param string $category
	* @return array
	*/
	protected function get_achievements(string $category = null): array
	{
		if (($achievements = $this->cache->get('_achievements')) === false)
		{
			$sql = 'SELECT *
					FROM ' . $this->legend . ' l, ' . $this->achievements . ' a
					WHERE l.id = a.cat_id
						AND active = 1
					ORDER BY l.id';
			$result = $this->db->sql_query($sql);

			$achievements = [];
			while ($row = $this->db->sql_fetchrow($result))
			{
				unset($row['id'], $row['active'], $row['cat_id']);

				$achievements[(string) $row['category']][] = array_filter($row);
			}
			$this->db->sql_freeresult($result);

			$this->cache->put('_achievements', $achievements);
		}

		return $achievements[(string) $category] ?? $achievements;
	}


	/**
	* Get user achievements
	*
	* @param string $category
	* @param int	$user_id
	* @return array
	*/
	protected function get_user_achievements(string $category = null, int $user_id): array
	{
		$sql = 'SELECT a.aid, a.achievement
				FROM ' . $this->achievements . ' a, ' . $this->a_user . ' au
				WHERE a.aid = au.aid
					AND au.user_id = ' . $user_id . '
				ORDER BY a.aid';
		$result = $this->db->sql_query($sql);

		$achievements = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$achievements[$category][(int) $row['aid']] = (string) $row['achievement'];
		}
		$this->db->sql_freeresult($result);


		return $achievements[$category] ?? $achievements;
	}


	/**
	* Check & prepare data for user achievements
	*
	* @param int	$user_id
	* @param mixed	$user_points
	* @param string $points
	* @return void
	*/
	protected function check(string $category, $user_id, mixed $user_points, string $points): void
	{
		$achievements = $this->get_achievements($category);
		$u_legend	  = $this->get_user_achievements($category, $user_id);

		foreach ($achievements as $row)
		{
			if (isset($u_legend[$row['aid']]))
			{
				continue;
			}

			if ($user_points >= $row[$points])
			{
				$row['user_id'] = $user_id;
				$this->set_user_achievements_data($row);
			}
		}
	}

	/**
	* Set user achievement data
	*
	* @param array $data
	* @return void
	*/
	protected function set_user_achievements_data(array $data): void
	{
		$sql_ary = [
			'aid'	  => (int) $data['aid'],
			'user_id' => $data['user_id'],
		];

		$this->db->sql_query('INSERT INTO ' . $this->a_user . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
	}
}
