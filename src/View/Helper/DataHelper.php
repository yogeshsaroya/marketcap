<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class DataHelper extends Helper
{


    public function getCaps($country = null )
    {
        $tbl = TableRegistry::get('Stocks');
        if( !empty($country) ){
            try {
                $query = $tbl->find('all', ['conditions' => ['type' => 'stock', 'name !=' => '','country'=>$country]]);
                $number = $query->count();
    
                $total_quantity = $tbl->find();
                $count_quantity = $total_quantity->where(['type' => 'stock', 'name !=' => '','country'=>$country])->select(['sum' => $total_quantity->func()->sum('Stocks.market_cap')])->first();
                $sum_quantity = $count_quantity->sum;
                return ['companies'=>$number,'market_cap'=>nice_number($sum_quantity)];
            } catch (\Throwable $th) {
            }
        }else{
            try {
                $query = $tbl->find('all', ['conditions' => ['type' => 'stock', 'name !=' => '']]);
                $number = $query->count();
    
                $total_quantity = $tbl->find();
                $count_quantity = $total_quantity->select(['sum' => $total_quantity->func()->sum('Stocks.market_cap')])->first();
                $sum_quantity = $count_quantity->sum;
                return ['companies'=>$number,'market_cap'=>nice_number($sum_quantity)];
            } catch (\Throwable $th) {
            }
        }
        
    }

    public function getSettings()
    {
        $tbl = TableRegistry::get('Settings');
        return $tbl->findById('1')->firstOrFail();
    }
}
