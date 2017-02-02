<?php
/**
 * @class  contactController
 * @author NHN (developers@xpressengine.com)
 * @brief  contact module Controller class
 **/

class contactController extends contact {

	/**
	 * @brief initialization
	 **/
	function init() {
	}

	/**
	 * @brief send email 
	 **/
	function procContactSendEmail(){
		$logged_info = Context::get('logged_info');
		if($this->module_info->send_grant_all != 'Y' && !$logged_info) return new Object(-1, 'msg_logged_can_send_mail');
		if(!$this->module_info->admin_mail) return new Object(-1, 'msg_do_set_admin_mail');

		$oMail = new Mail();

		$oMail->setContentType("plain");

		// get form variables submitted
		$obj = Context::getRequestVars();
		if($obj->enable_terms == 'Y' && !$obj->check_agree) return new Object(-1, 'msg_terms_of_license_agreement');

		$obj->email = $obj->Email;
		$obj->subject = $obj->Subject;
		$obj->comment = $obj->Comment;

		$oDocumentModel = &getModel('document');
		$extra_keys = $oDocumentModel->getExtraKeys($obj->module_srl);

		$mail_content = array();
		$filter_lang = Context::getLang('filter');
		$content = '';
		if(count($extra_keys)) {
			$oModuleController = &getController('module');
			foreach($extra_keys as $idx => $extra_item) {
				$value = '';
				if(isset($obj->{'extra_vars'.$idx})) $value = $obj->{'extra_vars'.$idx};
				elseif(isset($obj->{$extra_item->eid})) $value = $obj->{$extra_item->eid};
				if(!is_array($value)) $value = trim($value);
				if(!isset($value)) continue;
				//check if extra item is required
				$oModuleController->replaceDefinedLangCode($extra_item->name);
				if($extra_item->is_required == 'Y' && $value==""){
					return new Object(-1, sprintf($filter_lang->invalid,$extra_item->name));
				}
				//if the type of form component is email address
				if($extra_item->type == 'email_address' && !$oMail->isVaildMailAddress($value)){
					return new Object(-1, sprintf($filter_lang->invalid_email,$extra_item->name));
				}
				if($extra_item->type == "tel")
				{
					$mail_content[$extra_item->eid] = $obj->{'extra_vars'.$idx}[2];
					$content .= $extra_item->name. ':  ' . $obj->{'extra_vars'.$idx}[2] . "\r\n";
				}
				elseif(is_array($obj->{'extra_vars'.$idx}))
				{
					$mail_content[$extra_item->eid] = implode(",",$obj->{'extra_vars'.$idx});
					$content .= $extra_item->name. ':  ' . implode(",",$obj->{'extra_vars'.$idx}) . "\r\n";
				}
				else
				{
					$mail_content[$extra_item->eid] = $value;
					$content .= $extra_item->name. ':  ' . $value . "\r\n";
				}
				$mail_title[$extra_item->eid] = htmlspecialchars($extra_item->name);
			}
		}


		if(!$oMail->isVaildMailAddress($obj->email)){
			return new Object(-1, sprintf($filter_lang->invalid_email,Context::getLang('email_address')));
		}

		$oMail->setTitle($obj->subject);
		$content_all = $content . "\r\nComments:\r\n" . htmlspecialchars($obj->comment);
		$mail_content['Comments'] = $obj->comment;

		$oMail->setContent(htmlspecialchars($content_all));
		//$oMail->setSender("XE Contact Us", $obj->email);
		$oMail->setSender($obj->email."(".$_SERVER['REMOTE_ADDR'].")", $obj->email);

		$target_mail = explode(',',$this->module_info->admin_mail);

		for($i=0;$i<count($target_mail);$i++) {
			$email_address = trim($target_mail[$i]);
			if(!$email_address || !$oMail->isVaildMailAddress($email_address)) continue;
			$oMail->setReceiptor($email_address, $email_address);

			if($logged_info->is_admin != 'Y'){
				if($this->module_info->module_srl){
					$oModuleModel = &getModel('module');
					$moduleExtraVars = $oModuleModel->getModuleExtraVars($this->module_info->module_srl);
					if($moduleExtraVars[$this->module_info->module_srl]->interval){
						$interval = $moduleExtraVars[$this->module_info->module_srl]->interval;
						//transfer interval to mins
						$interval = $interval*60;
						$oContactModel = &getModel('contact');
						$output = $oContactModel->checkLimited($interval);	
						if(!$output->toBool()) return $output;
					}
				}
			}
			$oMail->send();
		}


		if(isset($_SESSION['mail_content'])) unset($_SESSION['mail_content']);
		if(isset($_SESSION['mail_title'])) unset($_SESSION['mail_title']);
		$_SESSION['mail_content'] = $mail_content;
		$_SESSION['mail_title'] = $mail_title;

		if($logged_info->is_admin != 'Y'){
			$oSpamController = &getController('spamfilter');
			$oSpamController->insertLog();
		}

		$this->add('mid', Context::get('mid'));

		$this->setMessage('msg_email_send_successfully');

		if(!in_array(Context::getRequestMethod(),array('XMLRPC','JSON'))) {
			$returnUrl = Context::get('success_return_url') ? Context::get('success_return_url') : getNotEncodedUrl('', 'act', 'dispCompleteSendMail','mid', $obj->mid);
			header('location:'.$returnUrl);
			return;
		}
	}

}
?>
