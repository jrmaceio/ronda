<?php
/**
 * PessoaUpdateList Listing
 * @author  <your name here>
 */
class PessoaUpdateList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $saveButton;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('facilitasmart');            // defines the database
        $this->setActiveRecord('Pessoa');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('nome', 'like', 'nome'); // filterField, operator, formField
        $this->addFilterField('cpf', 'like', 'cpf'); // filterField, operator, formField
        $this->addFilterField('condominio_id', '=', 'condominio_id'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_update_Pessoa');
        $this->form->setFormTitle('Pessoa');
        

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');
        $condominio_id = new TEntry('condominio_id');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Nome') ], [ $nome ] );
        $this->form->addFields( [ new TLabel('Cpf') ], [ $cpf ] );
        $this->form->addFields( [ new TLabel('Condominio Id') ], [ $condominio_id ] );


        // set sizes
        $id->setSize('100%');
        $nome->setSize('100%');
        $cpf->setSize('100%');
        $condominio_id->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );
        
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        
        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data Nascimento', 'left');
        $column_rg = new TDataGridColumn('rg', 'Rg', 'left');
        $column_cpf = new TDataGridColumn('cpf', 'Cpf', 'left');
        $column_cnpj = new TDataGridColumn('cnpj', 'Cnpj', 'left');
        $column_pessoa_fisica_juridica = new TDataGridColumn('pessoa_fisica_juridica', 'Pessoa Fisica Juridica', 'left');
        $column_telefone1 = new TDataGridColumn('telefone1', 'Telefone1', 'left');
        $column_telefone2 = new TDataGridColumn('telefone2', 'Telefone2', 'left');
        $column_telefone3 = new TDataGridColumn('telefone3', 'Telefone3', 'left');
        $column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_observacao = new TDataGridColumn('observacao', 'Observacao', 'left');
        $column_cep = new TDataGridColumn('cep', 'Cep', 'left');
        $column_endereco = new TDataGridColumn('endereco', 'Endereco', 'left');
        $column_numero = new TDataGridColumn('numero', 'Numero', 'left');
        $column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
        $column_cidade = new TDataGridColumn('cidade', 'Cidade', 'left');
        $column_estado = new TDataGridColumn('estado', 'Estado', 'left');
        $column_condominio_id = new TDataGridColumn('condominio_id', 'Condominio Id', 'right');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_data_nascimento);
        $this->datagrid->addColumn($column_rg);
        $this->datagrid->addColumn($column_cpf);
        $this->datagrid->addColumn($column_cnpj);
        $this->datagrid->addColumn($column_pessoa_fisica_juridica);
        $this->datagrid->addColumn($column_telefone1);
        $this->datagrid->addColumn($column_telefone2);
        $this->datagrid->addColumn($column_telefone3);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_observacao);
        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_endereco);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_bairro);
        $this->datagrid->addColumn($column_cidade);
        $this->datagrid->addColumn($column_estado);
        $this->datagrid->addColumn($column_condominio_id);

        
        $column_cpf->setTransformer( function($value, $object, $row) {
            $widget = new TEntry('cpf' . '_' . $object->id);
            $widget->setValue( $object->cpf );
            //$widget->setSize(120);
            $widget->setFormName('form_update_Pessoa');
            
            $action = new TAction( [$this, 'onSaveInline'], ['column' => 'cpf' ] );
            $widget->setExitAction( $action );
            return $widget;
        });
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Save the datagrid objects
     */
    public static function onSaveInline($param)
    {
        $name   = $param['_field_name'];
        $value  = $param['_field_value'];
        $column = $param['column'];
        
        $parts  = explode('_', $name);
        $id     = end($parts);
        
        try
        {
            // open transaction
            TTransaction::open('facilitasmart');
            
            $object = Pessoa::find($id);
            if ($object)
            {
                $object->$column = $value;
                $object->store();
            }
            
            TToast::show('success', 'Record saved', 'bottom center', 'far:check-circle');
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            // show the exception message
            TToast::show('error', $e->getMessage(), 'bottom center', 'fa:exclamation-triangle');
        }
    }
}
