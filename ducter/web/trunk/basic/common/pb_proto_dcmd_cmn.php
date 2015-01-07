<?php
/**
 * Auto generated from dcmd_cmn.proto at 2014-12-15 16:23:39
 *
 * dcmd_api package
 */

/**
 * DcmdMsgType enum
 */
final class Dcmd_api_DcmdMsgType
{
    const MTYPE_AGENT_REPORT = 1;
    const MTYPE_AGENT_REPORT_R = 2;
    const MTYPE_AGENT_HEATBEAT = 3;
    const MTYPE_CENTER_MASTER_NOTICE = 5;
    const MTYPE_CENTER_MASTER_NOTICE_R = 6;
    const MTYPE_CENTER_SUBTASK_CMD = 7;
    const MTYPE_CENTER_SUBTASK_CMD_R = 8;
    const MTYPE_AGENT_SUBTASK_PROCESS = 9;
    const MTYPE_AGENT_SUBTASK_CMD_RESULT = 11;
    const MTYPE_AGENT_SUBTASK_CMD_RESULT_R = 12;
    const MTYPE_CENTER_OPR_CMD = 13;
    const MTYPE_CENTER_OPR_CMD_R = 14;
    const MTYPE_CENTER_AGENT_SUBTASK_OUTPUT = 15;
    const MTYPE_CENTER_AGENT_SUBTASK_OUTPUT_R = 16;
    const MTYPE_CENTER_AGENT_RUNNING_TASK = 17;
    const MTYPE_CENTER_AGENT_RUNNING_TASK_R = 18;
    const MTYPE_CENTER_AGENT_RUNNING_OPR = 19;
    const MTYPE_CENTER_AGENT_RUNNING_OPR_R = 20;
    const MTYPE_UI_AGENT_SUBTASK_OUTPUT = 51;
    const MTYPE_UI_AGENT_SUBTASK_OUTPUT_R = 52;
    const MTYPE_UI_AGENT_RUNNING_SUBTASK = 53;
    const MTYPE_UI_AGENT_RUNNING_SUBTASK_R = 54;
    const MTYPE_UI_AGENT_RUNNING_OPR = 55;
    const MTYPE_UI_AGENT_RUNNING_OPR_R = 56;
    const MTYPE_UI_EXEC_OPR = 57;
    const MTYPE_UI_EXEC_OPR_R = 58;
    const MTYPE_UI_EXEC_DUP_OPR = 59;
    const MTYPE_UI_EXEC_DUP_OPR_R = 60;
    const MTYPE_UI_AGENT_INFO = 61;
    const MTYPE_UI_AGENT_INFO_R = 62;
    const MTYPE_UI_INVALID_AGENT = 63;
    const MTYPE_UI_INVALID_AGENT_R = 64;
    const MTYPE_UI_TASK_CMD_INFO = 65;
    const MTYPE_UI_TASK_CMD_INFO_R = 66;
    const MTYPE_UI_OPR_CMD_INFO = 67;
    const MTYPE_UI_OPR_CMD_INFO_R = 68;
    const MTYPE_UI_SUBTASK_PROCESS = 69;
    const MTYPE_UI_SUBTASK_PROCESS_R = 70;
    const MTYPE_UI_EXEC_TASK = 71;
    const MTYPE_UI_EXEC_TASK_R = 72;
    const MTYPE_UI_FETCH_AGENT_HOSTNAME = 73;
    const MTYPE_UI_FETCH_AGENT_HOSTNAME_R = 74;
    const MTYPE_UI_AUTH_INVALID_AGENT = 75;
    const MTYPE_UI_AUTH_INVALID_AGENT_R = 76;
    const MTYPE_INVALID_MTYPE = 101;
    const MTYPE_CENTER_INTERNAL_CONN = 103;
    const MTYPE_CENTER_INTERNAL_CONN_R = 104;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'MTYPE_AGENT_REPORT' => self::MTYPE_AGENT_REPORT,
            'MTYPE_AGENT_REPORT_R' => self::MTYPE_AGENT_REPORT_R,
            'MTYPE_AGENT_HEATBEAT' => self::MTYPE_AGENT_HEATBEAT,
            'MTYPE_CENTER_MASTER_NOTICE' => self::MTYPE_CENTER_MASTER_NOTICE,
            'MTYPE_CENTER_MASTER_NOTICE_R' => self::MTYPE_CENTER_MASTER_NOTICE_R,
            'MTYPE_CENTER_SUBTASK_CMD' => self::MTYPE_CENTER_SUBTASK_CMD,
            'MTYPE_CENTER_SUBTASK_CMD_R' => self::MTYPE_CENTER_SUBTASK_CMD_R,
            'MTYPE_AGENT_SUBTASK_PROCESS' => self::MTYPE_AGENT_SUBTASK_PROCESS,
            'MTYPE_AGENT_SUBTASK_CMD_RESULT' => self::MTYPE_AGENT_SUBTASK_CMD_RESULT,
            'MTYPE_AGENT_SUBTASK_CMD_RESULT_R' => self::MTYPE_AGENT_SUBTASK_CMD_RESULT_R,
            'MTYPE_CENTER_OPR_CMD' => self::MTYPE_CENTER_OPR_CMD,
            'MTYPE_CENTER_OPR_CMD_R' => self::MTYPE_CENTER_OPR_CMD_R,
            'MTYPE_CENTER_AGENT_SUBTASK_OUTPUT' => self::MTYPE_CENTER_AGENT_SUBTASK_OUTPUT,
            'MTYPE_CENTER_AGENT_SUBTASK_OUTPUT_R' => self::MTYPE_CENTER_AGENT_SUBTASK_OUTPUT_R,
            'MTYPE_CENTER_AGENT_RUNNING_TASK' => self::MTYPE_CENTER_AGENT_RUNNING_TASK,
            'MTYPE_CENTER_AGENT_RUNNING_TASK_R' => self::MTYPE_CENTER_AGENT_RUNNING_TASK_R,
            'MTYPE_CENTER_AGENT_RUNNING_OPR' => self::MTYPE_CENTER_AGENT_RUNNING_OPR,
            'MTYPE_CENTER_AGENT_RUNNING_OPR_R' => self::MTYPE_CENTER_AGENT_RUNNING_OPR_R,
            'MTYPE_UI_AGENT_SUBTASK_OUTPUT' => self::MTYPE_UI_AGENT_SUBTASK_OUTPUT,
            'MTYPE_UI_AGENT_SUBTASK_OUTPUT_R' => self::MTYPE_UI_AGENT_SUBTASK_OUTPUT_R,
            'MTYPE_UI_AGENT_RUNNING_SUBTASK' => self::MTYPE_UI_AGENT_RUNNING_SUBTASK,
            'MTYPE_UI_AGENT_RUNNING_SUBTASK_R' => self::MTYPE_UI_AGENT_RUNNING_SUBTASK_R,
            'MTYPE_UI_AGENT_RUNNING_OPR' => self::MTYPE_UI_AGENT_RUNNING_OPR,
            'MTYPE_UI_AGENT_RUNNING_OPR_R' => self::MTYPE_UI_AGENT_RUNNING_OPR_R,
            'MTYPE_UI_EXEC_OPR' => self::MTYPE_UI_EXEC_OPR,
            'MTYPE_UI_EXEC_OPR_R' => self::MTYPE_UI_EXEC_OPR_R,
            'MTYPE_UI_EXEC_DUP_OPR' => self::MTYPE_UI_EXEC_DUP_OPR,
            'MTYPE_UI_EXEC_DUP_OPR_R' => self::MTYPE_UI_EXEC_DUP_OPR_R,
            'MTYPE_UI_AGENT_INFO' => self::MTYPE_UI_AGENT_INFO,
            'MTYPE_UI_AGENT_INFO_R' => self::MTYPE_UI_AGENT_INFO_R,
            'MTYPE_UI_INVALID_AGENT' => self::MTYPE_UI_INVALID_AGENT,
            'MTYPE_UI_INVALID_AGENT_R' => self::MTYPE_UI_INVALID_AGENT_R,
            'MTYPE_UI_TASK_CMD_INFO' => self::MTYPE_UI_TASK_CMD_INFO,
            'MTYPE_UI_TASK_CMD_INFO_R' => self::MTYPE_UI_TASK_CMD_INFO_R,
            'MTYPE_UI_OPR_CMD_INFO' => self::MTYPE_UI_OPR_CMD_INFO,
            'MTYPE_UI_OPR_CMD_INFO_R' => self::MTYPE_UI_OPR_CMD_INFO_R,
            'MTYPE_UI_SUBTASK_PROCESS' => self::MTYPE_UI_SUBTASK_PROCESS,
            'MTYPE_UI_SUBTASK_PROCESS_R' => self::MTYPE_UI_SUBTASK_PROCESS_R,
            'MTYPE_UI_EXEC_TASK' => self::MTYPE_UI_EXEC_TASK,
            'MTYPE_UI_EXEC_TASK_R' => self::MTYPE_UI_EXEC_TASK_R,
            'MTYPE_UI_FETCH_AGENT_HOSTNAME' => self::MTYPE_UI_FETCH_AGENT_HOSTNAME,
            'MTYPE_UI_FETCH_AGENT_HOSTNAME_R' => self::MTYPE_UI_FETCH_AGENT_HOSTNAME_R,
            'MTYPE_UI_AUTH_INVALID_AGENT' => self::MTYPE_UI_AUTH_INVALID_AGENT,
            'MTYPE_UI_AUTH_INVALID_AGENT_R' => self::MTYPE_UI_AUTH_INVALID_AGENT_R,
            'MTYPE_INVALID_MTYPE' => self::MTYPE_INVALID_MTYPE,
            'MTYPE_CENTER_INTERNAL_CONN' => self::MTYPE_CENTER_INTERNAL_CONN,
            'MTYPE_CENTER_INTERNAL_CONN_R' => self::MTYPE_CENTER_INTERNAL_CONN_R,
        );
    }
}

