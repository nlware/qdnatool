<?php
App::uses('AppModel', 'Model');
/**
 * Tag Model
 *
 * @property QuestionsTag $QuestionsTag
 * @property QuestionsTag $QuestionsTagFilter
 */
class Tag extends AppModel {

/**
 * order
 *
 * @var string
 */
	public $order = array('name' => 'ASC');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'last' => true,
				'message' => 'This field cannot be left blank'
			),
			'required' => array(
				'rule' => 'notBlank',
				'required' => true,
				'on' => 'create'
			)
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => 'create',
				'message' => 'This field cannot be left blank'
			)
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'QuestionsTag' => array(
			'className' => 'QuestionsTag',
			'foreignKey' => 'tag_id',
			'dependent' => true
		)
	);

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'QuestionsTagFilter' => array(
			'className' => 'QuestionsTag'
		)
	);

/**
 * beforeValidate method
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @see Model::beforeValidate()
 */
	public function beforeValidate($options = array()) {
		if ($userId = AuthComponent::user('id')) {
			if (!$this->exists()) {
				$this->data[$this->alias]['user_id'] = $userId;
			}
		}
		return true;
	}

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
			$conditions = array(
				'Tag.name' => $this->data[$this->alias]['name'],
				'Tag.user_id' => AuthComponent::user('id')
			);
			$tag = $this->find('first', compact('conditions'));
			if (!empty($tag)) {
				$this->id = $tag['Tag']['id'];
				unset($this->data['Tag']);
			}
		}
	}

/**
 * Get list
 *
 * @return array
 */
	public function getList() {
		$conditions = array('Tag.user_id' => AuthComponent::user('id'));
		return $this->find('list', compact('conditions'));
	}

/**
 * Cleanup unused tags
 *
 * @param array $tagIds Array with tag IDs
 * @return void
 */
	public function cleanupUnused($tagIds = array()) {
		$conditions = array('QuestionsTagFilter.id' => null);
		if (!empty($tagIds)) {
			$conditions['Tag.id'] = $tagIds;
		}

		$contain = array('QuestionsTagFilter');
		$tags = $this->find('all', compact('conditions', 'contain'));
		$tagIds = Hash::extract($tags, '{n}.Tag.id');
		$this->deleteAll(array('Tag.id' => $tagIds), false);
	}

}
