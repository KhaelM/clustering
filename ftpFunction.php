<?php
function ftp_sync ($dir, $ftpConnection) {  

    if ($dir != ".") { 
        if (ftp_chdir($ftpConnection, $dir) == false) { 
            echo ("Change Dir Failed: $dir<BR>\r\n"); 
            return; 
        } 
        if (!(is_dir($dir))) 
            mkdir($dir); 
        chdir ($dir); 
    } 

    $contents = ftp_nlist($ftpConnection, "."); 
    foreach ($contents as $file) { 

        if ($file == '.' || $file == '..') 
            continue; 

        if (@ftp_chdir($ftpConnection, $file)) { 
            ftp_chdir ($ftpConnection, ".."); 
            ftp_sync ($file, $ftpConnection); 
        } 
        else 
            ftp_get($ftpConnection, $file, $file, FTP_BINARY); 
    } 

    ftp_chdir ($ftpConnection, ".."); 
    chdir (".."); 
} 