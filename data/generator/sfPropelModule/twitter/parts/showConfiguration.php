  protected function getConfig()
  {
    $configuration = parent::getConfig();
    $configuration['show'] = $this->getFieldsShow();

    return $configuration;
  }

  protected function compile()
  {
    parent::compile();

    $config = $this->getConfig();

    // add configuration for the show view
    $this->configuration['show'] = array( 'fields'         => array(),
                                          'title'          => $this->getShowTitle(),
                                          'actions'        => $this->getShowActions(),
                                        ) ;

    foreach ($this->getShowDisplay() as $name => $fields) {
      if (is_array($fields)) { //Fieldset
        foreach ($fields as $fieldName) {
          list($field, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($fieldName);
          $field = new sfModelGeneratorConfigurationField($field, array_merge(
            array('type' => 'Text', 'label' => sfInflector::humanize(sfInflector::underscore($field))),
            isset($config['default'][$field]) ? $config['default'][$field] : array(),
            isset($config['show'][$field]) ? $config['show'][$field] : array(),
            array('flag' => $flag)
          ));

          $field->setFlag($flag);
          $this->configuration['show']['fields'][$fieldName]  = $field;
          $this->configuration['show']['display'][$fieldName] = $field;
        }
      } else {
        $fieldName = $fields;

        list($field, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($fieldName);
        $field = new sfModelGeneratorConfigurationField($field, array_merge(
          array('type' => 'Text', 'label' => sfInflector::humanize(sfInflector::underscore($field))),
          isset($config['default'][$field]) ? $config['default'][$field] : array(),
          isset($config['show'][$field]) ? $config['show'][$field] : array(),
          array('flag' => $flag)
        ));

        $field->setFlag($flag);
        $this->configuration['show']['fields'][$fieldName]  = $field;
        $this->configuration['show']['display'][$fieldName] = $field;
      }
    }

    foreach ($this->configuration['show']['actions'] as $action => $parameters) {
      $this->configuration['show']['actions'][$action] = $this->fixActionParameters($action, $parameters);
    }

    $this->parseVariables('show', 'title');
  }
