<?php

/* ********************************************************************************

 * The content of this file is subject to the Global Search ("License");

 * You may not use this file except in compliance with the License

 * The Initial Developer of the Original Code is VTExperts.com

 * Portions created by VTExperts.com. are Copyright(C) VTExperts.com.

 * All Rights Reserved.

 * ****************************************************************************** */


 

class OS2UserSettings   {

	var $log;

	function __construct() {
	    
   }


    function vtlib_handler($modulename, $event_type) {
		 
		global $adb;
        if($event_type == 'module.postinstall') {  
			#Write js file in file 
			#check if not Write js file in file 
			$file = 'layouts/vlayout/modules/Vtiger/JSResources.tpl';
			$searchfor = 'OS2UserSettings';			
			header('Content-Type: text/plain'); 
			$contents = file_get_contents($file); 
			$pattern = preg_quote($searchfor, '/'); 
			$pattern = "/^.*$pattern.*$/m"; 
			if(preg_match_all($pattern, $contents, $matches)){			 
			}else{
				$old_c = file_get_contents($file, FILE_USE_INCLUDE_PATH);	
				$new_content = '
				<!------------------------------- OS2UserSettings -------------------->
				<script type="text/javascript" src="layouts/v7/modules/OS2UserSettings/resources/OS2UserSettings.js?z=v6.5"></script>
				<script type="text/javascript" src="layouts/v7/modules/OS2UserSettings/resources/Settings.js"></script>';			
				file_put_contents("layouts/v7/modules/Vtiger/JSResources.tpl",$old_c.' '.$new_content);
			}
			
			# add User settings link to settings table 
			$fieldid = $adb->getUniqueID('vtiger_settings_field');  
			$seq_res = $adb->query("SELECT max(sequence)+1 AS max_seq FROM vtiger_settings_field WHERE blockid =1");
			$seq = 1;
			if($adb->num_rows($seq_res) > 0){
                $cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
                if($cur_seq != null){
                    $seq = $cur_seq + 1;
                }
            }
            $usersettings_result = $adb->query("SELECT name FROM vtiger_settings_field where name = 'OS2 User Settings' ");
			$entryExists = $adb->num_rows($usersettings_result);
			if ( $entryExists == 0){
				$adb->pquery('INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned)
				VALUES (?,?,?,?,?,?,?,?,?)', array($fieldid, 1, 'OS2 User Settings', NULL, 'config User settings Configuration', 'index.php?module=OS2UserSettings&parent=Settings&view=ConfigUserSettingsEditorDetail', $seq, 0, 1));
            }

        } else if($event_type == 'module.disabled') {


        } else if($event_type == 'module.enabled') {


        } else if($event_type == 'module.preuninstall') {


        } else if($event_type == 'module.preupdate') {
			

        } else if($event_type == 'module.postupdate') {  

			#check if links are not in JSResources.js file then Write js file in JSResources.js file 
			$file = 'layouts/v7/modules/Vtiger/JSResources.tpl';
			$searchfor = 'OS2UserSettings';			
			header('Content-Type: text/plain'); 
			$contents = file_get_contents($file); 
			$pattern = preg_quote($searchfor, '/'); 
			$pattern = "/^.*$pattern.*$/m"; 
			if(preg_match_all($pattern, $contents, $matches)){			 
			}else{
				$old_c = file_get_contents($file, FILE_USE_INCLUDE_PATH);	
				$new_content = '
				<!------------------------------- OS2UserSettings -------------------->
				<script type="text/javascript" src="layouts/v7/modules/OS2UserSettings/resources/OS2UserSettings.js?z=v6.5"></script>
				<script type="text/javascript" src="layouts/v7/modules/OS2UserSettings/resources/Settings.js"></script>';			
				file_put_contents("layouts/v7/modules/Vtiger/JSResources.tpl",$old_c.' '.$new_content);
			} 
			
			# add User settings link to settings table 
			$fieldid = $adb->getUniqueID('vtiger_settings_field');  
			$seq_res = $adb->query("SELECT max(sequence)+1 AS max_seq FROM vtiger_settings_field WHERE blockid =1");
			$seq = 1;
			if($adb->num_rows($seq_res) > 0){
                $cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
                if($cur_seq != null){
                    $seq = $cur_seq + 1;
                }
            }
            $usersettings_result = $adb->query("SELECT name FROM vtiger_settings_field where name = 'OS2 User Settings' ");
			$entryExists = $adb->num_rows($usersettings_result);
			if ( $entryExists == 0){
				$adb->pquery('INSERT INTO vtiger_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned)
				VALUES (?,?,?,?,?,?,?,?,?)', array($fieldid, 1, 'OS2 User Settings', NULL, 'config User settings Configuration', 'index.php?module=OS2UserSettings&parent=Settings&view=ConfigUserSettingsEditorDetail', $seq, 0, 1));
            }

            
		}
		
		$adb->pquery("INSERT INTO `vtiger_user_config` (`id`, `failed_logins_criteria`, `max_login_attempts`, `UC_NAME_ONE`, `UC_EMAIL_ID_ONE`, `UC_NAME_TWO`, `UC_EMAIL_ID_TWO`, `Working_Hours_start`, `Working_Hours_end`, `weeks`, `holiday_lbl_val`, `status`) VALUES(1, 0, 5, 'Theracan 1', 'sri@theracanncorp.com', 'Theracan 2', 'sri@theracanncorp.com', '00:01', '23:59', '', '', 1);",array());
		
		
		$this->copy_r("modules/OS2UserSettings/system", ".");
		#$this->recursiveDelete("modules/OS2UserSettings/system");
	}
	
	function recursiveDelete($str){
		if(is_file($str)){
			return @unlink($str);
		}
		elseif(is_dir($str)){
			$scan = glob(rtrim($str,'/').'/*');
			foreach($scan as $index=>$path){
				$this->recursiveDelete($path);
			}
			return @rmdir($str);
		}
	}

	function copy_r( $path, $dest ) {
		
        if( is_dir($path) ) {
            @mkdir( $dest );
            $objects = scandir($path);
            if( sizeof($objects) > 0 ) {
                foreach( $objects as $file ) {
                    if( $file == "." || $file == ".." )
                        continue;
                    // go on
                    if( is_dir( $path.DIRECTORY_SEPARATOR.$file ) ) {
                        $this->copy_r( $path.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file );
                    }
                    else {
						// TODO Must save existing script copies for rollback
						$this->vtFileCopy($dest.DIRECTORY_SEPARATOR.$file);
                        $crslt = copy( $path.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file );
						if ($crslt) {
							//echo "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file."<br>";
							$this->copiedFiles[] = "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
						}
						else {
							$this->failedCopies[] = "Could not copy ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
						}
                    }
                }
            }
            return true;
        }
        elseif( is_file($path) ) {
			$crslt = copy($path, $dest);
			if ($crslt)
			{
				//echo "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file."<br>";
				$this->copiedFiles[] = "Copied ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
			}
			else {
				//echo "<font color=red>Could not copy ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file."</font><br>";
				$this->failedCopies[] = "Could not copy ".$path.DIRECTORY_SEPARATOR.$file." to ".$dest.DIRECTORY_SEPARATOR.$file;
			}
            return $crslt;
        }
        else {
            return false;
        }
	}
	function vtFileCopy( $source ) {
		if (is_file($source))
		{
			$data = file_get_contents($source);
			$targetfile = "modules/vtDZiner/yourcopies/".substr( $source , 2 );
			$targetpaths = explode (DIRECTORY_SEPARATOR, $targetfile);
			$targetfile = $targetpaths[count($targetpaths)-1];
			unset($targetpaths[count($targetpaths)-1]);
			$targetpath = implode(DIRECTORY_SEPARATOR, $targetpaths);
			if (!is_dir($targetpath))
			{
				if (mkdir($targetpath, 0777, true)){
					file_put_contents("modules/vtDZiner/yourcopies/".substr( $source , 2 ), $data);
					//echo "$source saved in modules/vtDZiner/yourcopies<br>";
					$this->savedFiles[] = "$source saved in modules/vtDZiner/yourcopies";
				}
				else {
					//echo "<br>Could not create $targetpath<br>";
					$this->failedDirectories[] = "Could not create $targetpath";
				}
			}
			else {
				file_put_contents("modules/vtDZiner/yourcopies/".substr( $source , 2 ), $data);
				//echo "Saved current copy of $source in modules/vtDZiner/yourcopies<br>";
				$this->savedFiles[] = "$source saved in modules/vtDZiner/yourcopies";
			}
		} else {
			//echo "&nbsp;&nbsp;&nbsp;"."<font color=red>Ignored file $source</font><br>";
			//TODO To check why the ignored file is needed
			//$this->ignoredFiles[] = "Ignored file for archiving $source";
		}
	}
  
}