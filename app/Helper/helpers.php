<?php

use Carbon\Carbon;

/**
 * Backend menu active.
 *
 * @param $path
 * @param string $active
 *
 * @return string
 */
function setActive($path, $active = 'active')
{
    if (is_array($path)) {
        foreach ($path as $k => $v) {
            $path[$k] = '/'.$v;
        }
    } else {
        $path = '/'.$path;
    }

    return call_user_func_array('Request::is', (array) $path) ? $active : '';
}

function getTypePesson() {

	return ['F'=> 'Física', 'J'=> 'Jurídica', 'E'=> 'Exterior'];

}

function getClassesConta() {
    return [
        'A'=> 'Analítica',
        'S'=> 'Sintética'
    ];
}

function getNatureza() {
    return [
        'D'=> 'Devedora',
        'C'=> 'Credora'
    ];
}

function getUfs() {

    return [
        ''=> 'Selecione...',
        'AC'=> 'Acre',
        'AL'=> 'Alagoas',
        'AM'=> 'Amazonas',
        'AP'=> 'Amapá',
        'BA'=> 'Bahia',
        'CE'=> 'Ceará',
        'DF'=> 'Distrito Federal',
        'ES'=> 'Espírito Santo',
        'GO'=> 'Góias',
        'MA'=> 'Maranhão',
        'MG'=> 'Minas Gerais',
        'MS'=> 'Mato Grosso do Sul',
        'MT'=> 'Mato Grosso',
        'PA'=> 'Pará',
        'PB'=> 'Paraíba',
        'PE'=> 'Pernambuco',
        'PI'=> 'Piauí',
        'PR'=> 'Paraná',
        'RJ'=> 'Rio de Janeiro',
        'RN'=> 'Rio Grande do Nornte',
        'RO'=> 'Rôndonia',
        'RR'=> 'Roraima',
        'RS'=> 'Rio Grande do Sul',
        'SC'=> 'Santa Catarina',
        'SE'=> 'Sergipe',
        'SP'=> 'São Paulo',
        'TO'=> 'Tocantins',
        'EX'=> 'Extrangeiro'
    ];

}

function linksDefault($controller, $id, $edit = true, $delete=true) {

    $update = "";
	if($edit) {
	   $update = '<li style="float:left;">';
	   $update .= '<button onclick="window.location=\''.url($controller).'/'.$id.'/edit\'" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Editar" class="btn  btn-sm tooltips" style="margin-bottom:0;"><i class="fa fa-pencil"></i> </button>';
	   $update .= '</li>';
    }

    $destroy = "";
    if($delete) {
    	$destroy  = '<li style="float:left;">';
        $destroy .= '<form action="'.url($controller . '/' .$id) .'" method="POST">';
        $destroy .= csrf_field();
        $destroy .= method_field('DELETE');
        $destroy .= '<button data-placement="top" type="button" data-original-title="Delete" class="btn btn-sm tooltips" data-toggle="tooltip" onclick="$(\'#modal-'.$id.'\').modal(\'toggle\')" style="margin-bottom:0;">';
        $destroy .= '<i class="fa fa-trash"></i></button>';

        $destroy .= '<div id="modal-'.$id.'" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">';
        $destroy .=  '<div class="modal-dialog modal-sm">';
        $destroy .=    '<div class="modal-content">';

        $destroy .=      '<div class="modal-header">';
        $destroy .=        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>';
        $destroy .=        '</button>';
        $destroy .=        '<h4 class="modal-title" id="myModalLabel2">Confirmação</h4>';
        $destroy .=     '</div>';
        $destroy .=     '<div class="modal-body">';
        $destroy .=        '<h4>Deseja realmento apagar este registro?</h4>';
        $destroy .=      '</div>';
        $destroy .=      '<div class="modal-footer">';
        $destroy .=        '<button type="button" class="btn btn-default" data-dismiss="modal">Não</button>';
        $destroy .=        '<button type="subtmit" class="btn btn-danger">Apagar</button>';
        $destroy .=      '</div>';
        
        $destroy .=    '</div>';
        $destroy .=  '</div>';
        $destroy .= '</div>';

        $destroy .= '</form></li>';
    }
    

	return '' . $update . ' ' . $destroy . '';
}

function getAddress($data) {

    $endereco = (object) [];
    $endereco->cep = $data['cep'];
    $endereco->endereco = $data['endereco'];
    $endereco->numero = $data['numero'];
    $endereco->complemento = $data['complemento'];
    $endereco->bairro = $data['bairro'];
    $endereco->cidade = $data['cidade'];
    $endereco->uf = $data['uf'];
   
    return $endereco;

}

