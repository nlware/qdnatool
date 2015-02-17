<?php
App::uses('AppModel', 'Model');
/**
 * QuestionsTag Model
 *
 * @property Question $Question
 * @property Tag $Tag
 */
class QuestionsTag extends AppModel {

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

/**
 * Called before each save operation, after validation. Return a non-true result
 * to halt the save.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if the operation should continue, false if it should abort
 * @see Model::save()
 */
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

/**
 * remove method
 *
 * @param int $id A QuestionsTag ID
 * @return bool
 */
	public function remove($id) {
		if ($this->__allowed($id)) {
			return $this->delete($id);
		}
		return false;
	}

/**
 * __allowed
 *
 * @param int $id A QuestionsTag ID
 * @return bool
 */
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
