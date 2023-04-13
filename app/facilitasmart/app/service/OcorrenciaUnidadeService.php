<?php
/**
 * OcorrenciaUnidade REST service
 */
class OcorrenciaUnidadeService extends AdiantiRecordService
{
    const DATABASE      = 'facilitasmart';
    const ACTIVE_RECORD = 'OcorrenciaUnidade';
    
    /*
    testado com:

    http://www.facilitahomeservice.com.br/v2/rest.php?class=OcorrenciaUnidadeService&method=newRonda&condominio_id=5&descricao="ronda gilson"
    
    */
    // http://www.facilitahomeservice.com.br/v2/rest.php?class=OcorrenciaUnidadeService&method=newRonda&condominio_id=99&descricao='teste 2'
    public static function newRonda( $param )
    {
  
        $location =  'https://www.facilitahomeservice.com.br/v2/rest.php';
        $parameters = array();
        $parameters['class'] = 'OcorrenciaUnidadeService';
        $parameters['method'] = 'store';
        $parameters['data'] = [
                                'unidade_id' => 99,
                                'system_user_login' => 'qrcode',
                                'data_ocorrencia' => date("Y-m-d"),
                                'hora_ocorrencia' => date('H:i:s'),
                                'condominio_id' => $param['condominio_id'], 
                                'descricao' => $param['descricao'],
                                'tipo_id' => 8, // 8 - Ronda
                              ];
        $url = $location . '?' . http_build_query($parameters);
        var_dump( json_decode( file_get_contents($url) ) );
        
        
    }

    /*
    https://www.facilitahomeservice.com.br/facilitasmart/rest.php?class=OcorrenciaUnidadeService&method=getOcorrencia
    &condominio_id=6
    */   
    public static function newOcorrencia( $param )
    {
  
        $location =  'https://www.facilitahomeservice.com.br/v2/rest.php';
        $parameters = array();
        $parameters['class'] = 'OcorrenciaUnidadeService';
        $parameters['method'] = 'store';
        $parameters['data'] = [
                                'condominio_id' => $param['condominio_id'], 
                                'descricao' => $param['registro']
                              ];
        $url = $location . '?' . http_build_query($parameters);
        var_dump( json_decode( file_get_contents($url) ) );
        
        
    }

    /*
    http://www.facilitahomeservice.com.br/facilitasmart/rest.php?class=OcorrenciaUnidadeService&method=getOcorrencia&condominio_id=6
    */   
    public static function getOcorrencia( $param )
    {
        TTransaction::open('facilitasmart');
        $response = array();
        
        // define o critério
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condominio_id', '=', $param['condominio_id']));
        $limite = 6;
        $param1['order'] = 'datahora_cadastro'; 
        $param1['direction'] = 'desc';
        $criteria->setProperties($param1);
        $criteria->setProperty('limit', $limite);   
            
        // carrega 
        $all = OcorrenciaUnidade::getObjects( $criteria );
        foreach ($all as $ocorrencia)
        {
            $response[] = $ocorrencia->toArray();
        }
        TTransaction::close();
        return $response;
    }
    
    /*
    5 - administradora
    6 - portaria
    
    http://www.facilitahomeservice.com.br/facilitasmart/rest.php?class=OcorrenciaUnidadeService&method=getOcorrenciaGeral&condominio_id=6
    */
    public static function getOcorrenciaGeral( $param )
    {
        TTransaction::open('facilitasmart');
        $response = array();
        
        // define o critério
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condominio_id', '=', $param['condominio_id']));
        $criteria->add(new TFilter('tipo_id', '=', $param['tipo_id']));
        //$criteria->add(new TFilter('tipo_id', '=', 5), TExpression::OR_OPERATOR); 
        //$criteria->add(new TFilter('tipo_id', '=', 6), TExpression::OR_OPERATOR);
 
        $limite = 6;
        $param1['order'] = 'datahora_cadastro'; 
        $param1['direction'] = 'desc';
        $criteria->setProperties($param1);
        $criteria->setProperty('limit', $limite);   
 
        // carrega
        $all = OcorrenciaUnidade::getObjects( $criteria );
        foreach ($all as $ocorrencia)
        {
            $response[] = $ocorrencia->toArray();
        }
        TTransaction::close();
        return $response;
    }
    
    /*
    http://www.facilitahomeservice.com.br/facilitasmart/rest.php?class=OcorrenciaUnidadeService&method=getOcorrenciaUnidade&condominio_id=6&unidade_id=312
    */
    public static function getOcorrenciaUnidade( $param )
    {
        TTransaction::open('facilitasmart');
        $response = array();
        
        // define o critério
        $criteria = new TCriteria;
        $criteria->add(new TFilter('condominio_id', '=', $param['condominio_id']));     
        $criteria->add(new TFilter('unidade_id', '=', $param['unidade_id']));    
        $limite = 6;
        $param1['order'] = 'datahora_cadastro'; 
        $param1['direction'] = 'desc';
        $criteria->setProperties($param1);
        $criteria->setProperty('limit', $limite);   
            
        // carrega 
        $all = OcorrenciaUnidade::getObjects( $criteria );
        foreach ($all as $ocorrencia)
        {
            $response[] = $ocorrencia->toArray();
        }
        TTransaction::close();
        return $response;
    }
}
