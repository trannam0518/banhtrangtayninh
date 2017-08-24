<?php

/**
 * Class SystemCheckTest
 * @property ALoader $load
 * @property ModelToolBackup $model_tool_backup
 * @property ModelSettingSetting $model_setting_setting
 */
class TaskTest extends AbanteCartTest{
	protected $task_details;
	protected function setUp(){
		parent::__construct();

		$this->load->model('tool/backup');
		$this->task_details = $this->model_tool_backup->createBackupTask(
				'manual_backup',
				array( 'compress_backup' => 1,
						'table_list' =>
								array(
										'cba_customers',
										'cba_addresses'
								)
				)
		);
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('api', array('task_api_key', '12345'));


	}

	protected function tearDown(){
		$tm = new ATaskManager();
		$tm->deleteTask($this->task_details['task_id']);
	}

	public function testTaskRunCLI(){
		$task_id = $this->task_details['task_id'];
		exec('php '.DIR_ROOT.'/task.php '.$task_id, $output);
		list($step_id1, $step_id2) = array_keys($this->task_details['steps']);
		$expected = array(
							"Running command line",
							"Task_id: ".$task_id." state - running.",
							"Task_id: ".$task_id." : step_id: ".$step_id1.". 122 tables dumped.",
							"Task_id: ".$task_id." : step_id: ".$step_id2.". ( compressing )",
							"Task_id: ".$task_id." state - finished."
		);

		$this->assertEquals($expected, $output);
	}
	public function testTaskStepRunCLI(){
		$task_id = $this->task_details['task_id'];
		list($step_id1, $step_id2) = array_keys($this->task_details['steps']);
		exec('php '.DIR_ROOT.'/task.php '.$task_id.' '.$step_id1, $output);

		$expected = array(
							"Running command line",
							"Task_id: ".$task_id." : step_id: ".$step_id1.". 122 tables dumped."
		);

		$this->assertEquals($expected, $output);
	}


}