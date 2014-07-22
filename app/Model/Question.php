<?php
App::uses('AppModel', 'Model');
App::uses('DevelopmentPhase', 'Model');
App::uses('Role', 'Model');
App::uses('QuestionFormat', 'Model');
/**
 * Question Model
 *
 * @property QuestionFormat $QuestionFormat
 * @property DevelopmentPhase $DevelopmentPhase
 * @property User $User
 * @property Image $Image
 * @property QuestionAnswer $QuestionAnswer
 * @property QuestionsTag $QuestionsTag
 * @property Tag $Tag
 * @property QuestionsTag $QuestionsTagFilter
 */
class Question extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * actsAs behaviors
 *
 * @var array
 */
	public $actsAs = array('I18n');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'code' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => 'create',
				'message' => 'This field cannot be left blank'
			)
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => 'create',
				'message' => 'This field cannot be left blank'
			)
		),
		'question_format_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => 'create',
				'message' => 'This field cannot be left blank'
			)
		),
		'development_phase_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => 'create',
				'message' => 'This field cannot be left blank'
			)
		),
		'stimulus' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => 'create',
				'message' => 'This field cannot be left blank'
			)
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'QuestionFormat' => array(
			'className' => 'QuestionFormat',
			'foreignKey' => 'question_format_id'
		),
		'DevelopmentPhase' => array(
			'className' => 'DevelopmentPhase',
			'foreignKey' => 'development_phase_id'
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'question_id',
			'dependent' => false
		),
		'QuestionAnswer' => array(
			'className' => 'QuestionAnswer',
			'foreignKey' => 'question_id',
			'dependent' => true,
			'order' => 'QuestionAnswer.order'
		),
		'QuestionsTag' => array(
			'className' => 'QuestionsTag',
			'foreignKey' => 'question_id',
			'dependent' => true
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Tag' => array(
			'className' => 'Tag',
			'with' => 'QuestionsTag',
			'foreignKey' => 'question_id',
			'associationForeignKey' => 'tag_id',
			'unique' => 'keepExisting',
			'order' => array('Tag.name' => 'ASC')
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
 * notContains
 *
 * @param array $check Value to validate
 * @param array $keywords Keywords
 * @return boolean
 */
	public function notContains($check, $keywords) {
		$value = array_values($check);
		$value = $value[0];
		if (!is_array($keywords)) {
			$keywords = array($keywords);
		}
		foreach ($keywords as $keyword) {
			if (strpos($value, $keyword) !== false) {
				return false;
			}
		}
		return true;
	}

/**
 * beforeValidate method
 *
 * @param array $options Options passed from Model::save().
 * @return boolean True if validate operation should continue, false to abort
 * @see Model::beforeValidate()
 */
	public function beforeValidate($options = array()) {
		$this->__deletedQuestionsTagIds = array();
		$this->__deletedQuestionAnswerIds = array();
		if ($userId = AuthComponent::user('id')) {
			if (!$this->exists()) {
				$this->data[$this->alias]['user_id'] = $userId;
			}
		}
		if (!empty($this->data['QuestionsTag'])) {
			foreach ($this->data['QuestionsTag'] as $i => $questionsTag) {
				if (isset($questionsTag['destroy']) && $questionsTag['destroy']) {
					if (!empty($questionsTag['id'])) {
						$this->__deletedQuestionsTagIds[] = $questionsTag['id'];
					}
					unset($this->data['QuestionsTag'][$i]);
				}
			}
		}
		if (!empty($this->data['QuestionAnswer'])) {
			foreach ($this->data['QuestionAnswer'] as $i => $questionAnswer) {
				if (isset($questionAnswer['destroy']) && $questionAnswer['destroy']) {
					if (!empty($questionAnswer['id'])) {
						$this->__deletedQuestionAnswerIds[] = $questionAnswer['id'];
					}
					unset($this->data['QuestionAnswer'][$i]);
				}
			}
			foreach ($this->data['QuestionAnswer'] as $i => $questionAnswer) {
				$this->data['QuestionAnswer'][$i]['order'] = $i + 1;
			}
		}

		if (isset($this->data[$this->alias]['stimulus'])) {
			$this->data[$this->alias]['name'] = strip_tags($this->data[$this->alias]['stimulus']);
			$this->data[$this->alias]['name'] = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $this->data[$this->alias]['name']);
			$this->data[$this->alias]['name'] = trim($this->data[$this->alias]['name']);
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
		$this->__oldTagIds = array();
		if (!empty($this->data['Question']['id'])) {
			$this->__oldTagIds = $this->__getTagsIds($this->data['Question']['id']);
		}
		return true;
	}

/**
 * Called after each successful save operation.
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @see Model::save()
 */
	public function afterSave($created, $options = array()) {
		if (!$created) {
			if (!empty($this->__deletedQuestionsTagIds)) {
				$this->QuestionsTag->deleteAll(array('QuestionsTag.id' => $this->__deletedQuestionsTagIds));
			}
			if (!empty($this->__deletedQuestionAnswerIds)) {
				$this->QuestionAnswer->deleteAll(array('QuestionAnswer.id' => $this->__deletedQuestionAnswerIds));
			}
			if (!empty($this->__oldTagIds)) {
				$this->Tag->cleanupUnused($this->__oldTagIds);
			}
		}
	}

/**
 * Get list of tag ids
 *
 * @param integer $id An question id
 * @return array List of tag ids
 */
	private function __getTagsIds($id) {
		return $this->QuestionsTag->find(
			'list', array(
				'fields' => array(
					'tag_id',
					'tag_id'
				),
				'conditions' => array(
					'QuestionsTag.question_id' => $this->data['Question']['id']
				)
			)
		);
	}

/**
 * Get find options for given Tag IDs
 *
 * @param array $tagIds Tag IDs
 * @return array
 */
	public function getFindOptionsForTagIds($tagIds = array()) {
		$options = array();
		$options['contain'] = array(
			'QuestionsTagFilter' => array(
				'conditions' => array(
					'QuestionsTagFilter.tag_id' => $tagIds
				)
			)
		);
		$options['conditions'] = array('NOT' => array('QuestionsTagFilter.id' => null));
		$options['group'] = 'Question.id';

		return $options;
	}

/**
 * analyse method
 *
 * @param array $question Question data
 * @return array Messages
 */
	public function analyse($question) {
		$messages = array();
		if (!empty($question['Question'])) {
			// Samengesteld antwoord maken en checks op uit te voeren
			$wordCountStimulus = $this->__wordCount($question['Question']['stimulus']);

			$answers = $question['Question']['answer'];

			if (!empty($question['QuestionAnswer'])) {
				$answers .= ' ' . implode(' ', Set::extract('/QuestionAnswer/name', $question));
			}

			if ($question['Question']['development_phase_id'] == DevelopmentPhase::CONVERGE) {
				// Controleer alle Stimulus_RT invoer
				if ($wordCountStimulus <= 8) {
					$messages[] = '<p>Uw stimulus bestaat uit minder dan tien woorden. Stelt u wel een <i>vraag?</i></p>
	<p>Het komt vaak voor dat heel korte stimuli samengaan met stellingvragen of vragen waar niet echt een vraag of probleem wordt gepresenteerd.</p>
	<p>Klik <a href="http://docs.qdnatool.org/2012/11/26/wat-kan-er-beter-aan-deze-vraag-2/" target="_blank">hier</a> voor een voorbeeld van stellingvragen.</p>
	<p>Klik <a href="http://docs.qdnatool.org/?p=489" target="_blank">hier</a> voor een voorbeeld van een vraag zonder probleem.</p>
	<p>Klik <a href="http://docs.qdnatool.org/ontwerpen/1-4-voor-en-nadelen-open-en-gesloten-vragen/multiple-choice-vraag/convergeer-multiple-choicevragen/convergeer-multiple-choicevragen-vervolg-1/" target="_blank">hier</a> voor	algemene informatie om toetsvragen zo helder mogelijk te maken.</p>';
				}
				if ($wordCountStimulus >= 40) {
					$messages[] = 'Uw stimulus bestaat uit meer dan veertig woorden. Is uw schrijfstijl niet te breedsprakig? Probeert u uw studenten niet iets nieuws te leren? Geeft u niet - in feite - irrelevante informatie? <br>Hou uw stimulus beknopt en vraag hetgeen u wilt weten van de student zo rechtstreeks mogelijk. Dat voorkomt veel interpretatiefouten door overbodig leeswerk.';
				}
				if ($this->__contains($question['Question']['stimulus'], 'elke bewering is waar')) {
					$messages[] = 'Heeft u alleen de tekst \'welke bewering is waar\' in uw vraag opgenomen?<br><br> De stam van de vraag is daarmee waarschijnlijk niet in de vorm van een vraag of probleem gesteld. De alternatieven zijn mogelijk stellingen waarvan er slechts één correct is. De student moet in feite nu bij elk alternatief raden wat de vragenmaker bedoelt.';
				}
				$keywords = array(
					"telling 1",
					"telling A",
					"telling a",
					"telling I",
					"telling 1)",
					"telling A)",
					"telling a)",
					"telling I)",
					"ptie 1)",
					"ptie A is",
					"ptie a is",
					"ptie I is",
					"ptie 1) is",
					"ptie A)",
					"ptie a)",
					"ptie I)",
					"tspraak 1",
					"tspraak A",
					"tspraak a",
					"tspraak I",
					"tspraak 1)",
					"tspraak A)",
					"tspraak a)",
					"tspraak I)",
					"telling I)"
				);
				if ($this->__contains($question['Question']['stimulus'], $keywords)) {
					$messages[] = 'U heeft waarschijnlijk een meerkeuzevraag gemaakt met daarin 2 stellingen verwerkt. U vraagt daarbij misschien welke combinatie van stellingen correct is. Het probleem van een dergelijke vraag is dat u waarschijnlijk twee onderwerpen in één vraag behandelt. Daarmee weet u achteraf niet precies wat de studenten wel of niet weet. Ook resulteren dergelijke vragen vaak in lage correlatiewaarden. Het is beter om deze vraag te splitsen in twee juist/onjuist vragen of in een meerkeuzevraag met duidelijk één goed alternatief. Gebruik stellingenvragen alleen als de onderwerpen over hetzelfde onderwerp gaan. Zie o.a. ook http://testdevelopment.nl/qdst/qdst-nl/prompts/a12.htm';
				}

				if ($this->__contains($question['Question']['stimulus'], array("at is nu juis", "at is juis"))) {
					$messages[] = 'U heeft waarschijnlijk de tekst \'wat is nu juist\' opgenomen in uw vraag. Nu moet de student bepalen welke van de gegeven alternatieven in overeenstemming is met dat gegeven. Dat is onzorgvuldig. Verwoord dit preciezer. Bijvoorbeeld: \'Op basis van dit gegeven, welke van onderstaande conclusie is dan correct\', of \'Welke van volgende stellingen is een correcte gevolgtrekking van dit gegeven\'.';
				}

				if ($this->__contains($question['Question']['stimulus'], array("niet", "NIET", "geen", "GEEN"))) {
					$messages[] = 'U heeft waarschijnlijk in de tekst een ontkenning opgenomen door het woord \'niet\' te gebruiken. Uw vraag is wellicht negatief gesteld. Is het mogelijk de vraag positief te formuleren? Zo niet, <u>onderstreep</u> dan in ieder geval de ontkenning';
				}

				if ($this->__contains($question['Question']['stimulus'], "geen")) {
					$messages[] = 'U heeft waarschijnlijk de tekst een ontkenning opgenomen. Uw vraag is wellicht negatief gesteld. Is het mogelijk de vraag positief te formuleren? Zo niet, <u>onderstreep</u> dan in ieder geval de ontkenning.';
				}

				$keywords = array(
					"ooit",
					"lleen",
					"een enkele",
					"llemaal",
					"ltijd"
				);
				if ($this->__contains($question['Question']['stimulus'], $keywords)) {
					$messages[] = 'U heeft in de tekst het woord \'nooit\', \'alleen\', \'geen enkele\', \'allemaal\' of \'altijd\' gebruikt. Probeer de vraag of de alternatieven zo te formuleren dat een dergelijke absolute aanduiding niet gebruikt hoeft te worden. Immers, er zijn altijd uitzondering denkbaar waardoor een dergelijk alternatief in principe altijd goed gerekend zou moeten worden. Bovendien weten studenten dat dergelijke alternatieven juist vaak niet correct zijn.';
				}

				if ($this->__contains($question['Question']['stimulus'], array("moeten", "dienen", "zinloos"))) {
					$messages[] = 'U heeft in de tekst het woord \'moeten\', \'dienen\' of \'zinloos\' gebruikt. Dit is een \'normatieve\' aanduiding waardoor impliciet naar een mening wordt gevraagd. Probeer of vraag of de alternatieven zo te formuleren dat deze aanduiding niet nodig is.';
				}

				$keywords = array(
					"isschien",
					"kan",
					"Kan",
					"oms",
					"n het algemeen",
					"en paar",
					"nkele"
				);
				if ($this->__contains($question['Question']['stimulus'], $keywords)) {
					$messages[] = 'U heeft in de tekst het woord \'kan\', \'misschien\', \'soms\', \'in het algemeen\', \'een paar\' of \'enkele\' gebruikt. Dergelijke woorden zijn vage aanduidingen. Die kunnen door studenten verschillend worden geïnterpreteerd. Gebruik deze woorden niet. Wees precies in uw aanduidingen.';
				}

				if ($this->__contains($question['Question']['stimulus'], array("Men", "men"))) {
					$keywords = array(
						"mens",
						"Mens",
						"omen",
						"amen",
						"emen",
						"ment",
						"mend",
						"meng",
						"menk"
					);
					if (!$this->__contains($question['Question']['stimulus'], $keywords)) {
						$messages[] = 'U heeft waarschijnlijk de tekst \'men\' opgenomen in uw vraag. Het kan zijn dat uw vraag naar \'Wat vond men van .....\'. U vraag dus naar een mening. Zorg ervoor dat het duidelijk is van wie die mening is door de naam te vermelden.';
					}
				}

				// Check Answers
				if ( ($question['Question']['question_format_id'] == QuestionFormat::MULTIPLE_CHOICE) ||
					($question['Question']['question_format_id'] == QuestionFormat::MULTIPLE_RESPONSE)) {
					$wordCountPerAnswer = array();
					if (!empty($question['QuestionAnswer'])) {
						foreach ($question['QuestionAnswer'] as $questionAnswer) {
							$wordCountPerAnswer[] = $this->__wordCount($questionAnswer['name']);
						}
					}

					$averageWordCount = $this->__average($wordCountPerAnswer);
					if (!empty($wordCountPerAnswer[0]) && $wordCountPerAnswer[0] >= 6) {
						foreach ($wordCountPerAnswer as $wordCount) {
							if ($wordCount >= 1.2 * $averageWordCount) {
								$messages[] = 'Één van de antwoordalternatieven bevat 20 procent meer woorden dan het gemiddelde antwoordalternatief. Dit kan er onbedoeld op wijzen dat dit het correcte alternatief is. Het probleem is vaak dat onervaren vragenmakers vergeten dat studenten dit antwoord als correct herkennen omdat een correct antwoord vaak meerdere toevoegingen en nuances nodig heeft om geheel correct te zijn.<br>Zorg ervoor dat de incorrecte alternatieven ook ongeveer even lang zijn als het correcte alternatief';
								break;
							}
						}
					}

					$problemExists = true;
					if (!empty($question['QuestionAnswer'])) {
						foreach ($question['QuestionAnswer'] as $i => $questionAnswer) {
							if (strlen($questionAnswer['name']) < 5) {
								$problemExists = false;
								break;
							} elseif ($i > 0) {
								if (substr($question['QuestionAnswer'][0]['name'], 0, 5) != substr($questionAnswer['name'], 0, 5)) {
									$problemExists = false;
									break;
								}
							}
							$wordCountPerAnswer[] = $this->__wordCount($questionAnswer['name']);
						}
					}
					if ($problemExists) {
						$messages[] = 'U begint de antwoordalternatieven met exact dezelfde letters cq.woorden. Het is beter om dit woord (of deze woorden) te verwerken in de stimulus zodat u herhaling van woorden voorkomt.';
					}
				}
				if ($this->__contains($answers, "lle bovenstaande")) {
					$messages[] = 'U heeft de alternatieven de tekst \'alle bovenstaande\' opgenomen. Dat levert vaak vragen op de minder studenten goed hebben, maar ook minder goed de goede van de slechte onderscheiden. Gebruik deze constructie alleen als er sprake is van een probleem waarbij het gelijktijdig aanwezig zijn van verschillende elementen/condities een noodzakelijke voorwaarde zijn om het gegeven probleem van de stimulus te kunnen oplossen of aan te voldoen.';
				}

				if ($this->__contains($answers, "n van bovenstaande")) {
					$messages[] = 'U heeft waarschijnlijk de tekst \'géén van bovenstaande\' in uw alternatieven opgenomen. Dit wordt niet aanbevolen. De stimulus is waarschijnlijk niet in de vorm van een vraag of probleem gesteld of u kunt geen goed alternatief verzinnen. Het is het best om in dit geval deze afleider in het gehaal te laten vervallen.';
				}

				if ($this->__contains($answers, "niet")) {
					$messages[] = 'U heeft waarschijnlijk in de alternatieven een ontkenning opgenomen door het woord \'niet\' te gebruiken. Is het mogelijk de alternatieven positief te formuleren? Zo niet, <u>onderstreep</u> dan in ieder geval de ontkenning.';
				}

				if ($this->__contains($answers, array("geen", "géén", "GEEN"))) {
					$messages[] = 'U heeft misschien in de alternatieven een ontkenning opgenomen (het woord \'geen\'). Uw vraag is wellicht negatief gesteld. Is het mogelijk de vraag positief te formuleren? Zo niet, <u>onderstreep</u> dan in ieder geval de ontkenning.';
				}

				if ($this->__contains($answers, array("ooit", "lleen", "geen enkel", "llemaal", "ltijd"))) {
					$messages[] = 'U heeft in de alternatieven misschien één van de woorden \'nooit\', \'alleen\', \'geen enkele\', \'allemaal’ of ‘altijd’ gebruikt. Probeer de vraag of de alternatieven zo te formuleren dat een dergelijke absolute aanduiding niet gebruikt hoeft te worden. Immers, er zijn altijd uitzondering denkbaar waardoor een dergelijk alternatief in principe goed gerekend zou moeten worden. Bovendien weten studenten dat dergelijke alternatieven juist vaak niet correct zijn.';
				}

				if ($this->__contains($answers, array("moeten", "Moeten", "dienen", "Dienen", "Dient", "inloos"))) {
					$messages[] = 'U heeft in de tekst wellicht één van de woorden \'moeten\', \'dienen\' of \'zinloos\' gebruikt. Dit is een \'normatieve\' aanduiding waardoor impliciet naar een mening wordt gevraagd. Probeer of vraag of de alternatieven zo te formuleren dat deze aanduiding niet nodig is.';
				}

				if ($this->__contains($answers, array("isschien", "kan", "Kan", "ormaal", "soms", "n het algemeen", "en paar", "nkele", "ebaald", "lgemen"))) {
					$messages[] = 'U heeft in de tekst het woord \'normaal\', \'kan\', \'misschien\', \'soms\', \'in het algemeen\', \'een paar\', \'bepaalde\', \'algemene\' of \'enkele\' gebruikt. Dergelijke woorden zijn vage aanduidingen. Die kunnen door studenten verschillend worden geïnterpreteerd. Gebruik deze woorden niet. Wees precies in uw aanduidingen.';
				}

				if ($this->__contains($answers, array("Men", "men"))) {
					if (!$this->__contains($answers, array("mens", "Mens", "omen", "amen", "emen", "ment", "mend", "meng", "menk"))) {
						$messages[] = 'U heeft waarschijnlijk in de alternatieven \'men\' opgenomen. Het kan zijn dat uw alternatieven iets bevatten zoals naar \'Men is van mening dat .....\'. U vraag dus naar een mening. Zorg ervoor dat het duidelijk is van wie die mening is door de naam te vermelden.';
					}
				}
			}
		}
		return $messages;
	}

/**
 * view method
 *
 * @param integer $id An quesiton id
 * @return Question data
 */
	public function view($id) {
		$options = array(
			'conditions' => array(
				'Question.id' => $id
			),
			'contain' => array(
				'QuestionAnswer',
				'QuestionFormat',
				'DevelopmentPhase',
				'User',
				'QuestionsTag' => 'Tag'
			)
		);
		if (AuthComponent::user('role_id') != Role::ADMIN) {
			$options['conditions'][] = array('Question.user_id' => AuthComponent::user('id'));
		}
		return $this->find('first', $options);
	}

/**
 * add method
 *
 * @param array $data Question data
 * @return boolean
 */
	public function add($data) {
		$this->create();
		return $this->saveAll($data, array('deep' => true));
	}

/**
 * edit moethod
 *
 * @param integer $id A question id
 * @return array
 */
	public function edit($id) {
		$options = array(
			'conditions' => array(
				'Question.id' => $id
			),
			'contain' => array(
				'QuestionAnswer',
				'QuestionsTag' => 'Tag'
			)
		);
		if (AuthComponent::user('role_id') != Role::ADMIN) {
			$options['conditions'][] = array('Question.user_id' => AuthComponent::user('id'));
		}
		$question = $this->find('first', $options);
		if (!empty($question['QuestionsTag'])) {
			foreach ($question['QuestionsTag'] as $i => $questionsTag) {
				$question['QuestionsTag'][$i]['destroy'] = 0;
			}
		}
		if (!empty($question['QuestionAnswer'])) {
			foreach ($question['QuestionAnswer'] as $i => $questionAnswer) {
				$question['QuestionAnswer'][$i]['destroy'] = 0;
			}
		}

		$minQuestionAnswers = QuestionFormat::getMinimalQuestionAnswers($question['Question']['question_format_id']);
		$questionAnswerCount = count($question['QuestionAnswer']);
		while ($questionAnswerCount < $minQuestionAnswers) {
			$question['QuestionAnswer'][] = array('destroy' => 0);
		}

		return $question;
	}

/**
 * update method
 *
 * @param array $data Question data
 * @return boolean
 */
	public function update($data) {
		/*
		if (!empty($data['QuestionsTag']))
		{
			foreach ($data['QuestionsTag'] as $i => $questionsTag)
			{
				if (isset($questionsTag['destroy']) && !$questionsTag['destroy'] && empty($questionsTag['tag_id'])
			}
		}
		*/

		$deletedTagIds = array();

		$deletedQuestionsTagIds = Set::extract('/QuestionsTag[destroy=1]/id', $data);

		return $this->saveAll($data, array('deep' => true));
	}

/**
 * Removed question for given ID
 *
 * @param integer $id ID of question to delete
 * @param boolean $cascade Dummy parameter
 * @return boolean True on success
 * @see Model::delete()
 */
	public function delete($id = null, $cascade = true) {
		if (AuthComponent::user('role_id') != Role::ADMIN) {
			if (!in_array($id, $this->getMineIds())) {
				return false;
			}
		}
		return parent::delete($id);
	}

/**
 * Get list of questions
 *
 * @return array
 */
	public function getList() {
		$options = array();
		if (AuthComponent::user('role_id') != Role::ADMIN) {
			$options['conditions'] = array('Question.id' => $this->getMineIds());
		}
		return $this->find('list', $options);
	}

/**
 * Get IDs of questions
 *
 * @return array
 */
	public function getMineIds() {
		$questions = $this->find(
			'all', array(
				'conditions' => array(
					'Question.user_id' => AuthComponent::user('id')
				)
			)
		);
		return Set::extract('/Question/id', $questions);
	}

/**
 * Get start sentences
 *
 * @return array
 */
	public function getStartSentences() {
		return array(
			__('A - Weten en Begrijpen') => array(
				__('Wat is de beste definitie voor ....?') => __('A1 - Wat is de beste definitie voor ....?'),
				__('Wat is (niet) karakteristiek voor ....?') => __('A2 - Wat is (niet) karakteristiek voor ....?'),
				__('Uit welke onderdelen bestaat het probleem?') => __('A3 - Uit welke onderdelen bestaat het probleem?'),
				__('Wat is de geschiedenis van het probleem?') => __('A4 - Wat is de geschiedenis van het probleem?'),
				__('Welke verschillende categorieën zijn er in het probleem?') => __('A5 - Welke verschillende categorieën zijn er in het probleem?')
			),
			__('B - Wat is het meest effectief (gepast) voor ....?') => array(
				__('Wat is beter (slechter) ....?') => __('B1 - Wat is beter (slechter) ....?'),
				__('Wat is het meest effectief voor ....?') => __('B2 - Wat is het meest effectief voor ....?'),
				__('Wat is de meest kritieke stap in aan procedure?') => __('B3 - Wat is de meest kritieke stap in aan procedure?'),
				__('Als je weet dat X waar is, wat is dan tevens waar over Y?') => __('B4 - Als je weet dat X waar is, wat is dan tevens waar over Y?'),
				__('Wat is (niet) nodig in een procedure?') => __('B5 - Wat is (niet) nodig in een procedure?'),
				__('Kritisch denken (evalueren)') => __('B6 - Kritisch denken (evalueren)'),
				__('Wat is het belang van het probleem ? So what ?') => __('B6 - Wat is het belang van het probleem ? So what ?')
			),
			__('C - Kritisch denken (voorspellen)') => array(
				__('Wat zou er gebeuren als ....?') => __('C1 - Wat zou er gebeuren als ....?'),
				__('Als dit gebeurt, wat zou je doen?') => __('C2 - Als dit gebeurt, wat zou je doen?'),
				__('Op basis van wat ...., wat zou je doen?') => __('C3 - Op basis van wat ...., wat zou je doen?'),
				__('Gegeven ... wat is belangrijkste reden dat ....') => __('C4 - Gegeven ... wat is belangrijkste reden dat ....'),
			),
			__('D - Probleem oplossen (gegeven een scenario)') => array(
				__('Wat is de aard van het probleem?') => __('D1 - Wat is de aard van het probleem?'),
				__('Wat heb je nodig om dit probleem op te lossen?') => __('D2 - Wat heb je nodig om dit probleem op te lossen?'),
				__('Wat is een mogelijke oplossing?') => __('D3 - Wat is een mogelijke oplossing?'),
				__('Wat is de meeste effectieve (efficiente) oplossing?') => __('D4 - Wat is de meeste effectieve (efficiente) oplossing?'),
				__('Waarom is .... de meest effectieve (efficiënte) oplossing?') => __('D5 - Waarom is .... de meest effectieve (efficiënte) oplossing?')
			),
			__('E - Andere manieren om vragen te bedenken waarbij kritisch denken of probleemoplossing gevraagd worden zijn.') => array(
				__('Premise - Consequence') => __('E1 - Premise - Consequence'),
				__('Analogie') => __('E2 - Analogie'),
				__('Case Study') => __('E3 - Case Study'),
				__('Incompleet Scenario') => __('E4 - Incompleet Scenario'),
				__('Probleem / Oplossing evaluatie') => __('E5 - Probleem / Oplossing evaluatie')
			),
		);
	}

/**
 * Convert questions to QMP format
 *
 * @param array $questions Questions data
 * @return array
 */
	public function toQMP($questions) {
		$items = array();
		$files = array();

		$implementation = new DOMImplementation();

		$dtd = $implementation->createDocumentType('questestinterop', null, 'ims_qtiasiv1p2.dtd');

		$dom = $implementation->createDocument('', '', $dtd);

		$questestinterop = $dom->createElement("questestinterop");
		$dom->appendChild($questestinterop);

		if (!empty($questions)) {
			foreach ($questions as $question) {
				list($item, $extraFiles) = $this->__toQMP($question, $dom);
				$questestinterop->appendChild($item);

				$files = array_merge($files, $extraFiles);
				$items[] = $item;
			}
		}

		// workaround for QMP: it seems that QMP can only parse 'human-readable' XML
		$dom->formatOutput = true;
		return array($dom->saveXML(), $files);
	}

/**
 * Convert questions to Respondus format
 *
 * @param array $questions Questions data
 * @return array
 */

	public function toRespondus($questions) {
		$items = array();
		$files = array();

		$dom = new DOMDocument('1.0', 'UTF-8');
		$questestinterop = $dom->createElement("questestinterop");
		$dom->appendChild($questestinterop);

		$assesment = $dom->createElement("assessment");
		$questestinterop->appendChild($assesment);

		$title = $dom->createAttribute("title");
		$assesment->appendChild($title);
		$title->appendChild($dom->createTextNode(__('Selection Name')));

		$ident = $dom->createAttribute("ident");
		$assesment->appendChild($ident);
		$ident->appendChild($dom->createTextNode('A' . __('Selection Identifier')));

		$section = $dom->createElement("section");
		$section = $assesment->appendChild($section);

		// create attribute node
		$title = $dom->createAttribute("title");
		$section->appendChild($title);
		$title->appendChild($dom->createTextNode("Main"));

		$ident = $dom->createAttribute("ident");
		$section->appendChild($ident);
		$ident->appendChild($dom->createTextNode('S' . __('Selection Identifier')));

		if (!empty($questions)) {
			foreach ($questions as $question) {
				list($item, $extraFiles) = $this->__toRespondus($question, $dom);
				$section->appendChild($item);

				$files = array_merge($files, $extraFiles);
				$items[] = $item;
			}
		}

		return array($dom->saveXML(), $files);
	}

/**
 * Converts given question to QMP format
 * and appends it to the given DOMDocument
 *
 * @param array $question Question data
 * @param DOMDocument $dom DOMDocument
 * @return array()
 */
	private function __toQMP($question, $dom) {
		$files = array();

		$item = $dom->createElement("item");

		$title = $dom->createAttribute("title");
		$item->appendChild($title);
		$title->appendChild($dom->createTextNode($question['Question']['code']));

		$ident = $dom->createAttribute("ident");
		$item->appendChild($ident);
		$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id']));

		$itemmetadata = $dom->createElement("itemmetadata");
		$item->appendChild($itemmetadata);

		$qmdItemtype = $dom->createElement("qmd_itemtype");
		$itemmetadata->appendChild($qmdItemtype);
		$qmdItemtype->appendChild($dom->createTextNode('Multiple Choice'));

		$qmdStatus = $dom->createElement("qmd_status");
		$itemmetadata->appendChild($qmdStatus);
		$qmdStatus->appendChild($dom->createTextNode('Normal'));

		$qmdToolvendor = $dom->createElement("qmd_toolvendor");
		$itemmetadata->appendChild($qmdToolvendor);
		$qmdToolvendor->appendChild($dom->createTextNode('qDNAtool'));

		$qmdTopic = $dom->createElement("qmd_topic");
		$itemmetadata->appendChild($qmdTopic);
		$qmdTopic->appendChild($dom->createTextNode('Topic'));

		$ident = $dom->createAttribute("ident");
		$item->appendChild($ident);
		$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id']));

		$presentation = $dom->createElement("presentation");
		$item->appendChild($presentation);

		list($material, $extraFiles) = $this->__materialToRespondus($question['Question']['stimulus'], $dom);
		$files = array_merge($files, $extraFiles);
		$presentation->appendChild($material);

		list($response, $extraFiles) = $this->__responseToQMP($question, $dom);
		$files = array_merge($files, $extraFiles);
		$presentation->appendChild($response);

		list($itemResprocessing, $extraFiles) = $this->__itemResprocessingToRespondus($question, $dom);
		$files = array_merge($files, $extraFiles);
		$item->appendChild($itemResprocessing);

		list($itemFeedbacks, $extraFiles) = $this->__itemFeedbackToRespondus($question, $dom);
		$files = array_merge($files, $extraFiles);
		if (!is_array($itemFeedbacks)) {
			$itemFeedbacks = array($itemFeedbacks);
		}
		foreach ($itemFeedbacks as $itemFeedback) {
			$item->appendChild($itemFeedback);
		}

		return array($item, $files);
	}

/**
 * Converts given question to Respondus format
 * and appends it to the given DOMDocument
 *
 * @param array $question Question data
 * @param DOMDocument $dom DOMDocument
 * @return array()
 */
	private function __toRespondus($question, $dom) {
		$files = array();

		$item = $dom->createElement("item");

		$title = $dom->createAttribute("title");
		$item->appendChild($title);
		$title->appendChild($dom->createTextNode($question['Question']['code']));

		$ident = $dom->createAttribute("ident");
		$item->appendChild($ident);
		$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id']));

		$itemmetadata = $dom->createElement("itemmetadata");
		$item->appendChild($itemmetadata);

		$qmdItemtype = $dom->createElement("qmd_itemtype");
		$itemmetadata->appendChild($qmdItemtype);
		$qmdItemtype->appendChild($dom->createTextNode('Multiple Choice'));

		$qmdStatus = $dom->createElement("qmd_status");
		$itemmetadata->appendChild($qmdStatus);
		$qmdStatus->appendChild($dom->createTextNode('Normal'));

		$qmdToolvendor = $dom->createElement("qmd_toolvendor");
		$itemmetadata->appendChild($qmdToolvendor);
		$qmdToolvendor->appendChild($dom->createTextNode('qDNAtool'));

		$qmdTopic = $dom->createElement("qmd_topic");
		$itemmetadata->appendChild($qmdTopic);
		$qmdTopic->appendChild($dom->createTextNode('Topic'));

		$ident = $dom->createAttribute("ident");
		$item->appendChild($ident);
		$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id']));

		$presentation = $dom->createElement("presentation");
		$item->appendChild($presentation);

		list($material, $extraFiles) = $this->__materialToRespondus($question['Question']['stimulus'], $dom);
		$files = array_merge($files, $extraFiles);
		$presentation->appendChild($material);

		list($response, $extraFiles) = $this->__responseToRespondus($question, $dom);
		$files = array_merge($files, $extraFiles);
		$presentation->appendChild($response);

		list($itemResprocessing, $extraFiles) = $this->__itemResprocessingToRespondus($question, $dom);
		$files = array_merge($files, $extraFiles);
		$item->appendChild($itemResprocessing);

		list($itemFeedbacks, $extraFiles) = $this->__itemFeedbackToRespondus($question, $dom);
		$files = array_merge($files, $extraFiles);
		if (!is_array($itemFeedbacks)) {
			$itemFeedbacks = array($itemFeedbacks);
		}
		foreach ($itemFeedbacks as $itemFeedback) {
			$item->appendChild($itemFeedback);
		}

		return array($item, $files);
	}

/**
 * Converts resprocessing of given question to Respondus format
 * and appends it to the given DOMDocument
 *
 * @param array $question Question data
 * @param DOMDocument $dom DOMDocument
 * @return array()
 */
	private function __itemResprocessingToRespondus($question, $dom) {
		$files = array();

		$resprocessing = $dom->createElement("resprocessing");

		$outcomes = $dom->createElement("outcomes");
		$resprocessing->appendChild($outcomes);

		$decvar = $dom->createElement("decvar");
		$outcomes->appendChild($decvar);

		if (!empty($question['QuestionAnswer'])) {
			foreach ($question['QuestionAnswer'] as $i => $questionAnswer) {
				$respcondition = $dom->createElement("respcondition");
				$resprocessing->appendChild($respcondition);

				$title = $dom->createAttribute("title");
				$respcondition->appendChild($title);
				$title->appendChild($dom->createTextNode($i));

				$continue = $dom->createAttribute("continue");
				$respcondition->appendChild($continue);
				$continue->appendChild($dom->createTextNode('Yes'));

				$conditionvar = $dom->createElement("conditionvar");
				$respcondition->appendChild($conditionvar);

				$varequal = $dom->createElement("varequal");
				$conditionvar->appendChild($varequal);
				$varequal->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A' . ($i + 1)));

				$respident = $dom->createAttribute("respident");
				$varequal->appendChild($respident);
				$respident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_RL'));

				$setvar = $dom->createElement("setvar");
				$respcondition->appendChild($setvar);
				$setvar->appendChild($dom->createTextNode($questionAnswer['is_correct']?'1':'0'));

				$action = $dom->createAttribute("action");
				$setvar->appendChild($action);
				$action->appendChild($dom->createTextNode('Set'));

				$displayfeedback = $dom->createElement("displayfeedback");
				$respcondition->appendChild($displayfeedback);

				$linkrefid = $dom->createAttribute("linkrefid");
				$displayfeedback->appendChild($linkrefid);

				$linkrefid->appendChild($dom->createTextNode($i + 3));
			}

			foreach ($question['QuestionAnswer'] as $i => $questionAnswer) {
				$respcondition = $dom->createElement("respcondition");
				$resprocessing->appendChild($respcondition);

				$title = $dom->createAttribute("title");
				$respcondition->appendChild($title);
				$title->appendChild($dom->createTextNode($i));

				$continue = $dom->createAttribute("continue");
				$respcondition->appendChild($continue);
				$continue->appendChild($dom->createTextNode('Yes'));

				$conditionvar = $dom->createElement("conditionvar");
				$respcondition->appendChild($conditionvar);

				$varequal = $dom->createElement("varequal");
				$conditionvar->appendChild($varequal);
				$varequal->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A' . ($i + 1)));

				$respident = $dom->createAttribute("respident");
				$varequal->appendChild($respident);
				$respident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_RL'));

				$setvar = $dom->createElement("setvar");
				$respcondition->appendChild($setvar);
				$setvar->appendChild($dom->createTextNode('0'));

				$action = $dom->createAttribute("action");
				$setvar->appendChild($action);
				$action->appendChild($dom->createTextNode('Add'));

				$displayfeedback = $dom->createElement("displayfeedback");
				$respcondition->appendChild($displayfeedback);

				$linkrefid = $dom->createAttribute("linkrefid");
				$displayfeedback->appendChild($linkrefid);

				$linkrefid->appendChild($dom->createTextNode($questionAnswer['is_correct']?'1':'2'));
			}
		}

		return array($resprocessing, $files);
	}

/**
 * Converts feedback of given question to Respondus format
 * and appends it to the given DOMDocument
 *
 * @param array $question Question data
 * @param DOMDocument $dom DOMDocument
 * @return array()
 */
	private function __itemFeedbackToRespondus($question, $dom) {
		$itemFeedbacks = array();
		$files = array();

		$itemfeedback = $dom->createElement("itemfeedback");

		$ident = $dom->createAttribute("ident");
		$itemfeedback->appendChild($ident);
		$ident->appendChild($dom->createTextNode('1'));

		$view = $dom->createAttribute("view");
		$itemfeedback->appendChild($view);
		$view->appendChild($dom->createTextNode('Candidate'));

		list($material, $extraFiles) = $this->__materialToRespondus($question['Question']['feedback_when_correct'], $dom);
		$files = array_merge($files, $extraFiles);
		$itemfeedback->appendChild($material);

		$itemFeedbacks[] = $itemfeedback;

		$itemfeedback = $dom->createElement("itemfeedback");

		$ident = $dom->createAttribute("ident");
		$itemfeedback->appendChild($ident);
		$ident->appendChild($dom->createTextNode('2'));

		$view = $dom->createAttribute("view");
		$itemfeedback->appendChild($view);
		$view->appendChild($dom->createTextNode('Candidate'));

		list($material, $extraFiles) = $this->__materialToRespondus($question['Question']['feedback_when_wrong'], $dom);
		$files = array_merge($files, $extraFiles);
		$itemfeedback->appendChild($material);

		$itemFeedbacks[] = $itemfeedback;

		if (!empty($question['QuestionAnswer'])) {
			foreach ($question['QuestionAnswer'] as $i => $questionAnswer) {
				$itemfeedback = $dom->createElement("itemfeedback");

				$ident = $dom->createAttribute("ident");
				$itemfeedback->appendChild($ident);
				$ident->appendChild($dom->createTextNode($i + 3));

				$view = $dom->createAttribute("view");
				$itemfeedback->appendChild($view);
				$view->appendChild($dom->createTextNode('Candidate'));

				list($material, $extraFiles) = $this->__materialToRespondus($questionAnswer['feedback'], $dom);
				$files = array_merge($files, $extraFiles);
				$itemfeedback->appendChild($material);

				$itemFeedbacks[] = $itemfeedback;
			}
		}
		return array($itemFeedbacks, $files);
	}

/**
 * Converts response of given question to QMP format
 * and appends it to the given DOMDocument
 *
 * @param array $question Question data
 * @param DOMDocument $dom DOMDocument
 * @return array()
 */
	private function __responseToQMP($question, $dom) {
		$files = array();
		$response = $dom->createElement("response_lid");

		$ident = $dom->createAttribute("ident");
		$response->appendChild($ident);
		$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_RL'));

		$rcardinality = $dom->createAttribute("rcardinality");
		$response->appendChild($rcardinality);
		$rcardinality->appendChild($dom->createTextNode('Single'));

		$rtiming = $dom->createAttribute("rtiming");
		$response->appendChild($rtiming);
		$rtiming->appendChild($dom->createTextNode('No'));

		switch ($question['Question']['question_format_id']) {
			case QuestionFormat::TRUE_FALSE:
				$responseChoice = $dom->createElement("render_choice");
				$shuffle = $dom->createAttribute("shuffle");
				$responseChoice->appendChild($shuffle);
				$shuffle->appendChild($dom->createTextNode('No'));

				$response->appendChild($responseChoice);

				$responseLabel = $dom->createElement("response_label");
				$responseChoice->appendChild($responseLabel);

				$ident = $dom->createAttribute("ident");
				$responseLabel->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A1'));

				list($material, $extraFiles) = $this->__materialToRespondus((empty($question['QuestionAnswer'][0]['name'])?'':$question['QuestionAnswer'][0]['name']), $dom);
				$files = array_merge($files, $extraFiles);
				$responseLabel->appendChild($material);

				$responseLabel = $dom->createElement("response_label");
				$responseChoice->appendChild($responseLabel);

				$ident = $dom->createAttribute("ident");
				$responseLabel->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A2'));

				list($material, $extraFiles) = $this->__materialToRespondus((empty($question['QuestionAnswer'][1]['name'])?'':$question['QuestionAnswer'][1]['name']), $dom);
				$files = array_merge($files, $extraFiles);
				$responseLabel->appendChild($material);
				break;
			case QuestionFormat::MULTIPLE_CHOICE:
			case QuestionFormat::MULTIPLE_RESPONSE:
				if (!empty($question['QuestionAnswer'])) {
					$responseChoice = $dom->createElement("render_choice");
					$shuffle = $dom->createAttribute("shuffle");
					$responseChoice->appendChild($shuffle);
					$shuffle->appendChild($dom->createTextNode('No'));
					$response->appendChild($responseChoice);

					foreach ($question['QuestionAnswer'] as $i => $questionAnswer) {
						$responseLabel = $dom->createElement("response_label");
						$responseChoice->appendChild($responseLabel);

						$ident = $dom->createAttribute("ident");
						$responseLabel->appendChild($ident);
						$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A' . ($i + 1)));

						list($material, $extraFiles) = $this->__materialToRespondus($questionAnswer['name'], $dom);
						$files = array_merge($files, $extraFiles);
						$responseLabel->appendChild($material);
					}
				}
				break;
			case QuestionFormat::OPEN_ANSWER:
				$responseStr = $dom->createElement("response_str");
				$response->appendChild($responseStr);

				$ident = $dom->createAttribute("ident");
				$responseStr->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_RS'));

				$responseFib = $dom->createElement("render_fib");
				$responseStr->appendChild($responseFib);

				$fibtype = $dom->createAttribute("fibtype");
				$responseFib->appendChild($fibtype);
				$fibtype->appendChild($dom->createTextNode('String'));

				$prompt = $dom->createAttribute("prompt");
				$responseFib->appendChild($prompt);
				$prompt->appendChild($dom->createTextNode('Box'));

				$rows = $dom->createAttribute("rows");
				$responseFib->appendChild($rows);
				$rows->appendChild($dom->createTextNode('5'));

				$columns = $dom->createAttribute("columns");
				$responseFib->appendChild($columns);
				$columns->appendChild($dom->createTextNode('50'));

				$responseLabel = $dom->createElement("response_label");
				$responseFib->appendChild($responseLabel);

				$ident = $dom->createAttribute("ident");
				$responseLabel->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_ANS'));
				break;
		}

		return array($response, $files);
	}

/**
 * Converts response of given question to Respondus format
 * and appends it to the given DOMDocument
 *
 * @param array $question Question data
 * @param DOMDocument $dom DOMDocument
 * @return array()
 */
	private function __responseToRespondus($question, $dom) {
		$files = array();
		$response = $dom->createElement("response_lid");

		$ident = $dom->createAttribute("ident");
		$response->appendChild($ident);
		$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_RL'));

		$rcardinality = $dom->createAttribute("rcardinality");
		$response->appendChild($rcardinality);
		$rcardinality->appendChild($dom->createTextNode('Single'));

		$rtiming = $dom->createAttribute("rtiming");
		$response->appendChild($rtiming);
		$rtiming->appendChild($dom->createTextNode('No'));

		switch ($question['Question']['question_format_id']) {
			case QuestionFormat::TRUE_FALSE:
				$responseChoice = $dom->createElement("render_choice");
				$response->appendChild($responseChoice);

				$responseLabel = $dom->createElement("response_label");
				$responseChoice->appendChild($responseLabel);

				$ident = $dom->createAttribute("ident");
				$responseLabel->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A1'));

				list($material, $extraFiles) = $this->__materialToRespondus((empty($question['QuestionAnswer'][0]['name'])?'':$question['QuestionAnswer'][0]['name']), $dom);
				$files = array_merge($files, $extraFiles);
				$responseLabel->appendChild($material);

				$responseChoice = $dom->createElement("render_choice");
				$response->appendChild($responseChoice);

				$responseLabel = $dom->createElement("response_label");
				$responseChoice->appendChild($responseLabel);

				$ident = $dom->createAttribute("ident");
				$responseLabel->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A2'));

				list($material, $extraFiles) = $this->__materialToRespondus((empty($question['QuestionAnswer'][1]['name'])?'':$question['QuestionAnswer'][1]['name']), $dom);
				$files = array_merge($files, $extraFiles);
				$responseLabel->appendChild($material);
				break;
			case QuestionFormat::MULTIPLE_CHOICE:
			case QuestionFormat::MULTIPLE_RESPONSE:
				if (!empty($question['QuestionAnswer'])) {
					foreach ($question['QuestionAnswer'] as $i => $questionAnswer) {
						$responseChoice = $dom->createElement("render_choice");
						$response->appendChild($responseChoice);

						$responseLabel = $dom->createElement("response_label");
						$responseChoice->appendChild($responseLabel);

						$ident = $dom->createAttribute("ident");
						$responseLabel->appendChild($ident);
						$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_A' . ($i + 1)));

						list($material, $extraFiles) = $this->__materialToRespondus($questionAnswer['name'], $dom);
						$files = array_merge($files, $extraFiles);
						$responseLabel->appendChild($material);
					}
				}
				break;
			case QuestionFormat::OPEN_ANSWER:
				$responseStr = $dom->createElement("response_str");
				$response->appendChild($responseStr);

				$ident = $dom->createAttribute("ident");
				$responseStr->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_RS'));

				$responseFib = $dom->createElement("render_fib");
				$responseStr->appendChild($responseFib);

				$fibtype = $dom->createAttribute("fibtype");
				$responseFib->appendChild($fibtype);
				$fibtype->appendChild($dom->createTextNode('String'));

				$prompt = $dom->createAttribute("prompt");
				$responseFib->appendChild($prompt);
				$prompt->appendChild($dom->createTextNode('Box'));

				$rows = $dom->createAttribute("rows");
				$responseFib->appendChild($rows);
				$rows->appendChild($dom->createTextNode('5'));

				$columns = $dom->createAttribute("columns");
				$responseFib->appendChild($columns);
				$columns->appendChild($dom->createTextNode('50'));

				$responseLabel = $dom->createElement("response_label");
				$responseFib->appendChild($responseLabel);

				$ident = $dom->createAttribute("ident");
				$responseLabel->appendChild($ident);
				$ident->appendChild($dom->createTextNode('QUE_' . $question['Question']['id'] . '_ANS'));
				break;
		}

		return array($response, $files);
	}

/**
 * Converts given stimulus to Respondus format
 * and appends it to the given DOMDocument
 *
 * @param string $stimulus Stimulus
 * @param DOMDocument $dom DOMDocument
 * @return array()
 */
	private function __materialToRespondus($stimulus, $dom) {
		$files = array();
		$parts = preg_split('/<img[^>]+>/i', $stimulus);
		preg_match_all('/<img[^>]+>/i', $stimulus, $images);

		$material = $dom->createElement("material");

		$data = array();
		foreach ($parts as $i => $part) {
			$mattext = $dom->createElement("mattext");
			$material->appendChild($mattext);

			$texttype = $dom->createAttribute("texttype");
			$mattext->appendChild($texttype);
			$texttype->appendChild($dom->createTextNode("text/html"));

			$cdata = $dom->createCDATASection($part);
			$mattext->appendChild($cdata);

			if (!empty($images[0][$i])) {
				// get the src for that image
				$pattern = '/src="([^"]*)"/';
				preg_match($pattern, $images[0][$i], $matches);
				$imageSource = $matches[1];
				$prefix = Router::url(array('controller' => 'images', 'action' => 'get')) . DS;
				if (strpos($imageSource, $prefix) === 0) {
					$imageId = substr($imageSource, strlen($prefix));
					$image = $this->Image->find(
						'first', array(
							'conditions' => array(
								'Image.id' => $imageId
							)
						)
					);
					if (!empty($image)) {
						$matimage = $dom->createElement("matimage");
						$material->appendChild($matimage);

						$imagtype = $dom->createAttribute("imagtype");
						$matimage->appendChild($imagtype);
						$imagtype->appendChild($dom->createTextNode($image['Image']['file_type']));

						$uri = $dom->createAttribute("uri");
						$matimage->appendChild($uri);
						$uri->appendChild($dom->createTextNode($imageId . '.' . $image['Image']['extension']));

						$files[] = $imageId . '.' . $image['Image']['extension'];
					}
				}
			}
		}
		return array($material, $files);
	}

/**
 * Returns the number of words in given string
 *
 * @param string $str String
 * @return integer
 */
	private function __wordCount($str) {
		return count(explode(" ", $str));
	}

/**
 * Returns whether or not the given check contains given keywords
 *
 * @param string $check Value to validate
 * @param array $keywords Keywords to check
 * @return boolean
 */
	private function __contains($check, $keywords) {
		if (!is_array($keywords)) {
			$keywords = array($keywords);
		}
		foreach ($keywords as $keyword) {
			if (strpos($check, $keyword) !== false) {
				return true;
			}
		}
		return false;
	}

/**
 * Calculates the average of given values
 *
 * @param array $array Array of values
 * @return float
 */
	private function __average($array) {
		return array_sum($array) / count($array);
	}
}