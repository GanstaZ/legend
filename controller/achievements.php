<?php
/**
*
* An extension for the phpBB Forum Software package.
*
* @copyright (c) GanstaZ, https://www.github.com/GanstaZ/
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace ganstaz\legend\controller;

use phpbb\controller\helper as controller;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\user;
use ganstaz\legend\core\manager;

/**
* Achievements controller
*/
class achievements
{
	/** @var controller helper */
	protected $controller;

	/** @var language */
	protected $language;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var manager */
	protected $manager;

	/**
	* Constructor
	*
	* @param controller $controller Controller helper object
	* @param language   $language   Language object
	* @param template   $template   Template object
	* @param user       $user       User object
	* @param manager    $manager    Achievements object
	*/
	public function __construct(controller $controller,	language $language,	template $template,	user $user,	manager $manager)
	{
		$this->controller = $controller;
		$this->language   = $language;
		$this->template   = $template;
		$this->user       = $user;
		$this->manager    = $manager;
	}

	/**
	* Achievements controller
	*
	* @throws \phpbb\exception\http_exception
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle(): \Symfony\Component\HttpFoundation\Response
	{
		// Load language
		// $this->language->add_lang('common', 'ganstaz/lagend');

		// TODO: Guests see page inna strange way.. Needs fixing
		if ($this->user->data['user_id'] != ANONYMOUS)
		{
			foreach ($this->manager->get_achievement_types() as $type)
			{
				$achievement = $this->manager->get($type);
				$data = array_slice($achievement, 0, 3);
				$data['user']   = $this->user->data[$data['user']];
				$data['points'] = $this->user->data[$data['points']] ?? 0;

				if ($data['type'] === 'membership')
				{
					$memberdays = max(1, round((time() - $this->user->data['user_regdate']) / 86400));

					$data['points'] = $memberdays;
				}

				$achievement_data = $this->manager->load($achievement['action']->set($data));

				//var_dump($achievement_data);

				//var_dump($data);
				// Set categories
			    $this->template->assign_block_vars('achievements', [
				    'category' => $data['type'],
				    'count'    => count($achievement_data),
			    ]);

				//var_dump(count($achievement_data));
			    // Add data to given category
			    foreach ($achievement_data as $item)
			    {
				    $this->template->assign_block_vars('achievements.item', $item);
			    }
			}
		}

		return $this->controller->render('legend.twig', $this->language->lang('GZO_ACHIEVEMENTS'), 200, true);
	}
}
