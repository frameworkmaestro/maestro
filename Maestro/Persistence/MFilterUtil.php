<?php

/**
 * Essa classe tem o objetivo de simplificar a implementação dos métodos listByFilter, presentes nos models.
 *
 * Utilização:
 *
 * Estenda essa classe CriteriaFilter. Na subclasse, para cada propriedade no $filter implemente um método
 * byNomePropriedade($valor, $criteria) que vai criar o filtro da maneira apropriada.
 *
 * No model, instancie a classe que você criou e use o método applyFilters($filter, $criteria). $criteria será
 * "configurada" de acordo com as propriedades de $filter sem a necessidade de vários condicionais e sem poluir o model.
 *
 * Observações:
 *
 * 1) Recomenda-se o uso de alguma padronização para usar essa classe. A sugestão é criar uma pasta filter dentro
 * da pasta model e criar classes com nome FilterModel.
 *
 *   Ex: FilterUsuario, FilterEmpenho, FilterDocumento
 */

namespace Maestro\Persistence;

use Maestro\Persistence\Criteria\RetrieveCriteria;

abstract class MFilterUtil {

    private $appliedFilters = [];
    private $enableShowAll = true;

    /**
     * @return array
     */
    protected function getAppliedFilters() {
        return $this->appliedFilters;
    }

    private static $instance;

    private function __construct() {}

    /**
     * @return static
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    /**
     * @param stdClass $filter
     * @param RetrieveCriteria $criteria
     * @return RetrieveCriteria
     * @throws Exception
     */
    public final function applyFilters($filter, $criteria) {
        $this->doBefore($filter, $criteria);
        $this->process($filter,$criteria);
        $this->doAfter($filter, $criteria);

        if (!$this->hasFilters() && !$this->enableShowAll) {
            $criteria = $this->doDefault($criteria);

            if (!$criteria instanceof RetrieveCriteria) {
                throw new \Exception("A função doDefault deve retornar uma RetrieveCriteria!");
            }
        }

        return $criteria;
    }

    private function hasFilters() {
        return count($this->appliedFilters) > 0;
    }

    private function process($filter, $criteria) {
        if (!$filter) {
            return;
        }

        $filters = get_object_vars($filter);
        foreach ($filters as $filter => $value) {
            $function = 'by' . ucfirst($filter);
            if ($this->notNull($value) && method_exists($this, $function)) {
                $this->appliedFilters[$filter] = $value;
                $this->$function($value, $criteria);
            }
        }
    }

    private function notNull($value) {
        return ($value !== null && $value !== '');
    }

    protected function doDefault($criteria) {
        return $criteria->where("1 = 0");
    }

    /**
     * Define o comportamento do componente quando nenhum filtro puder ser aplicado.
     *
     * @param boolean $switch  Se true nenhum valor será exibido. Se false serão exibidos todos os registros.
     * @return $this Interface fluente
     */
    public function showAllWhenNoFilter($switch) {
        $this->enableShowAll = $switch;
        return $this;
    }

    /**
     * Formato padrão para filtros simples que usam like.
     * @param $value
     * @param $parameterName
     * @param RetrieveCriteria $criteria
     */
    protected final function byDefaultLike($parameterName, $value, RetrieveCriteria $criteria) {
        $bind = $this->removePontos($parameterName);
        $criteria->where("upper($parameterName) like upper(:$bind)")
            ->addParameter($bind, "%$value%");
    }

    /**
     * Formato padrão para filtros simples que usam a igualdade.
     * @param $value
     * @param $parameterName
     * @param RetrieveCriteria $criteria
     */
    protected final function byDefaultEqual($parameterName, $value, RetrieveCriteria $criteria) {
        $this->doDefaultBind($parameterName, $value, '=', $criteria);
    }

    /**
     * Formato padrão para filtros simples que usam o operador 'menor' <
     * @param $value
     * @param $parameterName
     * @param RetrieveCriteria $criteria
     */
    protected final function byDefaultLessThan($parameterName, $value, RetrieveCriteria $criteria)
    {
        $this->doDefaultBind($parameterName, $value, '<', $criteria);
    }

    /**
     * Formato padrão para filtros simples que usam o operador 'menor ou igual' <=
     * @param $value
     * @param $parameterName
     * @param RetrieveCriteria $criteria
     */
    protected final function byDefaultLessThanOrEqualTo($parameterName, $value, RetrieveCriteria $criteria)
    {
        $this->doDefaultBind($parameterName, $value, '<=', $criteria);
    }

    /**
     * Formato padrão para filtros simples que usam o operador 'maior' >
     * @param $value
     * @param $parameterName
     * @param RetrieveCriteria $criteria
     */
    protected final function byDefaultMoreThan($parameterName, $value, RetrieveCriteria $criteria)
    {
        $this->doDefaultBind($parameterName, $value, '>', $criteria);
    }

    /**
     * Formato padrão para filtros simples que usam o operador 'maior ou igual' >=
     * @param $value
     * @param $parameterName
     * @param RetrieveCriteria $criteria
     */
    protected final function byDefaultMoreThanOrEqualTo($parameterName, $value, RetrieveCriteria $criteria)
    {
        $this->doDefaultBind($parameterName, $value, '>=', $criteria);
    }


    protected function doBefore($filter, $criteria) {}
    protected function doAfter($filter, $criteria) {}


    private function removePontos($parameter) {
        return str_replace('.', '', $parameter);
    }

    /**
     * @param $parameterName
     * @param $value
     * @param $operator
     * @param RetrieveCriteria $criteria
     */
    protected function doDefaultBind($parameterName, $value, $operator, RetrieveCriteria $criteria)
    {
        $bind = $this->removePontos($parameterName);
        $criteria->where("$parameterName $operator :$bind")
            ->addParameter($bind, $value);
    }
}