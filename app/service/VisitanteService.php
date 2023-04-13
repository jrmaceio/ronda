<?php
/**
 * Visitante REST service
 */
class VisitanteService extends AdiantiRecordService
{
    const DATABASE      = 'ronda';
    const ACTIVE_RECORD = 'Visitante';
    
    //http://www.facilitahomeservice.com.br/ronda/rest.php?class=VisitanteService&method=getVisitante&visitante_id=3 // antigo uolhost
    public static function getVisitante( $param )
    {
        try
        {
            TTransaction::open('ronda');
            $response = array();
     
            // define o critério
            $criteria = new TCriteria;
            $criteria->add(new TFilter('id', '=', $param['visitante_id']));
            
            // carrega
            $all = Visitante::getObjects( $criteria );
            foreach ($all as $visitante)
            {
                $response[] = $visitante->toArray();
            }
            TTransaction::close();
            return $response;

        }
        catch (Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }

    }
    
    public static function newAcesso( $param )
    {
      try
      {
        $location =  'https://ronda.facilitahomeservice.com.br/rest.php';
        $parameters = array();'AcessoService';
        $parameters['method'] = 'store';
        $parameters['data'] = [
                                'unidade_id' => $param['unidade_id'],
                                'patrulheiro_id' => $param['patrulheiro_id'],
                                'ponto_ronda_id' => $param['ponto_ronda_id'],
                                'posto_id' => $param['posto_id'],
                                'data_ronda' => date("Y-m-d"),
                                'hora_ronda' => date('H:i:s'),
                                'status_tratamento' => 0, 
                                'descricao' => $param['descricao'],
                                'tipo_id' => 8, // 8 - Ronda
                                'latitude' => $param['latitude'],
                                'longitude' => $param['longitude'],
                              ];
        $url = $location . '?' . http_build_query($parameters);
        //var_dump( json_decode( file_get_contents($url) ) );        
        // pegar o objeto que vem do serviço
        $obj = json_decode( file_get_contents($url) ); 
        //$obj['id');
        return $obj;
        
      }
      catch (Exception $e)
      {
        echo 'Error: '. $e->getMessage();
      }    
    }

}
