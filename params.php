<?php  
    
    $params = array ( // Параметры.    В будущем как $key => $value
        '$uuid' => 'Игрок',
        'stat.deaths' => 'Количество смертей',
        'stat.timeSinceDeath' => 'Время последней смерти',
        'stat.playOneMinute' => 'Игровое время',
        'eff' => 'Мастерство'
    );
    
    $namefile = ("$server/usernamecache.json"); //Файл с никами и UUID
    $statsdir = ("$server/stats"); //Директория со статой;

    $sort = 'Мастерство'; //Сортировка по..
?>
