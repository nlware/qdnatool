<?php
App::uses('AppModel', 'Model');
/**
 * QuestionsTag Model
 *
 * @property Question $Question
 * @property Tag $Tag
 */
class QuestionsTag extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'question_id'
		),
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'tag_id'
		)
	);

	public function beforeSave($options = array()) {
		if (empty($this->data[$this->alias]['id'])) {
			$questionsTag = $this->find(
				'first', array(
					'conditions' => array(
						'QuestionsTag.question_id' => $this->data[$this->alias]['question_id'],
						'QuestionsTag.tag_id' => $this->data[$this->alias]['tag_id']
					)
				)
			);
			if (!empty($questionsTag)) {
				$this->id = $questionsTag['QuestionsTag']['id'];
				unset($this->data[$this->alias]);
			}
		}
	}

	public function remove($id) {
		if ($this->__allowed($id)) {
			return $this->delete($id);
		}
		return false;
	}

	private function __allowed($id) {
		return ($this->find(
			'count', array(
				'conditions' => array(
					'QuestionsTag.id' => $id,
					'QuestionsTag.tag_id' => $this->Tag->getMineIds()
				)
			)
		) > 0);
	}
}