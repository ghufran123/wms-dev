<?php namespace HerzGarlan\Jobs\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Report extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('HerzGarlan.Jobs', 'jobs', 'reports');
    }
}