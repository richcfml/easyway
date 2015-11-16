<?php
listFolderFiles("logs");

function listFolderFiles($pRootPath)
{
    $mIgnored = array('.', '..', '.svn', '.htaccess', '.gitignore', '.git');
    $ffs = scandir($pRootPath);
    foreach($ffs as $ff)
    {
        if (!in_array($ff, $mIgnored))
        {
            if(is_dir($pRootPath.'/'.$ff)) 
            {
                listFolderFiles($pRootPath.'/'.$ff);
            }
            else
            {
                $mNow   = time();
                $mFileTime = filemtime($pRootPath."/".$ff);
                if ($mNow - $mFileTime >= 60 * 60 * 24 * 30)
                {
                     unlink($pRootPath."/".$ff);
                }
            }
        }
    }
}
?>