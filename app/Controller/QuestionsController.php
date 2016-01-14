<?php
App::uses('CakeText', 'Utility');
App::uses('DevelopmentPhase', 'Model');
App::uses('AppController', 'Controller');
/**
 * Questions Controller
 *
 * @property Instruction $Instruction
 * @property Question $Question
 * @property Tip $Tip
 */
class QuestionsController extends AppController {

/**
 * An array containing the class names of models this controller uses.
 *
 * @var mixed
 * @see AppController::uses
 */
	public $uses = array('Question', 'Tip', 'Instruction');

/**
 * An array of names of helpers to load
 *
 * @var mixed A single name as a string or a list of names as an array.
 * @see AppController::helpers
 */
	public $helpers = array('CkSource', 'Output');

/**
 * beforeFilter
 *
 * @return void
 * @see AppController::beforeFilter()
 */
	public function beforeFilter() {
		parent::beforeFilter();
		if ($this->request->action == 'analyse' || $this->request->action == 'instruction') {
			$this->Components->disable('Security');
		}
	}

/**
 * analyse method
 *
 * @return void
 */
	public function analyse() {
		//if (!$this->request->is('post'))
		//{
		//	throw new MethodNotAllowedException();
		//}
		$analyses = $this->Question->analyse($this->request->data);
		$this->set(compact('analyses'));
	}

/**
 * instruction method
 *
 * @return void
 */
	public function instruction() {
		//if (!$this->request->is('post'))
		//{
		//	throw new MethodNotAllowedException();
		//}

		$instruction = $this->Instruction->get($this->request->data('Question.development_phase_id'), $this->request->data('Question.question_format_id'));
		$this->set(compact('instruction'));
	}

/**
 * view method
 *
 * @param string $id A question id
 * @return void
 * @throws NotFoundException
 */
	public function view($id = null) {
		$this->Question->id = $id;
		if (!$this->Question->exists()) {
			throw new NotFoundException(__('Invalid question'));
		}
		$referer = $this->Session->read('_App.referer');
		if (empty($referer)) {
			$referer = array('action' => 'index');
		}
		$question = $this->Question->view($id);
		$analyses = $this->Question->analyse($question);
		$this->set(compact('referer', 'question', 'analyses'));
	}

/**
 * add method
 *
 * @param int $tagId A tag id
 * @return void
 */
	public function add($tagId = null) {
		if ($this->request->is('post')) {
			if ($this->Question->add($this->request->data)) {
				$this->Flash->success(__('The question has been saved'));
				return $this->redirect(array('action' => 'view', $this->Question->id));
			} else {
				$this->Flash->error(__('The question could not be saved. Please, try again.'));
			}
		} else {
			if (!empty($tagId)) {
				$this->request->data = array(
					'Tag' => array(
						'Tag' => array($tagId)
					)
				);
			}
		}
		$referer = $this->Session->read('_App.referer');
		if (empty($referer)) {
			$referer = array('action' => 'index');
		}
		$instruction = $this->Instruction->get($this->request->data('Question.development_phase_id'), $this->request->data('Question.question_format_id'));
		$analyses = $this->Question->analyse($this->request->data);
		$questionFormats = $this->Question->QuestionFormat->find('list');
		$developmentPhases = $this->Question->DevelopmentPhase->find('list');
		$tags = $this->Question->QuestionsTag->Tag->getList();
		$startSentences = $this->Question->getStartSentences();
		$this->set(compact('referer', 'instruction', 'analyses', 'questionFormats', 'developmentPhases', 'tags', 'startSentences'));
	}

/**
 * edit method
 *
 * @param string $id A question id
 * @return void
 * @throws NotFoundException
 * @throws ForbiddenException
 */
	public function edit($id = null) {
		$this->Question->id = $id;
		if (!$this->Question->exists()) {
			throw new NotFoundException(__('Invalid question'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Question->update($this->request->data)) {
				$this->Flash->success(__('The question has been saved'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Flash->error(__('The question could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Question->edit($id);
			if (empty($this->request->data)) {
				throw new ForbiddenException();
			}
		}
		$referer = $this->Session->read('_App.referer');
		if (empty($referer)) {
			$referer = array('action' => 'index');
		}
		$instruction = $this->Instruction->get($this->request->data('Question.development_phase_id'), $this->request->data('Question.question_format_id'));
		$analyses = $this->Question->analyse($this->request->data);
		$questionFormats = $this->Question->QuestionFormat->find('list');
		$developmentPhases = $this->Question->DevelopmentPhase->find('list');
		$tags = $this->Question->QuestionsTag->Tag->getList();
		$startSentences = $this->Question->getStartSentences();
		$this->set(compact('referer', 'instruction', 'analyses', 'questionFormats', 'developmentPhases', 'tags', 'startSentences'));
	}

/**
 * delete method
 *
 * @param string $id A question id
 * @return void
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Question->id = $id;
		if (!$this->Question->exists()) {
			throw new NotFoundException(__('Invalid question'));
		}
		if ($this->Question->delete($id)) {
			$this->Flash->success(__('Question deleted'));
			$referer = $this->Session->read('_App.referer');
			if (empty($referer)) {
				$referer = array('action' => 'index');
			}
			return $this->redirect($referer);
		}
		$this->Flash->error(__('Question was not deleted'));
		$referer = $this->Session->read('_App.referer');
		if (empty($referer)) {
			$referer = array('action' => 'index');
		}
		return $this->redirect($referer);
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if (empty($this->request->data['Tag']['Tag'])) {
				return $this->redirect(array('action' => 'index'));
			} else {
				return $this->redirect(array('tag_id' => array_values($this->request->data['Tag']['Tag'])));
			}
		}

		$this->Session->write('_App.referer', $this->request->here(false));

		$options = array('contain' => array('QuestionAnswer'));

		if (!empty($this->request->params['named']['tag_id'])) {
			$options = Hash::merge($options, $this->Question->getFindOptionsForTagIds(array_values($this->request->params['named']['tag_id'])));
			$this->request->data = array('Tag' => array('Tag' => $this->request->params['named']['tag_id']));
		}

		//if ($this->Auth->user('role_id') != Role::ADMIN)
		{
			$options['conditions'][] = array('Question.user_id' => $this->Auth->user('id'));
		}
		$options['contain'][] = 'Tag';

		$options['limit'] = 25;
		$this->paginate = $options;
		//$this->paginate = compact('conditions', 'contain', 'group', 'limit');
		$questions = $this->paginate();
		$tags = $this->Question->Tag->getList();
		foreach ($tags as $i => $tag) {
			$tags[$i] = sprintf('<span class="label label-info" title="%s">%s</span>', $tag, CakeText::truncate($tag, 20, array('ellipsis' => '...')));
		}

		$tip = $this->Tip->find(
			'first', array(
				'order' => 'RAND()'
			)
		);

		$this->set(compact('questions', 'tags', 'tip'));
	}

/**
 * download method
 *
 * @return void
 */
	public function download() {
		$options = array('contain' => array());

		if (!empty($this->request->params['named']['tag_id'])) {
			$options = array_merge($options, $this->Question->getFindOptionsForTagIds(array_values($this->request->params['named']['tag_id'])));
			$this->request->data = array('Tag' => array('Tag' => $this->request->params['named']['tag_id']));
		}

		$options['conditions'][] = array('Question.user_id' => $this->Auth->user('id'));
		$options['contain'][] = 'QuestionAnswer';
		$options['contain'][] = 'Tag';

		$questions = $this->Question->find('all', $options);

		$showCorrectAnswerInline = (!empty($this->request->query['show_correct_answer_inline']) && $this->request->query['show_correct_answer_inline'])?true:false;
		$showCorrectAnswerAtTheEnding = (!empty($this->request->query['show_correct_answer_at_the_ending']) && $this->request->query['show_correct_answer_at_the_ending'])?true:false;
		$showAllTagsPerQuestion = (!empty($this->request->query['show_all_tags_per_question']) && $this->request->query['show_all_tags_per_question'])?true:false;
		$showQuestionFormat = (!empty($this->request->query['show_question_format']) && $this->request->query['show_question_format'])?true:false;
		//	show_direct_comments=0&

		$this->set(compact('questions', 'showCorrectAnswerInline', 'showCorrectAnswerAtTheEnding', 'showAllTagsPerQuestion', 'showQuestionFormat'));
		$this->layout = 'download';
	}

	/*
	public function export_qmp()
	{
		$options = array('contain' => array());

		if (!empty($this->request->params['named']['tag_id']))
		{
			$options = array_merge($options, $this->Question->getFindOptionsForTagIds(array_values($this->request->params['named']['tag_id'])));
			$this->request->data = array('Tag' => array('Tag' => $this->request->params['named']['tag_id']));
		}

		$options['conditions'][] = array('Question.user_id' => $this->Auth->user('id'));
		$options['contain'][] = 'QuestionAnswer';
		$options['contain'][] = 'Tag';

		$questions = $this->Question->find('all', $options);
		$result = $this->Question->toQMP($questions);


		$this->set(compact('result'));
		$this->set('_serialize', 'result');
	}
	*/

/**
 * export_qmp method
 *
 * @return void
 */
	public function export_qmp() {
		$options = array('contain' => array());

		if (!empty($this->request->params['named']['tag_id'])) {
			$options = array_merge($options, $this->Question->getFindOptionsForTagIds(array_values($this->request->params['named']['tag_id'])));
			$this->request->data = array('Tag' => array('Tag' => $this->request->params['named']['tag_id']));
		}

		$options['conditions'][] = array('Question.user_id' => $this->Auth->user('id'));
		$options['contain'][] = 'QuestionAnswer';
		$options['contain'][] = 'Tag';

		$questions = $this->Question->find('all', $options);

		list($data, $files) = $this->Question->toQMP($questions);

		$this->set(compact('data'));
		$this->response->header('Content-Disposition', 'attachment; filename="export.xml"');
	}

/**
 * export_respondus method
 *
 * @return void
 */
	public function export_respondus() {
		$options = array('contain' => array());

		if (!empty($this->request->params['named']['tag_id'])) {
			$options = array_merge($options, $this->Question->getFindOptionsForTagIds(array_values($this->request->params['named']['tag_id'])));
			$this->request->data = array('Tag' => array('Tag' => $this->request->params['named']['tag_id']));
		}

		$options['conditions'][] = array('Question.user_id' => $this->Auth->user('id'));
		$options['contain'][] = 'QuestionAnswer';
		$options['contain'][] = 'Tag';

		$questions = $this->Question->find('all', $options);

		list($data, $files) = $this->Question->toRespondus($questions);

		$manifest = $this->__createImsManifest($files);

		// Prepare File
		$file = tempnam("tmp", "zip");
		$zip = new ZipArchive();
		$zip->open($file, ZipArchive::OVERWRITE);

		// Staff with content
		$zip->addFromString('imsmanifest.xml', $manifest);
		$zip->addFromString('data.xml', $data);
		if (!empty($files)) {
			foreach ($files as $filename) {
				if (file_exists(ROOT . DS . 'data' . DS . $filename)) {
					$zip->addFile(ROOT . DS . 'data' . DS . $filename, $filename);
				}
			}
		}

		// Close and send to users
		$zip->close();
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($file));

		header('Content-Disposition: attachment; filename="export.zip"');

		readfile($file);
		unlink($file);
		exit;

		/*
		$this->response->header('Content-Disposition', 'attachment; filename="export.zip"');

		$this->response->type(array('zip' => 'application/zip'));

		$this->response->body(file_get_contents($file));
		unlink($file);
		$this->response->send();
		exit;
		*/
	}

/**
 * Generates XML of IMS manifest
 *
 * @param array $files Files to include in manifest
 * @param string $name Name of the manifest
 * @return string
 */
	private function __createImsManifest($files, $name = 'Test') {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$manifest = $dom->createElement("manifest");
		$dom->appendChild($manifest);

		$identifier = $dom->createAttribute("identifier");
		$manifest->appendChild($identifier);
		$identifier->appendChild($dom->createTextNode('MANIFEST1'));

		$metadata = $dom->createElement("metadata");
		$manifest->appendChild($metadata);

		$schema = $dom->createElement("schema");
		$metadata->appendChild($schema);
		$schema->appendChild($dom->createTextNode('IMS Content'));

		$schemaversion = $dom->createElement("schemaversion");
		$metadata->appendChild($schemaversion);
		$schemaversion->appendChild($dom->createTextNode('1.1.3'));

		$imsmdlom = $dom->createElement("imsmd:lom");
		$metadata->appendChild($imsmdlom);

		$imsmdgeneral = $dom->createElement("imsmd:general");
		$imsmdlom->appendChild($imsmdgeneral);

		$imsmdidentifier = $dom->createElement("imsmd:identifier");
		$imsmdgeneral->appendChild($imsmdidentifier);

		$imsmdlangstring = $dom->createElement("imsmd:langstring");
		$imsmdidentifier->appendChild($imsmdlangstring);
		$imsmdlangstring->appendChild($dom->createTextNode('305BD1E39978461796D8E242C4442D6E'));

		$xmllang = $dom->createAttribute("xml:lang");
		$imsmdlangstring->appendChild($xmllang);
		$xmllang->appendChild($dom->createTextNode('en-US'));

		$imsmdtitle = $dom->createElement("imsmd:title");
		$imsmdgeneral->appendChild($imsmdtitle);

		$imsmdlangstring = $dom->createElement("imsmd:langstring");
		$imsmdtitle->appendChild($imsmdlangstring);
		$imsmdlangstring->appendChild($dom->createTextNode($name));

		$xmllang = $dom->createAttribute("xml:lang");
		$imsmdlangstring->appendChild($xmllang);
		$xmllang->appendChild($dom->createTextNode('en-US'));

		$organizations = $dom->createElement("organizations");
		$manifest->appendChild($organizations);

		$default = $dom->createAttribute("default");
		$organizations->appendChild($default);
		$default->appendChild($dom->createTextNode('EXAM1'));

		$organization = $dom->createElement("organization");
		$organizations->appendChild($organization);

		$identifier = $dom->createAttribute("identifier");
		$organization->appendChild($identifier);
		$identifier->appendChild($dom->createTextNode('EXAM1'));

		$structure = $dom->createAttribute("structure");
		$organization->appendChild($structure);
		$structure->appendChild($dom->createTextNode('hierarchical'));

		$title = $dom->createElement("title");
		$organization->appendChild($title);
		$title->appendChild($dom->createTextNode('default'));

		$item = $dom->createElement("item");
		$organization->appendChild($item);

		$identifier = $dom->createAttribute("identifier");
		$item->appendChild($identifier);
		$identifier->appendChild($dom->createTextNode('ITEM1'));

		$identifierref = $dom->createAttribute("identifierref");
		$item->appendChild($identifierref);
		$identifierref->appendChild($dom->createTextNode('RESOURCE1'));

		$title = $dom->createElement("title");
		$item->appendChild($title);
		$title->appendChild($dom->createTextNode('Exam 1'));

		$resources = $dom->createElement("resources");
		$manifest->appendChild($resources);

		$resource = $dom->createElement("resource");
		$resources->appendChild($resource);

		$identifier = $dom->createAttribute("identifier");
		$resource->appendChild($identifier);
		$identifier->appendChild($dom->createTextNode('RESOURCE1'));

		$type = $dom->createAttribute("type");
		$resource->appendChild($type);
		$type->appendChild($dom->createTextNode('imsqti_xmlv1p1'));

		$href = $dom->createAttribute("href");
		$resource->appendChild($href);
		$href->appendChild($dom->createTextNode('data.xml'));

		$file = $dom->createElement("file");
		$resources->appendChild($file);
		$href = $dom->createAttribute("href");
		$file->appendChild($href);
		$href->appendChild($dom->createTextNode('data.xml'));

		foreach ($files as $file) {
			$fileelm = $dom->createElement("file");
			$resources->appendChild($fileelm);
			$href = $dom->createAttribute("href");
			$fileelm->appendChild($href);
			$href->appendChild($dom->createTextNode($file));
		}
		return $dom->saveXML();
	}

}