function getOnlyChildPlanosContas($planos) {

    $retorno = [];
    $retorno['0'] = "Selecione...";

    foreach ($planos as $key => $plano) {
        # code...
        if(count($plano->children) == 0) {
            //$item = [$plano->id, $plano->descricao];
            $id = $plano->id;
            $descricao = $plano->descricao;
            $codigo = $plano->codigo;
            $parent = $plano->parent;
            while($parent != null) {
                
                $codigo = $parent->codigo .'.'. $codigo;

                $parent = $parent->parent;
            }
            $retorno[$id] = $codigo." - ".$descricao;
        }
    }
    //echo implode("glue", $retorno);die();
    return $retorno;
}

function getCodigoCompletoPlanoConta($plano_id) {

    if($plano_id != null) {

        $plano = \App\Model\Cadastro\ContaContabel::find($plano_id);
    
        $cod = $plano->codigo;
        while($plano->parent != null) {
            $cod = $plano->parent->codigo.'.'.$cod;
            $plano = $plano->parent;
        }

        return $cod;
    } 

    return '???';
}


function getCodigoCompletoCentroResultado($centro_id) {

    if($centro_id != null) {
        $centro = \App\Model\Cadastro\CentroResultado::find($centro_id);

        $cod = $centro->codigo;
        while($centro->parent != null) {
            $cod = $centro->parent->codigo.'.'.$cod;
            $centro = $centro->parent;
        }

        return $cod;
    }

    return '???';
}

function getCodigoCompletoProjeto($projeto_id) {

    if($projeto_id != null) {
        $projeto = \App\Model\Cadastro\Projeto::find($projeto_id);

        $cod = $projeto->codigo;
        while($projeto->parent != null) {
            $cod = $projeto->parent->codigo.'.'.$cod;
            $projeto = $projeto->parent;
        }

        return $cod;
    }
    
    return '???';
}

function getPeriodosAliquota() {
    return [
        '' => '',
        '1'=> 'Diário',
        '2'=> 'Mensal',
        '3'=> 'Anual'
    ];
}

function getPeriodoAliquota($k) {
    $values = getPeriodosAliquota();

    foreach ($values as $key => $value) {
        # code...
        if($key == $k) return $value;
    }

    return null;
}

// Recebe data 00/00/0000 converte para 0000-00-00
if (!function_exists('convertDateEn')) {
    function convertDateEn($date) {
        if(!empty($date) && $date!=null) {
            $date = DateTime::createFromFormat('d/m/Y', $date);
            return $date->format('Y-m-d');
        }
        return '0000-00-00';
    }
}

// Recebe data 0000-00-00 converte para 00/00/0000
if (!function_exists('convertDatePt')) {
    function convertDatePt($date) {
        if($date!=null) {
            $date = DateTime::createFromFormat('Y-m-d', $date);
            return $date->format('d/m/Y');
        }
        return '00/00/0000';
    }
}

if(!function_exists('getPeriodoRepeticao')) {
    function getPeriodoRepeticao() {
        return [
        '0'=> 'Não se repete',
        '1'=> 'Sim, Semanalmente',
        '2'=> 'Sim, quinzenalmente',
        '3'=> 'Sim, mensalmente',
        '4'=> 'Sim, bimestralmente',
        '5'=> 'Sim, trimestramente',
        '6'=> 'Sim, semestral',
        '7'=> 'Sim, anual'
    ];
    }
}

if(!function_exists('getDataCompetenciaRepeticao')) {
    function getDataCompetenciaRepeticao() {
        return [
            '1' => 'Repetir em todas as parcelas',
            '2' => 'Diferente conforme o mês da parcelas'
        ];
    }
}

/* Exemplo 1.000,00 => 1000.00 */
function decimalFormat($value) {
    
    if(strpos($value, ","))
        $value = str_replace(",", ".", str_replace(".", "", $value));

    //$value = floatval(str_replace(",", ".", $value));
    if($value == "" || floatval($value) < 0)
        return 0;

    // mas porque isso tá aqui?
    $valueFormatted = floatval(number_format($value, 2, ".", ""));
    
    return $valueFormatted;
}


function priceFormat($price, $simbol=true) {
        $price = floatval(str_replace(",", ".", $price));
        if($price == "") {
            if($simbol)
                return "R$ 0,00";
            else
                return "0,00";
        }

        if($simbol)
            $priceFormatted = "R$ " . number_format($price, 2, ",", ".");
        else
            $priceFormatted = number_format($price, 2, ",", ".");

        return $priceFormatted;
}

