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

class m2_achievements extends \phpbb\db\migration\migration
{
	/**
	* {@inheritdoc}
	*/
	static public function depends_on()
	{
		return ['\ganstaz\legend\migrations\m1_main'];
	}

	/**
	* Add the initial data in the database
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return [
			['custom', [[$this, 'add_categories']]],
			['custom', [[$this, 'add_achievements']]],
		];
	}


	/**
	* Custom function to add categories
	*/
	public function add_categories()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'gzo_legend'))
		{
			$categories = [
				[
					'category'    => 'posts',
					'active'	  => 1,
					'special'     => 0,
				],
				[
					'category'    => 'topics',
					'active'	  => 1,
					'special'     => 0,
				],
				[
					'category'    => 'anniversary',
					'active'	  => 1,
					'special'     => 0,
				],
				[
					'category'    => 'points',
					'active'	  => 0,
					'special'     => 0,
				],
				[
					'category'    => 'special',
					'active'	  => 0,
					'special'     => 1,
				],
			];

			$insert_buffer = new \phpbb\db\sql_insert_buffer($this->db, $this->table_prefix . 'gzo_legend');

			foreach ($categories as $row)
			{
				$insert_buffer->insert($row);
			}

			$insert_buffer->flush();
		}
	}

	/**
	* Custom function to add achievements data
	*/
	public function add_achievements()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'gzo_achievements'))
		{
			$achievements = [
				[
					'cat_id'      => 1,
					'achievement' => 'first_post',
					'posts'		  => 1,
					'topics'	  => 0,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'      => 1,
					'achievement' => 'ten_posts',
					'posts'		  => 10,
					'topics'	  => 0,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'      => 2,
					'achievement' => 'ten_topics',
					'posts'		  => 0,
					'topics'	  => 10,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'      => 3,
					'achievement' => 'week',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '1 week',
					'points'	  => 0,
				],
				[
					'cat_id'      => 3,
					'achievement' => 'month',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '1 month',
					'points'	  => 0,
				],
				[
					'cat_id'      => 3,
					'achievement' => 'six_months',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '6 months',
					'points'	  => 0,
				],
				[
					'cat_id'      => 3,
					'achievement' => 'year',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '1 year',
					'points'	  => 0,
				],
			];

			$insert_buffer = new \phpbb\db\sql_insert_buffer($this->db, $this->table_prefix . 'gzo_achievements');

			foreach ($achievements as $row)
			{
				$insert_buffer->insert($row);
			}

			$insert_buffer->flush();
		}
	}
}
