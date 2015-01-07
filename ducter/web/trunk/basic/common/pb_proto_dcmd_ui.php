<?php
/**
 * Auto generated from dcmd_ui.proto at 2014-12-15 16:23:39
 *
 * dcmd_api package
 */

/**
 * UiTaskOutput message
 */
class Dcmd_api_UiTaskOutput extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const SUBTASK_ID = 2;
    const IP = 3;
    const OFFSET = 4;
    const USER = 5;
    const PASSWD = 6;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::SUBTASK_ID => array(
            'name' => 'subtask_id',
            'required' => true,
            'type' => 7,
        ),
        self::IP => array(
            'name' => 'ip',
            'required' => true,
            'type' => 7,
        ),
        self::OFFSET => array(
            'name' => 'offset',
            'required' => true,
            'type' => 5,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::SUBTASK_ID] = null;
        $this->values[self::IP] = null;
        $this->values[self::OFFSET] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'offset' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setOffset($value)
    {
        return $this->set(self::OFFSET, $value);
    }

    /**
     * Returns value of 'offset' property
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->get(self::OFFSET);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiTaskOutputReply message
 */
class Dcmd_api_UiTaskOutputReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const RESULT = 3;
    const OFFSET = 4;
    const ERR = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
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
        self::OFFSET => array(
            'name' => 'offset',
            'required' => true,
            'type' => 5,
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::RESULT] = null;
        $this->values[self::OFFSET] = null;
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'offset' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setOffset($value)
    {
        return $this->set(self::OFFSET, $value);
    }

    /**
     * Returns value of 'offset' property
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->get(self::OFFSET);
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
}

/**
 * UiAgentRunningTask message
 */
class Dcmd_api_UiAgentRunningTask extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const IP = 2;
    const SVR_POOL = 3;
    const USER = 4;
    const PASSWD = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::IP => array(
            'name' => 'ip',
            'required' => false,
            'type' => 7,
        ),
        self::SVR_POOL => array(
            'name' => 'svr_pool',
            'required' => false,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::IP] = null;
        $this->values[self::SVR_POOL] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiAgentRunningTaskReply message
 */
class Dcmd_api_UiAgentRunningTaskReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const RESULT = 3;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::RESULT => array(
            'name' => 'result',
            'repeated' => true,
            'type' => 'Dcmd_api_SubTaskInfo'
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::RESULT] = array();
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Appends value to 'result' list
     *
     * @param Dcmd_api_SubTaskInfo $value Value to append
     *
     * @return null
     */
    public function appendResult(Dcmd_api_SubTaskInfo $value)
    {
        return $this->append(self::RESULT, $value);
    }

    /**
     * Clears 'result' list
     *
     * @return null
     */
    public function clearResult()
    {
        return $this->clear(self::RESULT);
    }

    /**
     * Returns 'result' list
     *
     * @return Dcmd_api_SubTaskInfo[]
     */
    public function getResult()
    {
        return $this->get(self::RESULT);
    }

    /**
     * Returns 'result' iterator
     *
     * @return ArrayIterator
     */
    public function getResultIterator()
    {
        return new \ArrayIterator($this->get(self::RESULT));
    }

    /**
     * Returns element from 'result' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_SubTaskInfo
     */
    public function getResultAt($offset)
    {
        return $this->get(self::RESULT, $offset);
    }

    /**
     * Returns count of 'result' list
     *
     * @return int
     */
    public function getResultCount()
    {
        return $this->count(self::RESULT);
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
}

/**
 * UiAgentRunningOpr message
 */
class Dcmd_api_UiAgentRunningOpr extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const IP = 2;
    const USER = 3;
    const PASSWD = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::IP => array(
            'name' => 'ip',
            'required' => false,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::IP] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiAgentRunningOprReply message
 */
