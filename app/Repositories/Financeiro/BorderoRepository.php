<?php
namespace App\Repositories\Financeiro;

use App\Model\Financeiro\Bordero;
use App\Model\Financeiro\LancamentoBancario;

use Input;
use DateTime;
use DateInterval;

use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class BorderoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $bordero;

    /**
     * Rules.
     *
     * @var array
     */
    protected static $rules = [
        'data_emissao' => 'required',
        'descricao' => 'required',
        'tipo_bordero' => 'required'
    ];

    /**
     * @param Company $company
     */
    public function __construct(Bordero $bordero)
    {
        $this->bordero = $bordero;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->bordero->all();
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->bordero->with('agendamentos')->find($id);
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
            
            $return = $this->bordero->fill($attributes)->save();
            
            return $return;
            
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

        $this->bordero = $this->find($id);

        if ($this->isValid($attributes)) {

            $attributes = $this->ajustaValores($attributes);
            $this->bordero->fill($attributes)->save();

            return true;
        }

        throw new ValidationException('Erros ao validar dados', $this->getErrors());
    }

    private function ajustaValores($attributes) {

        $attributes['data_emissao'] = convertDateEn($attributes['data_emissao']);
        
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
    public function paginate($page = 1, $limit = 10, $orderBy = null, $where = null)
    {
  
        $query = \App\Model\Financeiro\Bordero::orderBy($orderBy[0], $orderBy[1]);     

        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->bordero = $this->find($id);

        if($this->bordero != null) {
            
            $this->bordero->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Borderô', $this->getErrors());

    }

    public function get() {
        return $this->bordero;
    }
}