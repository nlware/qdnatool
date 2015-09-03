<?php
App::uses('NumberHelper', 'View/Helper');
App::uses('OutputHelper', 'View/Helper');
App::uses('AppController', 'Controller');
/**
 * Exams Controller
 *
 * @property Exam $Exam
 */
class ExamsController extends AppController {

	public $helpers = array('Number', 'Output');

/**
 * Blackhole callback
 *
 * @param string $type Type of error
 * @return void
 * @see AppController::blackhole()
 */
	public function blackhole($type) {
		$this->Flash->error(__('Sorry, something went wrong. Please, try again.'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$conditions = array(
			'Exam.user_id' => $this->Auth->user('id'),
			'Exam.parent_id' => null,
			'Exam.deleted' => null
		);
		$contain = array(
			'Child' => array(
				'conditions' => array('Child.deleted' => null),
				'ExamState'
			),
			'ExamState'
		);
		$this->paginate = compact('conditions', 'contain');
		$exams = $this->paginate();
		$this->set(compact('exams'));
	}

/**
 * view method
 *
 * @param string $id An exam id
 * @return void
 * @throws NotFoundException
 * @todo throw exception when exam doesn't belong to current user
 */
	public function view($id = null) {
		$this->Exam->id = $id;
		if (!$this->Exam->exists()) {
			throw new NotFoundException(__('Invalid exam'));
		}
		$conditions = array(
			'Exam.id' => $id,
			'Exam.user_id' => $this->Auth->user('id')
		);
		$contain = array(
			'Item' => 'AnswerOption',
			'User'
		);
		$exam = $this->Exam->find('first', compact('conditions', 'contain'));
		$this->set(compact('exam'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if ($this->Exam->add($this->request->data)) {
				$this->Flash->success(__('The exam has been saved'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The exam could not be saved. Please, try again.'));
			}
		}
		$examFormats = $this->Exam->ExamFormat->find('list');
		$this->set(compact('examFormats'));
	}

/**
 * delete method
 *
 * @param string $id An exam id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Exam->id = $id;
		if (!$this->Exam->exists()) {
			throw new NotFoundException(__('Invalid exam'));
		}
		if ($this->Exam->remove($id)) {
			$this->Flash->success(__('Exam deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Flash->error(__('Exam was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * stevie method
 *
 * @param string $id An exam id
 * @param string $offset An offset
 * @return void
 * @throws NotFoundException
 */
	public function stevie($id = null, $offset = 'overview') {
		/*
		if (!$this->request->is('post'))
		{
			throw new MethodNotAllowedException();
		}
		*/
		$this->Exam->id = $id;
		if (!$this->Exam->exists()) {
			throw new NotFoundException(__('Invalid exam'));
		}

		$exam = $this->Exam->stevie($id, $offset);
		$this->set(compact('exam', 'offset'));
	}

/**
 * report method
 *
 * @param string $id An exam id
 * @return void
 * @throws NotFoundException
 * @throws NotFoundException
 */
	public function report($id) {
		$this->Exam->id = $id;
		if (!$this->Exam->exists()) {
			throw new NotFoundException(__('Invalid exam'));
		}
		$conditions = array('Exam.id' => $id);
		$exam = $this->Exam->find('first', compact('conditions'));
		if (empty($exam['Exam']['report_generated']) || !file_exists(Exam::REPORT_DIRECTORY . $exam['Exam']['id'] . '.pdf')) {
			throw new NotFoundException(__('Invalid exam'));
		}
		$this->response->file(
			Exam::REPORT_DIRECTORY . $exam['Exam']['id'] . '.pdf', array(
				'download' => true,
				'name' => $exam['Exam']['name']
			)
		);
		return $this->response;
	}

/**
 * reanalyse method
 *
 * @param string $id An exam id
 * @return void
 */
	public function reanalyse($id = null) {
		/*
		if (!$this->request->is('post'))
		{
			throw new MethodNotAllowedException();
		}
		$this->Exam->id = $id;
		if (!$this->Exam->exists())
		{
			throw new NotFoundException(__('Invalid exam'));
		}
		*/

		if (empty($this->request->data)) {
			$this->request->data = array('Exam' => array('parent_id' => $id));
		} else {
			if ($id = $this->Exam->scheduleReanalyse($this->request->data)) {
				$this->Flash->success(__('The exam has been scheduled to reanalyse'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The exam could not be reanalysed. Please, try again.'));
			}
		}

		$conditions = array('Item.exam_id' => $this->request->data('Exam.parent_id'));
		$contain = array('AnswerOption');
		$items = $this->Exam->Item->find('all', compact('conditions', 'contain'));
		$this->set(compact('items'));
	}

/**
 * scores method
 *
 * @param string $id An exam id
 * @param string $format A output format
 * @return void
 * @throws NotFoundException
 */
	public function scores($id, $format = null) {
		$this->Exam->id = $id;
		if (!$this->Exam->exists()) {
			throw new NotFoundException(__('Invalid exam'));
		}
		$scores = $this->Exam->scores($id);
		$this->set(compact('scores', 'format'));
	}

/**
 * missings method
 *
 * @param string $id An exam id
 * @return void
 * @throws NotFoundException
 */
	public function missings($id) {
		$this->Exam->id = $id;
		if (!$this->Exam->exists()) {
			throw new NotFoundException(__('Invalid exam'));
		}
		$missings = $this->Exam->missings($id);
		$conditions = array('Exam.id' => $id);
		$exam = $this->Exam->find('first', compact('conditions'));
		$this->set(compact('missings', 'exam'));
	}

}
