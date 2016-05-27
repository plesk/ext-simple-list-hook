<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

class Modules_HookSimplelist_SimpleList extends pm_Hook_SimpleList
{
    public function isEnabled($controller, $action, $activeList)
    {
        // Modify only domains lists 
        return $controller === 'domain' && $action === 'list'
            || $controller === 'customer' && $action === 'domains'
            || $controller === 'reseller' && $action === 'domains';
    }

    public function getDataProvider($controller, $action, $activeList, $data)
    {
        // Hide all domains for client with id 5
        $data->where('c.id <> ?', 5);

        return $data;
    }

    public function getData($controller, $action, $activeList, $data)
    {
        foreach ($data as &$row) {
            // Modify some data
            $row['realSize'] += 1048576;

            // Add spme data for new column
            $row['rand'] = rand(0, 100);
        }

        return $data;
    }


    public function getColumns($controller, $action, $activeList)
    {
        return [
            // Add 'Random' column
            'rand' => [
                'title' => 'Random',
                'order' => -3,
            ]
        ];
    }

    public function getColumnsOverride($controller, $action, $activeList)
    {
        if ($controller === 'customer' || $controller === 'reseller') {
            return [
                // Hide 'Setup Date' column
                3 => [
                    'isVisible' => false,
                ],
            ];
        }

        return [
            // Change 'Subscriber' column title to 'Owner'
            3 => [
                'title' => 'Owner',
            ],
            // Hide 'Setup Date' column
            4 => [
                'isVisible' => false,
            ],
        ];
    }
}
