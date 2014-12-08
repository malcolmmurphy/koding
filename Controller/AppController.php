<?php
/**
* Application level Controller
*
* This file is application-wide controller file. You can put all
* application-wide controller-related methods here.
*
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link          http://cakephp.org CakePHP(tm) Project
* @package       app.Controller
* @since         CakePHP(tm) v 0.2.9
* @license       http://www.opensource.org/licenses/mit-license.php MIT License
*/

App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $uses = array('Temperature', 'SeaLevel', 'Co2');

    public function index()
    {
        $vitalsData = array();
        $vitalsData['temperature'] = $this->Temperature->load();
        $vitalsData['seaLevel'] = $this->SeaLevel->load(); // mm per year
        $vitalsData['co2'] = $this->Co2->load();

        $this->set(compact('vitalsData'));
    }
    
    public function update_data($modelName)
    {

        if (!in_array(($modelName = Inflector::camelize($modelName)), App::objects('model'))) {
            throw new NotImplementedException("Model '{$modelName}' does not exist");
        }
        $this->loadModel($modelName);
        if (!method_exists($this->{$modelName}, 'update')) {
            throw new NotImplementedException("Model '{$modelName}' does not have an update method");
        }
        if ($this->{$modelName}->update()) {
            die('Updated');
        } else {
            throw new InternalErrorException('Unable to update annual mean temperature data');
        }
    }
    
}
