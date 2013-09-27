<?php

/**
 * Part of Web Table (Windwaker)
 *
 * Read through a *.tbl file and preserve all checked/unchecked states. This
 * assumes that new fields are only ever appended and that names (i.e.
 * identifiers) remain constant.
 *
 * PHP 5.3
 *
 * @package   Console\Parser
 * @author    Matt Sirocki <mattsirocki@gmail.com>
 * @copyright 2012-2013, Matt Sirocki
 * @license   MIT License
 * @version   SVN: $Revision: 13 $
 * @since     0.1.0 Initial Release
 * @filesource
 */

$json_old = file_get_contents($argv[1]);
$json_new = file_get_contents($argv[2]);
$data_old = json_decode($json_old, true);
$data_new = json_decode($json_new, true);
$save = array();

foreach ($data_old['rows'] as $row)
{
    $ri = $row['index'];
    foreach ($row['cells'] as $cell)
    {
        $ci = $cell['index'];
        foreach ($cell['groups'] as $group)
        {
            $gi = $group['index'];
            foreach ($group['items'] as $item)
            {
                $ii = $item['index'];

                $save[$ri][$ci][$gi][$ii] = $item['completed'];
            }
        }
    }
}

foreach ($data_new['rows'] as &$row)
{
    $ri = $row['index'];
    foreach ($row['cells'] as &$cell)
    {
        $ci = $cell['index'];
        foreach ($cell['groups'] as &$group)
        {
            $gi = $group['index'];
            foreach ($group['items'] as &$item)
            {
                $ii = $item['index'];

                try
                {
                    if (@$save[$ri][$ci][$gi][$ii])
                        echo "Completing {$item['name']}\n";
                    @$item['completed'] = $save[$ri][$ci][$gi][$ii];
                }
                catch(Exception $e)
                {
                }
            }
        }
    }
}

@file_put_contents($argv[1], json_encode($data_new));
