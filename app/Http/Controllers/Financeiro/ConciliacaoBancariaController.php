<?php

namespace App\Http\Controllers\Financeiro;

use Auth;
use Input;
use Flash;
use Redirect;
use DateTime;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Repositories\Financeiro\LancamentoBancarioRepository;
use App\Repositories\Cadastro\BancoRepository;

use App\Exceptions\Validation\ValidationException;

class ConciliacaoBancariaController extends Controller
{
    //
    protected $bancoRepository;
    protected $lancamentoBancarioRepository;
    
    public function __construct(
            BancoRepository $banco,
            LancamentoBancarioRepository $lancamento

    ) {
        $this->bancoRepository = $banco;
        $this->lancamentoBancarioRepository = $lancamento;
        
        // verifica login
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attributes = Input::all();
        $data_lancamento = $banco_id = null;
        $where = null;

        //faz baixas
        if(isset($attributes['data_liquidado']) && !empty($attributes['data_liquidado'])) {
            
            $data_liquidado = convertDateEn($attributes['data_liquidado']);
            $result = false;
            // verifica se teve algum check marcado
            if(isset($attributes['lancamento_id'])) {
                $result = $this->fazerConciliacaoAgendamentos($data_liquidado, $attributes['lancamento_id']);

                if($result)
                Flash::success('Baixas de Agendamentos realizada com sucesso!');
            }

            if(isset($attributes['lancamento_baixa_id'])) {
                $result = $this->fazerConciliacaoBorderos($data_liquidado, $attributes['lancamento_baixa_id']);

                if($result)
                Flash::success('Baixas de Borderôs realizadas com sucesso!');
            }

        } else if(isset($attributes['lancamento_id']) || isset($attributes['lancamento_baixa_id'])) {
            Flash::error('Data de Liquidação não informada!');
        }


        // faz os filtros
        if(isset($attributes['banco_id']) && !empty($attributes['banco_id'])) {
            $banco_id = $attributes['banco_id'];
            $where['banco_id'] = $banco_id;
        }
        if(isset($attributes['data_lancamento']) && !empty($attributes['data_lancamento'])) {
            $data_lancamento = explode(" - ", $attributes['data_lancamento']);
            $where['data_lancamento'] = ['whereBetween', array( convertDateEn(trim($data_lancamento[0])), convertDateEn(trim($data_lancamento[1]))) ];
        }

        $lancamentos = $this->lancamentoBancarioRepository->getNaoLiquidadosAgendamentos($where);
        $borderos = $this->lancamentoBancarioRepository->getNaoLiquidadosBordero($where);

        $bancos = $this->bancoRepository->lists();

        if($data_lancamento != null)
            $data_lancamento = implode(" - ", $data_lancamento);

        return view('financeiro.conciliacao.index', [
            'lancamentos' => $lancamentos,
            'borderos' => $borderos,
            'bancos' => $bancos,
            'data_lancamento' => $data_lancamento,
            'banco_id' => $banco_id
        ]);
    }

    private function fazerConciliacaoAgendamentos($data, $lancamentos) {

        try {

            foreach ($lancamentos as $key => $value) {
                $lancamento = $this->lancamentoBancarioRepository->find($key);
                $lancamento->data_liquidacao = $data;
                $lancamento->save();
            }

            return true;
        } catch (ValidationException $e) {
            return false;
        }
    }

    private function fazerConciliacaoBorderos($data, $identificadores) {
        try {

            foreach ($identificadores as $key => $value) {
                $lancamentos = $this->lancamentoBancarioRepository->findIdenticador($key);
                foreach ($lancamentos as $lancamento) {
                    $lancamento->data_liquidacao = $data;
                    $lancamento->save();
                }
            }

            return true;
        } catch (ValidationException $e) {
            return false;
        }
    }

}