function getWhere($query, $where, $table = "") {

    if(isset($where)) {
        foreach ($where as $key => $value) {
            if($value[0] == 'whereBetween') {
                //$query->whereBetween($key, $value[1]);
                $query->whereRaw( $table.$key." between '".$value[1][0]."' and '".$value[1][1]."'" );
                //$query->where($key, '>=',$value[1][0]);
                //$query->where($key, '<=',$value[1][1]);
            }
            elseif($value[0] == 'whereBetweenDate') {
                $query->whereDate($table.$key, '>=', new DateTime($value[1][0]));
                $query->whereDate($table.$key, '<=', new DateTime($value[1][1]));
            }
            elseif($value[0] == 'whereIn')
                $query->whereIn($table.$key, $value[1]);
            elseif($value[0] == 'whereRaw')
                $query->whereRaw($value[1]);
            elseif(is_array($value))
                $query->where($table.$key , $value[0], $value[1]);
            else
                $query->where($table.$key , $value);
        }
    }
    //echo $query->toSql();
    return $query;
}

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}

function getIdstoSelectMultiple($objs) {

    $array = null;
    
    foreach ($objs as $key => $value) {
        $array[] = ($value->id);
    }

    return $array;
}


/* Comercial /*/

function getTipoCondicoes($id = null) {

    $tipos = [
        '' => 'Selecione...',
        '001' => '001 - PARCELAS IGUAIS',
        '002' => '002 - PERCENTUAIS'
    ];

    if(isset($id))
        return $tipos[$id];

    return $tipos;
}

function getTipoTransacao($id = null) {

    $tipos = [
        '' => 'Selecione...',
        'E' => 'Entrada',
        'S' => 'Saída'
    ];
    
    if(isset($id))
        return $tipos[$id];

    return $tipos;
}


function getTipoIntegraFinanceiro($id = null) {

    $tipos = [
        '' => 'Selecione...',
        'A' => 'Gerar Agendamento',
        'L' => 'Gerar Lançamento Gerencial',
        'N' => 'Não integrar'
    ];

    if(isset($id))
        return $tipos[$id];

    return $tipos;
}

function getTipoIntegraEstoque($id = null) {

    $tipos = [
        '' => 'Selecione...',
        'S' => 'Integrar ao estoque',
        'N' => 'Não integrar'
    ];

    if(isset($id))
        return $tipos[$id];

    return $tipos;
}

function getCategoriaVendedor($id = null) {

    $tipos = [
        '' => 'Selecione...',
        'ARQ' => 'ARQ - ARQUITETO',
        'REP' => 'REP - REPRESENTANTE',
        'VEN' => 'VEN - VENDEDOR'
    ];

    if(isset($id))
        return $tipos[$id];

    return $tipos;
}

function getEtapasComercial($id) {

    $etapas = [
        '' => 'Selecione...',
        '001' => '001 - CONTRATO ABERTO'
    ];

    if(isset($id))
        return $etapas[$id];

    return $etapas;
}

function dataVencido($data) {

   $now = Carbon::now();                        // America/Toronto
   $date = Carbon::createFromFormat('Y-m-d', $data);
   
   return $date->isPast();
}

function getNameMonth($month) {
    $array = [
        1   => 'Janeiro',
        2   => 'Fevereiro',
        3   => 'Março',
        4   => 'Abril',
        5   => 'Maio',
        6   => 'Junho',
        7   => 'Julho',
        8   => 'Agosto',
        9   => 'Setembro',
        10   => 'Outubro',
        11   => 'Novembro',
        12   => 'Dezembro'
    ];

    return $array[$month];
}

function getLinksPerPage($per_page = 30) {
    
    $class30 = $class60 = $class120 = '';
    if($per_page == null || $per_page == 30)
        $class30 = 'active';
    elseif($per_page == 60)
        $class60 = 'active';
    elseif($per_page == 120)
        $class120 = 'active';

    $links = '<span style="float:left; padding: 5px; padding-left:0;">Por Página: </span>';
    $links .= '<div class="btn-group  btn-group-sm">
                  <button class="btn btn-default '.$class30.'" type="button" onclick="location = \'?per_page=30\'">30</button>
                  <button class="btn btn-default '.$class60.'" type="button" onclick="location = \'?per_page=60\'">60</button>
                  <button class="btn btn-default '.$class120.'" type="button" onclick="location = \'?per_page=120\'">120</button>
                </div>';

    return $links;
}

