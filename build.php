<?php

/*
 * The purpose of this script is to generate the #windwaker table.
 */

require_once '../web-table/Source/Web/Table/Autoloader.php';
require_once 'islands.php';

$table = new \Web\Table\Table('windwaker', 'Light', true);
$table->alias = 'The Legend of Zelda: The Wind Waker';

// Group Names
$groups = array
(
    'Fishman'          => 'Fishman',
    'Heart Containers' => 'Heart Container',
    'Pieces of Heart'  => 'Piece of Heart',
    'Sunken Treasures' => 'Sunken Treasure',
    'Wind Waker Songs' => 'Wind Waker Song',
    'Required Items'   => 'Required Item',
    'Optional Items'   => 'Optional Item',
    'Triforce Charts'  => 'Triforce Chart',
    'Treasure Charts'  => 'Treasure Chart',
    'Other Charts'     => 'Other Chart',
    'Big Octos'        => 'Big Octo',
    'Blue ChuChus'     => 'Blue ChuChu',
    'Great Fairies'    => 'Great Fairy',
    'Mini-Games'       => 'Mini-Game',
    'Pirate Platforms' => 'Pirate Platform',
    'Secret Caves'     => 'Secret Cave',
    'Side Quests'      => 'Side Quest',
    'Submarines'       => 'Submarine',
    'Tingle Quests'    => 'Tingle Quest',
    'Other Secrets'    => 'Other Secret',
    'Quest Log'        => 'Quest Log',
    'Information'      => 'Information',
);

// Initialize Counters
$totals = array();
foreach (array_keys($groups) as $key)
    $totals[$key] = 0;

foreach ($islands as $ri => $r)
{
    $row = $table->appendRow('(' . ($ri + 1) . ')');
    foreach ($r as $ci => $c)
    {
        $cell = $row->appendCell($ci);

        $items = array();

        $deltas = array();
        foreach (array_keys($groups) as $key)
            $deltas[$key] = 0;

        foreach ($c as $gi => $g)
        {
            if (empty($g))
                continue;

            $group = $cell->appendGroup($gi);

            foreach ($g as $ii => $i)
            {
                $totals[$gi]++;
                $deltas[$gi]++;

                switch ($gi)
                {
                    case 'Fishman':
                        $items[] = $group->appendItem('', $i, false);
                        break;
                    case 'Heart Containers':
                        $items[] = $group->appendItem("$groups[$gi] #$totals[$gi]", $i, true);
                        break;
                    case 'Pieces of Heart':
                        $items[] = $group->appendItem("$groups[$gi] #$totals[$gi]", $i, true);
                        break;
                    case 'Sunken Treasures':
                        $items[] = $group->appendItem("$groups[$gi] #$totals[$gi] ($i[0])", $i[1], true);
                        break;
                    case 'Wind Waker Songs':
                        $items[] = $group->appendItem($i[0], isset($i[1]) ? $i[1] : '', true);
                        break;
                    case 'Required Items':
                        $items[] = $group->appendItem($i[0], $i[1], true);
                        break;
                    case 'Optional Items':
                        $items[] = $group->appendItem($i[0], $i[1], true);
                        break;
                    case 'Triforce Charts':
                        $items[] = $group->appendItem($i[0], $i[1], true);
                        break;
                    case 'Treasure Charts':
                        $items[] = $group->appendItem($i[0], $i[1], true);
                        break;
                    case 'Other Charts':
                        $items[] = $group->appendItem($i[0], $i[1], true);
                        break;
                    case 'Big Octos':
                        $items[] = $group->appendItem("$groups[$gi] #$totals[$gi] ($i[0] Eyes)", $i[1], true);
                        break;
                    case 'Blue ChuChus':
                        $items[] = $group->appendItem("$groups[$gi] #$totals[$gi]", $i, true);
                        break;
                    case 'Great Fairies':
                        $items[] = $group->appendItem("$groups[$gi] #$totals[$gi]", $i, true);
                        break;
                    case 'Mini-Games':
                        $items[] = $group->appendItem($i[0], $i[1], true);
                        break;
                    case 'Pirate Platforms':
                        $items[] = $group->appendItem("$groups[$gi] #$totals[$gi]", $i, true);
                        break;
                   case 'Secret Caves':
                       $items[] = $group->appendItem("$groups[$gi] #$totals[$gi]", $i, true);
                       break;
                   case 'Side Quests':
                       $items[] = $group->appendItem($i[0], $i[1], true);
                       break;
                   case 'Submarines':
                       $items[] = $group->appendItem("$groups[$gi] #$totals[$gi]", $i, true);
                       break;
                   case 'Tingle Quests':
                       $items[] = $group->appendItem($i[0], $i[1], true);
                       break;
                   case 'Other Secrets':
                       $items[] = $group->appendItem($i[0], $i[1], true);
                       break;
                   case 'Quest Log':
                       $items[] = $group->appendItem($i[0], $i[1], true);
                       break;
                   case 'Information':
                       $items[] = $group->appendItem('', $i, false);
                       break;
                }
            }
        }

        // Relative Numbering
        foreach ($items as $item)
        {
            preg_match_all('((' . implode('|', $groups) . ') ##([0-9]+))', $item->text, $matches);

            for ($i = 0; $i < count($matches[0]); $i++)
            {
                $group = array_search($matches[1][$i], $groups);
                $index = $totals[$group] - ($deltas[$group] - 1) + ($matches[2][$i] - 1);

                $item->text = str_replace($matches[0][$i], $matches[1][$i] . ' #' . $index, $item->text);
            }
        }
    }
}

$row = $table->appendRow('Nintendo Gallery');
$special = array('!', '~', '*', '+');

foreach ($gallery as $ci => $c)
{
    $cell = $row->appendCell($ci . ' (x' . count($c) . ')');
    $group = $cell->appendGroup('');

    foreach ($c as $di => $d)
    {
        $name = ('#' . ($di + 1)) . ' ' . array_shift($d);
        $note = '';


        if (strlen($d[0]) && $d[0][0] === '!')
            $name = "<span style=\"color:red;\">$name</span>";

        foreach ($d as $ei => &$e)
            if (strlen($e) && in_array($e[0], $special))
                $e = "$e";
            else
                $e = "<span style=\"font-size:smaller;\">$e</span>";

        $group->appendItem($name, '<br />' . join('<br />', $d), true);
    }

    /*
    for ($i = 0; $i < count($c); $i++)
    {
        $text = '';
        foreach ($c[$i] as $j => $k)
            if ($j > 0)
                if (strlen($k) && in_array($k[0], array('!', '~', '*')))
                    $text .= '<b>' . $k . '</b>';
                else
                    $text .= '<br /><span style="font-size: smaller;">' . $k . '</span>';

        $group->appendItem('#' . ($i+1) . ' ' . $c[$i][0], $text, true);
    }
    */
}

echo $table->save('.') . NL;

?>