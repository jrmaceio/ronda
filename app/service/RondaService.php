<?php
/**
 * Ronda REST service
 */
class RondaService extends AdiantiRecordService
{
    const DATABASE      = 'ronda';
    const ACTIVE_RECORD = 'Ronda';
    
    // pega a ultima ronda feita em um ponto
    //http://www.facilitahomeservice.com.br/ronda/rest.php?class=RondaService&method=getRondaReg&unidade_id=6&ponto_ronda_id=29 // antigo uolhost
    public static function getRondaReg( $param )
    {
        try
        {
   
            TTransaction::open('ronda');
            $response = array();
        
            // define o critério
            $criteria = new TCriteria;
            $criteria->add(new TFilter('unidade_id', '=', $param['unidade_id']));
            $criteria->add(new TFilter('ponto_ronda_id', '=', $param['ponto_ronda_id']));
            $limite = 10;
            $param1['order'] = 'id'; 
            $param1['direction'] = 'asc';
            $criteria->setProperties($param1);
            $criteria->setProperty('limit', $limite);   
            
            // carrega 
            $all = Ronda::getObjects( $criteria );
            foreach ($all as $ronda)
            {                               
                $response[] = $ronda->toArray();
            }
            
            TTransaction::close();
            return $response;

        }
        catch (Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }

    }
    
    //http://www.facilitahomeservice.com.br/ronda/rest.php?class=RondaService&method=getPontosRonda&unidade_id=6 // antigo uolhost
    public static function getPontosRonda( $param )
    {
        try
        {
            // load all objects using pagination, and filter
            //$location = 'https://ronda.facilitahomeservice.com.br/ronda/rest.php';
            //$parameters = array();
            //$parameters['class'] = 'RondaService';
            //$parameters['method'] = 'loadAll';
            //$parameters['limit'] = '100';
            //$parameters['order'] = 'data_ronda';
            //$parameters['direction'] = 'asc';
            //$parameters['filters'] = [ ['unidade_id', '=', $param['unidade_id']] ];
            //$url = $location . '?' . http_build_query($parameters);
            //var_dump( json_decode( file_get_contents($url) ) );
            
            TTransaction::open('ronda');
            $response = array();
        
            // define o critério
            $criteria = new TCriteria;
            $criteria->add(new TFilter('unidade_id', '=', $param['unidade_id']));     
        
            $limite = 100;
            $param1['order'] = 'data_ronda'; 
            $param1['direction'] = 'desc';
            $criteria->setProperties($param1);
            $criteria->setProperty('limit', $limite);   
            
            // carrega 
            $all = Ronda::getObjects( $criteria );
            foreach ($all as $arquivo)
            {
                $ponto = new PontoRonda($arquivo->ponto_ronda_id);
                $arquivo->ponto_ronda_id = $ponto->descricao;
                
                $patrulheiro = new Patrulheiro($arquivo->patrulheiro_id);
                $arquivo->patrulheiro_id = $patrulheiro->nome;
                
                $posto = new Posto($arquivo->posto_id);
                $arquivo->posto_id = $posto->descricao;
                
                $response[] = $arquivo->toArray();
            }
            TTransaction::close();
            return $response;

        }
        catch (Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }

    }
    
    // antigo uolhost
    //http://www.facilitahomeservice.com.br/ronda/rest.php?class=RondaService&method=getRonda&unidade_id=3&descricao="TESTE"&patrulheiro_id=4&ponto_ronda_id=9&posto_id=2&longitude="-36.6460585"&latitude="-9.7688983"&data_ronda=2021-01-12&hora_ronda=21:38:45
    public static function getRonda( $param )
    {
        try
        {
   
            TTransaction::open('ronda');
            $response = array();
        
            // define o critério
            $criteria = new TCriteria;
            $criteria->add(new TFilter('unidade_id', '=', $param['unidade_id']));
            $criteria->add(new TFilter('ponto_ronda_id', '=', $param['ponto_ronda_id']));
            $criteria->add(new TFilter('data_ronda', '=', $param['data_ronda']));
            $criteria->add(new TFilter('hora_ronda', '=', $param['hora_ronda']));
            $limite = 1;
            $param1['order'] = 'id'; 
            $param1['direction'] = 'desc';
            $criteria->setProperties($param1);
            $criteria->setProperty('limit', $limite);   
            
            // carrega 
            $all = Ronda::getObjects( $criteria );
            foreach ($all as $ronda)
            {
                $response[] = $ronda->toArray();
            }
            TTransaction::close();
            return $response;

        }
        catch (Exception $e)
        {
            echo 'Error: ' . $e->getMessage();
        }

    }
    
    // http://www.facilitahomeservice.com.br/v2/rest.php?class=OcorrenciaUnidadeService&method=newRonda&condominio_id=99&descricao='teste 2'
    public static function newRonda( $param )
    {
      try
      {
        $location =  'https://ronda.facilitahomeservice.com.br/rest.php';
        $parameters = array();
        $parameters['class'] = 'RondaService';
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
    
    //http://www.facilitahomeservice.com.br/ronda/rest.php?class=RondaService&method=SincRonda&unidade_id=3&descricao="TESTE"&patrulheiro_id=4&ponto_ronda_id=9&posto_id=2&longitude="-36.6460585"&latitude="-9.7688983"&data_ronda=2021-01-12&hora_ronda=16:38:45
    public static function SincRonda( $param )
    {
        try
        {
            $location =  'https://ronda.facilitahomeservice.com.br/rest.php';
            $parameters = array();
            $parameters['class'] = 'RondaService';
            $parameters['method'] = 'store';
            $parameters['data'] = [
                                'unidade_id' => $param['unidade_id'],
                                'patrulheiro_id' => $param['patrulheiro_id'],
                                'ponto_ronda_id' => $param['ponto_ronda_id'],
                                'posto_id' => $param['posto_id'],
                                'data_ronda' => $param['data_ronda'],
                                'hora_ronda' => $param['hora_ronda'],
                                'status_tratamento' => 0, 
                                'descricao' => 'ronda sincronizada (feita offline)',
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
            echo 'Error: ' . $e->getMessage();
        }

    }
    
    
    
}
