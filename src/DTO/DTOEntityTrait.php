<?php

namespace App\DTO;

use Doctrine\ORM\EntityManagerInterface;

trait DTOEntityTrait
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * DTOEntityTrait constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Рекурсивно связывает DTO с сущностями Doctrine.
     * Или наоборот связывает Сущности из Doctrine с DTO.
     *
     * @param $bind (DTO|Entity) - Объект который нужно заполнить данными
     * @param $entity (DTO|Entity) - Данные для заполнения
     * @return mixed
     * @throws
     */
    public function bind($bind, $entity)
    {
        $entityClass = new \ReflectionClass($entity);
        $bindClass = new \ReflectionClass($bind);

        foreach ($entityClass->getMethods() as $method) {
            if (strpos($method->name, 'set') === 0) {
                $getter = 'get'.substr($method->name, 3);

                if (method_exists($bind, $method->name)) {
                    $bindType = $bindClass->getMethod($method->getName())->getParameters()[0]->getType();
                    $entityType = $method->getParameters()[0]->getType();

                    /**
                     * Если методы в обоих классах принимают сущности из App\...
                     * И в DTO в качестве сущности используется другое DTO, а не сущность Doctrine
                     * Вызываем метод bind() повторно.
                     */
                    if (($bindType && $entityType)
                        && ($bindType->getName() !== $entityType->getName())
                        && (strpos($entityType->getName(), 'App') === 0)
                        && (strpos($bindTypeClass = $bindType->getName(), 'App') === 0)
                    ) {
                        $child = $this->bind(new $bindTypeClass, $entityClass->getMethod($getter)->invoke($entity));
                        $bind->{$method->name}($child);
                    } else {
                        /**
                         * Если в Bind объекте set метод принимает int, а в Entity объекте
                         * set метод принимает объект - значит нужно трансформировать объект в int.
                         * Сделано для того, чтобы избежать использование EntityType в формах.
                         */
                        if (($bindType && $entityType)
                            && ($bindType->getName() === 'int')
                            && (strpos($entityType->getName(), 'App') === 0)
                        ) {
                            $bind->{$method->name}($entityClass->getMethod($getter)->invoke($entity)->getId());
                        }
                        /**
                         * Обратная трансформация.
                         * Если Bind объект в set метода принимает объект, а в Entity объекте
                         * set метод принимает int - значит нужно трансформировать int в объект.
                         * Это условие отрабатывает при сохранении в БД.
                         */
                        elseif (($bindType && $entityType)
                            && ($entityType->getName() === 'int')
                            && (strpos($bindType->getName(), 'App') === 0)
                        ) {
                            $entity = $this->em->find(
                                $bindType->getName(),
                                $entityClass->getMethod($getter)->invoke($entity)
                            );
                            $bind->{$method->name}($entity);
                        } else {
                            $bind->{$method->name}($entityClass->getMethod($getter)->invoke($entity));
                        }
                    }
                }
            }
        }

        return $bind;
    }
}
