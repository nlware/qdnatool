<?php
App::uses('AppShell', 'Console/Command');

/**
 * QueueReanalyseExam Task
 *
 * @property Exam $Exam
 */
class QueueReanalyseExamTask extends AppShell {

/**
 * An array of names of models to load.
 *
 * @var array
 */
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
