<?php
App::uses('AppShell', 'Console/Command');

/**
 * QueueAnalysisToReport Task
 *
 * @property Exam $Exam
 */
class QueueAnalysisToReportTask extends AppShell {

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
		if (empty($data['Exam']['id'])) {
			$this->err(__('Invalid exam'));
			return false;
		} else {
			$this->Exam->id = $data['Exam']['id'];
			if ($this->Exam->exists() && !$this->Exam->field('deleted')) {
				return $this->Exam->report($data['Exam']['id']);
			} else {
				$this->err(__('Invalid exam'));
				return false;
			}
		}
	}

}