/**
 * DcmdState enum
 */
final class Dcmd_api_DcmdState
{
    const DCMD_STATE_SUCCESS = 0;
    const DCMD_STATE_NO_MASTER = 1;
    const DCMD_STATE_WRONG_MASTER = 2;
    const DCMD_STATE_NO_TASK = 3;
    const DCMD_STATE_NO_SUBTASK = 4;
    const DCMD_STATE_HOST_LOST = 5;
    const DCMD_STATE_FAILED = 6;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'DCMD_STATE_SUCCESS' => self::DCMD_STATE_SUCCESS,
            'DCMD_STATE_NO_MASTER' => self::DCMD_STATE_NO_MASTER,
            'DCMD_STATE_WRONG_MASTER' => self::DCMD_STATE_WRONG_MASTER,
            'DCMD_STATE_NO_TASK' => self::DCMD_STATE_NO_TASK,
            'DCMD_STATE_NO_SUBTASK' => self::DCMD_STATE_NO_SUBTASK,
            'DCMD_STATE_HOST_LOST' => self::DCMD_STATE_HOST_LOST,
            'DCMD_STATE_FAILED' => self::DCMD_STATE_FAILED,
        );
    }
}

/**
 * CmdType enum
 */
final class Dcmd_api_CmdType
{
    const CMD_UNKNOWN = 0;
    const CMD_START_TASK = 1;
    const CMD_PAUSE_TASK = 2;
    const CMD_RESUME_TASK = 3;
    const CMD_RETRY_TASK = 4;
    const CMD_FINISH_TASK = 5;
    const CMD_ADD_NODE = 6;
    const CMD_CANCEL_SUBTASK = 7;
    const CMD_CANCEL_SVR_SUBTASK = 8;
    const CMD_DO_SUBTASK = 9;
    const CMD_REDO_TASK = 10;
    const CMD_REDO_SVR_POOL = 11;
    const CMD_REDO_SUBTASK = 12;
    const CMD_IGNORE_SUBTASK = 13;
    const CMD_FREEZE_TASK = 14;
    const CMD_UNFREEZE_TASK = 15;
    const CMD_UPDATE_TASK = 16;
    const CMD_DEL_NODE = 17;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'CMD_UNKNOWN' => self::CMD_UNKNOWN,
            'CMD_START_TASK' => self::CMD_START_TASK,
            'CMD_PAUSE_TASK' => self::CMD_PAUSE_TASK,
            'CMD_RESUME_TASK' => self::CMD_RESUME_TASK,
            'CMD_RETRY_TASK' => self::CMD_RETRY_TASK,
            'CMD_FINISH_TASK' => self::CMD_FINISH_TASK,
            'CMD_ADD_NODE' => self::CMD_ADD_NODE,
            'CMD_CANCEL_SUBTASK' => self::CMD_CANCEL_SUBTASK,
            'CMD_CANCEL_SVR_SUBTASK' => self::CMD_CANCEL_SVR_SUBTASK,
            'CMD_DO_SUBTASK' => self::CMD_DO_SUBTASK,
            'CMD_REDO_TASK' => self::CMD_REDO_TASK,
            'CMD_REDO_SVR_POOL' => self::CMD_REDO_SVR_POOL,
            'CMD_REDO_SUBTASK' => self::CMD_REDO_SUBTASK,
            'CMD_IGNORE_SUBTASK' => self::CMD_IGNORE_SUBTASK,
            'CMD_FREEZE_TASK' => self::CMD_FREEZE_TASK,
            'CMD_UNFREEZE_TASK' => self::CMD_UNFREEZE_TASK,
            'CMD_UPDATE_TASK' => self::CMD_UPDATE_TASK,
            'CMD_DEL_NODE' => self::CMD_DEL_NODE,
        );
    }
}