class Dcmd_api_UiAgentRunningOprReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const RESULT = 3;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::RESULT => array(
            'name' => 'result',
            'repeated' => true,
            'type' => 'Dcmd_api_OprInfo'
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::RESULT] = array();
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Appends value to 'result' list
     *
     * @param Dcmd_api_OprInfo $value Value to append
     *
     * @return null
     */
    public function appendResult(Dcmd_api_OprInfo $value)
    {
        return $this->append(self::RESULT, $value);
    }

    /**
     * Clears 'result' list
     *
     * @return null
     */
    public function clearResult()
    {
        return $this->clear(self::RESULT);
    }

    /**
     * Returns 'result' list
     *
     * @return Dcmd_api_OprInfo[]
     */
    public function getResult()
    {
        return $this->get(self::RESULT);
    }

    /**
     * Returns 'result' iterator
     *
     * @return ArrayIterator
     */
    public function getResultIterator()
    {
        return new \ArrayIterator($this->get(self::RESULT));
    }

    /**
     * Returns element from 'result' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_OprInfo
     */
    public function getResultAt($offset)
    {
        return $this->get(self::RESULT, $offset);
    }

    /**
     * Returns count of 'result' list
     *
     * @return int
     */
    public function getResultCount()
    {
        return $this->count(self::RESULT);
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
}

/**
 * UiExecOprCmd message
 */
