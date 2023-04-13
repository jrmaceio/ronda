<?php
/**
 * FinRetornoSegT Active Record
 * @author  <your-name-here>
 */
class FinRetornoSegT extends TRecord
{
    const TABLENAME = 'fin_retorno_seg_t';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $condominio;
    private $fin_retorno;
    private $contas_receber;
    private $tipo_movto_retorno;
    private $tipo_movto_retorno_item;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('id_condominio');
        parent::addAttribute('id_fin_retorno');
        parent::addAttribute('seguimento');
        parent::addAttribute('sequencia');
        parent::addAttribute('nosso_numero');
        parent::addAttribute('id_contas_receber');
        parent::addAttribute('docto');
        parent::addAttribute('id_movto_retorno');
        parent::addAttribute('id_movto_retorno_item');
        parent::addAttribute('vlr_titulo');
        parent::addAttribute('vlr_taxa');
        parent::addAttribute('forma');
        parent::addAttribute('id_fin_retornosegu');
        parent::addAttribute('dt_vencto');
        parent::addAttribute('bco_cred');
        parent::addAttribute('age_cred');
        parent::addAttribute('cli_pfj');
        parent::addAttribute('cli_cnpj_cpf');
        parent::addAttribute('cli_nome');
        parent::addAttribute('id_fin_titulo_liquida');
    }

    
    
    /**
     * Method set_fin_retorno
     * Sample of usage: $fin_retorno_seg_t->fin_retorno = $object;
     * @param $object Instance of FinRetorno
     */
    public function set_fin_retorno(FinRetorno $object)
    {
        $this->fin_retorno = $object;
        $this->id_fin_retorno = $object->id;
    }
    
    /**
     * Method get_fin_retorno
     * Sample of usage: $fin_retorno_seg_t->fin_retorno->attribute;
     * @returns FinRetorno instance
     */
    public function get_fin_retorno()
    {
        // loads the associated object
        if (empty($this->fin_retorno))
            $this->fin_retorno = new FinRetorno($this->id_fin_retorno);
    
        // returns the associated object
        return $this->fin_retorno;
    }
    
    
    /**
     * Method set_contas_receber
     * Sample of usage: $fin_retorno_seg_t->contas_receber = $object;
     * @param $object Instance of ContasReceber
     */
    public function set_contas_receber(ContasReceber $object)
    {
        $this->contas_receber = $object;
        $this->id_contas_receber = $object->id;
    }
    
    /**
     * Method get_contas_receber
     * Sample of usage: $fin_retorno_seg_t->contas_receber->attribute;
     * @returns ContasReceber instance
     */
    public function get_contas_receber()
    {
        // loads the associated object
        if (empty($this->contas_receber))
            $this->contas_receber = new ContasReceber($this->id_contas_receber);
    
        // returns the associated object
        return $this->contas_receber;
    }
    
    
    /**
     * Method set_tipo_movto_retorno
     * Sample of usage: $fin_retorno_seg_t->tipo_movto_retorno = $object;
     * @param $object Instance of TipoMovtoRetorno
     */
    public function set_tipo_movto_retorno(TipoMovtoRetorno $object)
    {
        $this->tipo_movto_retorno = $object;
        $this->id_movto_retorno = $object->id;
    }
    
    /**
     * Method get_tipo_movto_retorno
     * Sample of usage: $fin_retorno_seg_t->tipo_movto_retorno->attribute;
     * @returns TipoMovtoRetorno instance
     */
    public function get_tipo_movto_retorno()
    {
        // loads the associated object
        if (empty($this->tipo_movto_retorno))
            $this->tipo_movto_retorno = new TipoMovtoRetorno($this->id_movto_retorno);
    
        // returns the associated object
        return $this->tipo_movto_retorno;
    }
    
    
    /**
     * Method set_tipo_movto_retorno_item
     * Sample of usage: $fin_retorno_seg_t->tipo_movto_retorno_item = $object;
     * @param $object Instance of TipoMovtoRetornoItem
     */
    public function set_tipo_movto_retorno_item(TipoMovtoRetornoItem $object)
    {
        $this->tipo_movto_retorno_item = $object;
        $this->id_movto_retorno_item = $object->id;
    }
    
    /**
     * Method get_tipo_movto_retorno_item
     * Sample of usage: $fin_retorno_seg_t->tipo_movto_retorno_item->attribute;
     * @returns TipoMovtoRetornoItem instance
     */
    public function get_tipo_movto_retorno_item()
    {
        // loads the associated object
        if (empty($this->tipo_movto_retorno_item))
            $this->tipo_movto_retorno_item = new TipoMovtoRetornoItem($this->id_movto_retorno_item);
    
        // returns the associated object
        return $this->tipo_movto_retorno_item;
    }
    




}