/**
 * AgentState enum
 */
final class Dcmd_api_AgentState
{
    const AGENT_UN_CONNECTED = 0;
    const AGENT_UN_AUTH = 1;
    const AGENT_UN_REPORTED = 2;
    const AGENT_CONNECTED = 3;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'AGENT_UN_CONNECTED' => self::AGENT_UN_CONNECTED,
            'AGENT_UN_AUTH' => self::AGENT_UN_AUTH,
            'AGENT_UN_REPORTED' => self::AGENT_UN_REPORTED,
            'AGENT_CONNECTED' => self::AGENT_CONNECTED,
        );
    }
}

/**
 * TaskState enum
 */
final class Dcmd_api_TaskState
{
    const TASK_INIT = 0;
    const TASK_DOING = 1;
    const TASK_FAILED = 2;
    const TASK_FINISHED = 3;
    const TASK_FINISHED_WITH_FAILED = 4;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'TASK_INIT' => self::TASK_INIT,
            'TASK_DOING' => self::TASK_DOING,
            'TASK_FAILED' => self::TASK_FAILED,
            'TASK_FINISHED' => self::TASK_FINISHED,
            'TASK_FINISHED_WITH_FAILED' => self::TASK_FINISHED_WITH_FAILED,
        );
    }
}

/**
 * SubTaskState enum
 */
