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
		$tags = $this->Tag->find(
			'list', array(
				'fields' => array('name', 'name'),
				'conditions' => $conditions,
				'limit' => 8
			)
		);
		$tags = array_keys($tags);
		$this->set(compact('tags'));
		$this->set('_serialize', 'tags');
	}

}
