<?php
    /**
     * @class  contactModel
     * @author NHN (developers@xpressengine.com)
     * @brief  contact module Model class
     **/

    class contactModel extends module {

		/**
		 * @brief initialization
		 **/
		function init() {
		}

		/**
         * @brief return get editor
         **/
        function getEditor($module_srl) {

            if(!$module_srl) $module_srl = Context::get('module_srl');

            $oEditorModel = &getModel('editor');

            return $oEditorModel->getModuleEditor('document', $module_srl, $module_srl, 'module_srl', 'term');
        }

		function getFormCompsCount($module_srl) {
			$oDocumentAdminModel = &getModel('document');
            $extra_keys = $oDocumentAdminModel->getExtraKeys($module_srl);

            return count($extra_keys);
        }

        /**
         * @brief check spam interval
         **/
        function checkLimited($interval) {
			if(!$interval) return new Object();

			$oSpamModel = &getModel('spamfilter');
			$count = $oSpamModel->getLogCount($interval);
			
            if($count) {
                $message = sprintf(Context::getLang('msg_alert_limited_by_config_mail'), $interval/60);
                $oSpamFilterController = &getController('spamfilter');
                $oSpamFilterController->insertLog();

                return new Object(-1, $message);
            }
			
			return new Object();
        }

		/**
		 * @brief return module name in sitemap
		 **/
		function triggerModuleListInSitemap(&$obj)
		{
			array_push($obj, 'contact');
		}
    }
?>
