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

use phpbb\di\service_collection;

/**
* GZO Web: Achievements manager
*/
class manager
{
	/** @var array Contains all available achievement types */
	protected static $achievements = false;

	/**
	* Constructor
	*
	* @param service_collection $collection
	*/
	public function __construct(service_collection $collection)
	{
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
				if (!empty($type->get_name()))
				{
					self::$achievements[$type->get_name()] = $type;
				}
			}
		}
	}

	/**
	* Get achievement type by name
	*
	* @param string $name Name of the achievement
	* @return object
	*/
	public function get($name): object
	{
		return self::$achievements[$name] ?? (object) [];
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
}
