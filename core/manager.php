<?php
/**
*
* An extension for the phpBB Forum Software package.
*
* @copyright (c) GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\core;

use phpbb\cache\service as cache;
use phpbb\db\driver\driver_interface;
use ganstaz\legend\core\helper;
use phpbb\di\service_collection;

/**
* Achievements manager
*/
class manager
{
	/** @var cache */
	protected $cache;

	/** @var driver_interface */
	protected $db;

	/** @var helper */
	protected $helper;

	/** @var table prefix */
	protected $table;

	/** @var array Contains all available achievement types */
	protected static $achievements = false;

	/**
	* Constructor
	*
	* @param service_collection $collection Service collection object
	* @param cache			    $cache		Cache object
	* @param driver_interface   $db			Database object
	* @param helper             $helper     Legend helper class
	* @param string			    $table		Table prefix
	*/
	public function __construct(cache $cache, driver_interface $db,	helper $helper, service_collection $collection, $table)
	{
		$this->cache  = $cache;
		$this->db     = $db;
		$this->helper = $helper;
		$this->table  = $table;

		$this->register_achievement_types($collection);
	}

	/**
	* Register all available achievement types
	*
	* @param Service collection of achievement types
	*/
	protected function register_achievement_types($collection): void
	{
		if (!empty($collection))
		{
			self::$achievements = [];
			foreach ($collection as $type)
			{
				foreach ($type->get_legend_data() as $data)
				{
					if (is_array($data))
					{
						self::$achievements[$data['type']] = [
							'user'   => $data['user'],
							'points' => $data['points'],
							'type'   => $data['type'],
							'action' => $type,
						];
					}
				}
			}
		}
	}

	/**
	* Get achievement type by name
	*
	* @param string $name Name of the achievement
	* @return array
	*/
	public function get($name): array
	{
		return self::$achievements[$name] ?? [];
	}

	/**
	* Get all available achievement types
	*
	* @return array
	*/
	public function get_achievement_types(): array
	{
		return array_keys(self::$achievements) ?? [];
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
					FROM ' . $this->table . 'gzo_achievements l, ' . $this->table . 'gzo_achievement_types a
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
	* Check & prepare data for user achievements
	*
	* @param integer $user_id     User id
	* @param mixed   $user_points User points
	* @param mixed   $points      Point type/s [(string) posts or [posts, topics, ...]]
	* @return void
	*/
	protected function check($user_id, mixed $user_points, mixed $points): void
	{
		$achievements = $this->get_achievements($points);
		$u_legend	  = $this->helper->get_user_achievements($points, $user_id);

		foreach ($achievements as $row)
		{
			if (isset($u_legend[$row['aid']]))
			{
				continue;
			}

			// TODO: Remove
			//var_dump($row['aid']);

			// TODO:
			// if (isset($row['special']))
			// {
			// 	var_dump($row['achievement']);
			// }

			// if (is_array($points))
			// {}

			if (is_string($points) && $user_points >= $row[$points])
			{
				$row['user_id'] = $user_id;

				var_dump($row['achievement']);

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

		$this->db->sql_query('INSERT INTO ' . $this->table . 'gzo_achievements_user ' . $this->db->sql_build_array('INSERT', $sql_ary));
		$this->cache->purge();
	}

	public function load($data)
	{
		if ($data)
		{
			// TODO: Remove
			//var_dump($data);

			$this->check($data['user_id'], $data['points'], $data['type']);
		}

		return $this->get_achievements($data['type']);
	}
}
