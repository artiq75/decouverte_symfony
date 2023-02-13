<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use App\Validator\UniqueThumbnailValidtor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Housing $housing = null;

    #[Vich\UploadableField(mapping: 'housing_images', fileNameProperty: 'name')]
    private ?File $file = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $is_thumbnail = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getHousing(): ?Housing
    {
        return $this->housing;
    }

    public function setHousing(?Housing $housing): self
    {
        $this->housing = $housing;

        return $this;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function isIsThumbnail(): ?bool
    {
        return $this->is_thumbnail;
    }

    public function setIsThumbnail(bool $is_thumbnail): self
    {
        $this->is_thumbnail = $is_thumbnail;

        return $this;
    }
}
