<?php
/**
 * Created by PhpStorm.
 * User: luis
 * Date: 27/03/18
 * Time: 17:16
 */

namespace AppBundle\Services;


/**
 * Clase para centralizar la normalización de las estadisticas y los gráficos
 * 
 * @category Utiles
 * @package  CutomerControl
 * @author   Luis Riego <luisriego@hotmail class="com">
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 */
class Stats
{
    /**
     * Convierte el Array dado a JSON
     *
     * @param array $arrays Array a normalizar
     *
     * @return String
     */
    public function fazerJSON(Array $arrays)
    {
        $valores = $this->_normalizarArray($arrays);

        return json_encode($valores);
    }

    /**
     * La idea de esta función es normalizar un array para que se pueda mostrar
     * como gráfico
     *
     * @param array $arrays Array a normalizar
     *
     * @return array
     */
    private function _normalizarArray(Array $arrays)
    {
        $valores = [0];
        for ($i = 2; $i <= 12; $i++) {
            array_push($valores, 0);
        }
        foreach ($arrays as $array) {
            $valor = [$array['mes']-1 => intval($array['qtd'])];
            $valores = array_replace($valores, $valor);
        }

        return $valores;
    }

    public function average(Array $arrays)
    {
        $average = [];
        foreach ($arrays as $array) {
            $valor = [$array['mes']-1 => intval($array['qtd'])];
            $average = array_replace($average, $valor);
        }
        if (count($average) !== 0) {
            $average = array_sum($average) / count($average);
        } else {
            $average = 0;
        }
        
        return $average;
    }
}