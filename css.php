<?php
header('Content-Type: text/css');
recurseFolderScan("./css", 0);
function recurseFolderScan($currentFolder, $level)
{
    $result = scandir($currentFolder);
    foreach($result as $f)
    {
        if($f === "." or $f === "..")
        {
            continue;
        }
        

        if( is_dir($currentFolder."/".$f) )
        {
            recurseFolderScan($currentFolder."/".$f, $level+1);
        }
		else
		{
			$file = file_get_contents($currentFolder."/".$f);
			echo $file;
		}
        
    }
}

?>