final class Dcmd_api_SubTaskState
{
    const SUBTASK_INIT = 0;
    const SUBTASK_DOING = 1;
    const SUBTASK_FINISHED = 2;
    const SUBTASK_FAILED = 3;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'SUBTASK_INIT' => self::SUBTASK_INIT,
            'SUBTASK_DOING' => self::SUBTASK_DOING,
            'SUBTASK_FINISHED' => self::SUBTASK_FINISHED,
            'SUBTASK_FAILED' => self::SUBTASK_FAILED,
        );
    }
}

/**
 * CommandState enum
 */
final class Dcmd_api_CommandState
{
    const COMMAND_DOING = 0;
    const COMMAND_SUCCESS = 1;
    const COMMAND_FAILED = 2;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'COMMAND_DOING' => self::COMMAND_DOING,
            'COMMAND_SUCCESS' => self::COMMAND_SUCCESS,
            'COMMAND_FAILED' => self::COMMAND_FAILED,
        );
    }
}

/**
 * KeyValue message
 */
class Dcmd_api_KeyValue extends \ProtobufMessage
{
    /* Field index constants */
    const KEY = 1;
    const VALUE = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::KEY => array(
            'name' => 'key',
            'required' => true,
            'type' => 7,
        ),
        self::VALUE => array(
            'name' => 'value',
            'required' => true,
            'type' => 7,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::KEY] = null;
        $this->values[self::VALUE] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'key' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setKey($value)
    {
        return $this->set(self::KEY, $value);
    }

    /**
     * Returns value of 'key' property
     *
     * @return string
     */
    public function getKey()
    {
        return $this->get(self::KEY);
    }

    /**
     * Sets value of 'value' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setValue($value)
    {
        return $this->set(self::VALUE, $value);
    }

    /**
     * Returns value of 'value' property
     *
     * @return string
     */
    public function getValue()
    {
        return $this->get(self::VALUE);
    }
}

/**
 * SubTaskInfo message
 */
class Dcmd_api_SubTaskInfo extends \ProtobufMessage
{
    /* Field index constants */
    const SVR_NAME = 1;
    const SVR_POOL = 2;
    const TASK_CMD = 3;
    const TASK_ID = 4;
    const SUBTASK_ID = 5;
    const CMD_ID = 6;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::SVR_NAME => array(
            'name' => 'svr_name',
            'required' => true,
            'type' => 7,
        ),
        self::SVR_POOL => array(
            'name' => 'svr_pool',
            'required' => true,
            'type' => 7,
        ),
        self::TASK_CMD => array(
            'name' => 'task_cmd',
            'required' => true,
            'type' => 7,
        ),
        self::TASK_ID => array(
            'name' => 'task_id',
            'required' => true,
            'type' => 7,
        ),
        self::SUBTASK_ID => array(
            'name' => 'subtask_id',
            'required' => true,
            'type' => 7,
        ),
        self::CMD_ID => array(
            'name' => 'cmd_id',
            'required' => true,
            'type' => 7,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::SVR_NAME] = null;
        $this->values[self::SVR_POOL] = null;
        $this->values[self::TASK_CMD] = null;
        $this->values[self::TASK_ID] = null;
        $this->values[self::SUBTASK_ID] = null;
        $this->values[self::CMD_ID] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'svr_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSvrName($value)
    {
        return $this->set(self::SVR_NAME, $value);
    }

