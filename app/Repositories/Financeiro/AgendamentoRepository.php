<?php
namespace App\Repositories\Financeiro;

use App\Model\Financeiro\Agendamento;
use App\Model\Financeiro\Rateio;
use App\Model\Cadastro\ContaContabel;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class AgendamentoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $agendamento;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'historico' => 'required',
        'favorecido_id' => 'required',
        'valor_titulo' => 'required',
        'numero_titulo' => 'required',
        'data_competencia' => 'required',
        'data_vencimento' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Agendamento $agendamento)
    {
        $this->agendamento = $agendamento;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {
        
        if($orderBy != null)
            $query = \App\Model\Financeiro\Agendamento::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Financeiro\Agendamento::orderBy('data_vencimento', 'ASC');

        $query->selectRaw('agendamentos.*');
        $query->leftJoin('agendamento_rateio', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');
        $query->groupBy('agendamento_rateio.agendamento_id');
        // retirei o "leftjoin" porque no relatorio de agendamentos duplicava para cada rateio//
        // tem que ter o leftfoin porque o filtros por projeto e plano de conta puxa daqui
        
        $query = getWhere($query, $where);
        //echo $query->toSql();
        return $query->get();
    }

    public function getTotalFluxoCaixa($periodo, $tipo) {

        $total = null;
        
        for ($i=0; $i <= 5 ; $i++) {
            $query = \App\Model\Financeiro\Agendamento::where('tipo_movimento', '=', $tipo);

            if(isset($periodo[$i][2])) {
                $query->whereDate('data_vencimento', '>=', $periodo[$i][1]->toDateString());
                $query->whereDate('data_vencimento', '<=', $periodo[$i][2]->toDateString());
            } else {
                $query->whereDate('data_vencimento', '=', $periodo[$i][1]->toDateString());
            }

            $total[$i] = $query->sum('valor_saldo');
        }
        
        return $total;
    }

    public function getAtrasadoFluxoCaixa($hoje) {

        $total = null;
        
        $query = \App\Model\Financeiro\Agendamento::where('tipo_movimento', '=', 'RCT');
        $query->whereDate('data_vencimento', '<', $hoje);

        $total['receber'] = $query->sum('valor_saldo');
        
        $query = \App\Model\Financeiro\Agendamento::where('tipo_movimento', '=', 'PGT');
        $query->whereDate('data_vencimento', '<', $hoje);

        $total['apagar'] = $query->sum('valor_saldo');

        return $total;
    }

    public function getToFluxoCaixa_NAOUSADO($periodo, $tipo) {

        $lancamentos = null;

        if($tipo == 'PGT') {
            $natureza = 'D';
        } else {
            $natureza = 'C';
        }

        $planosConta = ContaContabel::where('classe', '=', 'A')->where('natureza', '=', $natureza)->orderBy('id', 'ASC')->get();
        
        $count = 1;
        
        foreach ($planosConta as $key => $plano) {
            
            $total = 0;
            $lancamentos[$count]['plano'] = $plano->descricao;

            for ($i=1; $i <= 5 ; $i++) { 
                $query = \App\Model\Financeiro\Agendamento::where('tipo_movimento', '=', $tipo);
                $query->leftJoin('agendamento_rateio', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');
                
                $query->where('agendamento_rateio.plano_conta_id', '=', $plano->id);

                if(!isset($periodo[$i][2])) {
                    $query->whereDate('data_vencimento', '=', $periodo[$i][1]->toDateString());
                }

                $valor = $query->sum('valor_saldo');
                
                $lancamentos[$count]['valor'][$i] = $valor;
                $total += $valor;

            }

            $lancamentos[$count]['total'] = $total;
            $count++; 
        }

        
        //var_dump($lancamentos);
        return $lancamentos;

    }

    

    /**
     * @return mixed
     */
    public function getToDreDetails($where = null, $orderBy = null)
    {
        
        if($orderBy != null)
            $query = \App\Model\Financeiro\Agendamento::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Financeiro\Agendamento::orderBy('data_vencimento', 'ASC');

        
        $query->leftJoin('agendamento_rateio', 'agendamentos.id', '=', 'agendamento_rateio.agendamento_id');
        $query->leftJoin('projetos', 'projetos.id', '=', 'agendamento_rateio.projeto_id');
        $query->leftJoin('centros_resultado', 'centros_resultado.id', '=', 'agendamento_rateio.centro_resultado_id');

        if(isset($where['data_lancamento'])) {
            $query->leftJoin('lancamentos_bancarios', 'agendamentos.id', '=', 'lancamentos_bancarios.agendamento_id');
            $query->where("lancamentos_bancarios.deleted_at", NULL);

            $query->selectRaw('
            agendamentos.id, agendamentos.data_competencia, agendamentos.data_vencimento, agendamentos.historico, agendamentos.tipo_movimento,
            lancamentos_bancarios.data_lancamento, SUM(lancamentos_bancarios.valor_lancamento) as valor_titulo,
            agendamento_rateio.porcentagem,
            projetos.descricao as projeto, centros_resultado.descricao as centro');
        } else {
            $query->selectRaw('
            agendamentos.id, agendamentos.data_competencia, agendamentos.data_vencimento, agendamentos.historico, agendamentos.valor_titulo, agendamentos.tipo_movimento,
            agendamento_rateio.porcentagem,
            projetos.descricao as projeto, centros_resultado.descricao as centro');
        }

        $query = getWhere($query, $where);
        $query->groupBy('agendamentos.id');
                //echo $query->toSql();
        return $query->get();
    }


	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->agendamento->find($id);
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
        //print_r($attributes);
        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);

            if(!isset($attributes['valor_saldo']))
                $attributes['valor_saldo'] = $attributes['valor_titulo'];
            
            //var_dump($attributes);die();
            $strDataVencimento = $attributes['data_vencimento'];
            $strDataCompetencia = $attributes['data_competencia'];

            $parcelaInicial = $attributes['parcela_inicial'];
            $quantParcelas = $attributes['quantidade_parcelas'];

            if($attributes['periodo_repeticao'] == 0)
                $parcelaFinal = $parcelaInicial;
            else
                $parcelaFinal = ($parcelaInicial+$quantParcelas)-1;

            $nParcela = 0;
            for($cont=$parcelaInicial; $cont<=$parcelaFinal; $cont++) {

                $attributes['numero_parcela'] = $cont;
                $dataCompetencia = new DateTime($strDataCompetencia);
                $dataVencimento = new DateTime($strDataVencimento);

                if($cont>$parcelaInicial && $attributes['periodo_repeticao'] > 0) {

                    if($attributes['tipo_competencia'] == 1) {

                        $attributes['data_competencia'] =  $dataCompetencia->add(new DateInterval('P0D'));
                        
                        switch ($attributes['periodo_repeticao']) {
                            case 1: # semanalmente
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(7*$nParcela).'D'));
                                break;
                            case 2: # quizenalmente
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(15*$nParcela).'D'));
                                break;
                             case 3: # mensalmente
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(1*$nParcela).'M'));
                                break;
                            case 4: # bisemestral                            
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(2*$nParcela).'M'));
                                break;
                            case 5: # trisemestral
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(7*$nParcela).'M'));
                                break;
                            case 6: # semestral
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(6*$nParcela).'M'));
                                break;
                            case 7: # anual
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(1*$nParcela).'Y'));
                                break;
                            default:
                                # code...
                                break;
                        }

                    } else {
                        switch ($attributes['periodo_repeticao']) {
                            case 1: # semanalmente
                                $attributes['data_competencia'] = $dataCompetencia->add(new DateInterval('P'.(7*$nParcela).'D'));
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(7*$nParcela).'D'));
                                break;
                            case 2: # quizenalmente
                                $attributes['data_competencia'] = $dataCompetencia->add(new DateInterval('P'.(15*$nParcela).'D'));
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(15*$nParcela).'D'));
                                break;
                             case 3: # mensalmente
                                $attributes['data_competencia'] = $dataCompetencia->add(new DateInterval('P'.(1*$nParcela).'M'));
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(1*$nParcela).'M'));
                                break;
                            case 4: # bisemestral
                                $attributes['data_competencia'] = $dataCompetencia->add(new DateInterval('P'.(2*$nParcela).'M'));
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(2*$nParcela).'M'));
                                break;
                            case 5: # trisemestral
                                $attributes['data_competencia'] = $dataCompetencia->add(new DateInterval('P'.(3*$nParcela).'M'));
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(3*$nParcela).'M'));
                                break;
                            case 6: # semestral
                                $attributes['data_competencia'] = $dataCompetencia->add(new DateInterval('P'.(6*$nParcela).'M'));
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(6*$nParcela).'M'));
                                break;
                            case 7: # anual
                                $attributes['data_competencia'] = $dataCompetencia->add(new DateInterval('P'.(1*$nParcela).'Y'));
                                $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P'.(1*$nParcela).'Y'));
                                break;
                            default:
                                # code...
                                break;
                        }
                    }

                } else {
                    $attributes['data_competencia'] =  $dataCompetencia->add(new DateInterval('P0D'));
                    $attributes['data_vencimento'] = $dataVencimento->add(new DateInterval('P0D'));
                }

                $nParcela++;
                
                $agendamento = new Agendamento();
                $agendamento->fill($attributes)->save();
                $this->saveRateio($attributes, $agendamento);

            }

            return $agendamento;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
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

        $this->agendamento = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);

            // define o valor do saldo ao atualizar o valor do titulo;
            $attributes['valor_saldo'] = $attributes['valor_titulo'];
            
            $this->agendamento->fill($attributes)->save();

            if($this->agendamento->valor_titulo==$this->agendamento->valor_saldo)
                $this->saveRateio($attributes, $this->agendamento);

            return $this->agendamento;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function saveRateio($attributes, $agendamento) {

        $indexs = explode(",", $attributes['tblAppendGrid_rowOrder']);
        
        for ($row=0; $row<count($indexs); $row++) {
            
            $idRateio = $attributes['tblAppendGrid_RecordId_'.$indexs[$row]];
            $rateio = new Rateio();

            if($idRateio>0)
                $rateio = $rateio->find($idRateio);

            $rateio->agendamento_id = $agendamento->id;
            $rateio->plano_conta_id = $attributes['tblAppendGrid_plano_conta_id_'.$indexs[$row]];
            $rateio->centro_resultado_id = $attributes['tblAppendGrid_centro_resultado_id_'.$indexs[$row]];
            $rateio->projeto_id = $attributes['tblAppendGrid_projeto_id_'.$indexs[$row]];
            $rateio->porcentagem = $attributes['tblAppendGrid_rateio_porcentagem_'.$indexs[$row]];
            $rateio->valor = decimalFormat($attributes['tblAppendGrid_rateio_valor_'.$indexs[$row]]);
            $rateio->ordem = $indexs[$row];
            
            $rateio->save();
        }


    }

    private function ajustaValores($attributes) {
        
        $attributes['valor_titulo'] = decimalFormat($attributes['valor_titulo']);
        
        if(isset($attributes['valor_saldo']))
            $attributes['valor_saldo'] = decimalFormat($attributes['valor_saldo']);
        
        $attributes['data_competencia'] = convertDateEn($attributes['data_competencia']);
        $attributes['data_vencimento'] = convertDateEn($attributes['data_vencimento']);
        
        return $attributes;
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
    public function paginate($page = 1, $limit = 50, $orderBy = null, $where = null)
    {
  
        $query = \App\Model\Financeiro\Agendamento::orderBy($orderBy[0], $orderBy[1]);
        $query->join('favorecidos', function ($join) {
            $join->on('favorecidos.id', '=', 'favorecido_id');
        })->select('agendamentos.*', 'favorecidos.nome_empresarial');

        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);
    }

    public function delete($id) {
        
        $this->agendamento = $this->find($id);

        if($this->agendamento != null) {
            
            foreach ($this->agendamento->rateios as $rateio) {
                $rateio->delete();
            }
            $this->agendamento->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Agendamento', $this->getErrors());

    }

    public function updateSaldo($id, $value) {
        
        $this->agendamento = $this->find($id);

        if($this->agendamento != null) {

            $this->agendamento->valor_saldo = $value;
            $this->agendamento->save();

            return $this->agendamento;
        }
        throw new ValidationException('Erros ao atualizar Agendamento', $this->getErrors());

    }
}