<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Mapping;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\LogicException;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\ConstraintMapperInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ExactMaxLengthRule implements ConstraintMapperInterface
{
    const RULE_NAME = 'maxlength';

    /**
     * {@inheritdoc}
     */
    public function resolve(Constraint $constraint, FormInterface $form, RuleCollection $collection)
    {
        /** @var \Symfony\Component\Validator\Constraints\Length $constraint */
        if (!$this->supports($constraint, $form)) {
            throw new LogicException();
        }

        $collection->set(
            self::RULE_NAME,
            new ConstraintRule(
                self::RULE_NAME,
                $constraint->max,
                new RuleMessage($constraint->exactMessage, array('{{ limit }}' => $constraint->max), (int) $constraint->max),
                $constraint->groups
            )
        );
    }

    public function supports(Constraint $constraint, FormInterface $form)
    {
        /** @var Length $constraint */
        if (get_class($constraint) === Length::class && $constraint->min == $constraint->max) {
            return true;
        }

        return false;
    }
}
