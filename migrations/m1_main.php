<?php
/**
*
* An extension for the phpBB Forum Software package.
*
* @copyright (c) GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\migrations;

class m1_main extends \phpbb\db\migration\migration
{
	/**
	* {@inheritdoc}
	*/
	public function effectively_installed()
	{
		return $this->check('achievements') && $this->check('achievement_types') && $this->check('achievements_user');
	}

	/**
	* Check condition exists for a given table name
	*
	* @param $name Name of the table
	* @return bool
	*/
	public function check($name)
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'gzo_' . $name);
	}

	/**
	* {@inheritdoc}
	*/
	static public function depends_on()
	{
		return array('\ganstaz\gzo\migrations\v24\m1_main');
	}

	/**
	* Add the table schemas to the database:
	*
	* @return array Array of table schema
	* @access public
	*/
	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'gzo_achievements' => [
					'COLUMNS' => [
						'id'		  => ['UINT', null, 'auto_increment'],
						'category'	  => ['VCHAR', ''],
						'active'	  => ['BOOL', 0],
						'special'	  => ['BOOL', 0],
					],
					'PRIMARY_KEY' => ['id'],
				],
				$this->table_prefix . 'gzo_achievement_types' => [
					'COLUMNS' => [
						'aid'		   => ['UINT', null, 'auto_increment'],
						'cat_id'	   => ['UINT', 0],
						'achievement'  => ['VCHAR', ''],
						'posts'		   => ['UINT', 0],
						'topics'	   => ['UINT', 0],
						'membership'   => ['UINT', 0],
						'points'	   => ['UINT', 0],
						'level'  	   => ['UINT', 0],
						'contribution' => ['UINT', 0],
						'donation'	   => ['UINT', 0],
						'birthday'	   => ['UINT', 0],
						'likes'   	   => ['UINT', 0],
						'subscribers'  => ['UINT', 0],
					],
					'PRIMARY_KEY' => ['aid'],
				],
				$this->table_prefix . 'gzo_achievements_user' => [
					'COLUMNS' => [
						'aid'		  => ['UINT', 0],
						'user_id'	  => ['UINT', 0],
					],
				],
			],
			'add_columns' => [
				$this->table_prefix . 'users' => [
					'topic_count'	  => ['UINT', 0],
				],
			],
		];
	}

	/**
	* Drop the schemas from the database
	*
	* @return array Array of table schema
	* @access public
	*/
	public function revert_schema()
	{
		return [
			'drop_tables' => [
				$this->table_prefix . 'gzo_achievements',
				$this->table_prefix . 'gzo_achievement_types',
				$this->table_prefix . 'gzo_achievements_user',
			],
			'drop_columns'	=> [
				$this->table_prefix . 'users' => [
					'topic_count',
				],
			],
		];
	}
}
