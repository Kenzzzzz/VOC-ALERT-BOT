<?php
    require('../libs/database/connect-db.php');
    require('../libs/utils/auc-funtions.php');

    $target_path = uploadXLSXFile($conn, $_FILES['aucfile']);
    $namedDataArray = getDataFromXLSXPath($target_path);
    clearAUCData($conn);
    insertAUCData($conn, $namedDataArray);