    /**
     * Returns value of 'svr_name' property
     *
     * @return string
     */
    public function getSvrName()
    {
        return $this->get(self::SVR_NAME);
    }

    /**
     * Sets value of 'svr_pool' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSvrPool($value)
    {
        return $this->set(self::SVR_POOL, $value);
    }

    /**
     * Returns value of 'svr_pool' property
     *
     * @return string
     */
    public function getSvrPool()
    {
        return $this->get(self::SVR_POOL);
    }

    /**
     * Sets value of 'task_cmd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTaskCmd($value)
    {
        return $this->set(self::TASK_CMD, $value);
    }

    /**
     * Returns value of 'task_cmd' property
     *
     * @return string
     */
    public function getTaskCmd()
    {
        return $this->get(self::TASK_CMD);
    }

    /**
     * Sets value of 'task_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTaskId($value)
    {
        return $this->set(self::TASK_ID, $value);
    }

    /**
     * Returns value of 'task_id' property
     *
     * @return string
     */
    public function getTaskId()
    {
        return $this->get(self::TASK_ID);
    }

    /**
     * Sets value of 'subtask_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSubtaskId($value)
    {
        return $this->set(self::SUBTASK_ID, $value);
    }

    /**
     * Returns value of 'subtask_id' property
     *
     * @return string
     */
    public function getSubtaskId()
    {
        return $this->get(self::SUBTASK_ID);
    }

    /**
     * Sets value of 'cmd_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCmdId($value)
    {
        return $this->set(self::CMD_ID, $value);
    }

    /**
     * Returns value of 'cmd_id' property
     *
     * @return string
     */
    public function getCmdId()
    {
        return $this->get(self::CMD_ID);
    }
}

/**
 * OprInfo message
 */
class Dcmd_api_OprInfo extends \ProtobufMessage
{
    /* Field index constants */
    const NAME = 1;
    const START_TIME = 2;
    const RUNNING_SECOND = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::NAME => array(
            'name' => 'name',
            'required' => true,
            'type' => 7,
        ),
        self::START_TIME => array(
            'name' => 'start_time',
            'required' => true,
            'type' => 7,
        ),
        self::RUNNING_SECOND => array(
            'name' => 'running_second',
            'required' => true,
            'type' => 5,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::NAME] = null;
        $this->values[self::START_TIME] = null;
        $this->values[self::RUNNING_SECOND] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setName($value)
    {
        return $this->set(self::NAME, $value);
    }

    /**
     * Returns value of 'name' property
     *
     * @return string
     */
    public function getName()
    {
        return $this->get(self::NAME);
    }

    /**
     * Sets value of 'start_time' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setStartTime($value)
    {
        return $this->set(self::START_TIME, $value);
    }

    /**
     * Returns value of 'start_time' property
     *
     * @return string
     */
    public function getStartTime()
    {
        return $this->get(self::START_TIME);
    }

    /**
     * Sets value of 'running_second' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setRunningSecond($value)
    {
        return $this->set(self::RUNNING_SECOND, $value);
    }

    /**
     * Returns value of 'running_second' property
     *
     * @return int
     */
    public function getRunningSecond()
    {
        return $this->get(self::RUNNING_SECOND);
    }
}

/**
 * SubTaskProcess message
 */
class Dcmd_api_SubTaskProcess extends \ProtobufMessage
{
    /* Field index constants */
    const SUBTASK_ID = 1;
    const PROCESS = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::SUBTASK_ID => array(
            'name' => 'subtask_id',
            'required' => true,
            'type' => 7,
        ),
        self::PROCESS => array(
            'name' => 'process',
            'required' => false,
            'type' => 7,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::SUBTASK_ID] = null;
        $this->values[self::PROCESS] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'subtask_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setSubtaskId($value)
    {
        return $this->set(self::SUBTASK_ID, $value);
    }

