<?php
namespace App\Repositories\Cadastro;

use App\Model\Cadastro\Favorecido;
use App\Model\Cadastro\FavorecidoObs;

use Auth;
use Input;
use DateTime;
use App\Repositories\RepositoryAbstract;
use App\Repositories\RepositoryInterface;
use App\Exceptions\Validation\ValidationException;

class FavorecidoRepository extends RepositoryAbstract implements RepositoryInterface 
{

	/**
     * @var \Company
     */
    protected $favorecido;

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
    public function __construct(Favorecido $favorecido)
    {
        $this->favorecido = $favorecido;
    }

    /**
     * @return mixed
     */
    public function all($where = null, $orderBy = null)
    {   
        if($orderBy != null)
            $query = \App\Model\Cadastro\Favorecido::orderBy($orderBy[0], $orderBy[1]);
        else
            $query = \App\Model\Cadastro\Favorecido::orderBy('nome_empresarial', 'ASC');

        $query = getWhere($query, $where);
                
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function lists($addEmpty = true)
    {
        $favorecidos =  $this->favorecido->get()->pluck('nome_fantasia', 'id');
        if($addEmpty)
            $favorecidos->prepend('', '');
        return $favorecidos;
    }

	/**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->favorecido->find($id);
    }

    /**
     * @param Null
     *
     * @return mixed
     */
    public function first()
    {
        return $this->favorecido->first();
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

            $this->data_cadastro = new DateTime();
            $this->favorecido->fill($attributes)->save();
            $this->saveHasMany($attributes);
            $this->saveObs($attributes);
            return true;
        }

        throw new ValidationException('Erros ao validar dados do Favorecido', $this->getErrors());
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

         $this->favorecido = $this->find($id);

        if ($this->isValid($attributes)) {
            $this->favorecido->fill($attributes)->save();
            $this->saveHasMany($attributes);
            $this->saveObs($attributes);
            return true;
        }

        throw new ValidationException('Erros ao validar dados da Favorecido', $this->getErrors());
    }

    public function saveHasMany($attributes) {
        
        if(isset($attributes['condicao_pagamento_id'])) {
            $this->favorecido->condicoes_pagamento()->detach();
            foreach ($attributes['condicao_pagamento_id'] as $key => $value) {
                $this->favorecido->condicoes_pagamento()->attach($value);
            }
        }

        if(isset($attributes['vendedor_id'])) {
            $this->favorecido->vendedores()->detach();
            foreach ($attributes['vendedor_id'] as $key => $value) {
                $this->favorecido->vendedores()->attach($key, ['comissao'=> decimalFormat($attributes['vendedor_comissao'][$key]) ]);
            }
        }

        if(isset($attributes['produto'])) {
            $this->favorecido->descontos()->detach();
            foreach ($attributes['produto'] as $key => $value) {
                $this->favorecido->descontos()->attach($key, ['percentual'=> decimalFormat($value), 'tabela_preco_id' => $attributes['tabela_preco_id'] ]);
            }
        }

    }

    public function saveObs($attributes) {
        if(!empty($attributes['obs'])) {
            unset($attributes['id']); // retira o id para não auto-fill;
            $attributes['favorecido_id'] = $this->favorecido->id;
            $attributes['usuario'] = Auth::user()->name;
            $obs = new FavorecidoObs();
            $obs->fill($attributes)->save();
        }
    }

    public function deleteObs($id) {
        $obs = new FavorecidoObs();
        $obs->find($id)->delete();
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
  
        $query = \App\Model\Cadastro\Favorecido::orderBy($orderBy[0], $orderBy[1]);     

        $query = getWhere($query, $where);
        
        //echo $query->toSql();
        return $query->paginate($limit);

    }

    public function delete($id) {
        
        $this->favorecido = $this->find($id);

        if($this->favorecido != null) {
            
            $this->favorecido->delete();

            return true;
        }
        throw new ValidationException('Erros ao deletar Favorecido', $this->getErrors());

    }

}