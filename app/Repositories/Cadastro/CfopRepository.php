<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Cfop;

use Input;
use DateTime;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CfopRepository extends RepositoryAbstract
{

	/**
     * @var \Company
     */
    protected $cfop;

    /**
     * @param Company $company
     */
    public function __construct(Cfop $cfop)
    {
        $this->cfop = $cfop;
    }


    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $cfops = $this->cfop->selectRaw( "id, CONCAT(codigo, ' - ', descricao) AS descricao ")->get()->pluck( 'descricao', 'id');
        if($addEmpty)
            $cfops->prepend('', '');

        return $cfops;
    }


	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->cfop->find($id);
    }

    
}