    /**
     * Returns value of 'subtask_id' property
     *
     * @return string
     */
    public function getSubtaskId()
    {
        return $this->get(self::SUBTASK_ID);
    }

    /**
     * Sets value of 'process' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setProcess($value)
    {
        return $this->set(self::PROCESS, $value);
    }

    /**
     * Returns value of 'process' property
     *
     * @return string
     */
    public function getProcess()
    {
        return $this->get(self::PROCESS);
    }
}

/**
 * TaskInfo message
 */
class Dcmd_api_TaskInfo extends \ProtobufMessage
{
    /* Field index constants */
    const TASK_ID = 1;
    const TASK_STATE = 2;
    const DEPAND_TASK_ID = 3;
    const DEPAND_TASK_NAME = 4;
    const FREEZED = 5;
    const VALID = 6;
    const PAUSE = 7;
    const ERR = 8;
    const SUCCESS_SUBTASK = 9;
    const FAILED_SUBTASK = 10;
    const DOING_SUBTASK = 11;
    const UNDO_SUBTASK = 12;
    const IGNORE_DOING_SUBTASK = 13;
    const IGNORE_FAILED_SUBTASK = 14;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::TASK_ID => array(
            'name' => 'task_id',
            'required' => true,
            'type' => 7,
        ),
        self::TASK_STATE => array(
            'name' => 'task_state',
            'required' => true,
            'type' => 5,
        ),
        self::DEPAND_TASK_ID => array(
            'name' => 'depand_task_id',
            'required' => true,
            'type' => 7,
        ),
        self::DEPAND_TASK_NAME => array(
            'name' => 'depand_task_name',
            'required' => false,
            'type' => 7,
        ),
        self::FREEZED => array(
            'name' => 'freezed',
            'required' => false,
            'type' => 8,
        ),
        self::VALID => array(
            'name' => 'valid',
            'required' => false,
            'type' => 8,
        ),
        self::PAUSE => array(
            'name' => 'pause',
            'required' => false,
            'type' => 8,
        ),
        self::ERR => array(
            'name' => 'err',
            'required' => false,
            'type' => 7,
        ),
        self::SUCCESS_SUBTASK => array(
            'name' => 'success_subtask',
            'required' => false,
            'type' => 5,
        ),
        self::FAILED_SUBTASK => array(
            'name' => 'failed_subtask',
            'required' => false,
            'type' => 5,
        ),
        self::DOING_SUBTASK => array(
            'name' => 'doing_subtask',
            'required' => false,
            'type' => 5,
        ),
        self::UNDO_SUBTASK => array(
            'name' => 'undo_subtask',
            'required' => false,
            'type' => 5,
        ),
        self::IGNORE_DOING_SUBTASK => array(
            'name' => 'ignore_doing_subtask',
            'required' => false,
            'type' => 5,
        ),
        self::IGNORE_FAILED_SUBTASK => array(
            'name' => 'ignore_failed_subtask',
            'required' => false,
            'type' => 5,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::TASK_ID] = null;
        $this->values[self::TASK_STATE] = null;
        $this->values[self::DEPAND_TASK_ID] = null;
        $this->values[self::DEPAND_TASK_NAME] = null;
        $this->values[self::FREEZED] = null;
        $this->values[self::VALID] = null;
        $this->values[self::PAUSE] = null;
        $this->values[self::ERR] = null;
        $this->values[self::SUCCESS_SUBTASK] = null;
        $this->values[self::FAILED_SUBTASK] = null;
        $this->values[self::DOING_SUBTASK] = null;
        $this->values[self::UNDO_SUBTASK] = null;
        $this->values[self::IGNORE_DOING_SUBTASK] = null;
        $this->values[self::IGNORE_FAILED_SUBTASK] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'task_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setTaskId($value)
    {
        return $this->set(self::TASK_ID, $value);
    }

    /**
     * Returns value of 'task_id' property
     *
     * @return string
     */
    public function getTaskId()
    {
        return $this->get(self::TASK_ID);
    }

    /**
     * Sets value of 'task_state' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setTaskState($value)
    {
        return $this->set(self::TASK_STATE, $value);
    }

    /**
     * Returns value of 'task_state' property
     *
     * @return int
     */
    public function getTaskState()
    {
        return $this->get(self::TASK_STATE);
    }

