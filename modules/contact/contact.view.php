<?php
    /**
     * @class  ContactView
     * @author NHN (developers@xpressengine.com)
     * @brief  contact us module View class
     **/

    class contactView extends contact {


        /**
         * @brief initialize contact view class.
         **/
		function init() {
           /**
             * get skin template_path
             * if it is not found, default skin is xe_contact
             **/
            $template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
            if(!is_dir($template_path)||!$this->module_info->skin) {
                $this->module_info->skin = 'xe_contact_official';
                $template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
            }
            $this->setTemplatePath($template_path);

		}

        /**
         * @brief display contact content
         **/
        function dispContactContent() {

			Context::addJsFilter($this->module_path.'tpl/filter', 'search.xml');
			Context::addJsFilter($this->module_path.'tpl/filter', 'send_email.xml');

			/**
			 * get extra variables from xe_document_extra_keys table, context set
			 **/
			$oDocumentModel = &getModel('document');
			$form_extra_keys = $oDocumentModel->getExtraKeys($this->module_info->module_srl);
			Context::set('form_extra_keys', $form_extra_keys);

			// set template_file to be list.html
            $this->setTemplateFile('index');
        }

		function dispCompleteSendMail() {

			if(isset($_SESSION['mail_content'])){
				$mail_content = $_SESSION['mail_content'];
				Context::set('mail_content',$mail_content);
				Context::set('mail_title',$_SESSION['mail_title']);

			}else{
				Context::set('mail_content','');
				$url = getUrl('mid',$this->mid,'act','');
				header('Location: '.$url);
			}

			unset($_SESSION['mail_content']);

			$this->setTemplateFile('success_form');
		}

    }
?>
