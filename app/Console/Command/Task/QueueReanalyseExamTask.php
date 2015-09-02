<?php
App::uses('Exam', 'Model');
/**
 * QueueReanalyseExamTask Shell
 *
 * @property Exam $Exam
 */
class QueueReanalyseExamTask extends Shell {

	public $uses = array('Exam');

/**
 * Executed, when a worker is executing this task.
 *
 * @param mixed $data Job data (passed on creation)
 * @return bool Success
 */
	public function run($data) {
		if (empty($data['Exam']['parent_id'])) {
			$this->err(__('Invalid exam'));
			return false;
		} else {
			$this->Exam->id = $data['Exam']['parent_id'];
			if ($this->Exam->exists() && !$this->Exam->field('deleted')) {
				return $this->Exam->reanalyse($data);
			} else {
				$this->err(__('Invalid exam'));
				return false;
			}
		}
	}

}
