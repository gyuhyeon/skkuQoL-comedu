<?php
    /**
     * @class  contact
     * @author NHN (developers@xpressengine.com)
     * @brief  contact module high class
     **/

    class contact extends ModuleObject {

        var $skin = "xe_contact_official"; ///< skin name

        /**
         * @brief module installation
         **/
        function moduleInstall() {
            // action forward get module controller and model
            $oModuleController = &getController('module');
            $oModuleModel = &getModel('module');

			$oModuleController->insertTrigger('member.getMemberMenu', 'contact', 'controller', 'triggerMemberMenu', 'after');
			$oModuleController->insertTrigger('menu.getModuleListInSitemap', 'contact', 'model', 'triggerModuleListInSitemap', 'after');

            return new Object();
        }

        /**
         * @brief check update method
         **/
        function checkUpdate() {
            $oModuleModel = &getModel('module');
			if(!$oModuleModel->getTrigger('member.getMemberMenu', 'contact', 'controller', 'triggerMemberMenu', 'after')) return true;

			// 2012. 09. 11 when add new menu in sitemap, custom menu add
			if(!$oModuleModel->getTrigger('menu.getModuleListInSitemap', 'contact', 'model', 'triggerModuleListInSitemap', 'after')) return true;

            return false;
        }

        /**
         * @brief update module
         **/
        function moduleUpdate() {
            $oModuleModel = &getModel('module');
            $oModuleController = &getController('module');

			if(!$oModuleModel->getTrigger('member.getMemberMenu', 'contact', 'controller', 'triggerMemberMenu', 'after'))
                $oModuleController->insertTrigger('member.getMemberMenu', 'contact', 'controller', 'triggerMemberMenu', 'after');

			// 2012. 09. 11 when add new menu in sitemap, custom menu add
			if(!$oModuleModel->getTrigger('menu.getModuleListInSitemap', 'contact', 'model', 'triggerModuleListInSitemap', 'after'))
				$oModuleController->insertTrigger('menu.getModuleListInSitemap', 'contact', 'model', 'triggerModuleListInSitemap', 'after');
            return new Object(0, 'success_updated');
        }

		function moduleUninstall() {
			$output = executeQueryArray("contact.getAllContact");
			if(!$output->data) return new Object();
			set_time_limit(0);
			$oModuleController =& getController('module');
			foreach($output->data as $faq)
			{
				$oModuleController->deleteModule($faq->module_srl);
			}
			return new Object();
		}

        /**
         * @brief create cache file
         **/
        function recompileCache() {
        }

    }
?>
