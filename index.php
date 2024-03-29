    <a href="?page=ST"><img src="">ST СТАТИСТИКА</a></li>
    <a href="?page=FHT"><img src="">FHT СТАТИСТИКА</a></li>
<?php
//Обработка ссылок
if (isset($_GET['page']))
{   //Обработка ссылок
    switch($_GET['page']) 
    {
    case 'ST': $server = 'ST'; //Переменная для директории
    break;

    case 'FHT': $server = 'FHT'; //Переменная для директории
    break;
    }
        

    include 'params.php';

    $myfileslist = scandir($statsdir); //Сканируем директорию и записываем в массив
    
    foreach ($myfileslist as $myfilename)//Проверяем каждый файл из массива
        if (strpos($myfilename, '.json') !== false)   //Проверяем заканчивается ли файл на .json 
            $uuidlist[] = substr($myfilename, 0, -5); //Создаём массив со всеми UUID

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
                    if ( isset($jsondata->{$key}) )
                        $playerdata[$value] = $jsondata->{$key}; //Выписываем нужную статистику с названием $key (stat.***)
                    else $playerdata[$value] = 0;
                else
                    $playerdata[$value] = $namedata->{$uuid} ; //Если это uuid то запишем ник а не статистику
            }
            
            if ($playerdata{'Количество смертей'}) //Проверяем умирал ли игрок
                $points = round( ( $playerdata{'Игровое время'} + $playerdata{'Время последней смерти'} ) / $playerdata{'Количество смертей'} ) ;
            else $points = $playerdata{'Игровое время'} + $playerdata{'Время последней смерти'} ;
            $playerdata['Мастерство'] = $points; //Добавляем очки

            $stats[]=$playerdata ; //Добавим игрока в массив всей статистики
        }
    }

    usort($stats, create_function('$a, $b', "return \$a['$sort'] < \$b['$sort'];")); // Сортировка будущей таблицы по нужному столбцу

    //Вывод таблицы
    echo 
        '<table>
            <tr>';
    
    foreach($params  as  $key => $value) // По нужным параметрам создаются стобцы таблицы
        echo "<td> $value </td>"; 

    echo '</tr>';

    foreach($stats as $key => $Player) //Перебираем каждого игрока и создаём строку
    {
        echo '<tr>';
        foreach($Player  as  $secondkey => $value) //Перебираем каждую статистику игрока
        {
            echo "<td> $value </td>"; //Выводим её в ячейку таблицы
        }
        echo '</tr>';
    }

    echo '</table>';
}
 ?>
