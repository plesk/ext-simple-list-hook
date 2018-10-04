<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

class Modules_SimpleListHook_SimpleList extends pm_Hook_SimpleList
{
    public function isEnabled($controller, $action, $activeList)
    {
        // Modify only domains lists
        return Zend_Controller_Front::getInstance()->getRequest()->getModuleName() === 'admin'
            && ($controller === 'domain' && $action === 'list'
                || $controller === 'customer' && $action === 'domains'
                || $controller === 'reseller' && $action === 'domains');
    }

    public function getDataProvider($controller, $action, $activeList, $data)
    {
        // Hide all domains for client from "News Snow" company
        $data->where('c.cname <> ?', 'News Snow');

        return $data;
    }

    public function getData($controller, $action, $activeList, $data)
    {
        foreach ($data as &$row) {
            // Modify some data
            $row['diskUsage'] = size_mb_printing($row['realSize'] + 1048576, true);

            // Add some data for new column
            $row['extHookSimplelistRand'] = rand(0, 100);
        }

        return $data;
    }

    public function getColumns($controller, $action, $activeList)
    {
        return [
            // Add 'Random' column
            'extHookSimplelistRand' => [
                'title' => 'Random',
                'insertBefore' => -3,
            ]
        ];
    }

    public function getColumnsOverride($controller, $action, $activeList)
    {
        return [
            // Change 'Subscriber' column title to 'Owner'
            'ownerName' => [
                'title' => 'Owner',
            ],
            // Hide 'Setup Date' column
            'setupDate' => [
                'isVisible' => false,
            ],
        ];
    }
}
