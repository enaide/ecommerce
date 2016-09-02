<?php

namespace AtlanteIt\EmployeeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @UniqueEntity(fields={"firstname", "email"})
 * @ORM\Entity(repositoryClass="AtlanteIt\EmployeeBundle\Repository\EmployeeRepository")
 */
class Employee implements AdvancedUserInterface, \Serializable
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
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="lastname", type="string", length=20, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="firstname", type="string", length=10, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=30, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="titleofcourtesy", type="string", length=25, nullable=true)
     */
    private $titleofcourtesy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hiredate", type="datetime", nullable=true)
     */
    private $hiredate;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=60, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=15, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=15, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="postalcode", type="string", length=10, nullable=true)
     */
    private $postalcode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=15, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="homephone", type="string", length=24, nullable=true)
     */
    private $homephone;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=4, nullable=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", length=16777215, nullable=true)
     */
    private $notes;

    /**
     * @var int
     *
     * @ORM\Column(name="reports_to", type="integer", nullable=true)
     */
    private $reportsTo;

    /**
     * @var string
     *
     * @ORM\Column(name="photopath", type="string", length=225, nullable=true)
     */
    private $photopath;

    /**
     * @var float
     *
     * @ORM\Column(name="salary", type="float", precision=10, scale=0, nullable=true)
     */
    private $salary;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AtlanteIt\EmployeeBundle\Entity\Role", inversedBy="employees")
     * @ORM\JoinTable(name="employees_roles",
     *    joinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="id")},
     *    inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    public function __construct()
    {
        $this->isActive = true;
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->firstname,
            $this->password,
            $this->isActive,
            // ver la sección salt debajo
            // $this->salt,
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->firstname,
            $this->password,
            $this->isActive,
            // ver la sección salt debajo
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->firstname;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Employee
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Employee
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Employee
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titleofcourtesy
     *
     * @param string $titleofcourtesy
     * @return Employee
     */
    public function setTitleofcourtesy($titleofcourtesy)
    {
        $this->titleofcourtesy = $titleofcourtesy;

        return $this;
    }

    /**
     * Get titleofcourtesy
     *
     * @return string
     */
    public function getTitleofcourtesy()
    {
        return $this->titleofcourtesy;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Employee
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set hiredate
     *
     * @param \DateTime $hiredate
     * @return Employee
     */
    public function setHiredate($hiredate)
    {
        $this->hiredate = $hiredate;

        return $this;
    }

    /**
     * Get hiredate
     *
     * @return \DateTime
     */
    public function getHiredate()
    {
        return $this->hiredate;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Employee
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Employee
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Employee
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     * @return Employee
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    /**
     * Get postalcode
     *
     * @return string
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Employee
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set homephone
     *
     * @param string $homephone
     * @return Employee
     */
    public function setHomephone($homephone)
    {
        $this->homephone = $homephone;

        return $this;
    }

    /**
     * Get homephone
     *
     * @return string
     */
    public function getHomephone()
    {
        return $this->homephone;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Employee
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Employee
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set reportsTo
     *
     * @param integer $reportsTo
     * @return Employee
     */
    public function setReportsTo($reportsTo)
    {
        $this->reportsTo = $reportsTo;

        return $this;
    }

    /**
     * Get reportsTo
     *
     * @return integer
     */
    public function getReportsTo()
    {
        return $this->reportsTo;
    }

    /**
     * Set photopath
     *
     * @param string $photopath
     * @return Employee
     */
    public function setPhotopath($photopath)
    {
        $this->photopath = $photopath;

        return $this;
    }

    /**
     * Get photopath
     *
     * @return string
     */
    public function getPhotopath()
    {
        return $this->photopath;
    }

    /**
     * Set salary
     *
     * @param float $salary
     * @return Employee
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return float
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Employee
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Employee
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roleNames = [];
        foreach ($this->roles as $role) {
            $roleNames[] = $role->getName();
        }
        return $roleNames;
    }

    /**
     * Add roles
     *
     * @param \AtlanteIt\EmployeeBundle\Entity\Role $roles
     * @return Employee
     */
    public function addRole(\AtlanteIt\EmployeeBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \AtlanteIt\EmployeeBundle\Entity\Role $roles
     */
    public function removeRole(\AtlanteIt\EmployeeBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles ass ArrayCollection.
     *
     * @return ArrayCollection
     */
    public function getRolesCollection() {
        return $this->roles;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Employee
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    public function isAdmin(){
        return in_array('ROLE_ADMIN',$this->getRoles(),true);
    }
}