function getDatePeriodo($periodo) {
        
    $dt = Carbon::now();

    if($dt->dayOfWeek === Carbon::SATURDAY)   // int(6)
        $addDays =  6;
    if($dt->dayOfWeek === Carbon::SUNDAY)     // int(0)
        $addDays =  5;
    if($dt->dayOfWeek === Carbon::MONDAY)     // int(1)
        $addDays =  4;
    if($dt->dayOfWeek === Carbon::TUESDAY)    // int(2)
        $addDays =  3;
    if($dt->dayOfWeek === Carbon::WEDNESDAY)  // int(3)
        $addDays =  2;
    if($dt->dayOfWeek === Carbon::THURSDAY)   // int(4)
        $addDays =  1;
    if($dt->dayOfWeek === Carbon::FRIDAY)     // int(5)
        $addDays =  0;

    for ($i=1; $i <= 5; $i++) { 

        switch ($periodo) {
            case 1:
                // Hoje
                $datas[0][1] = Carbon::now();
                
                // Outros dias
                $datas[$i][1] = Carbon::now()->addDays($i);
                break;
            case 2:
                // Hoje
                $datas[0][1] = Carbon::now();
                $datas[0][2] = Carbon::now()->addDays($addDays);

                // Outras semanas
                $datas[$i][1] = Carbon::now()->addDays($addDays)->addWeeks($i-1)->addDay();
                $datas[$i][2] = Carbon::now()->addDays($addDays)->addWeeks($i);
                break;
            case 3:

                // Hoje
                $datas[0][1] = Carbon::now();
                $datas[0][2] = Carbon::now()->addDays($addDays+7);

                // Outras semanas
                $datas[$i][1] = Carbon::now()->addDays($addDays+7)->addWeeks(2*($i-1))->addDay();
                $datas[$i][2] = Carbon::now()->addDays($addDays+7)->addWeeks(2*$i);
                break;
            case 4:

                // Hoje
                $datas[0][1] = Carbon::now();
                $datas[0][2] = Carbon::now()->addDays( Carbon::now()->daysInMonth - Carbon::now()->day );

                $inicioMes = Carbon::now()->addDays( Carbon::now()->daysInMonth - Carbon::now()->day )->addDay();
                $fimMes = Carbon::now()->addDays( Carbon::now()->daysInMonth - Carbon::now()->day )->addDays( Carbon::now()->addMonth($i)->daysInMonth );

                $datas[$i][1] = $inicioMes->addMonth($i-1);
                $datas[$i][2] = $fimMes->addMonth($i-1);
                break;
            default:
                # code...
                break;
        }
        
    }
    //var_dump($datas);
    return $datas;
}

/**
 * Upload the image and return the data
 *
 * @param $request
 * @param string $field
 * @return mixed
 */
function uploadImage($request, $field, $path)
{
    
    if ($file = $request->file($field)) {
        $fileName = rename_file($file->getClientOriginalName(), $file->getClientOriginalExtension());
        $path = $path . "/" . str_plural($field);
        $file->move(public_path($path)."/", $fileName);
        $field = $path . $fileName;
    }
    
    return $field;
}

if (!function_exists('rename_file')) {
    /**
     * Rename the filename, convert string to url friendly form and attach random string
     *
     * @param $filename
     * @param $mime
     * @return string
     */
    function rename_file($filename, $mime)
    {
        // remove extension first
        $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
        $filename = str_slug($filename, "-");
        $filename = '/' . $filename . '_' . str_random(16) .  '.' . $mime;
        return $filename;
    }
}

/* Modulo galeria ativo */


function getTipoPessoaGaleria($tipo = null) {
    
    $array = [
        'F' => 'Fornecedor',
        'C' => 'Cliente',
        'A' => 'Artista',
        'Q' => 'Arquiteto',
        'V' => 'Vendedor',
        'P' => 'Parceiro Comercial',
        'N' => 'Funcionário'
    ];

    if(isset($tipo)) {
        return $array[$tipo];
    } else {
        return $array;
    }

}

function getSituacaoConsignacao($situacao) {
    
    $array = [
        'A' => 'Aberto',
        'D' => 'Devolvido Tudo',
        'N' => 'Negociada'
    ];

    if(isset($situacao)) {
        return $array[$situacao];
    } else {
        return $array;
    }

}

function getSituacaoVenda($situacao) {
    
    $array = [
        'A' => 'Aberta',
        'C' => 'Cancelada',
        'F' => 'Finalizada'
    ];

    if(isset($situacao)) {
        return $array[$situacao];
    } else {
        return $array;
    }

}
