<?php
define('IMAGE_UPLOAD_DIRECTORY', ROOT . DS . 'data' . DS . 'images' . DS);
App::uses('AppModel', 'Model');
/**
 * Image Model
 *
 * @property Question $Question
 */
class Image extends AppModel {

	const UPLOAD_DIRECTORY = IMAGE_UPLOAD_DIRECTORY;

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'question_id'
		)
	);
}