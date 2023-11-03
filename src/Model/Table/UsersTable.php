<?php
// src/Model/Table/UsersTable.php
namespace App\Model\Table;


use Cake\Event\EventInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;



class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        $this->addBehavior('Timestamp');
        //$this->belongsTo('Profiles');
    }

    public function validationDefault(Validator $validator): Validator
    {
        // adding model validation for fields
        $validator

            ->requirePresence("email")
            ->notEmptyString("email", "Email is required")
            ->add("email", [
                'unique' => ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Email address is already in use'],
                "valid_email" => ["rule" => ["email"], "message" => "Email Address is not valid"]
            ])

            ->requirePresence("username")
            ->notEmptyString("username", "Username is required")
            ->add("username", ['unique' => ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Username is already in use']])

            ->requirePresence("password")
            ->notEmptyString("password", "Password is required")
            ->minLength("password", 6, "Password must be 6-20 characters")
            ->maxLength("password", 20, "Password must be 6-20 characters");

        return $validator;
    }
}
