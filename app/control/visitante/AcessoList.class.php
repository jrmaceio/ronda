<?php
/**
 * AcessoList Listing
 * @author  <your name here>
 */
class AcessoList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('ronda');            // defines the database
        $this->setActiveRecord('Acesso');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('id', '=', 'id'); // filterField, operator, formField
        $this->addFilterField('id_patrulheiro', '=', 'id_patrulheiro'); // filterField, operator, formField
        $this->addFilterField('id_posto', '=', 'id_posto'); // filterField, operator, formField
        $this->addFilterField('id_visitante', '=', 'id_visitante'); // filterField, operator, formField
        $this->addFilterField('data_visita', 'like', 'data_visita'); // filterField, operator, formField
        $this->addFilterField('fluxo', 'like', 'fluxo'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Acesso');
        $this->form->setFormTitle('Acesso');
        

        // create the form fields
        $id = new TEntry('id');
        $id_patrulheiro = new TDBUniqueSearch('id_patrulheiro', 'ronda', 'Patrulheiro', 'id', 'nome');
        $id_posto = new TDBUniqueSearch('id_posto', 'ronda', 'Posto', 'id', 'descricao');
        $id_visitante = new TDBUniqueSearch('id_visitante', 'ronda', 'Visitante', 'id', 'nome');
        $data_visita = new TEntry('data_visita');
        $fluxo = new TEntry('fluxo');


        // add the fields
        $this->form->addFields( [ new TLabel('Id') ], [ $id ] );
        $this->form->addFields( [ new TLabel('Id Patrulheiro') ], [ $id_patrulheiro ] );
        $this->form->addFields( [ new TLabel('Id Posto') ], [ $id_posto ] );
        $this->form->addFields( [ new TLabel('Id Visitante') ], [ $id_visitante ] );
        $this->form->addFields( [ new TLabel('Data Visita') ], [ $data_visita ] );
        $this->form->addFields( [ new TLabel('Fluxo') ], [ $fluxo ] );

        $data_visita->setMask('dd/mm/yyyy'); 

        // set sizes
        $id->setSize('100%');
        $id_patrulheiro->setSize('100%');
        $id_posto->setSize('100%');
        $id_visitante->setSize('100%');
        $data_visita->setSize('100%');
        $fluxo->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['AcessoForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_id_patrulheiro = new TDataGridColumn('id_patrulheiro', 'Id Patrulheiro', 'right');
        $column_id_posto = new TDataGridColumn('id_posto', 'Id Posto', 'right');
        $column_id_visitante = new TDataGridColumn('id_visitante', 'Id Visitante', 'right');
        $column_data_visita = new TDataGridColumn('data_visita', 'Data Visita', 'left');
        $column_fluxo = new TDataGridColumn('fluxo', 'Fluxo', 'left');
        $column_veiculo = new TDataGridColumn('veiculo', 'Veiculo', 'left');
        $column_observacao = new TDataGridColumn('observacao', 'Observacao', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_id_patrulheiro);
        $this->datagrid->addColumn($column_id_posto);
        $this->datagrid->addColumn($column_id_visitante);
        $this->datagrid->addColumn($column_data_visita);
        $this->datagrid->addColumn($column_fluxo);
        $this->datagrid->addColumn($column_veiculo);
        $this->datagrid->addColumn($column_observacao);

        
        $action1 = new TDataGridAction(['AcessoForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
}