class Dcmd_api_UiExecOprCmd extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const OPR_ID = 2;
    const USER = 3;
    const PASSWD = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::OPR_ID => array(
            'name' => 'opr_id',
            'required' => false,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::OPR_ID] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Sets value of 'opr_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOprId($value)
    {
        return $this->set(self::OPR_ID, $value);
    }

    /**
     * Returns value of 'opr_id' property
     *
     * @return string
     */
    public function getOprId()
    {
        return $this->get(self::OPR_ID);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiExecOprCmdReply message
 */
class Dcmd_api_UiExecOprCmdReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const RESULT = 3;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::RESULT => array(
            'name' => 'result',
            'repeated' => true,
            'type' => 'Dcmd_api_AgentOprCmdReply'
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::RESULT] = array();
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Appends value to 'result' list
     *
     * @param Dcmd_api_AgentOprCmdReply $value Value to append
     *
     * @return null
     */
    public function appendResult(Dcmd_api_AgentOprCmdReply $value)
    {
        return $this->append(self::RESULT, $value);
    }

    /**
     * Clears 'result' list
     *
     * @return null
     */
    public function clearResult()
    {
        return $this->clear(self::RESULT);
    }

    /**
     * Returns 'result' list
     *
     * @return Dcmd_api_AgentOprCmdReply[]
     */
    public function getResult()
    {
        return $this->get(self::RESULT);
    }

    /**
     * Returns 'result' iterator
     *
     * @return ArrayIterator
     */
    public function getResultIterator()
    {
        return new \ArrayIterator($this->get(self::RESULT));
    }

    /**
     * Returns element from 'result' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_AgentOprCmdReply
     */
    public function getResultAt($offset)
    {
        return $this->get(self::RESULT, $offset);
    }

    /**
     * Returns count of 'result' list
     *
     * @return int
     */
    public function getResultCount()
    {
        return $this->count(self::RESULT);
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
}

/**
 * UiExecDupOprCmd message
 */
class Dcmd_api_UiExecDupOprCmd extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const DUP_OPR_NAME = 2;
    const ARGS = 3;
    const AGENTS = 4;
    const USER = 5;
    const PASSWD = 6;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::DUP_OPR_NAME => array(
            'name' => 'dup_opr_name',
            'required' => false,
            'type' => 7,
        ),
        self::ARGS => array(
            'name' => 'args',
            'repeated' => true,
            'type' => 'Dcmd_api_KeyValue'
        ),
        self::AGENTS => array(
            'name' => 'agents',
            'repeated' => true,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::DUP_OPR_NAME] = null;
        $this->values[self::ARGS] = array();
        $this->values[self::AGENTS] = array();
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Sets value of 'dup_opr_name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDupOprName($value)
    {
        return $this->set(self::DUP_OPR_NAME, $value);
    }

    /**
     * Returns value of 'dup_opr_name' property
     *
     * @return string
     */
    public function getDupOprName()
    {
        return $this->get(self::DUP_OPR_NAME);
    }

    /**
     * Appends value to 'args' list
     *
     * @param Dcmd_api_KeyValue $value Value to append
     *
     * @return null
     */
    public function appendArgs(Dcmd_api_KeyValue $value)
    {
        return $this->append(self::ARGS, $value);
    }

    /**
     * Clears 'args' list
     *
     * @return null
     */
    public function clearArgs()
    {
        return $this->clear(self::ARGS);
    }

    /**
     * Returns 'args' list
     *
     * @return Dcmd_api_KeyValue[]
     */
    public function getArgs()
    {
        return $this->get(self::ARGS);
    }

    /**
     * Returns 'args' iterator
     *
     * @return ArrayIterator
     */
    public function getArgsIterator()
    {
        return new \ArrayIterator($this->get(self::ARGS));
    }

    /**
     * Returns element from 'args' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_KeyValue
     */
    public function getArgsAt($offset)
    {
        return $this->get(self::ARGS, $offset);
    }

    /**
     * Returns count of 'args' list
     *
     * @return int
     */
    public function getArgsCount()
    {
        return $this->count(self::ARGS);
    }

    /**
     * Appends value to 'agents' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendAgents($value)
    {
        return $this->append(self::AGENTS, $value);
    }

    /**
     * Clears 'agents' list
     *
     * @return null
     */
    public function clearAgents()
    {
        return $this->clear(self::AGENTS);
    }

    /**
     * Returns 'agents' list
     *
     * @return string[]
     */
    public function getAgents()
    {
        return $this->get(self::AGENTS);
    }

    /**
     * Returns 'agents' iterator
     *
     * @return ArrayIterator
     */
    public function getAgentsIterator()
    {
        return new \ArrayIterator($this->get(self::AGENTS));
    }

    /**
     * Returns element from 'agents' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getAgentsAt($offset)
    {
        return $this->get(self::AGENTS, $offset);
    }

    /**
     * Returns count of 'agents' list
     *
     * @return int
     */
    public function getAgentsCount()
    {
        return $this->count(self::AGENTS);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiExecDupOprCmdReply message
 */
class Dcmd_api_UiExecDupOprCmdReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const RESULT = 3;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::RESULT => array(
            'name' => 'result',
            'repeated' => true,
            'type' => 'Dcmd_api_AgentOprCmdReply'
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::RESULT] = array();
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Appends value to 'result' list
     *
     * @param Dcmd_api_AgentOprCmdReply $value Value to append
     *
     * @return null
     */
    public function appendResult(Dcmd_api_AgentOprCmdReply $value)
    {
        return $this->append(self::RESULT, $value);
    }

    /**
     * Clears 'result' list
     *
     * @return null
     */
    public function clearResult()
    {
        return $this->clear(self::RESULT);
    }

    /**
     * Returns 'result' list
     *
     * @return Dcmd_api_AgentOprCmdReply[]
     */
    public function getResult()
    {
        return $this->get(self::RESULT);
    }

    /**
     * Returns 'result' iterator
     *
     * @return ArrayIterator
     */
    public function getResultIterator()
    {
        return new \ArrayIterator($this->get(self::RESULT));
    }

    /**
     * Returns element from 'result' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_AgentOprCmdReply
     */
    public function getResultAt($offset)
    {
        return $this->get(self::RESULT, $offset);
    }

    /**
     * Returns count of 'result' list
     *
     * @return int
     */
    public function getResultCount()
    {
        return $this->count(self::RESULT);
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
}

/**
 * UiAgentInfo message
 */
class Dcmd_api_UiAgentInfo extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const IPS = 2;
    const VERSION = 3;
    const USER = 4;
    const PASSWD = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::IPS => array(
            'name' => 'ips',
            'repeated' => true,
            'type' => 7,
        ),
        self::VERSION => array(
            'name' => 'version',
            'required' => true,
            'type' => 8,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::IPS] = array();
        $this->values[self::VERSION] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Appends value to 'ips' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendIps($value)
    {
        return $this->append(self::IPS, $value);
    }

    /**
     * Clears 'ips' list
     *
     * @return null
     */
    public function clearIps()
    {
        return $this->clear(self::IPS);
    }

    /**
     * Returns 'ips' list
     *
     * @return string[]
     */
    public function getIps()
    {
        return $this->get(self::IPS);
    }

    /**
     * Returns 'ips' iterator
     *
     * @return ArrayIterator
     */
    public function getIpsIterator()
    {
        return new \ArrayIterator($this->get(self::IPS));
    }

    /**
     * Returns element from 'ips' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getIpsAt($offset)
    {
        return $this->get(self::IPS, $offset);
    }

    /**
     * Returns count of 'ips' list
     *
     * @return int
     */
    public function getIpsCount()
    {
        return $this->count(self::IPS);
    }

    /**
     * Sets value of 'version' property
     *
     * @param bool $value Property value
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
     * @return bool
     */
    public function getVersion()
    {
        return $this->get(self::VERSION);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiAgentInfoReply message
 */
class Dcmd_api_UiAgentInfoReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const AGENTINFO = 3;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::AGENTINFO => array(
            'name' => 'agentinfo',
            'repeated' => true,
            'type' => 'Dcmd_api_AgentInfo'
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::AGENTINFO] = array();
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Appends value to 'agentinfo' list
     *
     * @param Dcmd_api_AgentInfo $value Value to append
     *
     * @return null
     */
    public function appendAgentinfo(Dcmd_api_AgentInfo $value)
    {
        return $this->append(self::AGENTINFO, $value);
    }

    /**
     * Clears 'agentinfo' list
     *
     * @return null
     */
    public function clearAgentinfo()
    {
        return $this->clear(self::AGENTINFO);
    }

    /**
     * Returns 'agentinfo' list
     *
     * @return Dcmd_api_AgentInfo[]
     */
    public function getAgentinfo()
    {
        return $this->get(self::AGENTINFO);
    }

    /**
     * Returns 'agentinfo' iterator
     *
     * @return ArrayIterator
     */
    public function getAgentinfoIterator()
    {
        return new \ArrayIterator($this->get(self::AGENTINFO));
    }

    /**
     * Returns element from 'agentinfo' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_AgentInfo
     */
    public function getAgentinfoAt($offset)
    {
        return $this->get(self::AGENTINFO, $offset);
    }

    /**
     * Returns count of 'agentinfo' list
     *
     * @return int
     */
    public function getAgentinfoCount()
    {
        return $this->count(self::AGENTINFO);
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
}

/**
 * UiInvalidAgentInfo message
 */
class Dcmd_api_UiInvalidAgentInfo extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const USER = 2;
    const PASSWD = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiInvalidAgentInfoReply message
 */
class Dcmd_api_UiInvalidAgentInfoReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const AGENTINFO = 3;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::AGENTINFO => array(
            'name' => 'agentinfo',
            'repeated' => true,
            'type' => 'Dcmd_api_AgentInfo'
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::AGENTINFO] = array();
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Appends value to 'agentinfo' list
     *
     * @param Dcmd_api_AgentInfo $value Value to append
     *
     * @return null
     */
    public function appendAgentinfo(Dcmd_api_AgentInfo $value)
    {
        return $this->append(self::AGENTINFO, $value);
    }

    /**
     * Clears 'agentinfo' list
     *
     * @return null
     */
    public function clearAgentinfo()
    {
        return $this->clear(self::AGENTINFO);
    }

    /**
     * Returns 'agentinfo' list
     *
     * @return Dcmd_api_AgentInfo[]
     */
    public function getAgentinfo()
    {
        return $this->get(self::AGENTINFO);
    }

    /**
     * Returns 'agentinfo' iterator
     *
     * @return ArrayIterator
     */
    public function getAgentinfoIterator()
    {
        return new \ArrayIterator($this->get(self::AGENTINFO));
    }

    /**
     * Returns element from 'agentinfo' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_AgentInfo
     */
    public function getAgentinfoAt($offset)
    {
        return $this->get(self::AGENTINFO, $offset);
    }

    /**
     * Returns count of 'agentinfo' list
     *
     * @return int
     */
    public function getAgentinfoCount()
    {
        return $this->count(self::AGENTINFO);
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
}

/**
 * UiTaskScriptInfo message
 */
class Dcmd_api_UiTaskScriptInfo extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const TASK_CMD = 2;
    const USER = 3;
    const PASSWD = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::TASK_CMD => array(
            'name' => 'task_cmd',
            'required' => true,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::TASK_CMD] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiTaskScriptInfoReply message
 */
class Dcmd_api_UiTaskScriptInfoReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const SCRIPT = 3;
    const ERR = 4;
    const MD5 = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::SCRIPT => array(
            'name' => 'script',
            'required' => false,
            'type' => 7,
        ),
        self::ERR => array(
            'name' => 'err',
            'required' => false,
            'type' => 7,
        ),
        self::MD5 => array(
            'name' => 'md5',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::SCRIPT] = null;
        $this->values[self::ERR] = null;
        $this->values[self::MD5] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'script' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setScript($value)
    {
        return $this->set(self::SCRIPT, $value);
    }

    /**
     * Returns value of 'script' property
     *
     * @return string
     */
    public function getScript()
    {
        return $this->get(self::SCRIPT);
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
     * Sets value of 'md5' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMd5($value)
    {
        return $this->set(self::MD5, $value);
    }

    /**
     * Returns value of 'md5' property
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->get(self::MD5);
    }
}

/**
 * UiOprScriptInfo message
 */
class Dcmd_api_UiOprScriptInfo extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const OPR_FILE = 2;
    const USER = 3;
    const PASSWD = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::OPR_FILE => array(
            'name' => 'opr_file',
            'required' => true,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::OPR_FILE] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Sets value of 'opr_file' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setOprFile($value)
    {
        return $this->set(self::OPR_FILE, $value);
    }

    /**
     * Returns value of 'opr_file' property
     *
     * @return string
     */
    public function getOprFile()
    {
        return $this->get(self::OPR_FILE);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiOprScriptInfoReply message
 */
class Dcmd_api_UiOprScriptInfoReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const SCRIPT = 3;
    const ERR = 4;
    const MD5 = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::SCRIPT => array(
            'name' => 'script',
            'required' => false,
            'type' => 7,
        ),
        self::ERR => array(
            'name' => 'err',
            'required' => false,
            'type' => 7,
        ),
        self::MD5 => array(
            'name' => 'md5',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::SCRIPT] = null;
        $this->values[self::ERR] = null;
        $this->values[self::MD5] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'script' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setScript($value)
    {
        return $this->set(self::SCRIPT, $value);
    }

    /**
     * Returns value of 'script' property
     *
     * @return string
     */
    public function getScript()
    {
        return $this->get(self::SCRIPT);
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
     * Sets value of 'md5' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setMd5($value)
    {
        return $this->set(self::MD5, $value);
    }

    /**
     * Returns value of 'md5' property
     *
     * @return string
     */
    public function getMd5()
    {
        return $this->get(self::MD5);
    }
}

/**
 * UiAgentTaskProcess message
 */
class Dcmd_api_UiAgentTaskProcess extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const SUBTASK_ID = 2;
    const USER = 3;
    const PASSWD = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::SUBTASK_ID => array(
            'name' => 'subtask_id',
            'repeated' => true,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::SUBTASK_ID] = array();
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Appends value to 'subtask_id' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendSubtaskId($value)
    {
        return $this->append(self::SUBTASK_ID, $value);
    }

    /**
     * Clears 'subtask_id' list
     *
     * @return null
     */
    public function clearSubtaskId()
    {
        return $this->clear(self::SUBTASK_ID);
    }

    /**
     * Returns 'subtask_id' list
     *
     * @return string[]
     */
    public function getSubtaskId()
    {
        return $this->get(self::SUBTASK_ID);
    }

    /**
     * Returns 'subtask_id' iterator
     *
     * @return ArrayIterator
     */
    public function getSubtaskIdIterator()
    {
        return new \ArrayIterator($this->get(self::SUBTASK_ID));
    }

    /**
     * Returns element from 'subtask_id' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getSubtaskIdAt($offset)
    {
        return $this->get(self::SUBTASK_ID, $offset);
    }

    /**
     * Returns count of 'subtask_id' list
     *
     * @return int
     */
    public function getSubtaskIdCount()
    {
        return $this->count(self::SUBTASK_ID);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiAgentTaskProcessReply message
 */
class Dcmd_api_UiAgentTaskProcessReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const PROCESS = 3;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::PROCESS => array(
            'name' => 'process',
            'repeated' => true,
            'type' => 'Dcmd_api_SubTaskProcess'
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::PROCESS] = array();
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Appends value to 'process' list
     *
     * @param Dcmd_api_SubTaskProcess $value Value to append
     *
     * @return null
     */
    public function appendProcess(Dcmd_api_SubTaskProcess $value)
    {
        return $this->append(self::PROCESS, $value);
    }

    /**
     * Clears 'process' list
     *
     * @return null
     */
    public function clearProcess()
    {
        return $this->clear(self::PROCESS);
    }

    /**
     * Returns 'process' list
     *
     * @return Dcmd_api_SubTaskProcess[]
     */
    public function getProcess()
    {
        return $this->get(self::PROCESS);
    }

    /**
     * Returns 'process' iterator
     *
     * @return ArrayIterator
     */
    public function getProcessIterator()
    {
        return new \ArrayIterator($this->get(self::PROCESS));
    }

    /**
     * Returns element from 'process' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return Dcmd_api_SubTaskProcess
     */
    public function getProcessAt($offset)
    {
        return $this->get(self::PROCESS, $offset);
    }

    /**
     * Returns count of 'process' list
     *
     * @return int
     */
    public function getProcessCount()
    {
        return $this->count(self::PROCESS);
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
}

/**
 * UiTaskCmd message
 */
class Dcmd_api_UiTaskCmd extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const TASK_ID = 2;
    const UID = 3;
    const SUBTASK_ID = 4;
    const IP = 5;
    const SVR_NAME = 6;
    const SVR_POOL = 7;
    const CONCURRENT_NUM = 8;
    const CONCURRENT_RATE = 9;
    const TASK_TIMEOUT = 10;
    const AUTO = 11;
    const CMD_TYPE = 12;
    const USER = 13;
    const PASSWD = 14;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::TASK_ID => array(
            'name' => 'task_id',
            'required' => true,
            'type' => 7,
        ),
        self::UID => array(
            'name' => 'uid',
            'required' => true,
            'type' => 5,
        ),
        self::SUBTASK_ID => array(
            'name' => 'subtask_id',
            'required' => false,
            'type' => 7,
        ),
        self::IP => array(
            'name' => 'ip',
            'required' => false,
            'type' => 7,
        ),
        self::SVR_NAME => array(
            'name' => 'svr_name',
            'required' => false,
            'type' => 7,
        ),
        self::SVR_POOL => array(
            'name' => 'svr_pool',
            'required' => false,
            'type' => 7,
        ),
        self::CONCURRENT_NUM => array(
            'name' => 'concurrent_num',
            'required' => false,
            'type' => 5,
        ),
        self::CONCURRENT_RATE => array(
            'name' => 'concurrent_rate',
            'required' => false,
            'type' => 5,
        ),
        self::TASK_TIMEOUT => array(
            'name' => 'task_timeout',
            'required' => false,
            'type' => 5,
        ),
        self::AUTO => array(
            'name' => 'auto',
            'required' => false,
            'type' => 8,
        ),
        self::CMD_TYPE => array(
            'name' => 'cmd_type',
            'required' => true,
            'type' => 5,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::TASK_ID] = null;
        $this->values[self::UID] = null;
        $this->values[self::SUBTASK_ID] = null;
        $this->values[self::IP] = null;
        $this->values[self::SVR_NAME] = null;
        $this->values[self::SVR_POOL] = null;
        $this->values[self::CONCURRENT_NUM] = null;
        $this->values[self::CONCURRENT_RATE] = null;
        $this->values[self::TASK_TIMEOUT] = null;
        $this->values[self::AUTO] = null;
        $this->values[self::CMD_TYPE] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'uid' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setUid($value)
    {
        return $this->set(self::UID, $value);
    }

    /**
     * Returns value of 'uid' property
     *
     * @return int
     */
    public function getUid()
    {
        return $this->get(self::UID);
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
     * Sets value of 'concurrent_num' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setConcurrentNum($value)
    {
        return $this->set(self::CONCURRENT_NUM, $value);
    }

    /**
     * Returns value of 'concurrent_num' property
     *
     * @return int
     */
    public function getConcurrentNum()
    {
        return $this->get(self::CONCURRENT_NUM);
    }

    /**
     * Sets value of 'concurrent_rate' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setConcurrentRate($value)
    {
        return $this->set(self::CONCURRENT_RATE, $value);
    }

    /**
     * Returns value of 'concurrent_rate' property
     *
     * @return int
     */
    public function getConcurrentRate()
    {
        return $this->get(self::CONCURRENT_RATE);
    }

    /**
     * Sets value of 'task_timeout' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setTaskTimeout($value)
    {
        return $this->set(self::TASK_TIMEOUT, $value);
    }

    /**
     * Returns value of 'task_timeout' property
     *
     * @return int
     */
    public function getTaskTimeout()
    {
        return $this->get(self::TASK_TIMEOUT);
    }

    /**
     * Sets value of 'auto' property
     *
     * @param bool $value Property value
     *
     * @return null
     */
    public function setAuto($value)
    {
        return $this->set(self::AUTO, $value);
    }

    /**
     * Returns value of 'auto' property
     *
     * @return bool
     */
    public function getAuto()
    {
        return $this->get(self::AUTO);
    }

    /**
     * Sets value of 'cmd_type' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setCmdType($value)
    {
        return $this->set(self::CMD_TYPE, $value);
    }

    /**
     * Returns value of 'cmd_type' property
     *
     * @return int
     */
    public function getCmdType()
    {
        return $this->get(self::CMD_TYPE);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiTaskCmdReply message
 */
class Dcmd_api_UiTaskCmdReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const ERR = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
}

/**
 * UiAgentHostName message
 */
class Dcmd_api_UiAgentHostName extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const AGENT_IP = 2;
    const USER = 3;
    const PASSWD = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::AGENT_IP => array(
            'name' => 'agent_ip',
            'required' => true,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::AGENT_IP] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Sets value of 'agent_ip' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAgentIp($value)
    {
        return $this->set(self::AGENT_IP, $value);
    }

    /**
     * Returns value of 'agent_ip' property
     *
     * @return string
     */
    public function getAgentIp()
    {
        return $this->get(self::AGENT_IP);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiAgentHostNameReply message
 */
class Dcmd_api_UiAgentHostNameReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const IS_EXIST = 3;
    const HOSTNAME = 4;
    const ERR = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::IS_EXIST => array(
            'name' => 'is_exist',
            'required' => true,
            'type' => 8,
        ),
        self::HOSTNAME => array(
            'name' => 'hostname',
            'required' => true,
            'type' => 7,
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::IS_EXIST] = null;
        $this->values[self::HOSTNAME] = null;
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
     * Sets value of 'is_exist' property
     *
     * @param bool $value Property value
     *
     * @return null
     */
    public function setIsExist($value)
    {
        return $this->set(self::IS_EXIST, $value);
    }

    /**
     * Returns value of 'is_exist' property
     *
     * @return bool
     */
    public function getIsExist()
    {
        return $this->get(self::IS_EXIST);
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
}

/**
 * UiAgentValid message
 */
class Dcmd_api_UiAgentValid extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const AGENT_IP = 2;
    const USER = 3;
    const PASSWD = 4;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::AGENT_IP => array(
            'name' => 'agent_ip',
            'required' => true,
            'type' => 7,
        ),
        self::USER => array(
            'name' => 'user',
            'required' => true,
            'type' => 7,
        ),
        self::PASSWD => array(
            'name' => 'passwd',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::AGENT_IP] = null;
        $this->values[self::USER] = null;
        $this->values[self::PASSWD] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
    }

    /**
     * Sets value of 'agent_ip' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAgentIp($value)
    {
        return $this->set(self::AGENT_IP, $value);
    }

    /**
     * Returns value of 'agent_ip' property
     *
     * @return string
     */
    public function getAgentIp()
    {
        return $this->get(self::AGENT_IP);
    }

    /**
     * Sets value of 'user' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUser($value)
    {
        return $this->set(self::USER, $value);
    }

    /**
     * Returns value of 'user' property
     *
     * @return string
     */
    public function getUser()
    {
        return $this->get(self::USER);
    }

    /**
     * Sets value of 'passwd' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPasswd($value)
    {
        return $this->set(self::PASSWD, $value);
    }

    /**
     * Returns value of 'passwd' property
     *
     * @return string
     */
    public function getPasswd()
    {
        return $this->get(self::PASSWD);
    }
}

/**
 * UiAgentValidReply message
 */
class Dcmd_api_UiAgentValidReply extends \ProtobufMessage
{
    /* Field index constants */
    const CLIENT_MSG_ID = 1;
    const STATE = 2;
    const ERR = 3;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CLIENT_MSG_ID => array(
            'name' => 'client_msg_id',
            'required' => true,
            'type' => 5,
        ),
        self::STATE => array(
            'name' => 'state',
            'required' => true,
            'type' => 5,
        ),
        self::ERR => array(
            'name' => 'err',
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
        $this->values[self::CLIENT_MSG_ID] = null;
        $this->values[self::STATE] = null;
        $this->values[self::ERR] = null;
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
     * Sets value of 'client_msg_id' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setClientMsgId($value)
    {
        return $this->set(self::CLIENT_MSG_ID, $value);
    }

    /**
     * Returns value of 'client_msg_id' property
     *
     * @return int
     */
    public function getClientMsgId()
    {
        return $this->get(self::CLIENT_MSG_ID);
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
}
require_once 'pb_proto_dcmd_cmn.php';