    /**
     * Sets value of 'depand_task_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDepandTaskId($value)
    {
        return $this->set(self::DEPAND_TASK_ID, $value);
    }

    /**
     * Returns value of 'depand_task_id' property
     *
     * @return string
     */
    public function getDepandTaskId()
    {
        return $this->get(self::DEPAND_TASK_ID);
    }

    /**
     * Sets value of 'depand_task_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDepandTaskName($value)
    {
        return $this->set(self::DEPAND_TASK_NAME, $value);
    }

    /**
     * Returns value of 'depand_task_name' property
     *
     * @return string
     */
    public function getDepandTaskName()
    {
        return $this->get(self::DEPAND_TASK_NAME);
    }

    /**
     * Sets value of 'freezed' property
     *
     * @param bool $value Property value
     *
     * @return null
     */
    public function setFreezed($value)
    {
        return $this->set(self::FREEZED, $value);
    }

    /**
     * Returns value of 'freezed' property
     *
     * @return bool
     */
    public function getFreezed()
    {
        return $this->get(self::FREEZED);
    }

    /**
     * Sets value of 'valid' property
     *
     * @param bool $value Property value
     *
     * @return null
     */
    public function setValid($value)
    {
        return $this->set(self::VALID, $value);
    }

    /**
     * Returns value of 'valid' property
     *
     * @return bool
     */
    public function getValid()
    {
        return $this->get(self::VALID);
    }

    /**
     * Sets value of 'pause' property
     *
     * @param bool $value Property value
     *
     * @return null
     */
    public function setPause($value)
    {
        return $this->set(self::PAUSE, $value);
    }

    /**
     * Returns value of 'pause' property
     *
     * @return bool
     */
    public function getPause()
    {
        return $this->get(self::PAUSE);
    }

    /**
     * Sets value of 'err' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setErr($value)
    {
        return $this->set(self::ERR, $value);
    }

    /**
     * Returns value of 'err' property
     *
     * @return string
     */
    public function getErr()
    {
        return $this->get(self::ERR);
    }

    /**
     * Sets value of 'success_subtask' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setSuccessSubtask($value)
    {
        return $this->set(self::SUCCESS_SUBTASK, $value);
    }

    /**
     * Returns value of 'success_subtask' property
     *
     * @return int
     */
    public function getSuccessSubtask()
    {
        return $this->get(self::SUCCESS_SUBTASK);
    }

    /**
     * Sets value of 'failed_subtask' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setFailedSubtask($value)
    {
        return $this->set(self::FAILED_SUBTASK, $value);
    }

    /**
     * Returns value of 'failed_subtask' property
     *
     * @return int
     */
    public function getFailedSubtask()
    {
        return $this->get(self::FAILED_SUBTASK);
    }

    /**
     * Sets value of 'doing_subtask' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setDoingSubtask($value)
    {
        return $this->set(self::DOING_SUBTASK, $value);
    }

    /**
     * Returns value of 'doing_subtask' property
     *
     * @return int
     */
    public function getDoingSubtask()
    {
        return $this->get(self::DOING_SUBTASK);
    }

    /**
     * Sets value of 'undo_subtask' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setUndoSubtask($value)
    {
        return $this->set(self::UNDO_SUBTASK, $value);
    }

    /**
     * Returns value of 'undo_subtask' property
     *
     * @return int
     */
    public function getUndoSubtask()
    {
        return $this->get(self::UNDO_SUBTASK);
    }

    /**
     * Sets value of 'ignore_doing_subtask' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setIgnoreDoingSubtask($value)
    {
        return $this->set(self::IGNORE_DOING_SUBTASK, $value);
    }

    /**
     * Returns value of 'ignore_doing_subtask' property
     *
     * @return int
     */
    public function getIgnoreDoingSubtask()
    {
        return $this->get(self::IGNORE_DOING_SUBTASK);
    }

    /**
     * Sets value of 'ignore_failed_subtask' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setIgnoreFailedSubtask($value)
    {
        return $this->set(self::IGNORE_FAILED_SUBTASK, $value);
    }

    /**
     * Returns value of 'ignore_failed_subtask' property
     *
     * @return int
     */
    public function getIgnoreFailedSubtask()
    {
        return $this->get(self::IGNORE_FAILED_SUBTASK);
    }
}

/**
 * AgentInfo message
 */
