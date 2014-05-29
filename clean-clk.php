<?php
// just for debugging
header('Content-type: text/plain');

/* recursive directory retrieve */
function getFileListIn($dir, $prefix = '') {
  $dir = rtrim($dir, '\\/');
  $result = array();

    foreach (scandir($dir) as $f) 
    {
      if ($f !== '.' and $f !== '..') 
      {
        if (is_dir("$dir/$f")) 
        {
          $result = array_merge($result, getFileListIn("$dir/$f", "$prefix$f/"));
        } else {
          $result[] = $prefix.$f;
        }
      }
    }

  return $result;
}

/* here starts the script */

// array for infected and cured files store
$arrInfected = $arrCured = array();

// configure the directory to check and cure
$strDirPathToCheck = './';

$arrFileList = getFileListIn($strDirPathToCheck);

/* while we have files to check... */
foreach($arrFileList as $strFile)
{
	$strPathFile = $strDirPathToCheck . $strFile;
	
	$strOriginalContent = file_get_contents($strPathFile);	

	// Let's check if the 'clk.php' call is inside the file 
	if (preg_match_all('/clk.php/sU', $strOriginalContent, $arrMatches))
	{
		// append infected item
		$arrInfected[] = $strPathFile;
		
		// getting extension
		$arrPathParts = pathinfo($strPathFile);
		
		// check the type file
		switch (strtolower($arrPathParts['extension']))
		{
			case 'php': // PHP files 		
				// #id# script call #/id#
				$strCuredContent = preg_replace('/<\?php[\n\r\s]+\#[a-z0-9]+\#.*\#\/[a-z0-9]+\#[\n\r\s]+\?>/sU', '', $strOriginalContent);				
				$strCuredContent = preg_replace('/<\?php[\n\r\s]+\?>/sU', '', $strCuredContent);				
				break;
			case 'js':	// JS files 		
				// /*id*/ script call /*id*/
				$strCuredContent = preg_replace('/\/\*[a-z0-9]+\*\/.*\/\*\/[a-z0-9]+\*\//sU', '', $strOriginalContent);
				break;				
			case 'htm': // HTML files 
			case 'html':
				// <!--id--> script call <!--/id-->
				$strCuredContent = preg_replace('/<!--[a-z0-9]+.*\/[a-z0-9]+-->/sU', '', $strOriginalContent);					
				break;				
			default:
				// possible others
				$strCuredContent = null;
		}
		
		// if we get the curated content, we'll replace original
		if (!is_null($strCuredContent))
		{
			// Write content to file
			if (file_put_contents($strPathFile, $strCuredContent))
			{
				// OK, perfect, cured
				$arrCured[] = $strPathFile;
			}
		}		
		
	}
}

echo "Files infected":
print_r($arrInfected);

echo "Files cured":
print_r($arrCured);
