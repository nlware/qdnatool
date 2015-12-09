<?php
App::uses('Folder', 'Utility');
App::uses('QueuedTask', 'Model');
App::uses('AppShell', 'Console/Command');
/**
 * Queue Shell
 *
 * @property QueuedTask $QueuedTask
 */
class QueueShell extends AppShell {

/**
 * Contains models to load and instantiate.
 *
 * @var array
 * @see AppShell::uses
 */
	public $uses = array('QueuedTask');

	private $__taskConf;

/**
 * Gets and configures the option parser.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addSubcommand('add', array(
			'help' => __('tries to call the cli `add()` function on a task.'),
			'parser' => array(
				'description' => array(
					__('tries to call the cli `add()` function on a task.'),
					__('tasks may or may not provide this functionality.')
				),
				'arguments' => array(
					'taskname' => array('required' => true)
				)
			)
		))->addSubcommand('runworker', array(
			'help' => __('run a queue worker'),
			'parser' => array(
				'description' => array(
					__('run a queue worker, which will look for a pending task it can execute.'),
					__('the worker will always try to find jobs matching its installed tasks.'),
					__('see "Available tasks" below.')
				)
			)
		))->addSubcommand('stats', array(
			'help' => __('display some general statistics.'),
			'parser' => array(
				'description' => __('display some general statistics.')
			)
		))->addSubcommand('clean', array(
			'help' => __('manually call cleanup function to delete task data of completed tasks.'),
			'parser' => array(
				'description' => __('manually call cleanup function to delete task data of completed tasks.')
			)
		))->addSubcommand('tasks', array(
			'help' => __('List available tasks')
		))->description(__( 'CakePHP Queue.'));
		return $parser;
	}

/**
 * Overwrite shell initialize to dynamically load all Queue Related Tasks.
 *
 * @return void
 */
	public function initialize() {
		// Check for tasks inside plugins and application
		$plugins = App::objects('plugin');
		$plugins[] = '';
		foreach ($plugins as $plugin) {
			if (!empty($plugin)) {
				$plugin .= '.';
			}

			foreach (App::objects($plugin . 'Console/Command/Task') as $task) {
				if (strpos($task, 'Queue') === 0 && substr($task, -4) === 'Task') {
					$taskName = substr($task, 0, -4);
					$this->{$taskName} = $this->Tasks->load($plugin . $taskName);
					$this->tasks[] = $taskName;
				}
			}
		}

		//Config can be overwritten via local app config.
		Configure::load('queue');

		$conf = Configure::read('Queue');
		if (!is_array($conf)) {
			$conf = array();
		}

		//merge with default configuration vars.
		Configure::write('Queue', array_merge(array(
			'sleeptime' => 10,
			'gcProp' => 10,
			'defaultWorkerTimeout' => 120,
			'defaultWorkerRetries' => 4,
			'workerMaxRuntime' => 0,
			'cleanuptimeout' => 2000,
			'exitWhenNothingTodo' => false
		), $conf));
	}

/**
 * Output some basic usage Info.
 *
 * @return void
 */
	public function help() {
		$this->out('CakePHP Queue Plugin:');
		$this->hr();
		$this->out('Information goes here.');
		$this->hr();
		$this->out('Usage: cake queue <command> <arg1> <arg2>...');
		$this->hr();
		$this->out('Commands:');
		$this->out('	queue help');
		$this->out('		shows this help message.', 2);
		$this->out('	queue add <taskname>');
		$this->out('		tries to call the cli `add()` function on a task.');
		$this->out('		tasks may or may not provide this functionality.', 2);
		$this->out('	cake queue runworker [--verbose]');
		$this->out('		run a queue worker, which will look for a pending task it can execute.');
		$this->out('		the worker will always try to find jobs matching its installed tasks.');
		$this->out('		see "Available tasks" below.', 2);
		$this->out('	queue stats');
		$this->out('		display some general statistics.', 2);
		$this->out('	queue clean');
		$this->out('		manually call cleanup function to delete task data of completed tasks.', 2);
		$this->out('Note:');
		$this->out('	<taskname> may either be the complete classname (eg. `queue_example`)');
		$this->out('	or the shorthand without the leading "queue_" (eg. `example`).', 2);
		$this->_listTasks();
	}

/**
 * Look for a Queue Task of hte passed name and try to call add() on it.
 * A QueueTask may provide an add function to enable the user to create new jobs via commandline.
 *
 * @return void
 */
	public function add() {
		if (count($this->args) < 1) {
			$this->out('Usage:');
			$this->out('			 cake queue add <taskname>', 2);
			$this->_listTasks();
		} else {
			if (in_array($this->args[0], $this->taskNames)) {
				$this->{$this->args[0]}->add();
			} elseif (in_array('queue_' . $this->args[0], $this->taskNames)) {
				$this->{'queue_' . $this->args[0]}->add();
			} else {
				$this->out('Error:');
				$this->out('			 Task not found: ' . $this->args[0], 2);
				$this->_listTasks();
			}
		}
	}

/**
 * Run a QueueWorker loop.
 * Runs a Queue Worker process which will try to find unassigned jobs in the queue
 * which it may run and try to fetch and execute them.
 *
 * @return void
 */
	public function runworker() {
		// Enable Garbage Collector (PHP >= 5.3)
		if (function_exists('gc_enable')) {
			gc_enable();
		}
		$exit = false;
		$starttime = time();
		$group = null;
		if (isset($this->params['group']) && !empty($this->params['group'])) {
			$group = $this->params['group'];
		}
		while (!$exit) {
			if ($this->params['verbose']) {
				$this->out('Looking for Job....');
			}
			$data = $this->QueuedTask->requestJob($this->__getTaskConf(), $group);
			if ($this->QueuedTask->exit === true) {
				$exit = true;
			} else {
				if ($data !== false) {
					$this->out('Running Job of type "' . $data['job_type'] . '"');
					$taskname = 'Queue' . $data['job_type'];
					$return = $this->{$taskname}->run(unserialize($data['data']));
					if ($return == true) {
						$this->QueuedTask->markJobDone($data['id']);
						$this->out('Job Finished.');
					} else {
						$failureMessage = null;
						if (isset($this->{$taskname}->failureMessage) && !empty($this->{$taskname}->failureMessage)) {
							$failureMessage = $this->{$taskname}->failureMessage;
						}
						$this->QueuedTask->markJobFailed($data['id'], $failureMessage);
						$this->out('Job did not finish, requeued.');
					}
				} elseif (Configure::read('Queue.exitWhenNothingTodo')) {
					$this->out('nothing to do, exiting.');
					$exit = true;
				} else {
					if ($this->params['verbose']) {
						$this->out('nothing to do, sleeping.');
					}
					sleep(Configure::read('Queue.sleeptime'));
				}

				// check if we are over the maximum runtime and end processing if so.
				if (Configure::read('Queue.workerMaxRuntime') != 0 && (time() - $starttime) >= Configure::read('Queue.workerMaxRuntime')) {
					$exit = true;
					$this->out('Reached runtime of ' . (time() - $starttime) . ' Seconds (Max ' . Configure::read('Queue.workerMaxRuntime') . '), terminating.');
				}
				if ($exit || rand(0, 100) > (100 - Configure::read('Queue.gcProp'))) {
					$this->out('Performing Old job cleanup.');
					$this->QueuedTask->cleanOldJobs();
				}
				if ($this->params['verbose']) {
					$this->hr();
				}
			}
		}
	}

/**
 * Manually trigger a Finished job cleanup.
 *
 * @return void
 */
	public function clean() {
		$this->out('Deleting old jobs, that have finished before ' . date('Y-m-d H:i:s', time() - Configure::read('Queue.cleanuptimeout')));
		$this->QueuedTask->cleanOldJobs();
	}

/**
 * Display Some statistics about Finished Jobs.
 *
 * @return void
 */
	public function stats() {
		$this->out('Jobs currenty in the Queue:');

		$types = $this->QueuedTask->getTypes();

		foreach ($types as $type) {
			$this->out("			" . str_pad($type, 20, ' ', STR_PAD_RIGHT) . ": " . $this->QueuedTask->getLength($type));
		}
		$this->hr();
		$this->out('Total unfinished Jobs			: ' . $this->QueuedTask->getLength());
		$this->hr();
		$this->out('Finished Job Statistics:');
		$data = $this->QueuedTask->getStats();
		foreach ($data as $item) {
			$this->out(" " . $item['QueuedTask']['job_type'] . ": ");
			$this->out("	 Finished Jobs in Database: " . $item[0]['num']);
			$this->out("	 Average Job existence		: " . $item[0]['alltime'] . 's');
			$this->out("	 Average Execution delay	: " . $item[0]['fetchdelay'] . 's');
			$this->out("	 Average Execution time	 : " . $item[0]['runtime'] . 's');
		}
	}

/**
 * Returns a List of available QueueTasks and their individual configurations.
 *
 * @return array
 */
	private function __getTaskConf() {
		if (!is_array($this->__taskConf)) {
			$this->__taskConf = array();
			foreach ($this->tasks as $task) {
				$this->__taskConf[$task]['name'] = $task;
				if (property_exists($this->{$task}, 'timeout')) {
					$this->__taskConf[$task]['timeout'] = $this->{$task}->timeout;
				} else {
					$this->__taskConf[$task]['timeout'] = Configure::read('Queue.defaultWorkerTimeout');
				}
				if (property_exists($this->{$task}, 'retries')) {
					$this->__taskConf[$task]['retries'] = $this->{$task}->retries;
				} else {
					$this->__taskConf[$task]['retries'] = Configure::read('Queue.defaultWorkerRetries');
				}
				if (property_exists($this->{$task}, 'rate')) {
					$this->__taskConf[$task]['rate'] = $this->{$task}->rate;
				}
			}
		}
		return $this->__taskConf;
	}

/**
 * List available tasks
 *
 * @return void
 */
	public function tasks() {
		$this->out('Available tasks:');
		foreach ($this->taskNames as $loadedTask) {
			$this->out('	- ' . $loadedTask);
		}
	}

}
