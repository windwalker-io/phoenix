<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Repository\Traits;

use Phoenix\Form\NullFiledDefinition;
use Phoenix\Form\Renderer\InputRenderer;
use Windwalker\Core\Mvc\MvcHelper;
use Windwalker\Core\Package\Resolver\FieldDefinitionResolver;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Form\Validate\ValidateResult;
use Windwalker\Ioc;

/**
 * The AbstractFormModel class.
 *
 * @since  1.0
 */
trait FormAwareRepositoryTrait
{
    /**
     * Property formRenderer.
     *
     * @var callable
     */
    protected $formRenderer = InputRenderer::class;

    /**
     * getDefaultData
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getFormDefaultData()
    {
        $item = $this->getItem();

        if ($item instanceof Entity) {
            // Use toArray() on Entity to make sure we dump data as casts.
            $item = $item->toArray(true);
        } elseif ($item instanceof DataInterface) {
            $item = $item->dump(true);
        }

        return (new Data())
            ->bind($item)
            ->bind($this['form.data'])
            ->dump(true);
    }

    /**
     * getForm
     *
     * @param string|FieldDefinitionInterface $definition
     * @param string                          $control
     * @param bool|mixed                      $loadData
     *
     * @return Form
     */
    public function getForm($definition = null, $control = null, $loadData = false)
    {
        $form = new Form($control);

        if (is_string($definition)) {
            $definition = $this->getFieldDefinition($definition);
        }

        $form->defineFormFields($definition);

        $data = [];

        if ($loadData === true) {
            $data = $this->getFormDefaultData();
        } elseif ($loadData) {
            $data = $loadData;
        }

        $form->bind($data);

        $renderer = $this->get('field.renderer', $this->formRenderer);

        if (class_exists($renderer)) {
            $form->setRenderer(new $renderer());
        }

        Ioc::getDispatcher()->triggerEvent('onModelAfterGetForm', [
            'data' => $data,
            'form' => $form,
            'model' => $this,
            'control' => $control,
            'definition' => $definition,
        ]);

        return $form;
    }

    /**
     * getFieldDefinition
     *
     * @param string $definition
     * @param string $name
     *
     * @return FieldDefinitionInterface
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    public function getFieldDefinition($definition = null, $name = null)
    {
        if (class_exists($definition) && is_subclass_of($definition, FieldDefinitionInterface::class)) {
            return new $definition();
        }

        $name = $name ?: $this->getName();

        if (!$class = FieldDefinitionResolver::create(ucfirst($name) . '\\' . ucfirst($definition))) {
            $class = sprintf(
                '%s\Form\%s\%sDefinition',
                MvcHelper::getPackageNamespace($this, 2),
                ucfirst($name),
                ucfirst($definition)
            );

            if (!class_exists($class)) {
                return new NullFiledDefinition();
            }
        }

        if (is_string($class)) {
            $class = Ioc::getContainer()->newInstance($class);
        }

        return $class;
    }

    /**
     * filter
     *
     * @param array  $data
     * @param string $formDefine
     *
     * @return array
     */
    public function prepareStore($data, $formDefine = 'edit')
    {
        $form = $this->getForm($formDefine);

        $form->bind($data);
        $form->filter();
        $form->prepareStore();

        return $form->getValues();
    }

    /**
     * validate
     *
     * @param   array $data
     * @param   Form  $form
     *
     * @return bool
     * @throws ValidateFailException
     */
    public function validate($data, Form $form = null)
    {
        $form = $form ?: $this->getForm('edit');

        $form->bind($data);

        if ($form->validate()) {
            return true;
        }

        $errors = $form->getErrors();

        $msg = [];

        foreach ($errors as $error) {
            $field = $error->getField();

            if ($error->getResult() === ValidateResult::STATUS_REQUIRED) {
                $msg[ValidateResult::STATUS_REQUIRED][] = __(
                    'phoenix.message.validation.required',
                    $field->getLabel() ?: $field->getName(false)
                );
            } elseif ($error->getResult() === ValidateResult::STATUS_FAILURE) {
                $msg[ValidateResult::STATUS_FAILURE][] = __(
                    'phoenix.message.validation.failure',
                    $field->getLabel() ?: $field->getName(false)
                );
            }
        }

        throw new ValidateFailException($msg);
    }

    /**
     * Method to get property FormRenderer
     *
     * @return  callable
     */
    public function getFormRenderer()
    {
        return $this->formRenderer;
    }

    /**
     * Method to set property formRenderer
     *
     * @param   callable $formRenderer
     *
     * @return  static  Return self to support chaining.
     */
    public function setFormRenderer($formRenderer)
    {
        $this->formRenderer = $formRenderer;

        return $this;
    }
}
