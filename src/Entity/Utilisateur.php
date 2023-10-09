<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Article::class)]
    private Collection $LesArticles;

    public function __construct()
    {
        $this->LesArticles = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->pseudo.' '.$this->mail;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getLesArticles(): Collection
    {
        return $this->LesArticles;
    }

    public function addLesArticle(Article $lesArticle): static
    {
        if (!$this->LesArticles->contains($lesArticle)) {
            $this->LesArticles->add($lesArticle);
            $lesArticle->setUtilisateur($this);
        }

        return $this;
    }

    public function removeLesArticle(Article $lesArticle): static
    {
        if ($this->LesArticles->removeElement($lesArticle)) {
            // set the owning side to null (unless already changed)
            if ($lesArticle->getUtilisateur() === $this) {
                $lesArticle->setUtilisateur(null);
            }
        }

        return $this;
    }
}
