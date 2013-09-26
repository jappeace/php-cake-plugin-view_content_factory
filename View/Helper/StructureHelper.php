<?php

/*
 *  See jappieklooster.nl/license for more information about the licensing
 */

/**
 * CakePHP StructureHelper, I decided to move this logic to a helper because its was getting to
 * big to reside in a single element file. Besides this code is way to complex for a mere view.
 * @author jappie
 */
class StructureHelper extends ViewContentFactoryAppHelper {

    /**
     * container for values from earlier builds.
     * for example when you want to edit an existing structure, this var
     * contains the values from that structure
     * @var type 
     */
    private $values = array();

    /*
     *  realy does not matter wich char aslong as jquery eats it
     */
    const CSS_ESCAPE_CHAR = '_';

    public $helpers = array('Js' => array('Jquery'), 'Form', 'Html');

    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
    }

    /**
     * To fill the form with existing information use this function.
     * It is the paths array variable only one level deeper with the information.
     * @param type $newValues
     */
    public function setValues($newValues) {
        $this->values = $newValues;
    }

    /**
     * Renders a menu if necisisary (when brackets are detected in the name of a form its necisary.)
     * @param type $from
     * @param type $cssEscapedPath
     * @param type $view
     * @return type
     */
    public function createMenu(&$from, $path) {
        return new Menu($from, $path, $this);
    }
    
    /**
     * core function of this helper. It generates the form for the recursive structure and returns
     * it as a string.
     * @param type $array is used as a structure to base the form upon
     * @param type $path is used to make every input unique
     * @return type
     */
    public function input($array, $path) {
        $return = '<ul>';

        foreach ($array as $key => $element) {

            // strip of : from key
            $options = null;
            if (($place = stripos($key, ':')) !== false) {
                $StringedOptions = substr($key, $place + 1);
                $key = substr($key, 0, $place); // key becomes a name later on
                $options = explode('|', $StringedOptions);
            }

            $menu = $this->createMenu($key, $path);

            if (!is_array($element)) {
                
                if (!$this->parseValues($menu, 
                            function($nr) use (&$return, &$menu, $options) {
                                $menu->setNumber($nr);
                                $return .= $this->listItem(
                                    $menu, 
                                    $options, 
                                    $this->getValue($menu->renderPathNumbered())
                                );
                            }
                        )
                   )
                {                
                    $return .= $this->listItem($menu, $options, $this->getValue($menu->renderPath()));
                }
            } else {
                
                if (!$this->parseValues($menu, 
                            function($nr) use (&$return, &$menu, $element) {
                                $menu->setNumber($nr);
                                $return .= '</ul><ul>' . $this->unordedList($element, $menu);
                            }
                        )
                   )
                {
                    $return .= $this->unordedList($element, $menu);
                }
            }
        }
        return $return . '</ul>';
    }

    /**
     * the values are in a nested array, the path can be used to 'select' a single value
     * @param type $path
     */
    private function getValue($path) {
        $steps = explode('.', $path);
        $stepsSize = count($steps);
        $result = $this->values;
        for ($i = 2; $i < $stepsSize; $i++) {
            if (!isset($result[$steps[$i]])) {
                return false;
            }
            $result = $result[$steps[$i]];
        }
        return $result;
    }

    /**
     * the eol final step in the input function
     * @param type $menu
     * @param type $options
     * @param string $value
     * @return type
     */
    private function listItem($menu, $options, $value = '') {
        if (is_array($value)) {
            $value = '';
        }
        $elementOptions = array(
            'label' => $menu->getElement() . ' ' . $menu->getButtons(),
            'value' => $value
        );

        if ($options !== null) {
            $elementOptions['options'] = $options;
        }

        // key and element are in this case the same
        return
                $menu->renderLi() .
                $this->Form->input(
                        $menu->renderPathNumbered(), $elementOptions
                ) .
                '</li>';
    }

    /**
     * the recursive final step in the input function
     * @param type $array
     * @param type $menu
     * @return type
     */
    private function unordedList($array, $menu) {
        return  $menu->renderLi() .
                $menu->getElement() . $menu->getButtons() .
                $this->input(
                        $array, $menu->renderPathNumbered(), $menu
                )
                . '</li>';
    }

    /**
     * finds and retrieves values from the created form and puts them 
     * in the calback
     * @param type $menu
     * @param type $callback 2 args calback
     * @return boolean values are parsed?
     */
    private function parseValues($menu, $callback) {
        if (!$menu->isRendered()) {
            return false;
        }

        $values = $this->getValue($menu->renderPath());
        if ($values === false) {
            return false;
        }
        foreach ($values as $key => $value) {
            $callback($key);
        }
        return true;
    }

}

/**
 * a helper helper (yes heplerception) class.
 * contains some vars wich have to do with the anonymous form/array uniqueness. 
 * Without making them huge.
 */
class Menu {

    private $buttons;
    private $number;
    private $li;
    private $creator;
    private $rendered = false;
    private $element;
    private $path;

    public function Menu(&$from, $path, $creator) {
        $this->creator = $creator;
        $cssEscapedPath = str_replace('.', StructureHelper::CSS_ESCAPE_CHAR, $path);
        $nr = -1;
        $menu = '';
        if (stripos($from, '[]') !== false) {
            $this->rendered = true;
            $from = str_replace('[]', '', $from);
            $nr = 0;

            $menu .= $creator->Form->button(
                'Add', 
                array(
                    'class' => 'form-grow',
                    'type' => 'button',
                    'value' => $cssEscapedPath . StructureHelper::CSS_ESCAPE_CHAR . $from
                )
            );
            $menu .= $creator->Form->button(
                'Remove', 
                array(
                    'class' => 'form-shrink',
                    'type' => 'button',
                    'value' => $cssEscapedPath . StructureHelper::CSS_ESCAPE_CHAR . $from
                )
            );
            // allows extra styling
            $menu = $creator->Html->tag('span', $menu, array('class' => 'form-edit-menu'));
        }

        $this->buttons = $menu;
        $this->number = $nr;
        $this->li = '<li class="form-element-' .
                $cssEscapedPath . StructureHelper::CSS_ESCAPE_CHAR . $from . '">';
        $this->element = $from;
        $this->path = $path;
    }

    public function getNumberString() {
        if ($this->number >= 0) {
            $return = '.' . $this->number;
            $this->number++;
            return $return;
        } else {
            return '';
        }
    }

    public function getButtons() {
        return $this->buttons;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($value) {
        $this->number = $value;
    }

    public function renderLi() {
        return $this->li;
    }

    public function isRendered() {
        return $this->rendered;
    }

    public function getElement() {
        return $this->element;
    }

    public function getPath() {
        return $this->path;
    }

    public function renderPath() {
        return $this->getPath() . '.' . $this->getElement();
    }

    public function renderPathNumbered() {
        return $this->renderPath() . $this->getNumberString();
    }

}