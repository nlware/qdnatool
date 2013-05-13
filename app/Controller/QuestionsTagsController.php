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

	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->QuestionsTag->id = $id;
		if (!$this->QuestionsTag->exists()) {
			throw new NotFoundException(__('Invalid questionstag'));
		}
		if ($this->QuestionsTag->remove($id)) {
			$this->Session->setFlash(__('Question removed from tag'), 'alert', array('plugin' => 'TwitterBootstrap', 'class' => 'alert-success'));
			return $this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Question was not removed from tag'), 'alert', array('plugin' => 'TwitterBootstrap', 'class' => 'alert-error'));
		return $this->redirect($this->referer());
	}

	public function move_down($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->QuestionsTag->id = $id;
		if (!$this->QuestionsTag->exists()) {
			throw new NotFoundException(__('Invalid questionstag'));
		}
		if ($this->QuestionsTag->moveDown($id)) {
			$this->Session->setFlash(__('Question moved down'), 'alert', array('plugin' => 'TwitterBootstrap', 'class' => 'alert-success'));
			return $this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Question was not moved down'), 'alert', array('plugin' => 'TwitterBootstrap', 'class' => 'alert-error'));
		return $this->redirect($this->referer());
	}

	public function move_up($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->QuestionsTag->id = $id;
		if (!$this->QuestionsTag->exists()) {
			throw new NotFoundException(__('Invalid questionstag'));
		}
		if ($this->QuestionsTag->moveUp($id)) {
			$this->Session->setFlash(__('Question moved up'), 'alert', array('plugin' => 'TwitterBootstrap', 'class' => 'alert-success'));
			return $this->redirect($this->referer());
		}
		$this->Session->setFlash(__('Question was not moved up'), 'alert', array('plugin' => 'TwitterBootstrap', 'class' => 'alert-error'));
		return $this->redirect($this->referer());
	}
}