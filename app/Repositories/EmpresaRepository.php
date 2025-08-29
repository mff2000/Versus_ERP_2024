<?php
namespace App\Repositories;

use App\Model\Empresa;

use Input;

use App\Repositories\RepositoryAbstract;
use App\Exceptions\Validation\ValidationException;

class EmpresaRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $empresa;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'nome_empresarial' => 'required',
        'nome_fantasia' => 'required',
        'cnpj' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->empresa->where('lang', $this->getLang())->get();
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->empresa->find($id)->first();
    }

    /**
     * @param Null
     *
     * @return mixed
     */
    public function first()
    {
        return $this->empresa->where('id', '>', 0)->first();
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
            $this->empresa->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da empresa', $this->getErrors());
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

        $this->empresa = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->empresa->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados da empresa', $this->getErrors());
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
    public function paginate($empresa = 1, $limit = 10, $all = false)
    {
        
    }

}