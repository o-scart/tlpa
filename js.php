<?php
echo $file = file_get_contents("./js/libs/jquery-1.11.3.js");
recurseFolderScan("./js", 0);
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
			if($f != "jquery-1.11.3.js")
			{
				$file = file_get_contents($currentFolder."/".$f);
				echo $file;
			}
		}
        
    }
}

?>