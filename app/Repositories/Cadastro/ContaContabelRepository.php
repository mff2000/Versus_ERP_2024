<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\ContaContabel;
use App\Model\Cadastro\LancamentoGerencial;
use App\Model\Financeiro\MultaJuroDesconto;

use Input;
use DateTime;
use Flash;

use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class ContaContabelRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $conta;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'codigo' => 'required',
        'descricao' => 'required',
    ];

    /**
     * @param Company $company
     */
    public function __construct(ContaContabel $conta)
    {
        $this->conta = $conta;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {
        if($orderBy != null)
            $query = \App\Model\Cadastro\ContaContabel::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Cadastro\ContaContabel::orderBy('codigo', 'ASC');

        $query = getWhere($query, $where);
                
        return $query->get();
    }

    public function getRazao($where = null, $orderBy = null) {

        if($orderBy != null) {
            $query = \App\Model\Financeiro\Agendamento::orderBy($orderBy[0], $orderBy[1]);
        } else {
            $query = \App\Model\Financeiro\Agendamento::orderBy('agendamento_rateio.plano_conta_id', 'ASC');
        }

        # code...
        $query->selectRaw('
            agendamentos.id, agendamentos.historico, agendamentos.favorecido_id, agendamentos.tipo_movimento,
            agendamentos.data_competencia, agendamentos.data_vencimento,
            favorecidos.nome_empresarial,
            agendamento_rateio.plano_conta_id, agendamento_rateio.centro_resultado_id, agendamento_rateio.projeto_id, agendamento_rateio.valor,
            agendamento_rateio.ordem,
            lancamentos_bancarios.data_liquidacao
        ');
        $query->leftJoin('lancamentos_bancarios', 'lancamentos_bancarios.agendamento_id', '=', 'agendamentos.id');
        $query->leftJoin('favorecidos', 'favorecidos.id', '=', 'agendamentos.favorecido_id');
        $query->leftJoin('agendamento_rateio', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');
        $query->leftJoin('planos_contas', 'planos_contas.id', '=', 'agendamento_rateio.plano_conta_id');
        
        $query = getWhere($query, $where);

        //$query->leftJoin('centros_resultado', 'centros_resultado.id', '=', 'agendamento_rateio.centro_resultado_id');
        //$query = $query->groupBy('planos_contas.id');
        //echo $query->toSql();
        
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function dre($where = null, $orderBy = null)
    {
        
        if(isset($where['data_competencia']) && !empty($where['data_competencia']) )
            $data_referencia = $where['data_competencia'];
        elseif(isset($where['data_vencimento']) && !empty($where['data_vencimento']) )
            $data_referencia = $where['data_vencimento'];
        else
            $data_referencia = $where['data_lancamento'];

        $valores = null;
        $valores_multa = null;
        $valores_desconto = null;

        // contas superior -- sem pai
        $all = $this->all(['conta_superior' => NULL]);
        $valores['resultado'] = 0;
        foreach ($all as $key => $conta) {
            
            $valores[$conta->id] = ['id'=> $conta->id, 'classe'=>$conta->classe, 'natureza'=>$conta->natureza, 'desconto'=>'N', 'codigo' => $conta->codigo, 'descricao' => $conta->descricao, 'total' => 0, 'orcado'=> 0, 'childrens'=>count($conta->children)];
            

            if (count($conta->children) > 0) {
                
                $childrens = $conta->children;
                $cont = 0;
                $ids = array();
                $child = $childrens[$cont];
                $codPai = $conta->codigo.".";

                //var_dump($valores);
                while( $child ) {

                    // Orçando = 
                    $queryOrcado = \App\Model\Financeiro\Orcamento::orderBy('valor_lancamento', 'ASC');

                    if($orderBy != null) {
                        $query = \App\Model\Cadastro\ContaContabel::orderBy($orderBy[0], $orderBy[1]);
                        // Para Lançamentos Gerenciais
                        $query2 = \App\Model\Cadastro\ContaContabel::orderBy($orderBy[0], $orderBy[1]);

                    }
                    else {
                        $query = \App\Model\Cadastro\ContaContabel::orderBy('codigo', 'ASC');
                        // Para Lançamentos Gerenciais
                        $query2 = \App\Model\Cadastro\ContaContabel::orderBy('codigo', 'ASC');
                    }

                    # code...
                    $query->leftJoin('agendamento_rateio', 'planos_contas.id', '=', 'agendamento_rateio.plano_conta_id');
                    $query->leftJoin('agendamentos', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');

                    $query2->leftJoin('agendamento_rateio', 'planos_contas.id', '=', 'agendamento_rateio.plano_conta_id');
                    $query2->leftJoin('agendamentos', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');
                    
                    $calculaCorrecao = false;
                    if(isset($where['data_lancamento']) && !empty($where['data_lancamento']) ) {
                        
                        $query->leftJoin('lancamentos_bancarios', 'agendamentos.id', '=', 'lancamentos_bancarios.agendamento_id');

                        $query->selectRaw('
                            planos_contas.codigo, 
                            planos_contas.descricao, 
                            agendamentos.correcao_financeira_id,
                            agendamentos.tipo_movimento,
                            lancamentos_bancarios.desconto_id,
                            SUM( 
                                CASE 
                                    WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "C" 
                                    THEN (lancamentos_bancarios.valor_lancamento * (agendamento_rateio.porcentagem/100)) * (-1)
                                    WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "D" 
                                    THEN (lancamentos_bancarios.valor_lancamento * (agendamento_rateio.porcentagem/100))
                                    WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "C" 
                                    THEN (lancamentos_bancarios.valor_lancamento * (agendamento_rateio.porcentagem/100)) 
                                    WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "D" 
                                    THEN (lancamentos_bancarios.valor_lancamento * (agendamento_rateio.porcentagem/100)) * (-1)
                                END
                            ) as total
                        ');//SUM( lancamentos_bancarios.valor_lancamento * (agendamento_rateio.porcentagem/100) ) as total
                        $query->groupBy('lancamentos_bancarios.agendamento_id');

                        $where['lancamentos_bancarios.deleted_at'] = NULL;

                        $calculaCorrecao = true;

                        // Orçando
                    }
                    
                    else {
                        
                        $query->selectRaw('
                            planos_contas.codigo, 
                            planos_contas.descricao, 
                            agendamentos.correcao_financeira_id,
                            agendamentos.tipo_movimento,
                            SUM( 
                                CASE 
                                    WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "C" 
                                    THEN (agendamentos.valor_titulo * (agendamento_rateio.porcentagem/100)) * (-1)
                                    WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "D" 
                                    THEN (agendamentos.valor_titulo * (agendamento_rateio.porcentagem/100))
                                    WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "C" 
                                    THEN (agendamentos.valor_titulo * (agendamento_rateio.porcentagem/100)) 
                                    WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "D" 
                                    THEN (agendamentos.valor_titulo * (agendamento_rateio.porcentagem/100)) * (-1)
                                END
                            ) as total
                        ');
                    }
                        
                        
                    $where['agendamentos.deleted_at'] = NULL;
                                        
                    $where['planos_contas.id'] = $child->id;

                    $query = getWhere($query, $where);
                    
                    // Lançamento Não Financeiros
                    if(isset($where['data_competencia']) && !empty($where['data_competencia']) ) {
                        
                        $query2->join('lancamentos_gerenciais', function ($join) {
                            $join->on('planos_contas.id', '=', 'lancamentos_gerenciais.plano_conta_credito_id')
                            ->orOn('planos_contas.id', '=', 'lancamentos_gerenciais.plano_conta_debito_id');
                        });
                        if(isset($where['data_lancamento']) && !empty($where['data_lancamento']) ) {
                            $query2->selectRaw('
                                planos_contas.codigo, 
                                planos_contas.descricao, 
                                NULL,
                                agendamentos.tipo_movimento,
                                SUM( 
                                    CASE 
                                        WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "C" 
                                        THEN (lancamentos_gerenciais.valor_lancamento) * (-1)
                                        WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "D" 
                                        THEN (lancamentos_gerenciais.valor_lancamento)
                                        WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "C" 
                                        THEN (lancamentos_gerenciais.valor_lancamento) 
                                        WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "D" 
                                        THEN (lancamentos_gerenciais.valor_lancamento) * (-1)
                                    END
                                ) as total
                            ');
                        } else {
                            $query2->selectRaw('
                                planos_contas.codigo, 
                                planos_contas.descricao, 
                                NULL,
                                agendamentos.tipo_movimento,
                                SUM( 
                                    CASE 
                                        WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "C" 
                                        THEN (lancamentos_gerenciais.valor_lancamento) * (-1)
                                        WHEN agendamentos.tipo_movimento = "PGT" AND planos_contas.natureza = "D" 
                                        THEN (lancamentos_gerenciais.valor_lancamento)
                                        WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "C" 
                                        THEN (lancamentos_gerenciais.valor_lancamento) 
                                        WHEN agendamentos.tipo_movimento = "RCT" AND planos_contas.natureza = "D" 
                                        THEN (lancamentos_gerenciais.valor_lancamento) * (-1)
                                    END
                                ) as total
                            '); //SUM( lancamentos_gerenciais.valor_lancamento ) as total
                        }
                        $where2['planos_contas.id'] = $child->id;
                        $where2['data_lancamento'] = $where['data_competencia'];
                        $query2 = getWhere($query2, $where2);
                        //echo $query2->toSql();

                    }
                    
                    if($query->count() > 0 || ((isset($where['data_competencia']) && !empty($where['data_competencia']) ) && $query2->count()) ) { // Só entra Plano de Contas de Agendamento ou Lançamentos. ####never mind
                        
                        if(isset($where['data_competencia']) && !empty($where['data_competencia']) ) {
                            $query->union($query2);                           
                        } 
                        //echo $query->toSql();
                        foreach ($query->get() as $key => $results) {
                            # code...                    

                            if(!isset($valores[$child->id])) { // valido porque inclui um plano avulso da correção acima
                                $valores[$child->id] = ['id'=> $child->id, 'classe'=>$child->classe, 'natureza'=>$child->natureza, 'desconto'=>'N', 'codigo' => $codPai.$child->codigo, 'descricao' => $child->descricao, 'total' => $results->total];
                                // calcula resultado geral
                                $valores['resultado'] = $this->calculaResultado($child, $results->total, $results->tipo_movimento, $valores['resultado']);

                            }
                            else { // Planos de Contas de Desconto ou Juros ou Nõa Relacioandos
                                    //echo "nunca entra aqui?";
                                    $valores[$child->id]['total'] += $results->total;
                                    // calcula resultado geral
                                    $valores['resultado'] = $this->calculaResultado($child, $results->total, $results->tipo_movimento, $valores['resultado']);
                            }
                       
                        }
                        
                    } else { // Planos de Contas com resultado vazio // Pode ser Vazio msm
                        
                        $valores[$child->id] = ['id'=> $child->id, 'classe'=>$child->classe, 'natureza'=>$child->natureza, 'desconto'=>'N', 'codigo' => $codPai.$child->codigo, 'descricao' => $child->descricao, 'total' => 0, 'orcado'=>0, 'childrens'=>count($child->children)];
                        
                    }

                    //Multas e Juros
                    // calculo de juros e multas por correção financeira                        
                    $queryJurosMulta = \App\Model\Financeiro\MultaJuroDesconto::whereIn('tipo', ['J', 'M', 'D'])
                                        ->whereDate('data_lancamento', '>=', $data_referencia[1][0])
                                        ->whereDate('data_lancamento', '<=', $data_referencia[1][1])
                                        ->where('plano_conta_id', "=", $child->id);
                                        
                    $queryJurosMulta->leftJoin('agendamentos', 'agendamentos.id', '=', 'juros_multas_descontos.agendamento_id');
                    $queryJurosMulta->selectRaw('
                        agendamentos.tipo_movimento,
                        tipo,
                        SUM(
                            CASE
                                WHEN tipo = "J"
                                THEN valor_lancamento
                            END
                        ) as valor_juros,
                        SUM(
                            CASE
                                WHEN tipo = "M"
                                THEN valor_lancamento
                            END
                        ) as valor_multa,
                        SUM(
                            CASE
                                WHEN tipo = "D"
                                THEN valor_lancamento
                            END
                        ) as valor_desconto
                    ');
                    //echo $queryJurosMulta->toSql();
                    foreach ($queryJurosMulta->get() as $key => $value) {
                        
                        if($value->tipo != 'D') {          
                            
                            if(isset($valores_multa[$child->id])) {
                                $valores_multa[$child->id]['valor'] += ($value->valor_juros+$value->valor_multa);
                                $valores_multa[$child->id]['tipo_movimento'] = $value->tipo_movimento;
                            }
                            else {
                                $valores_multa[$child->id]['valor'] = ($value->valor_juros+$value->valor_multa);
                                $valores_multa[$child->id]['tipo_movimento'] = $value->tipo_movimento;
                            }

                        } else {

                            if(isset($valores_desconto[$child->id])) {
                                $valores_desconto[$child->id]['valor'] += ($value->valor_desconto);
                                $valores_desconto[$child->id]['tipo_movimento'] = $value->tipo_movimento;
                            }
                            else {
                                $valores_desconto[$child->id]['valor'] = ($value->valor_desconto);
                                $valores_desconto[$child->id]['tipo_movimento'] = $value->tipo_movimento;
                            }

                        }

                    }
                    //var_dump($valores_desconto);
                    //}

                    // Orçado
                    $queryOrcado->where('plano_conta_id', '=', $child->id);
                    if(isset($where['data_competencia'])) {
                        $queryOrcado->whereDate('data_competencia', '>=', new DateTime($where['data_competencia'][1][0]))
                                    ->whereDate('data_competencia', '<=', new DateTime($where['data_competencia'][1][1]));
                    }
                    if(isset($where['data_vencimento'])) {
                        $queryOrcado->whereDate('data_vencimento', '>=', new DateTime($where['data_vencimento'][1][0]))
                                    ->whereDate('data_vencimento', '<=', new DateTime($where['data_vencimento'][1][1]));
                    }
                    $valores[$child->id]['orcado'] = $queryOrcado->sum('valor_lancamento');;
                    
                    // calcula não mexer abaixo
                    array_push($ids, $child->id);
                    
                    if( count($child->children) > 0 )  {

                        $codPai .= $child->codigo.".";

                        $child = $child->children[0];

                    } else {

                        procura:
                        if(isset($child->parent)) {
                            
                            $valores[$child->parent->id]['total'] += $valores[$child->id]['total'];
                            $valores[$child->parent->id]['orcado'] += $valores[$child->id]['orcado'];
                            $parent = $child->parent;
                            
                            //
                            if (count($parent->children) > 0) {
                                $newChild = null;
                                foreach($parent->children as $item) {
                                    if(!in_array($item->id, $ids)) {
                                        $newChild = $item;
                                        //
                                        break;
                                    }
                                }
                            } 
                        } else {
                            $cont++;
                            if(isset($childrens[$cont])) {

                                $child = $childrens[$cont];
                                $codPai = substr( $codPai, 0, strrpos($codPai, "."));
                                
                            }
                            else
                                break;
                        }

                        if($newChild != null) {
                            $child=$newChild;
                        }
                        else {

                            $child = $parent;
                            
                            $codPai = substr( $codPai, 0, strrpos($codPai, "."));
                            $codPai = substr( $codPai, 0, strrpos($codPai, ".")).".";
                                                        
                            goto procura;
                        }


                    }
                }

                $child = $conta;
            }

        }
        
        $valores = $this->agregaPlanoMultaJurosDesconto($valores, $valores_multa);
        $valores = $this->agregaPlanoMultaJurosDesconto($valores, $valores_desconto);
        
        return $valores;
    }

    private function agregaPlanoMultaJurosDesconto($valores, $multaJurosOuDesconto) {

        foreach ($valores as $key => $value) {
            
            if(isset($multaJurosOuDesconto) && array_key_exists($key, $multaJurosOuDesconto)) {
                
                $planoConta = $this->find($key);

                $soma = true;

                if( count($planoConta->descontos) > 0) {
                    
                    if($planoConta->natureza == 'C') {
                        $valores[$key]['total'] += $multaJurosOuDesconto[$key]['valor'];
                        
                    }
                    else {
                        $valores[$key]['total'] -= $multaJurosOuDesconto[$key]['valor'];
                        $soma = false;
                    }

                } elseif(count($planoConta->correcoes) > 0) {

                    if($planoConta->natureza == 'C') {
                        $valores[$key]['total'] += $multaJurosOuDesconto[$key]['valor'];
                    }
                    else {
                        $valores[$key]['total'] -= $multaJurosOuDesconto[$key]['valor'];
                        $soma = false;
                    }
                }
                
                $valores['resultado'] = $this->calculaResultado($planoConta, $multaJurosOuDesconto[$key]['valor'], $multaJurosOuDesconto[$key]['tipo_movimento'], $valores['resultado']);

                while($planoConta->parent != null) {

                    if($soma)
                        $valores[$planoConta->parent->id]['total'] += $multaJurosOuDesconto[$key]['valor'];
                    else
                        $valores[$planoConta->parent->id]['total'] -= $multaJurosOuDesconto[$key]['valor'];

                    $planoConta = $planoConta->parent;
                }

                //$valores[$key]['total'] += $multaJurosOuDesconto[$key]['valor'];
                
            }
        }

        return $valores;
    }

    private function calculaResultado($plano, $valor, $tipo_movimento, $resultado) {
        // calcula resultado
        //if($valor != 0)
        //echo $valor.'('.$tipo_movimento.'----'.$plano.')=';
        
        // ?????????????????
        //if($valor < 0)
          //  $valor = $valor * (-1);

        if( count($plano->descontos) > 0) {
            
            if($plano->natureza == 'C') {
                $resultado += $valor;
            }
            else {
                $resultado -= $valor;
            }

        } elseif( count($plano->correcoes) > 0) {
            if($plano->natureza == 'C') {
                $resultado += $valor;
            }
            else {
                $resultado -= $valor;
            }
        }
        else {

            if($tipo_movimento == 'PGT') {
                $resultado -= $valor;
                /*if($plano->natureza == 'C')
                    $resultado -= $valor;
                else
                    $resultado += $valor;*/
            }
            elseif($tipo_movimento == 'RCT') {
                $resultado += $valor;
                /*if($plano->natureza == 'C')
                    $resultado += $valor;
                else
                    $resultado -= $valor;*/
            }
        }
        
        //echo $resultado."<br>";
        return $resultado;
    }
    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $objs = $this->conta->get()->pluck( 'descricao', 'id');
        if($addEmpty)
            $objs->prepend('', '');

        return $objs;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->conta->find($id);
    }

    /**
     * @param $attributes
     *
     * @return bool|mixed
     *
     * @throws \Fully\Exceptions\Validation\ValidationException
     */
    public function create($attributes)
    {
        if ($this->isValid($attributes)) {
            
            $this->conta->fill($attributes)->save();

            return $this->conta;
        }

        throw new ValidationException('Erros ao validar dados da Conta Contábel', $this->getErrors());
    }

    /**
     * @param $attributes
     *
     * @return bool|mixed
     *
     * @throws \Fully\Exceptions\Validation\ValidationException
     */
    public function update($id, $attributes)
    {

         $this->conta = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->conta->fill($attributes)->save();

            return $this->conta;
        }

        throw new ValidationException('Erros ao validar dados da Conta Contábel', $this->getErrors());
    }
    /**
     * Get paginated pages.
     *
     * @param int  $page  Number of pages per page
     * @param int  $limit Results per page
     * @param bool $all   Show published or all
     *
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function paginate($page = 1, $limit = 1000, $all = false)
    {
  
        return \App\Model\Cadastro\ContaContabel::orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function delete($id) {

        $this->conta = $this->find($id);

        if($this->conta != null) {
            
            if( count($this->conta->descontos) > 0 ) {
                Flash::error('Impossível deletar. Plano de conta relacionado a Descontos!');
                return false;
            }
            else if( count($this->conta->rateios) > 0 ) {
                Flash::error('Impossível deletar. Plano de conta relacionado a Agendamentos!');
                return false;
            }
            else if( count($this->conta->children) > 0 ) {
                Flash::error('Impossível deletar. Plano de conta possui outro planos de contas relacionados!');
                return false;
            }
            else if( count($this->conta->correcoes) > 0 ) {
                Flash::error('Impossível deletar. Plano de conta relacionado a Correção Financeira!');
                return false;
            }

            $this->conta->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Plano de Conta', $this->getErrors());

    }
}