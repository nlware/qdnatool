<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('CkSourceHelper', 'View/Helper');

/**
 * CkSourceHelper Test Case
 */
class CkSourceHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->CkSource = new CkSourceHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CkSource);

		parent::tearDown();
	}

/**
 * testCkeditor method
 *
 * @return void
 */
	public function testCkeditor() {
		$fieldName = 'fieldName';
		$result = $this->CkSource->ckeditor($fieldName);
		$expected = array(
			'div' => array('class' => 'input textarea'),
			'label' => array('for' => $fieldName),
			'Field Name',
			'/label',
			'textarea' => array('name', 'id' => $fieldName, 'cols', 'rows'),
			'/textarea',
			'/div',
		);
		$this->assertTags($result, $expected);
	}

}
