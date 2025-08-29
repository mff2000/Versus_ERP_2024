<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Repositories\Financeiro\AgendamentoRepository;
use App\Repositories\Cadastro\BancoRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BancoRepository $bancos, AgendamentoRepository $agendamentos)
    {
        $this->agendamentoRepository = $agendamentos;
        $this->bancoRepository = $bancos;

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $fluxo['datas'] = getDatePeriodo(2);

        $fluxo['saldo'] = $this->bancoRepository->calcSaldoAt($fluxo['datas'][0][1]->toDateString());
        $fluxo['totalApagar'] = $this->agendamentoRepository->getTotalFluxoCaixa($fluxo['datas'], 'PGT');
        $fluxo['totalAreceber'] = $this->agendamentoRepository->getTotalFluxoCaixa($fluxo['datas'], 'RCT');
        $fluxo['limite'] = $this->bancoRepository->getLimiteTotalAtual();

        //var_dump($fluxo['datas']);
        //var_dump($fluxo);
        
        return view('home', compact('fluxo'));
    }
}
