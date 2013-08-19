<?php
App::uses('AppShell', 'Console/Command');
/**
 * Instruction Model
 * Tip Model
 *
 */
class RemoteContentShell extends AppShell {

	public $uses = array('Instruction', 'Tip');

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addSubcommand('refresh_tips', array(
			'help' => __('Refresh "tips of the day"')
		))->addSubcommand('refresh_instructions', array(
			'help' => __('Refresh instructions')
		))->description(__( 'Remote Content'));
		return $parser;
	}

	public function refresh_all() {
		$this->refresh_tips();
		$this->refresh_instructions();
	}

	public function refresh_tips() {
		$url = Configure::read('Config.tipsFeedUrl');

		if ($xml = Xml::build($url)) {
			if ($xml = Xml::toArray($xml)) {
				$data = array();
				if (!empty($xml['rss']['channel']['item'])) {
					$this->Tip->deleteAll(array('1 = 1'), false);
					if (!empty($xml['rss']['channel']['item']['title'])) {
						$xml['rss']['channel']['item'] = array($xml['rss']['channel']['item']);
					}
					if (is_array($xml['rss']['channel']['item'])) {
						foreach ($xml['rss']['channel']['item'] as $item) {
							$record = array();
							if (!empty($item['title'])) {
								$record['name'] = $item['title'];
							}
							if (!empty($item['content:encoded'])) {
								$record['content'] = $item['content:encoded'];
							}
							$data[] = $record;
						}
					}
					$this->Tip->saveAll($data);
				}
			}
		}
	}

	public function refresh_instructions() {
		$url = Configure::read('Config.instructionsFeedUrl');

		if ($xml = Xml::build($url)) {
			if ($xml = Xml::toArray($xml)) {
				$data = array();
				if (!empty($xml['rss']['channel']['item'])) {
					if (!empty($xml['rss']['channel']['item']['title'])) {
						$xml['rss']['channel']['item'] = array($xml['rss']['channel']['item']);
					}
					if (is_array($xml['rss']['channel']['item'])) {
						foreach ($xml['rss']['channel']['item'] as $item) {
							$instructions = $this->Instruction->find(
								'all', array(
									'conditions' => array(
										'Instruction.url' => $item['link']
									)
								)
							);

							if (!empty($instructions)) {
								foreach ($instructions as $instruction) {
									$record = array('id' => $instruction['Instruction']['id']);
									if (!empty($item['title'])) {
										$record['name'] = $item['title'];
									}
									if (!empty($item['content:encoded'])) {
										$record['content'] = $item['content:encoded'];
									}
									$data[] = $record;
								}
							}
						}
					}
					$this->Instruction->saveAll($data);
				}
			}
		}
	}
}