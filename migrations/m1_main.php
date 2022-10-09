<?php
/**
*
* GZO Web. An extension for the phpBB Forum Software package.
*
* @copyright (c) 2022, GanstaZ, https://www.github.com/GanstaZ/
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
		return $this->check('legend');
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
		return array('\ganstaz\web\migrations\v24\m1_main');
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
				$this->table_prefix . 'gzo_legend' => [
					'COLUMNS' => [
						'id'		  => ['UINT', null, 'auto_increment'],
						'achievement' => ['VCHAR', ''],
						'posts'		  => ['UINT', 0],
						'topics'	  => ['UINT', 0],
						'anniversary' => ['UINT', 0],
					],
					'PRIMARY_KEY' => ['id'],
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
				$this->table_prefix . 'gzo_legend',
			],
		];
	}
}
