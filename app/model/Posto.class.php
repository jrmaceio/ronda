<?php
/**
 * Posto Active Record
 * @author  <your-name-here>
 */
class Posto extends TRecord
{
    const TABLENAME = 'posto';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('unidade_id');
    }
    
    public function set_unit(SystemUnit $object)
    {
        $this->unit = $object;
        $this->unidade_id = $object->id;
    }
    
    /**
     * Returns the unit
     */
    public function get_unit()
    {
        // loads the associated object
        if (empty($this->unit))
            $this->unit = new SystemUnit($this->unidade_id);
    
        // returns the associated object
        return $this->unit;
    }


}
