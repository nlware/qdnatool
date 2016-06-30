<?php
App::uses('AppController', 'Controller');

/**
 * Tags Controller
 *
 * @property Tag $Tag
 */
class TagsController extends AppController {

/**
 * autocomplete method
 *
 * @return void
 */
	public function autocomplete() {
		$conditions = array(
			'Tag.user_id' => $this->Auth->user('id')
		);
		if (!empty($this->request->query['query'])) {
			$conditions['Tag.name LIKE'] = $this->request->query['query'] . '%';
		}
		$fields = array('name', 'name');
		$limit = 8;
		$tags = $this->Tag->find('list', compact('fields', 'conditions', 'limit'));
		$tags = array_keys($tags);
		$this->set(compact('tags'));
		$this->set('_serialize', 'tags');
	}

}
