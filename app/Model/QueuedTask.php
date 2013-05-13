<?php
App::uses('AppModel', 'Model');
/**
 * QueuedTask Model
 *
 */
class QueuedTask extends AppModel {

	public $rateHistory = array();

	public $exit = false;

	protected $_findMethods = array(
		'progress' => true
	);

/**
 * Add a new Job to the Queue
 *
 * @param string $jobName QueueTask name
 * @param array $data any array
 * @param string $group Used to group similar QueuedTasks
 * @param string $reference any array
 * @return bool success
 */
	public function createJob($jobName, $data, $notBefore = null, $group = null, $reference = null) {
		$data = array(
			'job_type' => $jobName,
			'data' => serialize($data),
			'group' => $group,
			'reference' => $reference
		);
		if ($notBefore != null) {
			$data['not_before'] = date('Y-m-d H:i:s', strtotime($notBefore));
		}
		$this->create();
		return $this->save($data);
	}

	public function onError() {
		$this->exit = true;
	}

/**
 * Look for a new job that can be processed with the current abilities and
 * from the specified group (or any if null).
 *
 * @param array $capabilities Available QueueWorkerTasks.
 * @param string $group Request a job from this group, (from any group if null)
 * @return Array Taskdata.
 */
	public function requestJob($capabilities, $group = null) {
		$idlist = array();
		$wasFetched = array();

		$options = array(
			'conditions' => array(
				'completed' => null,
				'OR' => array()
			),
			'fields' => array(
				'id',
				'fetched',
				'timediff(NOW(),not_before) AS age'
			),
			'order' => array(
				'age DESC',
				'id ASC'
			),
			'limit' => 3
		);

		if (!is_null($group)) {
			$options['conditions']['group'] = $group;
		}

		// generate the task specific conditions.
		foreach ($capabilities as $task) {
			$tmp = array(
				'job_type' => str_replace('Queue', '', $task['name']),
				'AND' => array(
					array(
						'OR' => array(
							'not_before <' => date('Y-m-d H:i:s'),
							'not_before' => null
						)
					),
					array(
						'OR' => array(
							'fetched <' => date('Y-m-d H:i:s', time() - $task['timeout']),
							'fetched' => null
						)
					)
				),
				'failed <' => ($task['retries'] + 1)
			);
			if (array_key_exists('rate', $task) && array_key_exists($tmp['job_type'], $this->rateHistory)) {
				$tmp['NOW() >='] = date('Y-m-d H:i:s', $this->rateHistory[$tmp['job_type']] + $task['rate']);
			}
			$options['conditions']['OR'][] = $tmp;
		}
		// First, find a list of a few of the oldest unfinished tasks.
		$data = $this->find('all', $options);
		if (is_array($data) && count($data) > 0) {
			// generate a list of their ID's
			foreach ($data as $item) {
				$idlist[] = $item[$this->name]['id'];
				if (!empty($item[$this->name]['fetched'])) {
					$wasFetched[] = $item[$this->name]['id'];
				}
			}
			// Generate a unique Identifier for the current worker thread
			$key = sha1(microtime());
			// try to update one of the found tasks with the key of this worker.
			$this->query('UPDATE ' . $this->tablePrefix . $this->table . ' SET worker_key = "' . $key . '", fetched = "' . date('Y-m-d H:i:s') . '" WHERE id in(' . implode(',', $idlist) . ') AND (worker_key IS NULL OR		 fetched <= "' . date('Y-m-d H:i:s', time() - $task['timeout']) . '") ORDER BY timediff(NOW(),not_before) DESC LIMIT 1');
			// read which one actually got updated, which is the job we are supposed to execute.
			$data = $this->find
			( 'first', array
				( 'conditions' => array
					( 'worker_key' => $key
					)
				)
			);
			if (is_array($data)) {
				// if the job had an existing fetched timestamp, increment the failure counter
				if (in_array($data[$this->name]['id'], $wasFetched)) {
					$data[$this->name]['failed']++;
					$data[$this->name]['failure_message'] = 'Restart after timeout';
					$this->save($data);
				}
				//save last fetch by type for Rate Limiting.
				$this->rateHistory[$data[$this->name]['job_type']] = time();
				return $data[$this->name];
			}
		}
		return false;
	}

/**
 * Mark a job as Completed, removing it from the queue.
 *
 * @param integer $id
 * @return bool Success
 */
	public function markJobDone($id) {
		$this->id = $id;
		return $this->saveField('completed', date('Y-m-d H:i:s'));
	}

/**
 * Mark a job as Failed, Incrementing the failed-counter and Requeueing it.
 *
 * @param integer $id
 * @param string $failureMessage Optional message to append to the
 * failure_message field
 */
	public function markJobFailed($id, $failureMessage = null) {
		$db = $this->getDataSource();
		return ($this->updateAll(array(
			'failed' => "failed + 1",
			'failure_message' => $db->value($failureMessage, 'failure_message')
		), array(
			'id' => $id
		)));
	}

/**
 * Returns the number of items in the Queue.
 * Either returns the number of ALL pending tasks, or the number of pending tasks of the passed Type
 *
 * @param string $type jobType to Count
 * @return integer
 */
	public function getLength($type = null) {
		$options = array(
			'conditions' => array(
				'completed' => null
			)
		);
		if ($type != null) {
			$options['conditions']['job_type'] = $type;
		}
		return $this->find('count', $options);
	}

/**
 * Return a list of all jobtypes in the Queue.
 *
 * @return array
 */
	public function getTypes() {
		$options = array(
			'fields' => array(
				'job_type'
			),
			'group' => array(
				'job_type'
			)
		);
		return $this->find('list', $options);
	}

/**
 * Return some statistics about finished jobs still in the Database.
 * @return array
 */
	public function getStats() {
		$options = array(
			'fields' => array(
				'job_type',
				'count(id) as num',
				'AVG(UNIX_TIMESTAMP(completed)-UNIX_TIMESTAMP(created)) AS alltime',
				'AVG(UNIX_TIMESTAMP(completed)-UNIX_TIMESTAMP(fetched)) AS runtime',
				'AVG(UNIX_TIMESTAMP(fetched)-if (not_before is null,UNIX_TIMESTAMP(created),UNIX_TIMESTAMP(not_before))) AS fetchdelay'
			),
			'conditions' => array(
				'NOT' => array('completed' => null)
			),
			'group' => array(
				'job_type'
			)
		);
		return $this->find('all', $options);
	}

/**
 * Cleanup/Delete Completed Jobs.
 *
 */
	public function cleanOldJobs() {
		$this->deleteAll(array(
			'completed < ' => date('Y-m-d H:i:s', time() - Configure::read('queue.cleanuptimeout'))
		));
	}

