<?php
App::uses('Exam', 'Model');
/**
 * QueueAnalyseExamTask Shell
 *
 * @property Exam $Exam
 */
class QueueAnalyseExamTask extends Shell {

	public $uses = array('Exam');

/**
 * Executed, when a worker is executing this task.
 *
 * @param mixed $data Job data (passed on creation)
 * @return boolean Success
 */
	public function run($data) {
		if (empty($data['Exam']['id'])) {
			$this->err(__('Invalid exam'));
			return false;
		} else {
			$this->Exam->id = $data['Exam']['id'];
			if ($this->Exam->exists() && !$this->Exam->field('deleted')) {
				return $this->Exam->analyse($data['Exam']['id']);
			} else {
				$this->err(__('Invalid exam'));
				return false;
			}
		}
	}
}