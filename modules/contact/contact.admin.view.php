<?php
    /**
     * @class  contactAdminView
     * @author NHN (developers@xpressengine.com)
     * @brief  contact module admin view class
     **/

    class contactAdminView extends contact {

        function init() {
			// get module_srl if it exists
            $module_srl = Context::get('module_srl');
            if(!$module_srl && $this->module_srl) {
                $module_srl = $this->module_srl;
                Context::set('module_srl', $module_srl);
            }

            // module model class
            $oModuleModel = &getModel('module');

            // get module_info based on module_srl
            if($module_srl) {
                $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
                if(!$module_info) {
                    Context::set('module_srl','');
                    $this->act = 'list';
                } else {
                    ModuleModel::syncModuleToSite($module_info);
                    $this->module_info = $module_info;
                    Context::set('module_info',$module_info);
                }
            }

            if($module_info && $module_info->module != 'contact') return $this->stop("msg_invalid_request");

            // get module category
            $module_category = $oModuleModel->getModuleCategories();
            Context::set('module_category', $module_category);

            // set the module template path (modules/contact/tpl)
            $template_path = sprintf("%stpl/",$this->module_path);
            $this->setTemplatePath($template_path);

			$oSecurity = new Security();
			$oSecurity->encodeHTML('module_category..');
			$oSecurity->encodeHTML('module_info.');
        }
       
		// display contact module admin panel 
	    function dispContactAdminContent() {
			$args->sort_index = "module_srl";
            $args->page = Context::get('page');
            $args->list_count = 20;
            $args->page_count = 10;
            $args->s_module_category_srl = Context::get('module_category_srl');

			$s_mid = Context::get('s_mid');
			if($s_mid) $args->s_mid = $s_mid;

			$s_browser_title = Context::get('s_browser_title');
			if($s_browser_title) $args->s_browser_title = $s_browser_title;


            $output = executeQueryArray('contact.getContactList', $args);
            ModuleModel::syncModuleToSite($output->data);


            // setup module variables, context::set
            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('contact_list', $output->data);
            Context::set('page_navigation', $output->page_navigation);

			$oSecurity = new Security();
			$oSecurity->encodeHTML('contact_list..');

            // set template file
            $this->setTemplateFile('index');
		}

		function dispContactAdminContactInfo() {
            $this->dispContactAdminInsertContact();
        }

		 /**
         * @brief display insert contact admin page
         **/
        function dispContactAdminInsertContact() {
			if(!in_array($this->module_info->module, array('admin','contact','blog','guestbook'))) {
                return $this->alertMessage('msg_invalid_request');
            }

			//get skin list
			$oModuleModel = &getModel('module');
            $skin_list = $oModuleModel->getSkins($this->module_path);
            Context::set('skin_list',$skin_list);

			$mskin_list = $oModuleModel->getSkins($this->module_path, "m.skins");
			Context::set('mskin_list', $mskin_list);

			//get layout list
            $oLayoutModel = &getModel('layout');
            $layout_list = $oLayoutModel->getLayoutList();
            Context::set('layout_list', $layout_list);

			$mobile_layout_list = $oLayoutModel->getLayoutList(0,"M");
			Context::set('mlayout_list', $mobile_layout_list);

			$this->setTemplateFile('contact_insert');

			$oSecurity = new Security();
			$oSecurity->encodeHTML('skin_list..', 'mskin_list..');
			$oSecurity->encodeHTML('layout_list..', 'mlayout_list..');
			$oSecurity->encodeHTML('module_info.');
 
        }

        /**
         * @brief display Form Components admin page
         **/
        function dispContactAdminFormComps() {
            $oDocumentAdminModel = &getModel('document');
            $extra_vars_content = $oDocumentAdminModel->getExtraVarsHTML($this->module_info->module_srl);
            Context::set('extra_vars_content', $extra_vars_content);

            $this->setTemplateFile('form_comps');
        }

        /**
        * @brief display contact AdditionSetup admin page
		**/
        function dispContactAdminContactAdditionSetup() {

			$content = '';
            $oModuleModel = &getModel('module');
            $triggers = $oModuleModel->getTriggers('module.dispAdditionSetup', 'before');

			foreach($triggers as $item) {
                $module = $item->module;
                $type = $item->type;
                $called_method = $item->called_method;
				if($module == 'editor'){ //only display edtior
					$oModule = null;
					$oModule = &getModule($module, $type);
					if(!$oModule || !method_exists($oModule, $called_method)) continue;

					$output = $oModule->{$called_method}($content);
					if(is_object($output) && method_exists($output, 'toBool') && !$output->toBool()) return $output;
					unset($oModule);
				}

            }

            Context::set('setup_content', $content);
			$this->setTemplateFile('addition_setup');

		}

        /**
        * @brief display contact Agreement Term admin page
		**/
        function dispContactAdminContactAgreement() {

			// only admin user can write contact term
			if(!Context::get('is_logged'))  return $this->setTemplateFile('input_password_form');
            $logged_info = Context::get('logged_info');
            if($logged_info->is_admin != 'Y') return $this->setTemplateFile('input_password_form');

			$oContactModel = &getModel('contact');
			$editor_content = $oContactModel->getEditor($this->module_info->module_srl);
			Context::set('editor_content', $editor_content);

            /** 
             * add javascript filter file insert_question
             **/
			$termText = $this->module_info->content;
			Context::set('termText', $termText);

            Context::addJsFilter($this->module_path.'tpl/filter', 'insert_term.xml');

			$this->setTemplateFile('agreement_term');
		}

        /**
         * @brief display delete contact module page
         **/
        function dispContactAdminDeleteContact() {
            if(!Context::get('module_srl')) return $this->dispContactAdminContent();
            if(!in_array($this->module_info->module, array('admin', 'contact'))) {
                return $this->alertMessage('msg_invalid_request');
            }

            $module_info = Context::get('module_info');

            $oContactModel = &getModel('contact');
            $components_count = $oContactModel->getFormCompsCount($module_info->module_srl);
			$module_info->components_count = $components_count;

            Context::set('module_info',$module_info);

			$oSecurity = new Security();
			$oSecurity->encodeHTML('module_info.');

            // set template file
            $this->setTemplateFile('contact_delete');
        }

    }

?>
