<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;

use Cake\Event\EventInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class StocksTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
    }

    


    public function validationOnlyCheck(Validator $validator) {
        // adding model validation for fields
        $validator
            ->requirePresence("symbol")
            ->notEmptyString("symbol", "Symbol is required")
            ->add("symbol", ['unique' => ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Symbol is already in use']])

            ->requirePresence("slug")
            ->notEmptyString("slug", "URL is required")
            ->add("slug", ['unique' => ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'URL is already in use']]);
        

        return $validator;
    }

}
