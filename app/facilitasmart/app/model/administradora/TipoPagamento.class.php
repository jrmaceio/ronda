<?php
/**
 * TipoPagamento Active Record
 * @author  <your-name-here>
 */
class TipoPagamento extends TRecord
{
    const TABLENAME = 'tipo_pagamento';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
    }


}
