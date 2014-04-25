<?php
class MessageComponent extends Component{
	function __construct() {
        $this->Message = ClassRegistry::init("Message");
	}
	public function Sent($user_id,$recipient_id,$type,$content,$object_id,$object_type)
	{
        $data = array('user_id' => $user_id,'recipient_id'=>$recipient_id ,'type' => $type, 'content' => $content, 'object_id' => $object_id ,'object_type'=>$object_type);
        $message = array('Message' => $data);
        $this->Message->set($message);
		if ($this->Message->validates()) {
			$this->Message->Save();
			return True;
		} else {
		    $errors = $this->Message->validationErrors;
		   	return false;
		}
	}
	public function inbox($user_id)
	{
        $inbox = $this->Message->find('all',array(
        	'conditions' => array(
        			'recipient_id' => $user_id
        			),
        		)
        	);
        return $inbox;
	}
	
	public function unread($user_id)
	{
        $inbox = $this->Message->find('all',array(
        	'conditions' => array(
        			'recipient_id' => $user_id,
        			'read' => 0
        			),
        		)
        	);
        return sizeof($inbox);
	}

	public function read($message_id)
	{
		$this->Message->findById($message_id);
		$this->Message->read(null, $message_id);
		$this->Message->set('read',1);
		$this->Message->save();
	}
	
	public function remove($message_id)
	{
		$this->Message->delete($message_id);
	}

	public function get_message($value='')
	{
		$message = $this->Message->findById($value);
		return $message;
	}
}
?>