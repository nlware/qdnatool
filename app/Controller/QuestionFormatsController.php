<?php
App::uses('AppController', 'Controller');
/**
 * QuestionFormats Controller
 *
 * @property QuestionFormat $QuestionFormat
 */
class QuestionFormatsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->set('questionFormats', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 * @throws NotFoundException
 */
	public function admin_view($id = null) {
		$this->QuestionFormat->id = $id;
		if (!$this->QuestionFormat->exists()) {
			throw new NotFoundException(__('Invalid question format'));
		}
		$this->set('questionFormat', $this->QuestionFormat->read(null, $id));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 * @throws NotFoundException
 */
	public function admin_edit($id = null) {
		$this->QuestionFormat->id = $id;
		if (!$this->QuestionFormat->exists()) {
			throw new NotFoundException(__('Invalid question format'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->QuestionFormat->save($this->request->data)) {
				$this->setFlashSuccess(__('The question format has been saved'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->setFlashError(__('The question format could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->QuestionFormat->read(null, $id);
		}
	}
}