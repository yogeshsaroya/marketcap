<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;

use Cake\Event\EventInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PortfoliosTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Stocks');
    }


}
