<?php
/**
 * ContasReceber REST service
 */
class ContasReceberService extends AdiantiRecordService
{
    const DATABASE      = 'facilitasmart';
    const ACTIVE_RECORD = 'ContasReceber';
    
    /*
 
    https://www.facilitahomeservice.com.br/v2/rest.php?class=ContasReceberService&method=getBoleto&condominio_id=5&unidade_id=564&mes_ref=12/2020
    */
    public static function getBoleto( $param )
    {
        TTransaction::open('facilitasmart');
        $response = array();
        
        // define o critério
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condominio_id', '=', $param['condominio_id']));
        $criteria->add(new TFilter('unidade_id', '=', $param['unidade_id']));
        $criteria->add(new TFilter('mes_ref', '=', $param['mes_ref']));
        $criteria->add(new TFilter('situacao', '=', 0)); 
        //$criteria->add(new TFilter('tipo_id', '=', 6), TExpression::OR_OPERATOR);
 
        $limite = 1;
        //$param1['order'] = 'mes_ref'; 
        $param1['direction'] = 'desc';
        $criteria->setProperties($param1);
        $criteria->setProperty('limit', $limite);   
 
        // carrega
        $all = ContasReceber::getObjects( $criteria );
        foreach ($all as $titulo)
        {
            $link = $titulo->pjbank_linkBoleto;
            $unidade_id = $titulo->unidade_id;
            $response[] = $titulo->toArray();
        }
        //TTransaction::close();

        // envia o email para impressora hp na nuvem
        //TTransaction::open('permission'); 
        // inicio teste email
        $unidade = new Unidade($unidade_id);
        $pessoa = new Pessoa($unidade->proprietario_id);

        $preferences = SystemPreference::getAllPreferences();
        $mail = new TMail;
        $mail->setDebug(false);
        $mail->SMTPSecure = "ssl";
        $mail->setFrom( trim($preferences['mail_from']), 'FacilitaSmart' );
        $mail->addAddress( trim('jrmaceio09@gmail.com'), 'Boleto' );
        //$mail->addAddress( trim($pessoa->email), 'Boleto' );
        // $mail->setSubject( 'FacilitaSmart' );
        // $mail->addAttach( $file, 'Comprovante de inscrição.pdf' );
        if ($preferences['smtp_auth'])
        {
            $mail->SetUseSmtp();
            $mail->SetSmtpHost($preferences['smtp_host'], $preferences['smtp_port']);
            $mail->SetSmtpUser($preferences['smtp_user'], $preferences['smtp_pass']);
        }
        $body = $link;
        $mail->setTextBody($body);    
        sleep(3);            
        $mail->send();

        TTransaction::close();
        return $response;
    }

    /**
     * load($param)
     *
     * Load an Active Records by its ID
     * 
     * @return The Active Record as associative array
     * @param $param['id'] Object ID
     */
    
    
    /**
     * delete($param)
     *
     * Delete an Active Records by its ID
     * 
     * @return The Operation result
     * @param $param['id'] Object ID
     */
    
    
    /**
     * store($param)
     *
     * Save an Active Records
     * 
     * @return The Operation result
     * @param $param['data'] Associative array with object data
     */
    
    
    /**
     * loadAll($param)
     *
     * List the Active Records by the filter
     * 
     * @return Array of records
     * @param $param['offset']    Query offset
     *        $param['limit']     Query limit
     *        $param['order']     Query order by
     *        $param['direction'] Query order direction (asc, desc)
     *        $param['filters']   Query filters (array with field,operator,field)
     */
    
    
    /**
     * deleteAll($param)
     *
     * Delete the Active Records by the filter
     * 
     * @return Array of records
     * @param $param['filters']   Query filters (array with field,operator,field)
     */
}
