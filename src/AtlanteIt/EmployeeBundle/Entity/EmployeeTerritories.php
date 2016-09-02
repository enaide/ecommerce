<?php

namespace AtlanteIt\EmployeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployeeTerritories
 *
 * @ORM\Table(name="employee_territories")
 * @ORM\Entity(repositoryClass="AtlanteIt\EmployeeBundle\Repository\EmployeeTerritoriesRepository")
 */
class EmployeeTerritories
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
