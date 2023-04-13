<?php
/**
 * CondominioFormList Form List
 * @author  <your name here>
 */
class CondominioFormList extends TPage
{
    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    protected $loaded;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Condominio');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Condominio');
        
        // create the form fields
        $id = new THidden('id');
        $resumo = new TEntry('resumo');
        $nome = new TEntry('nome');
        $cnpj = new TEntry('cnpj');
        $inscricao_municipal = new TEntry('inscricao_municipal');
        $cep = new TEntry('cep');
        $endereco = new TEntry('endereco');
        $bairro = new TEntry('bairro');
        $cidade = new TEntry('cidade');
        $estado = new TEntry('estado');
        $site = new TEntry('site');
        $email = new TEntry('email');
        $telefone1 = new TEntry('telefone1');
        $telefone2 = new TEntry('telefone2');
        //$active = new TSelect('active');

        $label_telefone2 = new TLabel('Telefone 2');
        $label_telefone2->setFontStyle('b');
        $label_telefone2->style.=';float:left';
        
        $label_inscricao_municipal = new TLabel('Inscrição Municipal');
        $label_inscricao_municipal->setFontStyle('b');
        $label_inscricao_municipal->style.=';float:left';
        
        // add the fields
        $this->form->addQuickField('Id', $id,  '50%' );
        $this->form->addQuickField('Resumo', $resumo,  '100%' , new TRequiredValidator);
        $this->form->addQuickField('Nome', $nome,  '100%' , new TRequiredValidator);
        $this->form->addQuickFields('CNPJ', array( $cnpj, $label_inscricao_municipal, $inscricao_municipal));
        $this->form->addQuickField('Cep', $cep,  '100%' , new TRequiredValidator);
        $this->form->addQuickField('Endereco', $endereco,  '100%' , new TRequiredValidator);
        $this->form->addQuickField('Bairro', $bairro,  '100%' , new TRequiredValidator);
        $this->form->addQuickField('Cidade', $cidade,  '100%' , new TRequiredValidator);
        $this->form->addQuickField('Estado', $estado,  '100%' , new TRequiredValidator);
        $this->form->addQuickField('Site', $site,  '100%' );
        $this->form->addQuickField('Email', $email,  '100%' );
        $this->form->addQuickFields('Telefone 1', array( $telefone1, $label_telefone2, $telefone2)); 
        
        $cnpj->setSize('30%');
        $inscricao_municipal->setSize('30%');
        $telefone1->setSize(200);
        $telefone2->setSize(200);

        $telefone1->setMask('(99)9999-99999'); 
        $telefone2->setMask('(99)9999-99999'); 
        $cep->setMask('99999-999');
        $cnpj->setMask('99.999.999/9999-99');

        // buscar sep
        $buscaCep = new TAction(array($this, 'onCep'));
        $cep->setExitAction($buscaCep); 

        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->datatable = 'true';
        $this->datagrid->width = '100%';
        $this->datagrid->enablePopover('Informações', '<b>'.'Condomínio'.'</b><br>' . '{nome}' . '<br><b>'.'E-mail'.'</b><br>' . '{email}');
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_resumo = new TDataGridColumn('resumo', 'Resumo', 'left');
        //$column_nome = new TDataGridColumn('nome', 'Nome', 'left');
        //$column_cep = new TDataGridColumn('cep', 'Cep', 'left');
        //$column_endereco = new TDataGridColumn('endereco', 'Endereco', 'left');
        //$column_bairro = new TDataGridColumn('bairro', 'Bairro', 'left');
        //$column_cidade = new TDataGridColumn('cidade', 'Cidade', 'left');
        //$column_estado = new TDataGridColumn('estado', 'Estado', 'left');
        //$column_site = new TDataGridColumn('site', 'Site', 'left');
        //$column_email = new TDataGridColumn('email', 'Email', 'left');
        $column_telefone1 = new TDataGridColumn('telefone1', 'Telefone1', 'left');
        $column_telefone2 = new TDataGridColumn('telefone2', 'Telefone2', 'left');
        //$column_status = new TDataGridColumn('status', 'Status', 'left');
        $column_active = new TDataGridColumn('active', _t('Active'), 'center');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_resumo);
        //$this->datagrid->addColumn($column_nome);
        //$this->datagrid->addColumn($column_cep);
        //$this->datagrid->addColumn($column_endereco);
        //$this->datagrid->addColumn($column_bairro);
        //$this->datagrid->addColumn($column_cidade);
        //$this->datagrid->addColumn($column_estado);
        //$this->datagrid->addColumn($column_site);
        //$this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_telefone1);
        $this->datagrid->addColumn($column_telefone2);
        $this->datagrid->addColumn($column_active);
        
        $column_active->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        
        // creates two datagrid actions
        $action1 = new TDataGridAction(array($this, 'onEdit'));
        //$action1->setUseButton(TRUE);
        //$action1->setButtonClass('btn btn-default');
        $action1->setLabel(_t('Edit'));
        $action1->setImage('fa:pencil-square-o blue fa-lg');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        $action2->setLabel(_t('Delete'));
        $action2->setImage('fa:trash-o red fa-lg');
        $action2->setField('id');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
        // create ONOFF action
        $action_onoff = new TDataGridAction(array($this, 'onTurnOnOff'));
        $action_onoff->setButtonClass('btn btn-default');
        $action_onoff->setLabel(_t('Activate/Deactivate'));
        $action_onoff->setImage('fa:power-off fa-lg orange');
        $action_onoff->setField('id');
        $this->datagrid->addAction($action_onoff);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Condomínio', $this->form));
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }

    /**
     * Turn on/off an user
     */
    public function onTurnOnOff($param)
    {
        try
        {
            TTransaction::open('facilitasmart');
            $user = Condominio::find($param['id']);
            
            if ($user instanceof Condominio)
            {
                $user->active = $user->active == 'Y' ? 'N' : 'Y';
                $user->store();
            }
            
            TTransaction::close();
            
            $this->onReload($param);
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    /* 
    *  Função de busca de Endereço pelo CEP 
    *  -   Desenvolvido Felipe Olivaes para ajaxbox.com.br 
    *  -   Utilizando WebService de CEP da republicavirtual.com.br 
    */
    public static function onCep($param)
        {
            
            $resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($param['cep']).'&formato=query_string');  
            if(!$resultado){  
                $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";  
            }  

            parse_str($resultado, $retorno);   
            
            $obj = new StdClass;
            //$obj->cep      = $param['cep'];
            $obj->endereco = strtoupper( $retorno['tipo_logradouro'].' '.$retorno['logradouro']);
            $obj->bairro  = strtoupper( $retorno['bairro']);
            $obj->cidade   = strtoupper( $retorno['cidade']);
            $obj->estado       = strtoupper( $retorno['uf']); 
            
            /*
            // acha a localizacao pelo endereço
            $geocode = new TGeoCode(utf8_encode($retorno['tipo_logradouro'].' '.$retorno['logradouro'].','.$retorno['cidade']));
            $geocode->request();

            if($geocode->getStatus() == 'OK') {
                //echo $geocode->getLat();
                //echo $geocode->getLng();
                //echo $geocode->getFormattedAddress();
                
                $obj->lat      = $geocode->getLat();
                $obj->long     = $geocode->getLng();
                
                $obj->end_form = $geocode->getFormattedAddress();
                
                // key minha api :  AIzaSyD4JYvk3iEQUjXyDUuyHXgluLnFWo_0evA 
                
                // teste com imagemm estatica --> https://maps.googleapis.com/maps/api/staticmap?center=-9.7509084802915,-36.664387930291&size=800x600&zoom=12&maptype=roadmap&markers=icon:%20http://ijiya.com/images/marker-images/image.png|shadow:true|21.19365498864821,72.8217601776123&sensor=false&key=AIzaSyD4JYvk3iEQUjXyDUuyHXgluLnFWo_0evA
                
                $googleQuery = $geocode->getFormattedAddress();
               
                $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($googleQuery) . '&sensor=false';

                $response = file_get_contents($url);

                $json = json_decode($response,TRUE); //generate array object from the response from the web

                //echo ($json['results'][0]['geometry']['location']['lat'].",".$json['results'][0]['geometry']['location']['lng']);
                $lt = $json['results'][0]['geometry']['location']['lat'];
                $lg = $json['results'][0]['geometry']['location']['lng'];

                $this->pos = $lt.','.$lg;

                $mapElement = new TElement('img');
                $mapElement->generator = 'adianti';
                $mapElement->style = "width:900px;height:750px"; 
                $mapElement->src = "https://maps.googleapis.com/maps/api/staticmap?center=".$this->pos."&zoom=15&size=1024x800&markers=color:red%7Clabel:C%7C".$this->pos."&key=AIzaSyD4JYvk3iEQUjXyDUuyHXgluLnFWo_0evA";
                
                parent::add($mapElement); 
                
                
            } 
            */
            
            // envia dados ao form
            TForm::sendData('form_Condominio', $obj);
        }
        
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'facilitasmart'
            TTransaction::open('facilitasmart');
            
            // creates a repository for Condominio
            $repository = new TRepository('Condominio');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('Condominio_filter'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('Condominio_filter'));
            }
            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('facilitasmart'); // open a transaction with database
            $object = new Condominio($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            $this->onReload( $param ); // reload the listing
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('facilitasmart'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            
            $object = new Condominio;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            
            //retira caracteres da mascara do CEP
            $object->cep = str_replace('.','',$object->cep);
            $object->cep = str_replace('-','',$object->cep);
            
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved')); // success message
            $this->onReload(); // reload the listing
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
                TTransaction::open('facilitasmart'); // open a transaction
                $object = new Condominio($key); // instantiates the Active Record
                
                $object->cep = preg_replace('(([0-9]{2,})([0-9]{3,})([0-9]{3,}))','\\1.\\2-\\3',$object->cep);
                
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
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
