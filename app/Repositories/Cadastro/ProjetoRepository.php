<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Projeto;

use Input;
use DateTime;
use Flash;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class ProjetoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $projeto;

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
    public function __construct(Projeto $projeto)
    {
        $this->projeto = $projeto;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {
        if($orderBy != null)
            $query = \App\Model\Cadastro\Projeto::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Cadastro\Projeto::orderBy('codigo', 'ASC');

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
        return $this->projeto->find($id);
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
            
            $this->projeto->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Projeto', $this->getErrors());
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

         $this->projeto = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->projeto->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados do Projeto', $this->getErrors());
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
    public function paginate($page = 1, $limit = 10, $all = false)
    {
  
        return \App\Model\Cadastro\Projeto::orderBy('created_at', 'DESC')->where('deleted_at', null)->paginate($limit);
    }

    public function delete($id) {

        $this->projeto = $this->find($id);

        if($this->projeto != null) {
            
            if( count($this->projeto->rateios) > 0 ) {
                Flash::error('Impossível deletar. Projeto relacionado a Agendamentos!');
                return false;
            }
            else if( count($this->projeto->children) > 0 ) {
                Flash::error('Impossível deletar. Projeto possui outros projetos relacionados!');
                return false;
            }

            $this->projeto->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Projeto', $this->getErrors());

    }
}