class Dcmd_api_AgentInfo extends \ProtobufMessage
{
    /* Field index constants */
    const IP = 1;
    const STATE = 2;
    const VERSION = 3;
    const CONNECTED_IP = 4;
    const REPORTED_IP = 5;
    const HOSTNAME = 6;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::IP => array(
            'name' => 'ip',
            'required' => true,
            'type' => 7,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::VERSION => array(
            'name' => 'version',
            'required' => false,
            'type' => 7,
        ),
        self::CONNECTED_IP => array(
            'name' => 'connected_ip',
            'required' => false,
            'type' => 7,
        ),
        self::REPORTED_IP => array(
            'name' => 'reported_ip',
            'required' => false,
            'type' => 7,
        ),
        self::HOSTNAME => array(
            'name' => 'hostname',
            'required' => false,
            'type' => 7,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::IP] = null;
        $this->values[self::STATE] = null;
        $this->values[self::VERSION] = null;
        $this->values[self::CONNECTED_IP] = null;
        $this->values[self::REPORTED_IP] = null;
        $this->values[self::HOSTNAME] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'ip' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setIp($value)
    {
        return $this->set(self::IP, $value);
    }

    /**
     * Returns value of 'ip' property
     *
     * @return string
     */
    public function getIp()
    {
        return $this->get(self::IP);
    }

    /**
     * Sets value of 'state' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setState($value)
    {
        return $this->set(self::STATE, $value);
    }

    /**
     * Returns value of 'state' property
     *
     * @return int
     */
    public function getState()
    {
        return $this->get(self::STATE);
    }

    /**
     * Sets value of 'version' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setVersion($value)
    {
        return $this->set(self::VERSION, $value);
    }

    /**
     * Returns value of 'version' property
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->get(self::VERSION);
    }

    /**
     * Sets value of 'connected_ip' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setConnectedIp($value)
    {
        return $this->set(self::CONNECTED_IP, $value);
    }

    /**
     * Returns value of 'connected_ip' property
     *
     * @return string
     */
    public function getConnectedIp()
    {
        return $this->get(self::CONNECTED_IP);
    }

    /**
     * Sets value of 'reported_ip' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setReportedIp($value)
    {
        return $this->set(self::REPORTED_IP, $value);
    }

    /**
     * Returns value of 'reported_ip' property
     *
     * @return string
     */
    public function getReportedIp()
    {
        return $this->get(self::REPORTED_IP);
    }

    /**
     * Sets value of 'hostname' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setHostname($value)
    {
        return $this->set(self::HOSTNAME, $value);
    }

    /**
     * Returns value of 'hostname' property
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->get(self::HOSTNAME);
    }
}

/**
 * AgentOprCmdReply message
 */
class Dcmd_api_AgentOprCmdReply extends \ProtobufMessage
{
    /* Field index constants */
    const STATE = 1;
    const RESULT = 2;
    const ERR = 3;
    const IP = 4;
    const STATUS = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::RESULT => array(
            'name' => 'result',
            'required' => true,
            'type' => 7,
        ),
        self::ERR => array(
            'name' => 'err',
            'required' => true,
            'type' => 7,
        ),
        self::IP => array(
            'name' => 'ip',
            'required' => false,
            'type' => 7,
        ),
        self::STATUS => array(
            'name' => 'status',
            'required' => false,
            'type' => 5,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::STATE] = null;
        $this->values[self::RESULT] = null;
        $this->values[self::ERR] = null;
        $this->values[self::IP] = null;
        $this->values[self::STATUS] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'state' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setState($value)
    {
        return $this->set(self::STATE, $value);
    }

    /**
     * Returns value of 'state' property
     *
     * @return int
     */
    public function getState()
    {
        return $this->get(self::STATE);
    }

    /**
     * Sets value of 'result' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setResult($value)
    {
        return $this->set(self::RESULT, $value);
    }

    /**
     * Returns value of 'result' property
     *
     * @return string
     */
    public function getResult()
    {
        return $this->get(self::RESULT);
    }

    /**
     * Sets value of 'err' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setErr($value)
    {
        return $this->set(self::ERR, $value);
    }

    /**
     * Returns value of 'err' property
     *
     * @return string
     */
    public function getErr()
    {
        return $this->get(self::ERR);
    }

    /**
     * Sets value of 'ip' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setIp($value)
    {
        return $this->set(self::IP, $value);
    }

    /**
     * Returns value of 'ip' property
     *
     * @return string
     */
    public function getIp()
    {
        return $this->get(self::IP);
    }

    /**
     * Sets value of 'status' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setStatus($value)
    {
        return $this->set(self::STATUS, $value);
    }

    /**
     * Returns value of 'status' property
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->get(self::STATUS);
    }
}
