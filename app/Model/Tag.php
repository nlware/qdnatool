<?php
App::uses('AppModel', 'Model');
/**
 * Tag Model
 *
 * @property Question $Question
 */
class Tag extends AppModel {

	public $order = array('name' => 'ASC');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'last' => true,
				'message' => 'This field cannot be left blank'
			),
			'required' => array(
				'rule' => 'notEmpty',
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

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		/*,
		'Question' => array
		( 'className' => 'Question',
			'with' => 'QuestionsTag',
			'foreignKey' => 'tag_id',
			'associationForeignKey' => 'question_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => 'QuestionsTag.order',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
		*/
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

	public $hasOne = array(
		'QuestionsTagFilter' => array(
			'className' => 'QuestionsTag'
		)
	);

	public function beforeValidate($options = array()) {
		if ($userId = AuthComponent::user('id')) {
			if (!$this->exists()) $this->data[$this->alias]['user_id'] = $userId;
		}
		return true;
	}

	public function beforeSave($options = array()) {
		if (empty($this->data[$this->alias]['id'])) {
			$tag = $this->find(
				'first', array(
					'conditions' => array(
						'Tag.name' => $this->data[$this->alias]['name'],
						'Tag.user_id' => AuthComponent::user('id')
					)
				)
			);
			if (!empty($tag)) {
				$this->id = $tag['Tag']['id'];
				unset($this->data['Tag']);
			}
		}
	}

	/*
	public function edit($id)
	{
		$options = array
		( 'conditions' => array
			( 'Tag.id' => $id,
				'Tag.user_id' => AuthComponent::user('id')
			),
			'contain' => array
			( 'QuestionsTag' => 'Question'
			)
		);
		$tag = $this->find('first', $options);
		if (!empty($tag['QuestionsTag']))
		{
			foreach ($tag['QuestionsTag'] as $questionsTag)
			{
				$tag['Question'][] = $questionsTag['Question'];
			}
		}
		return $tag;
	}

	public function update($data)
	{
		if (!$this->saveAll($data, array('validate' => 'only'))) return false;

		$tag = $this->edit($data['Tag']['id']);
		if (!empty($tag['Tag']))
		{
			foreach ($tag['QuestionsTag'] as $questionsTag)
			{
				if (!empty($data['Tag']['Question']) && is_array($data['Tag']['Question']) && in_array($questionsTag['question_id'], $data['Tag']['Question']))
				{
					foreach ($data['Tag']['Question'] as $i => $value)
					{
						if ($value == $questionsTag['question_id'])
						unset($data['Tag']['Question'][$i]);
					}
				}
				else
				{ $this->QuestionsTag->delete($questionsTag['id'], false);
				}
			}
		}
		if (!empty($data['Tag']['Question']))
		{
			foreach ($data['Tag']['Question'] as $questionId)
			{
				$newQuestionTag= array
				( 'QuestionsTag' => array
					( 'question_id' => $questionId,
						'tag_id' => $data['Tag']['id'],
						'order' => null
					)
				);
				$this->QuestionsTag->create();
				$this->QuestionsTag->save($newQuestionTag);
			}
		}
		unset($data['Question']);

		$this->id = $data['Tag']['id'];
		return $this->save($data, false);
	}

	public function view($id)
	{
		$options = array
		( 'conditions' => array
			( 'Tag.id' => $id,
				'Tag.user_id' => AuthComponent::user('id')
			),
			'contain' => array
			( 'QuestionsTag' => array
				( 'Question' => array
					( 'QuestionFormat',
						'DevelopmentPhase',
						'User'
					)
				)
			)
		);
		return $this->find('first', $options);
	}

	public function add($data)
	{
		return $this->save($data);
	}

	public function delete($id = null, $cascade = true)
	{
		if (AuthComponent::user('role_id') != Role::ADMIN)
		{
			if (!in_array($id, $this->getMineIds())) return false;
		}
		return parent::delete($id);
	}
	*/

	public function getList() {
		return $this->find(
			'list', array(
				'conditions' => array(
					'Tag.user_id' => AuthComponent::user('id')
				)
			)
		);
	}

	public function cleanupUnused($tagIds = array()) {
		$conditions = array('QuestionsTagFilter.id' => null);
		if (!empty($tagIds)) $conditions['Tag.id'] = $tagIds;

		$tags = $this->find(
			'all', array(
				'conditions' => $conditions,
				'contain' => 'QuestionsTagFilter'
			)
		);
		$tagIds = Set::extract('/Tag/id', $tags);
		$this->deleteAll(array('Tag.id' => $tagIds), false, false);
	}
}