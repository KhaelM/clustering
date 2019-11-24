<?php
setcookie( session_name(), "", time()-3600, '/');
session_destroy();
session_unset();
header("Location:index.php");