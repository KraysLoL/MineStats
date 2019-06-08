<?php  
    
    $params = array ( // Параметры.    В будущем как $key => $value
        '$uuid' => 'Игрок',
        'stat.leaveGame' => 'Входов в игру',
        'stat.timeSinceDeath' => 'Время последней смерти',
        'stat.playOneMinute' => 'Игровое время',
        'stat.walkOneCm' => 'Пройдено пешком'
    );
    
    $statsdir = './stats'; //Директория со статой

    $myfileslist = scandir($statsdir); //Сканируем директорию и записываем в массив
    
    foreach ($myfileslist as $myfilename)//Проверяем каждый файл из массива
        if (strpos($myfilename, '.json') !== false)   //Проверяем заканчивается ли файл на .json 
            $uuidlist[] = substr($myfilename, 0, -5); //Создаём массив со всеми UUID

    $namefile = ('usernamecache.json'); //Файл с никами и UUID
    $namestr = file_get_contents($namefile); //Получаем строку из файла
    $namedata = json_decode($namestr); //Декодируем json строку

    foreach ($uuidlist as $uuid)//Из массива UUID выбираем по одному ID
    {
        $jsonfile = $statsdir.'/'.$uuid.'.json'; //Полный путь к файлу
        if (file_exists($jsonfile)) //Если файл с таким названием существует то..
        {
            $jsonstr = file_get_contents($jsonfile); //Получаем строку из файла
            $jsondata = json_decode($jsonstr); //Декодируем json строку

            foreach($params  as  $key => $value) //Используя заданные параметры достаём нужную статистику об игроке
            {
                if ($key !== '$uuid') //Если параметр это НЕ uuid 
                    $playerdata[''.$value] = $jsondata->{''.$key}; //Выписываем нужную статистику с названием $key (stat.***)
                else
                    $playerdata[''.$value] = $namedata->{$uuid} ; //Если это uuid то запишем ник а не статистику
            }

            $stats[]=$playerdata ; //Добавим игрока в массив всей статистики
        }
    }

    usort($stats, create_function('$a, $b', "return \$a['Входов в игру'] > \$b['Входов в игру'];")); // Сортировка будущей таблицы по нужному столбцу

    //Вывод таблицы
    echo 
        '<caption>Статистика сервера</caption>
        
        <table>
            <tr>';
    
    foreach($params  as  $key => $value) // По нужным параметрам создаются стобцы таблицы
        echo "<td> $value </td>"; 

    echo '</tr>';

    foreach($stats as $key => $Player) //Перебираем каждого игрока и создаём строку
    {
        echo "<tr>";
        foreach($Player  as  $secondkey => $value) //Перебираем каждую статистику игрока
        {
            echo "<td> $value </td>"; //Выводим её в ячейку таблицы
        }
        echo "</tr>";
    }

    echo '</table>';
 ?>
