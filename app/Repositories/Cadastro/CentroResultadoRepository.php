<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\CentroResultado;

use Input;
use DateTime;
use Flash;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class CentroResultadoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $centro;

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
    public function __construct(CentroResultado $centro)
    {
        $this->centro = $centro;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {
        if($orderBy != null)
            $query = \App\Model\Cadastro\CentroResultado::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Cadastro\CentroResultado::orderBy('codigo', 'ASC');

        $query = getWhere($query, $where);
                
        return $query->get();
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->centro->find($id);
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
            
            $this->centro->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Centro de Resultado', $this->getErrors());
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

         $this->centro = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->centro->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Centro de Resultado', $this->getErrors());
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
  
        return \App\Model\Cadastro\CentroResultado::orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function delete($id) {

        $this->centro = $this->find($id);

        if($this->centro != null) {
            
            if( count($this->centro->rateios) > 0 ) {
                Flash::error('Impossível deletar. Centro de Resultado relacionado a Agendamentos!');
                return false;
            }
            else if( count($this->centro->children) > 0 ) {
                Flash::error('Impossível deletar. Centro de Resultado possui outros centros de resultado relacionados!');
                return false;
            }

            $this->centro->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Centro de Resultado', $this->getErrors());

    }
}