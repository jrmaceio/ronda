<?php
/**
 * AcessoForm Form
 * @author  <your name here>
 */
class AcessoForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Acesso');
        $this->form->setFormTitle('Acesso');
        

        // create the form fields
        $id = new TEntry('id');
        $id_patrulheiro = new TDBUniqueSearch('id_patrulheiro', 'ronda', 'Patrulheiro', 'id', 'nome');
        $id_posto = new TDBUniqueSearch('id_posto', 'ronda', 'Posto', 'id', 'descricao');
        $id_visitante = new TDBUniqueSearch('id_visitante', 'ronda', 'Visitante', 'id', 'nome');
        $data_visita = new TDate('data_visita');
        $fluxo = new TEntry('fluxo');
        $veiculo = new TEntry('veiculo');
        $observacao = new TText('observacao');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Patrulheiro') ], [ $id_patrulheiro ] );
        $this->form->addFields( [ new TLabel('Posto') ], [ $id_posto ] );
        $this->form->addFields( [ new TLabel('Visitante') ], [ $id_visitante ] );
        $this->form->addFields( [ new TLabel('Data Visita') ], [ $data_visita ] );
        $this->form->addFields( [ new TLabel('Fluxo (E-ENTRADA/S-SAÍDA') ], [ $fluxo ] );
        $this->form->addFields( [ new TLabel('Veiculo') ], [ $veiculo ] );
        $this->form->addFields( [ new TLabel('Observacao') ], [ $observacao ] );

        $fluxo->forceUpperCase(); 

        // set sizes
        $id->setSize('100%');
        $id_patrulheiro->setSize('100%');
        $id_posto->setSize('100%');
        $id_visitante->setSize('100%');
        $data_visita->setSize('100%');
        $fluxo->setSize('100%');
        $veiculo->setSize('100%');
        $observacao->setSize('100%');
        
        $data_visita->setMask('dd/mm/yyyy'); 
        $data_visita->setDatabaseMask('yyyy-mm-dd'); 


        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('ronda'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Acesso;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('ronda'); // open a transaction
                $object = new Acesso($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
