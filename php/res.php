<?php 
include ('../bdlaboratorio.php');
class motorjs extends bdlaboratorio
{
    function resultados2(){
        $res='<div style="color: #448; background: #dde9ec;">
        <input checked id="'.$_GET['id'].'" type="checkbox" onclick="hace(this.id);"/>
        Marcar Todos</div>';
        $temp1=$this->selectw('*','tiporesultado','estado=true and idanalisis='.$_GET['id']);
        foreach ($temp1 as $temp2){
            $res.='<div style="color: #7A91A2; "><input checked class="resultado'.$_GET['id'].'" type="checkbox"  name="b[]" value="'.$temp2['id'].'">'.$temp2['nombre'].'</div>';
        }
        return $res;
    }
    
}
$s= new motorjs();
echo $s->resultados2();
?>