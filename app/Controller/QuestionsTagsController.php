<?php
App::uses('AppController', 'Controller');
/**
 * QuestionsTags Controller
 *
 * @property QuestionsTag $QuestionsTag
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
class QuestionsTagsController extends AppController {

/**
 * delete method
 *
 * @param int $id A questionsTag id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->QuestionsTag->id = $id;
		if (!$this->QuestionsTag->exists()) {
			throw new NotFoundException(__('Invalid questionstag'));
		}
		if ($this->QuestionsTag->remove($id)) {
			$this->Flash->success(__('Question removed from tag'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Question was not removed from tag'));
		return $this->redirect($this->referer());
	}

/**
 * move_down method
 *
 * @param int $id A questionsTag id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function move_down($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->QuestionsTag->id = $id;
		if (!$this->QuestionsTag->exists()) {
			throw new NotFoundException(__('Invalid questionstag'));
		}
		if ($this->QuestionsTag->moveDown($id)) {
			$this->Flash->success(__('Question moved down'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Question was not moved down'));
		return $this->redirect($this->referer());
	}

/**
 * move_up method
 *
 * @param int $id A questionsTag id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function move_up($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->QuestionsTag->id = $id;
		if (!$this->QuestionsTag->exists()) {
			throw new NotFoundException(__('Invalid questionstag'));
		}
		if ($this->QuestionsTag->moveUp($id)) {
			$this->Flash->success(__('Question moved up'));
			return $this->redirect($this->referer());
		}
		$this->Flash->error(__('Question was not moved up'));
		return $this->redirect($this->referer());
	}

}