	protected function _findProgress($state, $query = array(), $results = array()) {
		if ($state == 'before') {
			$query['fields'] = array
			( $this->alias . '.reference',
				'(CASE WHEN ' . $this->alias . '.not_before > NOW() THEN \'NOT_READY\' WHEN ' . $this->alias . '.fetched IS NULL THEN \'NOT_STARTED\' WHEN ' . $this->alias . '.fetched IS NOT NULL AND ' . $this->alias . '.completed IS NULL AND ' . $this->alias . '.failed = 0 THEN \'IN_PROGRESS\' WHEN ' . $this->alias . '.fetched IS NOT NULL AND ' . $this->alias . '.completed IS NULL AND ' . $this->alias . '.failed > 0 THEN \'FAILED\' WHEN ' . $this->alias . '.fetched IS NOT NULL AND ' . $this->alias . '.completed IS NOT NULL THEN \'COMPLETED\' ELSE \'UNKNOWN\' END) AS status',
				$this->alias . '.failure_message'
			);
			if (isset($query['conditions']['exclude'])) {
				$exclude = $query['conditions']['exclude'];
				unset($query['conditions']['exclude']);
				$exclude = trim($exclude, ',');
				$exclude = explode(',', $exclude);
				$query['conditions'][] = array(
					'NOT' => array(
						'reference' => $exclude
					)
				);
			}
			if (isset($query['conditions']['group'])) {
				$query['conditions'][][$this->alias . '.group'] = $query['conditions']['group'];
				unset($query['conditions']['group']);
			}
			return $query;
		} else {
			foreach ($results as $k => $result) {
				$results[$k] = array(
					'reference' => $result[$this->alias]['reference'],
					'status' => $result[0]['status']
				);
				if (!empty($result[$this->alias]['failure_message'])) {
					$results[$k]['failure_message'] = $result[$this->alias]['failure_message'];
				}
			}
			return $results;
		}
	}
}