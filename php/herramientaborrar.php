<?

function agregarsolicitud($iddoctor, $idpaciente, $diagnostico, $precio)
    {
        $res = $this->selectw('max(atenciondia) max', 'solicitud', "fechacreacion='" .
            strftime("%Y-%m-%d", time()) . "'");
        if ($res[0]['max'] != '') {
            $idsolicitud = $res[0]['max'] + 1;
        } else {
            $idsolicitud = 1;
        }
        $into = 'solicitud';
        $campos = 'atenciondia, fechacreacion, iddoctor, idpaciente, diagnostico, preciototalestimado';
        $values = $idsolicitud . ",now()," . $iddoctor . "," . $idpaciente . ",'" . $diagnostico .
            "'," . $precio;
        $this->insert($into, $campos, $values);
        return $this->selectw('MAX( id ) max', 'solicitud', "estado=true");
    }
function agregarmuestra($idsolicitud, $muestra)
    {
        //$re=$this->selectw('id','tipomuestra',"(nombre='".$muestra."') and (estado=true)");
        //echo $muestra.'??????????????????????????????';
        $into = 'muestra';
        $campos = 'idsolicitud,idtipomuestra';
        $values = $idsolicitud . ",'" . $muestra . "'";
        return $this->insert($into, $campos, $values);
    }
$idsolicitud = $this->agregarsolicitud($doctor, $pacientes, $diagnostico, $precio);
        foreach ($analisis as $i) {
            if ($i['seleccionado']) {
                $this->agregaratencionanalisis($idsolicitud[0]['max'], $i);
                $aten1 = $this->select('MAX( id ) max', 'atencion');
                $aten = $this->selectw('idanalisis', 'atencion', 'id=' . $aten1[0]['max']);
                $resultados = $this->selectw('tiporesultado.id',
                    'tiporesultado INNER JOIN analisisespecifico ON tiporesultado.idanalisis = analisisespecifico.id',
                    'analisisespecifico.id=' . $aten[0]['idanalisis']);
                foreach ($resultados as $m) {
                    $this->insert('resultados', 'idatencion, idtiporesultado', $aten1[0]['max'] .
                        ',' . $m['id']);
                }
                $subgrupo = $this->selectw('id', 'analisisespecifico', 'idanalisis=' . $i);

                foreach ($subgrupo as $a) {
                    $this->agregaratencionanalisis($idsolicitud[0]['max'], $a['id']);
                    $aten2 = $this->select('MAX( id ) max', 'atencion');
                    $aten3 = $this->selectw('idanalisis', 'atencion', 'id=' . $aten2[0]['max']);
                    $resultados2 = $this->selectw('tiporesultado.id',
                        'tiporesultado INNER JOIN analisisespecifico ON tiporesultado.idanalisis = analisisespecifico.id',
                        'analisisespecifico.id=' . $aten3[0]['idanalisis']);
                    foreach ($resultados2 as $b) {
                        $this->insert('resultados', 'idatencion, idtiporesultado', $aten2[0]['max'] .
                            ',' . $b['id']);
                    }
                }
            }

        }
        /*foreach ($muestras as $o){
        if ($o!=''){
        $this->agregarmuestra($idsolicitud[0]['max'], $o);
        }
        }
        if ($pago!=0){
        $this->agregarpago($idsolicitud[0]['max'],$pago);
        }*/
        //return $res;
?>        
        