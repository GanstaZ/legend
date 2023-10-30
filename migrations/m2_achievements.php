<?php
/**
*
* An extension for the phpBB Forum Software package.
*
* @copyright (c) GanstaZ, https://www.github.com/GanstaZ/

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
					'category'	  => 'membership',
					'active'	  => 1,
					'special'	  => 1,
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
				[
					'category'	  => 'level',
					'active'	  => 0,
					'special'	  => 1,
				],
				[
					'category'	  => 'contribution',
					'active'	  => 0,
					'special'	  => 0,
				],
				[
					'category'	  => 'donation',
					'active'	  => 0,
					'special'	  => 0,
				],
				[
					'category'	  => 'birthday',
					'active'	  => 0,
					'special'	  => 1,
				],
				[
					'category'	  => 'likes',
					'active'	  => 0,
					'special'	  => 0,
				],
				[
					'category'	  => 'subscribers',
					'active'	  => 0,
					'special'	  => 0,
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
					'cat_id'	   => 1,
					'achievement'  => 'first_post',
					'posts'		   => 1,
					'topics'	   => 0,
					'membership'   => 0,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 1,
					'achievement'  => '10ps',
					'posts'		   => 10,
					'topics'	   => 0,
					'membership'   => 0,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 1,
					'achievement'  => '25ps',
					'posts'		   => 25,
					'topics'	   => 0,
					'membership'   => 0,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 1,
					'achievement'  => '50ps',
					'posts'		   => 50,
					'topics'	   => 0,
					'membership'   => 0,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 1,
					'achievement'  => '100ps',
					'posts'		   => 100,
					'topics'	   => 0,
					'membership'   => 0,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 2,
					'achievement'  => '10ts',
					'posts'		   => 0,
					'topics'	   => 10,
					'membership'   => 0,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 2,
					'achievement'  => '50ts',
					'posts'		   => 0,
					'topics'	   => 50,
					'membership'   => 0,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 3,
					'achievement'  => 'member',
					'posts'		   => 0,
					'topics'	   => 0,
					'membership'   => 1,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 3,
					'achievement'  => 'week',
					'posts'		   => 0,
					'topics'	   => 0,
					'membership'   => 7,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 3,
					'achievement'  => 'month',
					'posts'		   => 0,
					'topics'	   => 0,
					'membership'   => 30.4167,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 3,
					'achievement'  => '6ms',
					'posts'		   => 0,
					'topics'	   => 0,
					'membership'   => 182.5,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
				],
				[
					'cat_id'	   => 3,
					'achievement'  => 'year',
					'posts'		   => 0,
					'topics'	   => 0,
					'membership'   => 365,
					'points'	   => 0,
					'level'	       => 0,
					'contribution' => 0,
					'donation'	   => 0,
					'birthday'	   => 0,
					'likes'	       => 0,
					'subscribers'  => 0,
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
