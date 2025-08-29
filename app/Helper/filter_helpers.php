
<?php

/*
 *
 */


function getFilter($table, $columns, $filtros, $orderBy = null) {
	
    $form = '<form class="form-horizontal" id="form-filter">';
    $cont = 0;
    $form .= getHeaderLine();

    foreach ($columns as $key => $value) {
        
        $form .= '<div class="controls col-sm-3">';
        // label              
        $form .= "<label>".trans('versus.'.$table.'.'.$key)."</label>";

    	switch ($value) {
    		case 'text':
    			$form .= '<input type="text" name="filtros['.$key.']" id="'.$key.'" class="form-control" value="'. (isset($filtros[$key]) ? $filtros[$key] : '' ) .'">';
    			break;

            case 'currency':
                $form .= '<input type="text" name="filtros['.$key.']" id="'.$key.'" class="form-control currency" value="'. (isset($filtros[$key]) ? $filtros[$key] : '' ) .'">';
                break;

            case 'cpf_cnpj':
                $form .= '<input type="text" name="filtros['.$key.']" id="'.$key.'" class="form-control cpf_cnpj" value="'. (isset($filtros[$key]) ? $filtros[$key] : '' ) .'">';
                break;

    		case 'data':
    			$form .= '<div class="input-prepend input-group">';
    			$form .= '<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>';
    			$form .= '<input type="text" name="filtros['.$key.']" id="'.$key.'" class="form-control date-range" date="'. (isset($filtros[$key]) ? $filtros[$key] : '' ) .'" value="'. (isset($filtros[$key]) ? $filtros[$key] : '' ) .'">';
    			$form .= '</div>';
    			break;
    		
    		case is_array($value):
    			$form .= '<select name="filtros['.$key.']" style="width:100%">';
    				foreach ($value[1] as $k => $v) {
    					if(isset($filtros[$key]) && $filtros[$key] == $k)
    						$form .= '<option value="'.$k.'" selected>'.$v.'</option>';
    					else
    						$form .= '<option value="'.$k.'">'.$v.'</option>';
    				}
    			$form .= '</select>';
    			break;
    		default:
    			# code...
    			break;
    	}

    	$form .= '</div>';

    	$cont++;
    	if( ($cont%4) == 0 ) {
    		$form .= '</div><div class="row">';			
        }

    }

    $form .= '<input type="hidden" name="orderBy" id="orderBy" value="'.$orderBy[0].'" />';
    $form .= '<input type="hidden" name="orderType" id="orderType" value="'.$orderBy[1].'" />';

    $form .= getFooterLine();
    $form .= '<span class="pull-right"><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-filter"></i> Filtrar</button></span>';
    $form .= '</form>';

    return $form;
}

function getHeaderLine() {
	return '<div class="well">
    			<fieldset>
        			<div class="row">';
}

function getFooterLine() {
	return '
		    	</div>
			</fieldset>
		</div>';
}

function getTitleColumn($title, $column, $orderBy) {

    $title = '<a href="javascript://" onclick="setOrderBy(\''.$column.'\')">'.$title;
    
    if($orderBy[0] == $column && $orderBy[1] == 'DESC')
        $title .= '&nbsp;<i class="fa fa-arrow-down" aria-hidden="true"></i>';
    elseif($orderBy[0] == $column && $orderBy[1] == 'ASC')
        $title .= '&nbsp;<i class="fa fa-arrow-up" aria-hidden="true"></i>';
    
    $title .= '</a>';

    return $title;
}
