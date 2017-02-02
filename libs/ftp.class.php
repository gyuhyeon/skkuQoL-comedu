<?php
    /*********************************************************************
     *
     *    PHP FTP Client Class By TOMO ( groove@spencernetwork.org )
	 *    Modified By NAVER ( developers@xpressengine.com )
     *
     *  - Version 0.13 (2010/09/29)
	 *
     *  - This script is free but without any warranty.
     *  - You can freely copy, use, modify or redistribute this script
     *    for any purpose.
     *  - But please do not erase this information!!.
	 *
	 *  Change log
	 *  - Version 0.13 (2010/09/29)
	 *    . use preg functions instead of ereg functions
	 *  - Version 0.12 (2002/01/11)
     *
     ********************************************************************/

    /*********************************************************************
     * List of available functions
     * ftp_connect($server, $port = 21)
     * ftp_login($user, $pass)
     * ftp_pwd()
     * ftp_size($pathname)
     * ftp_mdtm($pathname)
     * ftp_systype()
     * ftp_cdup()
     * ftp_chdir($pathname)
     * ftp_delete($pathname)
     * ftp_rmdir($pathname)
     * ftp_mkdir($pathname)
     * ftp_file_exists($pathname)
     * ftp_rename($from, $to)
     * ftp_nlist($arg = "", $pathname = "")
     * ftp_rawlist($pathname = "")
     * ftp_get($localfile, $remotefile, $mode = 1)
     * ftp_put($remotefile, $localfile, $mode = 1)
     * ftp_site($command)
     * ftp_quit()
     *********************************************************************/

    class ftp
    {
        /* Public variables */
        var $debug;
        var $umask;
        var $timeout;

        /* Private variables */
        var $ftp_sock;
        var $ftp_resp;

        /* Constractor */
        function ftp()
        {
            $this->debug = false;
            $this->umask = 0022;
            $this->timeout = 30;

            if (!defined("FTP_BINARY")) {
                define("FTP_BINARY", 1);
            }
            if (!defined("FTP_ASCII")) {
                define("FTP_ASCII", 0);
            }

            $this->ftp_resp = "";
        }

        /* Public functions */
        function ftp_connect($server, $port = 21)
        {
            $this->ftp_debug("Trying to ".$server.":".$port." ...\n");
            $this->ftp_sock = @fsockopen($server, $port, $errno, $errstr, $this->timeout);

            if (!$this->ftp_sock || !$this->ftp_ok()) {
                $this->ftp_debug("Error : Cannot connect to remote host \"".$server.":".$port."\"\n");
                $this->ftp_debug("Error : fsockopen() ".$errstr." (".$errno.")\n");
                return FALSE;
            }

			if(substr($this->ftp_resp, 0, 3) !== '220')
			{
				return FALSE;
			}

            $this->ftp_debug("Connected to remote host \"".$server.":".$port."\"\n");

            return TRUE;
        }

        function ftp_login($user, $pass)
        {
            $this->ftp_putcmd("USER", $user);
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : USER command failed\n");
                return FALSE;
            }

            $this->ftp_putcmd("PASS", $pass);
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : PASS command failed\n");
                return FALSE;
            }
            $this->ftp_debug("Authentication succeeded\n");

            return TRUE;
        }

        function ftp_pwd()
        {
            $this->ftp_putcmd("PWD");
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : PWD command failed\n");
                return FALSE;
            }

            return preg_replace("@^[0-9]{3} \"(.+)\" .+\r\n@", "\\1", $this->ftp_resp);
        }

        function ftp_size($pathname)
        {
            $this->ftp_putcmd("SIZE", $pathname);
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : SIZE command failed\n");
                return -1;
            }

            return preg_replace("@^[0-9]{3} ([0-9]+)\r\n@", "\\1", $this->ftp_resp);
        }

        function ftp_mdtm($pathname)
        {
            $this->ftp_putcmd("MDTM", $pathname);
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : MDTM command failed\n");
                return -1;
            }
            $mdtm = preg_replace("@^[0-9]{3} ([0-9]+)\r\n@", "\\1", $this->ftp_resp);
            $date = sscanf($mdtm, "%4d%2d%2d%2d%2d%2d");
            $timestamp = mktime($date[3], $date[4], $date[5], $date[1], $date[2], $date[0]);

            return $timestamp;
        }

        function ftp_systype()
        {
            $this->ftp_putcmd("SYST");
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : SYST command failed\n");
                return FALSE;
            }
            $DATA = explode(" ", $this->ftp_resp);

            return $DATA[1];
        }

        function ftp_cdup()
        {
            $this->ftp_putcmd("CDUP");
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : CDUP command failed\n");
            }
            return $response;
        }

        function ftp_chdir($pathname)
        {
            $this->ftp_putcmd("CWD", $pathname);
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : CWD command failed\n");
            }
            return $response;
        }

        function ftp_delete($pathname)
        {
            $this->ftp_putcmd("DELE", $pathname);
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : DELE command failed\n");
            }
            return $response;
        }

        function ftp_rmdir($pathname)
        {
            $this->ftp_putcmd("RMD", $pathname);
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : RMD command failed\n");
            }
            return $response;
        }

        function ftp_mkdir($pathname)
        {
            $this->ftp_putcmd("MKD", $pathname);
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : MKD command failed\n");
            }
            return $response;
        }

        function ftp_file_exists($pathname)
        {
            if (!($remote_list = $this->ftp_nlist("-a"))) {
                $this->ftp_debug("Error : Cannot get remote file list\n");
                return -1;
            }
            
            reset($remote_list);
            while (list(,$value) = each($remote_list)) {
                if ($value == $pathname) {
                    $this->ftp_debug("Remote file ".$pathname." exists\n");
                    return 1;
                }
            }
            $this->ftp_debug("Remote file ".$pathname." does not exist\n");

            return 0;
        }

        function ftp_rename($from, $to)
        {
            $this->ftp_putcmd("RNFR", $from);
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : RNFR command failed\n");
                return FALSE;
            }
            $this->ftp_putcmd("RNTO", $to);

            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : RNTO command failed\n");
            }
            return $response;
        }

        function ftp_nlist($arg = "", $pathname = "")
        {
            if (!($string = $this->ftp_pasv())) {
                return FALSE;
            }

            if ($arg == "") {
                $nlst = "NLST";
            } else {
                $nlst = "NLST ".$arg;
            }
            $this->ftp_putcmd($nlst, $pathname);

            $sock_data = $this->ftp_open_data_connection($string);
            if (!$sock_data || !$this->ftp_ok()) {
                $this->ftp_debug("Error : Cannot connect to remote host\n");
                $this->ftp_debug("Error : NLST command failed\n");
                return FALSE;
            }
            $this->ftp_debug("Connected to remote host\n");

			$list = array();
            while (!feof($sock_data)) {
                $list[] = preg_replace("@[\r\n]@", "", fgets($sock_data, 512));
            }
            $this->ftp_close_data_connection($sock_data);
            $this->ftp_debug(implode("\n", $list));

            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : NLST command failed\n");
                return FALSE;
            }

            return $list;
        }

        function ftp_rawlist($pathname = "")
        {
            if (!($string = $this->ftp_pasv())) {
                return FALSE;
            }

            $this->ftp_putcmd("LIST", $pathname);

            $sock_data = $this->ftp_open_data_connection($string);
            if (!$sock_data || !$this->ftp_ok()) {
                $this->ftp_debug("Error : Cannot connect to remote host\n");
                $this->ftp_debug("Error : LIST command failed\n");
                return FALSE;
            }

            $this->ftp_debug("Connected to remote host\n");

            while (!feof($sock_data)) {
                $list[] = preg_replace("@[\r\n]@", "", fgets($sock_data, 512));
            }
            $this->ftp_debug(implode("\n", $list));
            $this->ftp_close_data_connection($sock_data);

            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : LIST command failed\n");
                return FALSE;
            }

            return $list;
        }

        function ftp_get($localfile, $remotefile, $mode = 1)
        {
            umask($this->umask);

            if (@file_exists($localfile)) {
                $this->ftp_debug("Warning : local file will be overwritten\n");
            }

            $fp = @fopen($localfile, "w");
            if (!$fp) {
                $this->ftp_debug("Error : Cannot create \"".$localfile."\"");
                $this->ftp_debug("Error : GET command failed\n");
                return FALSE;
            }

            if (!$this->ftp_type($mode)) {
                $this->ftp_debug("Error : GET command failed\n");
                return FALSE;
            }

            if (!($string = $this->ftp_pasv())) {
                $this->ftp_debug("Error : GET command failed\n");
                return FALSE;
            }

            $this->ftp_putcmd("RETR", $remotefile);

            $sock_data = $this->ftp_open_data_connection($string);
            if (!$sock_data || !$this->ftp_ok()) {
                $this->ftp_debug("Error : Cannot connect to remote host\n");
                $this->ftp_debug("Error : GET command failed\n");
                return FALSE;
            }
            $this->ftp_debug("Connected to remote host\n");
            $this->ftp_debug("Retrieving remote file \"".$remotefile."\" to local file \"".$localfile."\"\n");
            while (!feof($sock_data)) {
                fputs($fp, fread($sock_data, 4096));
            }
            fclose($fp);

            $this->ftp_close_data_connection($sock_data);

            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : GET command failed\n");
            }
            return $response;
        }

        function ftp_put($remotefile, $localfile, $mode = 1)
        {
            
            if (!@file_exists($localfile)) {
                $this->ftp_debug("Error : No such file or directory \"".$localfile."\"\n");
                $this->ftp_debug("Error : PUT command failed\n");
                return FALSE;
            }

            $fp = @fopen($localfile, "r");
            if (!$fp) {
                $this->ftp_debug("Error : Cannot read file \"".$localfile."\"\n");
                $this->ftp_debug("Error : PUT command failed\n");
                return FALSE;
            }

            if (!$this->ftp_type($mode)) {
                $this->ftp_debug("Error : PUT command failed\n");
                return FALSE;
            }

            if (!($string = $this->ftp_pasv())) {
                $this->ftp_debug("Error : PUT command failed\n");
                return FALSE;
            }

            $this->ftp_putcmd("STOR", $remotefile);

            $sock_data = $this->ftp_open_data_connection($string);
            if (!$sock_data || !$this->ftp_ok()) {
                $this->ftp_debug("Error : Cannot connect to remote host\n");
                $this->ftp_debug("Error : PUT command failed\n");
                return FALSE;
            }
            $this->ftp_debug("Connected to remote host\n");
            $this->ftp_debug("Storing local file \"".$localfile."\" to remote file \"".$remotefile."\"\n");
            while (!feof($fp)) {
                fputs($sock_data, fread($fp, 4096));
            }
            fclose($fp);

            $this->ftp_close_data_connection($sock_data);

            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : PUT command failed\n");
            }
            return $response;
        }

        function ftp_site($command)
        {
            $this->ftp_putcmd("SITE", $command);
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : SITE command failed\n");
            }
            return $response;
        }

        function ftp_quit()
        {
            $this->ftp_putcmd("QUIT");
            if (!$this->ftp_ok() || !fclose($this->ftp_sock)) {
                $this->ftp_debug("Error : QUIT command failed\n");
                return FALSE;
            }
            $this->ftp_debug("Disconnected from remote host\n");
            return TRUE;
        }

        /* Private Functions */

        function ftp_type($mode)
        {
            if ($mode) {
                $type = "I"; //Binary mode
            } else {
                $type = "A"; //ASCII mode
            }
            $this->ftp_putcmd("TYPE", $type);
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : TYPE command failed\n");
            }
            return $response;
        }

        function ftp_port($ip_port)
        {
            $this->ftp_putcmd("PORT", $ip_port);
            $response = $this->ftp_ok();
            if (!$response) {
                $this->ftp_debug("Error : PORT command failed\n");
            }
            return $response;
        }

        function ftp_pasv()
        {
            $this->ftp_putcmd("PASV");
            if (!$this->ftp_ok()) {
                $this->ftp_debug("Error : PASV command failed\n");
                return FALSE;
            }

            $ip_port = preg_replace("@^.+ \\(?([0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]+,[0-9]+)\\)?.*\r\n$@", "\\1", $this->ftp_resp);
            return $ip_port;
        }

        function ftp_putcmd($cmd, $arg = "")
        {
            if ($arg != "") {
                $cmd = $cmd." ".$arg;
            }

            fputs($this->ftp_sock, $cmd."\r\n");
            $this->ftp_debug("> ".$cmd."\n");

            return TRUE;
        }

        function ftp_ok()
        {
            $this->ftp_resp = "";

			// 한줄을 읽는다.
			$line = '';
			while(($char = fgetc($this->ftp_sock)) !== FALSE)
			{
				$line .= $char;
				if($char === "\n") break;
			}
				
			// 세자리 응답 코드가 나와야 한다.
			if(!preg_match('@^[0-9]{3}@', $line))
			{
				return FALSE;
			}

			$this->ftp_resp = $line;

			// 4번째 문자가 -이면 여러줄인 응답이다.
			if($line[3] === '-')
			{
				$code = substr($line, 0, 3);

				// 한줄 단위로 읽어 나간다.
				do
				{
					$line = '';
					while(($char = fgetc($this->ftp_sock)) !== FALSE)
					{
						$line .= $char;
						if($char === "\n") break;
					}
					$this->ftp_resp .= $line;

					// 응답 코드와 같은 코드가 나오고 공백이 있으면 끝
					if($code . ' ' === substr($line, 0, 4)) break;
				}while($line);
			}

            $this->ftp_debug(str_replace("\r\n", "\n", $this->ftp_resp));

            if (!preg_match("@^[123]@", $this->ftp_resp)) {
                return FALSE;
            }

            return TRUE;
        }

        function ftp_close_data_connection($sock)
        {
            $this->ftp_debug("Disconnected from remote host\n");
            return fclose($sock);
        }

        function ftp_open_data_connection($ip_port)
        {
            if (!preg_match("@[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]+,[0-9]+@", $ip_port)) {
                $this->ftp_debug("Error : Illegal ip-port format(".$ip_port.")\n");
                return FALSE;
            }

            $DATA = explode(",", $ip_port);
            $ipaddr = $DATA[0].".".$DATA[1].".".$DATA[2].".".$DATA[3];
            $port   = $DATA[4]*256 + $DATA[5];
            $this->ftp_debug("Trying to ".$ipaddr.":".$port." ...\n");
            $data_connection = @fsockopen($ipaddr, $port, $errno, $errstr);
            if (!$data_connection) {
                $this->ftp_debug("Error : Cannot open data connection to ".$ipaddr.":".$port."\n");
                $this->ftp_debug("Error : ".$errstr." (".$errno.")\n");
                return FALSE;
            }

            return $data_connection;
        }

        function ftp_debug($message = "")
        {
            if ($this->debug) {
                echo $message;
            }

            return TRUE;
        }
    }
?>
