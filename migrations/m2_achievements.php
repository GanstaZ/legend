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
			['custom', [[$this, 'update_users']]],
		];
	}


	/**
	* Custom function to add categories
	*/
	public function add_categories()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'gzo_achievements'))
		{
			$categories = [
				[
					'category'	  => 'posts',
					'active'	  => 1,
					'special'	  => 0,
				],
				[
					'category'	  => 'topics',
					'active'	  => 1,
					'special'	  => 0,
				],
				[
					'category'	  => 'anniversary',
					'active'	  => 1,
					'special'	  => 0,
				],
				[
					'category'	  => 'points',
					'active'	  => 0,
					'special'	  => 0,
				],
				[
					'category'	  => 'special',
					'active'	  => 0,
					'special'	  => 1,
				],
			];

			$insert_buffer = new \phpbb\db\sql_insert_buffer($this->db, $this->table_prefix . 'gzo_achievements');

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
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'gzo_achievement_types'))
		{
			$achievements = [
				[
					'cat_id'	  => 1,
					'achievement' => 'first_post',
					'posts'		  => 1,
					'topics'	  => 0,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'	  => 1,
					'achievement' => '10ps',
					'posts'		  => 10,
					'topics'	  => 0,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'	  => 1,
					'achievement' => '25ps',
					'posts'		  => 25,
					'topics'	  => 0,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'	  => 1,
					'achievement' => '50ps',
					'posts'		  => 50,
					'topics'	  => 0,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'	  => 1,
					'achievement' => '100ps',
					'posts'		  => 100,
					'topics'	  => 0,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'	  => 2,
					'achievement' => '10ts',
					'posts'		  => 0,
					'topics'	  => 10,
					'anniversary' => 0,
					'points'	  => 0,
				],
				[
					'cat_id'	  => 3,
					'achievement' => 'week',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '1 week',
					'points'	  => 0,
				],
				[
					'cat_id'	  => 3,
					'achievement' => 'month',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '1 month',
					'points'	  => 0,
				],
				[
					'cat_id'	  => 3,
					'achievement' => '6ms',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '6 months',
					'points'	  => 0,
				],
				[
					'cat_id'	  => 3,
					'achievement' => 'year',
					'posts'		  => 0,
					'topics'	  => 0,
					'anniversary' => '1 year',
					'points'	  => 0,
				],
			];

			$insert_buffer = new \phpbb\db\sql_insert_buffer($this->db, $this->table_prefix . 'gzo_achievement_types');

			foreach ($achievements as $row)
			{
				$insert_buffer->insert($row);
			}

			$insert_buffer->flush();
		}
	}

	/**
	* Custom function to update users
	*/
	public function update_users()
	{
		$sql = 'SELECT topic_poster
				FROM  ' . $this->table_prefix . 'topics
				WHERE topic_visibility = ' . ITEM_APPROVED . '
					AND topic_poster <> ' . ANONYMOUS;
		$result = $this->db->sql_query($sql);

		$topic_posters = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$poster_id = (int) $row['topic_poster'];
			if (!isset($topic_posters[$poster_id]))
			{
				$topic_posters[$poster_id] = 0;
			}
			$topic_posters[$poster_id]++;
		}
		$this->db->sql_freeresult($result);

		foreach ($topic_posters as $poster => $count)
		{
			$this->db->sql_query('UPDATE ' . $this->table_prefix . 'users
				SET topic_count = ' . (int) $count . '
				WHERE user_id = ' . (int) $poster
			);
		}
	}
}
